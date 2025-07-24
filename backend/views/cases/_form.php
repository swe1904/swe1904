<?php
use backend\models\CaseType;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\components\GlobalConstant;
use backend\models\Client;
use backend\models\Applicant;
use common\models\Organisation;
use backend\models\ClientEntity;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\Cases */
/* @var $form yii\widgets\ActiveForm */
$isClientNClientPOC = in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_CLIENT,GlobalConstant::ROLE_CLIENT_HR]);

if (in_array(Yii::$app->user->identity->getRole(), [GlobalConstant::ROLE_ORGANISATION_ADMIN, GlobalConstant::ROLE_ORGANISATION_MANAGER, GlobalConstant::ROLE_CASE_MANAGER, GlobalConstant::ROLE_CASE_WORKER , GlobalConstant::ROLE_CLIENT_CASE_MANAGER , GlobalConstant::ROLE_FINANCE])) {
   $clients = ArrayHelper::map(Client::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all(),'id','client_name');
} 
else if($isClientNClientPOC)
{
    // getting the users's client
    $client = Client::findOne(Yii::$app->user->identity->client_id);

    // creating single length array containing only the user's client
    $clients = [$client->id => $client->client_name];

    //assigning the same client to the case and triggering change via javascript
    $model->client_id = $client->id;   
    $model->client_id_display = $client->id;   
}
$clientEntityArr = [];
$applicantArr = [];
$orgArr = [];
if(!$model->isNewRecord)
{

    $clientEntityArr = ArrayHelper::map(
            ClientEntity::find()->where(['client_id' => $model->client_id])->all(),'id','name'
        );

    $applicantArr = ArrayHelper::map(
            Applicant::find()->where(['client_id' => $model->client_id, 'parent_id' => null])->all(),
            'id',
            function($model) {
                return trim($model->first_name . ' ' . $model->last_name);
            }
        );

    $orgArr = ArrayHelper::map(Organisation::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.organisation_id = tbl_organisation.id')->andWhere(['tbl_client_organisation.client_id' => $model->client_id])->all(),'id','name');
}
?>

<style>
    .select2-selection__arrow{
        display: none !important;
    }
    .select2-selection__rendered{
        padding-top: 6px !important;
    }
</style>

    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-hading">
            <div class="row clearfix">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin(['fieldConfig' => [
                    'options' => [
                        'options' => ['class' => 'form-group invisible']
                    ],
                ],
                'id' => 'case-create',
                ]); ?>
                <?php if($isClientNClientPOC) {?>
                    
                <?= $form->field($model, 'client_id')->hiddenInput()->label(false) ?>
                    <div class="col-md-4">
                

                        <?= $form->field($model, 'client_id_display')->label('Client')->widget(Select2::className(), [
                                    'data' => $clients,
                                    //'model' => $model,
                                    // 'attribute' => 'categories',

                                    'language' => 'en',

                                    'options' => ['placeholder' => 'Select client',
                                                 'class'=>'multiple',
                                                'style'=>"height:250px",
                                                'disabled' => 'disabled',
                                                // 'id'=> 'multiselect',
                                                // 'onchange'=>'clientDropDownChange()'
                                                // 'name'=> 'Cases["client_id"]',
                                                // 'required' => true, // Make the field required
                                            ],
                                    'pluginOptions' => [
                                            'allowClear' => true,
                                            // 'multiple' => true,
                                            // 'closeOnSelect' => false,
                                            'label' => false,
                                        ],
                                        // 'pluginEvents' => [
                                        //                     'change' => 'function() { clientDropDownChange(); }',
                                        //                 ],

                                        ])
                                        ?>
                    </div>
                <?php } else {?>  
                    <div class="col-md-4">  
                            <?= $form->field($model, 'client_id')->label('Client')->widget(Select2::className(), [
                                    'data' => $clients,
//                                    'model' => $model,
                                    // 'attribute' => 'categories',

                                    'language' => 'en',

                                    'options' => ['placeholder' => 'Select client',
                                                 'class'=>'multiple',
                                                'style'=>"height:250px",
                                                // 'disabled' =>  $isClientNClientPOC ? 'disabled' : false,
                                                // 'id'=> 'multiselect',
                                                'onchange'=>'clientDropDownChange()'
                                                // 'name'=> 'Cases["client_id"]',
                                                // 'required' => true, // Make the field required
                                            ],
                                    'pluginOptions' => [
                                            'allowClear' => true,
                                            // 'multiple' => true,
                                            // 'closeOnSelect' => false,
                                            'label' => false,
                                        ],
                                        // 'pluginEvents' => [
                                        //                     'change' => 'function() { clientDropDownChange(); }',
                                        //                 ],

                                        ])
                                        ?>
                        </div>
                    <?php } ?>
                                        
                <div class="col-md-4">
                    <?= $form->field($model, 'client_entity')->label('Client Entity')->widget(Select2::className(), [
                                        'data' => $clientEntityArr,
    //                                    'model' => $model,
                                        // 'attribute' => 'categories',

                                        'language' => 'en',

                                        'options' => ['placeholder' => 'Select Client Entity',
                                                    'class'=>'multiple',
                                                    'style'=>"height:250px",
                                                    // 'id'=> 'multiselect',
                                                    // 'onchange'=>'dropDownChange()'
                                                ],
                                        'pluginOptions' => [
                                                'allowClear' => true,
                                                // 'multiple' => true,
                                                // 'closeOnSelect' => false,
                                                'label' => false,
                                            ],

                                            ])
                                            ?>
                                            <div class="fa fa-circle-o-notch fa-spin" id="loading-div-client_entity" style="display:none;" ></div>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'case_work_office_id')->label('Casework Office')->widget(Select2::className(), [
                                        'data' => $orgArr,
    //                                    'model' => $model,
                                        // 'attribute' => 'categories',

                                        'language' => 'en',

                                        'options' => ['placeholder' => 'Select casework office',
                                                    'class'=>'multiple',
                                                    'style'=>"height:250px",
                                                    // 'id'=> 'multiselect',
                                                    // 'onchange'=>'dropDownChange()'
                                                    'id' => 'cases-organisation_id_casework'
                                                ],
                                        'pluginOptions' => [
                                                'allowClear' => true,
                                                // 'multiple' => true,
                                                // 'closeOnSelect' => false,
                                                'label' => false,
                                            ],

                                            ])
                                            ?>
                                            <div class="fa fa-circle-o-notch fa-spin" id="loading-div-organisation_id" style="display:none;" ></div>
                </div>

                
            </div>
            <div class="col-md-12">
            <div class="col-md-4">
                    <?= $form->field($model, 'applicant_id')->label('Applicant')->widget(Select2::className(), [
                                    'data' => $applicantArr,
                                    // 'model' => $model,
                                    // 'attribute' => 'categories',

                                    'language' => 'en',

                                    'options' => ['placeholder' => 'Select applicant',
                                                 'class'=>'multiple',
                                                'style'=>"height:250px",
                                                // 'id'=> 'multiselect',
                                                // 'onchange'=>'dropDownChange()'
                                                // 'name'=> 'Cases["applicant_id"]',
                                                // 'required' => true, // Make the field required
                                            ],
                                    'pluginOptions' => [
                                            'allowClear' => true,
                                            // 'multiple' => true,
                                            // 'closeOnSelect' => false,
                                            'label' => false,
                                        ],

                                        ])
                                        ?>
                                        <div class="fa fa-circle-o-notch fa-spin" id="loading-div-applicant_id" style="display:none;" ></div>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'client_billing_entity')->label('Billing Identifier Entity')->textInput([
                        'placeholder' => 'Enter Billing Identifier Entity',
                        'class' => 'form-control',
                        'style' => "height:250px;color:#555 !important",
                     
                        
                    ]) ?>
                    <div class="fa fa-circle-o-notch fa-spin" id="loading-div-applicant_id" style="display:none;"></div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-4">
                    <?= $form->field($model, 'case_type_id')->label('Case Type')->widget(Select2::className(), [
                                        'data' => $caseTypes,
    //                                    'model' => $model,
                                        // 'attribute' => 'categories',

                                        'language' => 'en',

                                        'options' => ['placeholder' => 'Select case type',
                                                    'class'=>'multiple',
                                                    'style'=>"height:250px",
                                                    // 'id'=> 'multiselect',
                                                    // 'onchange'=>'dropDownChange()'
                                                ],
                                        'pluginOptions' => [
                                                'allowClear' => true,
                                                // 'multiple' => true,
                                                // 'closeOnSelect' => false,
                                                'label' => false,
                                            ],

                                            ])
                                            ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'organisation_id')->label('Northman Billing Office')->widget(Select2::className(), [
                                        'data' => $orgArr,
    //                                    'model' => $model,
                                        // 'attribute' => 'categories',

                                        'language' => 'en',

                                        'options' => ['placeholder' => 'Select northman billing office',
                                                    'class'=>'multiple',
                                                    'style'=>"height:250px",
                                                    // 'id'=> 'multiselect',
                                                    // 'onchange'=>'dropDownChange()'
                                                    'id' => 'cases-organisation_id_lead'
                                                ],
                                        'pluginOptions' => [
                                                'allowClear' => true,
                                                // 'multiple' => true,
                                                // 'closeOnSelect' => false,
                                                'label' => false,
                                            ],

                                            ])
                                            ?>
                                            <div class="fa fa-circle-o-notch fa-spin" id="loading-div-organisation_id" style="display:none;" ></div>
                </div>
                                        
            </div>
            <?php if(!$isClientNClientPOC) {?>
            <div class="col-md-12">
                <?php
                    $caseManagerArr = [];
                    $caseWorkerArr = [];

                    if(!$model->isNewRecord)
                    {
                        $orgsIds = ArrayHelper::getColumn(Organisation::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.organisation_id = tbl_organisation.id')->andWhere(['tbl_client_organisation.client_id' => $model->client_id])->all(),'id');
            
                        $caseManagerArr = ArrayHelper::map(User::find()->join('LEFT JOIN','tbl_rbac_auth_assignment','tbl_rbac_auth_assignment.user_id = id')->andWhere(['tbl_rbac_auth_assignment.item_name' => GlobalConstant::ROLE_CASE_MANAGER])->andWhere(['in', 'organisation_id',$orgsIds])->all(),
                                            'id',
                                            function($model) {
                                                if($model->userProfile)
                                                {
                                                    $firstName = $model->userProfile->firstname;
                                                    $lastName = $model->userProfile->lastname;

                                                    if (!empty($firstName) || !empty($lastName)) {
                                                        return trim($firstName . ' ' . $lastName);
                                                    }
                                                }
                                                return $model->username;//will return if $model->userProfile doesn't exists or first or last name doesn't exist

                                            }
                                        );

                        $caseWorkerArr = ArrayHelper::map(User::find()->join('LEFT JOIN','tbl_rbac_auth_assignment','tbl_rbac_auth_assignment.user_id = id')->andWhere(['tbl_rbac_auth_assignment.item_name' => GlobalConstant::ROLE_CASE_WORKER])->andWhere(['in', 'organisation_id',$orgsIds])->all(),
                                            'id',
                                            function($model) {
                                                if($model->userProfile)
                                                {
                                                    $firstName = $model->userProfile->firstname;
                                                    $lastName = $model->userProfile->lastname;
            
                                                    if (!empty($firstName) || !empty($lastName)) {
                                                        return trim($firstName . ' ' . $lastName);
                                                    }
                                                }
                                                return $model->username;//will return if $model->userProfile doesn't exists or first or last name doesn't exist
            
                                            }
                                        );
                         
                    }
                ?>
                <div class="col-md-4">
                    <?= $form->field($model, 'case_manager_id')->label('Case Manager')->widget(Select2::className(), [
                                        'data' => $caseManagerArr,
    //                                    'model' => $model,
                                        // 'attribute' => 'categories',

                                        'language' => 'en',

                                        'options' => ['placeholder' => 'Select case manager',
                                                    'class'=>'multiple',
                                                    'style'=>"height:250px",
                                                    // 'id'=> 'multiselect',
                                                    // 'onchange'=>'dropDownChange()'
                                                ],
                                        'pluginOptions' => [
                                                'allowClear' => true,
                                                // 'multiple' => true,
                                                // 'closeOnSelect' => false,
                                                'label' => false,
                                            ],

                                            ])
                                            ?>
                                            <div class="fa fa-circle-o-notch fa-spin" id="loading-div-case_manager_id" style="display:none;" ></div>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'assigned_to')->label('Case Worker')->widget(Select2::className(), [
                                        'data' => $caseWorkerArr,
    //                                    'model' => $model,
                                        // 'attribute' => 'categories',

                                        'language' => 'en',

                                        'options' => ['placeholder' => 'Select case worker',
                                                    'class'=>'multiple',
                                                    'style'=>"height:250px",
                                                    // 'id'=> 'multiselect',
                                                    // 'onchange'=>'dropDownChange()'
                                                ],
                                        'pluginOptions' => [
                                                'allowClear' => true,
                                                // 'multiple' => true,
                                                // 'closeOnSelect' => false,
                                                'label' => false,
                                            ],

                                            ])
                                            ?>
                                            <div class="fa fa-circle-o-notch fa-spin" id="loading-div-assigned_to" style="display:none;" ></div>
                </div>

            </div>
            <?php }?>

            <div class="col-md-12">
            <?php
                    $clientCaseManagerArr = [];
                    $clientCaseWorkerArr = [];
                    if(!$model->isNewRecord)
                    {
                        $clientCaseManagerArr =  ArrayHelper::map(User::find()->join('LEFT JOIN','tbl_rbac_auth_assignment','tbl_rbac_auth_assignment.user_id = id')->andWhere(['tbl_rbac_auth_assignment.item_name' => GlobalConstant::ROLE_CLIENT_CASE_MANAGER])->andWhere(['client_id'=>$model->client_id])->all(),
                                                                'id',
                                                                function($model) {
                                                                    if($model->userProfile)
                                                                    {
                                                                        $firstName = $model->userProfile->firstname;
                                                                        $lastName = $model->userProfile->lastname;

                                                                        if (!empty($firstName) || !empty($lastName)) {
                                                                            return trim($firstName . ' ' . $lastName);
                                                                        }
                                                                    }
                                                                    return $model->username;//will return if $model->userProfile doesn't exists or first or last name doesn't exist

                                                                }
                                                            );

                        $clientCaseWorkerArr = ArrayHelper::map(User::find()->join('LEFT JOIN','tbl_rbac_auth_assignment','tbl_rbac_auth_assignment.user_id = id')->andWhere(['tbl_rbac_auth_assignment.item_name' => GlobalConstant::ROLE_CLIENT_CASE_WORKER])->andWhere(['client_id'=>$model->client_id])->all(),
                                                            'id',
                                                            function($model) {
                                                                if($model->userProfile)
                                                                {
                                                                    $firstName = $model->userProfile->firstname;
                                                                    $lastName = $model->userProfile->lastname;

                                                                    if (!empty($firstName) || !empty($lastName)) {
                                                                        return trim($firstName . ' ' . $lastName);
                                                                    }
                                                                }
                                                                return $model->username;//will return if $model->userProfile doesn't exists or first or last name doesn't exist

                                                            }
                                                        );
                    }
                    ?>
                <div class="col-md-4">
                    <?= $form->field($model, 'client_case_manager_id')->label('Client Case Manager')->widget(Select2::className(), [
                                       'data' => $clientCaseManagerArr,
    //                                    'model' => $model,
                                        // 'attribute' => 'categories',

                                        'language' => 'en',

                                        'options' => ['placeholder' => 'Select client case manager',
                                                    'class'=>'multiple',
                                                    'style'=>"height:250px",
                                                    // 'id'=> 'multiselect',
                                                    // 'onchange'=>'dropDownChange()'
                                                ],
                                        'pluginOptions' => [
                                                'allowClear' => true,
                                                // 'multiple' => true,
                                                // 'closeOnSelect' => false,
                                                'label' => false,
                                            ],

                                            ])
                                            ?>
                                            <div class="fa fa-circle-o-notch fa-spin" id="loading-div-client_case_manager_id" style="display:none;" ></div>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'client_case_worker_id')->label('Client Case Worker')->widget(Select2::className(), [
                                       'data' => $clientCaseWorkerArr,
    //                                    'model' => $model,
                                        // 'attribute' => 'categories',

                                        'language' => 'en',

                                        'options' => ['placeholder' => 'Select client case worker',
                                                    'class'=>'multiple',
                                                    'style'=>"height:250px",
                                                    // 'id'=> 'multiselect',
                                                    // 'onchange'=>'dropDownChange()'
                                                ],
                                        'pluginOptions' => [
                                                'allowClear' => true,
                                                // 'multiple' => true,
                                                // 'closeOnSelect' => false,
                                                'label' => false,
                                            ],

                                            ])
                                            ?>
                                            <div class="fa fa-circle-o-notch fa-spin" id="loading-div-client_case_worker_id" style="display:none;" ></div>
                </div>
            </div>

            <?php /* 
<div class="col-md-12">
    <?= $form->field($model, 'case_number', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => true,'disabled'=>true]) ?>
</div>

<div class="col-md-12">
    <label class="control-label custom-label" for="case_type_id">
        <?php echo $model->getAttributeLabel('case_type_id'); ?>
    </label>
    <?= $form->field($model, 'case_type_id')
        ->dropDownList(ArrayHelper::map(CaseType::find()->all(), 'id', 'name'), ['prompt' => 'Select Case Type', 'class' => 'case_type_dropdown'])
        ->label(false) ?>
</div>

<div class="col-md-12">
    <label class="control-label custom-label target_completion_date_label"
           for="target_completion_date">
        <?php echo $model->getAttributeLabel('target_completion_date'); ?>
    </label>

    <?= $form->field($model, 'target_completion_date')->widget(DateControl::classname(), [
        'options' => ['style' => 'width:250px;', 'class' => 'form-control'],
        'type' => DateControl::FORMAT_DATE,
        'displayFormat' => 'dd-MM-yyyy',
        'ajaxConversion' => false,
        'widgetOptions' => [
            'pluginOptions' => [
                'autoclose' => true
            ]
        ]
    ])->label(false); ?>
</div>

<div class="col-md-12">
    <?= $form->field($model, 'updated_at')->hiddenInput(['maxlength' => true]) ?>
</div>

<div class="col-md-12">
    <?= $form->field($model, 'sending_country', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => true]) ?>
</div>

<div class="col-md-12">
    <?= $form->field($model, 'receiving_country', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => true]) ?>
</div>

<div class="col-md-12">
    <?= $form->field($model, 'applicant_last_name', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => true]) ?>
</div>

<div class="col-md-12">
    <?= $form->field($model, 'applicant_first_name', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => true]) ?>
</div>

<div class="col-md-12">
    <?= $form->field($model, 'client_name', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => true]) ?>
</div>

<div class="col-md-12">
    <label class="control-label custom-label" for="date_of_birth">
        <?php echo $model->getAttributeLabel('date_of_birth'); ?>
    </label>
    <?= $form->field($model, 'date_of_birth')->widget(DateControl::classname(), [
        'options' => ['style' => 'width:250px;', 'class' => 'form-control'],
        'type' => DateControl::FORMAT_DATE,
        'displayFormat' => 'dd-MM-yyyy',
        'ajaxConversion' => false,
        'widgetOptions' => [
            'pluginOptions' => [
                'autoclose' => true
            ]
        ]
    ])->label(false); ?>
</div>

<div class="col-md-12">
    <?= $form->field($model, 'passport_number', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => true]) ?>
</div>

<div class="col-md-12">
    <?= $form->field($model, 'mobile_number', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => true]) ?>
</div>

<div class="col-md-12">
    <?= $form->field($model, 'office_address', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => true]) ?>
</div>
*/ ?>
                <div class="col-md-12">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => 'btn btn-rounded btn-success mr-10']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(
    <<<JS
    $('.case_type_dropdown').on('change', function(){
        var case_type_id = this.value;
        $.ajax({
                url: 'case-type-meta',
                data: "case_type_id="+case_type_id,
                type: 'GET',
                dataType: 'json',
                success: function(data, textStatus) {
                    console.log(data);
                    if(data != 'false'){
                        $('.target_completion_date_label').html("Target Completion Date (<small>" + data.days + " day(s) from today</small>)");
                        $('#cases-target_completion_date-disp').val(data.targetCompletionDate);
                        $('#cases-target_completion_date').val(data.targetCompletionDate.split("-").reverse().join("-"));
                    }
                    else {
                        $('.target_completion_date_label').html("Target Completion Date");
                        $('#cases-target_completion_date-disp').val("");
                         $('#cases-target_completion_date').val("");
                    }
                },
        });
    })
JS
);
?>

<script>

    var client;
    var isNewRecord;

    $(document).ready(function() {
        client = "<?= $isClientNClientPOC?>";
        isNewRecord = "<?= $model->isNewRecord?>";
        
        if(client && isNewRecord){
            clientDropDownChange();   
        }        
    });

    function clientDropDownChange() {
        
       
        var clientId = $('#cases-client_id').val();

        enableLoading('applicant_id');
        enableLoading('client_entity');
        enableLoading('organisation_id');
        enableLoading('case_manager_id');
        enableLoading('assigned_to');
        enableLoading('client_case_manager_id');
        enableLoading('client_case_worker_id');
        // $('#cases-assigned_to').html("");

        $.ajax({
                url: 'get-client-applicants',
                data: "clientId="+clientId,
                type: 'GET',
                dataType: 'json',
                success: function(data, textStatus) {
                    // console.log("Applicants : ",data);
                    if(data)
                    {
                           
                        var jsondata = JSON.parse(JSON.stringify(data));
                       
                        var keys= Object.keys(jsondata);
                        
                        $('#cases-applicant_id').append(`<option value="" selected disabled>Select Applicant</option>`);
                        keys.forEach((key)=>{
                            $('#cases-applicant_id').append(`<option value="${key}">
                                            ${jsondata[key]}</option>`); 
                        });
                    }
                    disableLoading('applicant_id');
                },
        });

        $.ajax({
                url: 'get-client-entities',
                data: "clientId="+clientId,
                type: 'GET',
                dataType: 'json',
                success: function(data, textStatus) {
                    // console.log("Applicants : ",data);
                    if(data)
                    {
                           
                        var jsondata = JSON.parse(JSON.stringify(data));
                       
                        var keys= Object.keys(jsondata);
                        
                        $('#cases-client_entity').append(`<option value="" selected disabled>Select Applicant</option>`);
                        keys.forEach((key)=>{
                            $('#cases-client_entity').append(`<option value="${key}">
                                            ${jsondata[key]}</option>`); 
                        });
                    }
                    disableLoading('client_entity');
                },
        });

        $.ajax({
    url: 'get-client-orgs',
    data: "clientId=" + clientId,
    type: 'GET',
    dataType: 'json',
    success: function (data, textStatus) {
        console.log("client data Organisations: ", data);
        if (data) {
            var jsondata = JSON.parse(JSON.stringify(data));
            var keys = Object.keys(jsondata);

            // Update both dropdowns
            ['#cases-organisation_id_lead', '#cases-organisation_id_casework'].forEach(function (dropdownId) {
                $(dropdownId).empty(); 
                $(dropdownId).append('<option value="" selected disabled>Select an option</option>');
                keys.forEach(function (key) {
                    $(dropdownId).append(`<option value="${key}">${jsondata[key]}</option>`);
                });
            });
        }
        disableLoading('organisation_id');
    }
});

        $.ajax({
                url: 'get-client-side-case-manager',
                data: "clientId="+clientId,
                type: 'GET',
                dataType: 'json',
                success: function(data, textStatus) {
                    // console.log("Case Manager : ",data);
                    if(data)
                    {
                        var jsondata = JSON.parse(JSON.stringify(data));

                        var keys= Object.keys(jsondata);

                        $('#cases-client_case_manager_id').append(`<option value="" selected disabled>Select Case Manager</option>`);
                        keys.forEach((key)=>{
                            $('#cases-client_case_manager_id').append(`<option value="${key}">
                                            ${jsondata[key]}</option>`);
                        });

                }
                disableLoading('client_case_manager_id');
            },
        });

        $.ajax({
                url: 'get-client-side-case-worker',
                data: "clientId="+clientId,
                type: 'GET',
                dataType: 'json',
                success: function(data, textStatus) {
                    // console.log("Case Manager : ",data);
                    if(data)
                    {


                        var jsondata = JSON.parse(JSON.stringify(data));

                        var keys= Object.keys(jsondata);

                        $('#cases-client_case_worker_id').append(`<option value="" selected disabled>Select Case Manager</option>`);
                        keys.forEach((key)=>{
                            $('#cases-client_case_worker_id').append(`<option value="${key}">
                                            ${jsondata[key]}</option>`);
                        });

                }
                disableLoading('client_case_worker_id');
            },
        });

        if(client)
            return;

        $.ajax({
                url: 'get-client-orgs-case-manager',
                data: "clientId="+clientId,
                type: 'GET',
                dataType: 'json',
                success: function(data, textStatus) {
                    // console.log("Case Manager : ",data);
                    if(data)
                    {
                        
                       
                        var jsondata = JSON.parse(JSON.stringify(data));
                       
                        var keys= Object.keys(jsondata);
                    
                        $('#cases-case_manager_id').append(`<option value="" selected disabled>Select Case Manager</option>`);
                        keys.forEach((key)=>{
                            $('#cases-case_manager_id').append(`<option value="${key}">
                                            ${jsondata[key]}</option>`); 
                        });
                    
                }
                disableLoading('case_manager_id');
            },
        });

        $.ajax({
                url: 'get-client-orgs-case-worker',
                data: "clientId="+clientId,
                type: 'GET',
                dataType: 'json',
                success: function(data, textStatus) {
                    // console.log("Case Worker : ",data);
                    if(data)
                    {
                        
                       
                        var jsondata = JSON.parse(JSON.stringify(data));
                       
                        var keys= Object.keys(jsondata);
                    
                        $('#cases-assigned_to').append(`<option value="" selected disabled>Select Case Worker</option>`);
                        keys.forEach((key)=>{
                            $('#cases-assigned_to').append(`<option value="${key}">
                                            ${jsondata[key]}</option>`); 
                        });
                    
                }
                disableLoading('assigned_to');
            },
        });
    }

    $('#cases-organisation_id_lead','#cases-organisation_id_casework' ).on('change', function(){

        

        var orgId = $(this).val();
        if(orgId)
        {
            $('#cases-organisation_id_lead','#cases-organisation_id_casework').parent().removeClass('has-error').addClass('has-success');// This focuses the Select2 element
        }
        else
        {
            $('#cases-organisation_id_lead','#cases-organisation_id_casework').parent().removeClass('has-success').addClass('has-error');
            // return;
        }

        // if(client)
        //     return;
        
    })


    $('#case-create').on('beforeSubmit', function(e) {
        var client = $('#cases-client_id').val();
        var clientEntity = $('#cases-client_entity').val();
        var applicant = $('#cases-applicant_id').val();
        var casetype = $('#cases-case_type_id').val();
        var organisation = $('#cases-organisation_id_lead').val();
        var caseworkOffice =  $('#cases-organisation_id_casework').val();
        var requiredIsNull = 0;
        if (!client) {
            // alert('Please select an applicant.');
            $('#cases-client_id').parent().addClass('has-error'); // This focuses the Select2 element
            requiredIsNull = 1;
        }
        if (!clientEntity) {
            // alert('Please select an applicant.');
            $('#cases-client_entity').parent().addClass('has-error'); // This focuses the Select2 element
            requiredIsNull = 1;
        }
        if (!applicant) {
            // alert('Please select an applicant.');
            $('#cases-applicant_id').parent().addClass('has-error'); // This focuses the Select2 element
            requiredIsNull = 1;
        }
        if (!casetype) {
            // alert('Please select an applicant.');
            $('#cases-case_type_id').parent().addClass('has-error'); // This focuses the Select2 element
            requiredIsNull = 1;
        }
        if (!organisation) {
            // alert('Please select an applicant.');
            $('#cases-organisation_id_lead', '#cases-organisation_id_casework').parent().addClass('has-error'); // This focuses the Select2 element
            requiredIsNull = 1;
        }
        if(requiredIsNull)
            {
                toastr.error("Focused field(s) cannot be null");
                return false;

            }
        else
            return true;
    });

    function enableLoading(inputType){
        $('#cases-'+inputType).html("");
        $('#cases-'+inputType).prop('disabled', true);
        $('#loading-div-'+inputType).show();
    } 
    function disableLoading(inputType){

        $('#cases-'+inputType).prop('disabled', false);
        $('#loading-div-'+inputType).hide();
    } 
</script>