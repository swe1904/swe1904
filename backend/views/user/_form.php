<?php

use app\components\GlobalConstant;
use backend\models\Client;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\UserProfile;

/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $roles yii\rbac\Role[] */
/* @var $permissions yii\rbac\Permission[] */

$roleClient = GlobalConstant::ROLE_CLIENT;
$roleClientEntityManager = GlobalConstant::ROLE_CLIENT_ENTITY_MANAGER;
$roleClientGroupManager = GlobalConstant::ROLE_CLIENT_GROUP_MANAGER;

$roleClientHr = GlobalConstant::ROLE_CLIENT_HR;
$roleClientCaseManager = GlobalConstant::ROLE_CLIENT_CASE_MANAGER;
$roleClientCaseWorker = GlobalConstant::ROLE_CLIENT_CASE_WORKER;
$isNewRecord = $model->getModel()->getIsNewRecord();
// echo "New record : ".$isNewRecord;

        // if(!$isNewRecord) {
        //     $userProfile = UserProfile::findOne($model->getModel()->id);
        //     if ($userProfile && (!empty($userProfile->firstname) || !empty($userProfile->lastname)))
        //         $model->fullname = trim($userProfile->firstname . ' ' . $userProfile->lastname);
        // }
       

        if (isset($_GET['role']) && $_GET['role'] == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER) {
            $roles = ['Client Group Manager' => 'Client Group Manager']; 
        }
?>


<!-- Row -->

<div class="col-md-12">
<div class="panel panel-default border-panel card-view">
            <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark">
                        <?= !empty($model->username) ? "Update" : "Create" ?>
                        <?php 
                            if (Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN)) {
                                echo (isset($_GET['role']) && $_GET['role'] == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER) 
                                    ? 'Client Group Manager' 
                                    : 'Organisation-admin User';
                            } else {
                                echo 'User';
                            }
                        ?>
                    </h6>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-wrapper collapse in">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-wrap">
                               <?php if(
    (
        $model->hasRole(GlobalConstant::ROLE_ORGANISATION_ADMIN) || 
        $model->hasRole(GlobalConstant::ROLE_HR_MANAGER) || 
        $model->hasRole(GlobalConstant::ROLE_SUPERADMIN)
    ) 
    && empty($model->username) 
    && empty(Yii::$app->user->identity->organisation_id)
): ?>
                                <h6>
    Please fill in the Organisation Information before adding users! 
    Else, users will not show here. <br>
    <strong>Organisation ID:</strong> <?= Yii::$app->user->identity->organisation_id ?>
</h6>

                                <?php else: ?>
                                <?php $form = ActiveForm::begin(['fieldConfig' => [
                                    'options' => [
                                        'options' => ['class' => 'form-group invisible']
                                    ],
                                ],
                                ]); ?>
                                <div class="row">
                                    <div class="col-md-2">
                                        <?php echo $form->field($model, 'fullname')->textInput(['maxlength' => 255, 'class' => 'form-control border']); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $form->field($model, 'username')->textInput(['maxlength' => 255, 'class' => 'form-control border']); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $form->field($model, 'email')->textInput(['maxlength' => 255, 'class' => 'form-control border']); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $form->field($model, 'password')->passwordInput(['maxlength' => 255, 'class' => 'form-control border']); ?>
                                    </div>

                                    <!-- <div class="col-md-1">
                                        <div class="checkbox checkbox-default inline-block mt-30"> -->
                                            <?php echo $form->field($model, 'status',[ 'template' => "{input}{label}", 'options' => ['style' => 'display: none;']])->checkbox([], 1)->label(Yii::t('backend', 'Active')); ?>
                                        <!-- </div>
                                    </div> -->

                                    <div class="col-md-2">
                                        <div class="checkbox checkbox-default inline-block mt-20">
                                            <?php echo $form->field($model, 'roles',[ 'template' => "{input}"])->dropDownList($roles, ['itemOptions'=>['enclosedByLabel'=> false]])->label(false); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <!--                        <div class="form-group">-->
                                        <?php echo Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-sm btn-rounded btn-success mt-20', 'name' => 'signup-button']) ?>
                                        <!--                        </div>-->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 field-connect-client-dropdown">
                                        <?php echo $form->field($model, 'client_id')->dropDownList($connectClients,(!Yii::$app->user->can('administrator')&&empty($connectClients))?['prompt'=>'- no new client exist -', 'id' => 'connect-client-dropdown']:['prompt'=>'Select connect client', 'id' => 'connect-client-dropdown'])->label('Connect Client'); ?>
                                        <?php /*echo $form->field($model, 'client_id')->dropDownList(ArrayHelper::map(Client::find()->all(),'id','first_name')) */?>
                                    </div>
                                    <div class="col-md-3 field-all-client-dropdown">
                                        <?php echo $form->field($model, 'client_id')->dropDownList($allClients,['prompt'=>'Select client', 'id' => 'all-client-dropdown'])->label('Client'); ?>
                                    </div>
                                    <div class="col-md-3 field-client-entity-dropdown" style="display:none;" >
                                        <?php echo $form->field($model, 'client_entity')->dropDownList($clientEntityArr,['prompt'=>'Select client', 'id' => 'client-entity-dropdown'])->label('Client  Entity'); ?>
                                    </div>


                                </div>
                                <?php ActiveForm::end(); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- /Row -->

<?php //if($model->hasRole(GlobalConstant::ROLE_CLIENT)): ?>
    <?php
    $this->registerJs(
        <<<JS
       $(function() {
    
        // $('.field-userform-client_id').hide();
        //  $('#userform-client_id').hide();
        //  $('#userform-client_id').attr('disabled',true);
        disableNHide('all-client-dropdown');
        disableNHide('connect-client-dropdown');
         
});
JS
    );
    ?>
<?php //endif; ?>

<?php //onchange roles hide/show client id field
$script = <<< JS
var roleChange=function() {
    // var isclient = false;
    // $('#userform-roles input:checked').each(function() {
    //   
    //     if ($(this).attr('value') == 'client') {
    //         isclient = true;
    //     }
    // });
    
    if ($(this).children("option:selected").val() == '$roleClient'||$(this).children("option:selected").val() == '$roleClientHr' ) {
        disableNHide('all-client-dropdown');
        enableNShow('connect-client-dropdown');
        disableNHide('client-entity-dropdown');
        // $('.field-userform-client_id').show();
        // $('#userform-client_id').show();
        // $('#userform-client_id').attr('disabled',false);
        // Check if there are any items in the dropdown
        
        clientId = '$model->client_id';
        var options = $('#userform-connect-client-dropdown option');
        if(clientId)
        {
            $('#userform-client_id').val(clientId)
        }
        else if (options.length > 0 ) {
            // Select the first option
            $('#userform-client_id').val(options.first().val());
        }

       
        return true;
    } 
    else if(($(this).children("option:selected").val() == '$roleClientEntityManager') ){
        disableNHide('connect-client-dropdown');
        enableNShow('all-client-dropdown');
        disableNShow('client-entity-dropdown');
       
    }
    else if(($(this).children("option:selected").val() == '$roleClientCaseManager') || ($(this).children("option:selected").val() == '$roleClientCaseWorker') ||($(this).children("option:selected").val() == '$roleClientGroupManager')  ){
        disableNHide('connect-client-dropdown');
        enableNShow('all-client-dropdown');
        disableNHide('client-entity-dropdown');
    }
    else {
        // $('#userform-client_id').attr('disabled',true);
        // $('.field-userform-client_id').hide();
        // $('#userform-client_id').hide();
        // $('#userform-client_id').val(null); // Set the value to null
        disableNHide('all-client-dropdown');
        disableNHide('connect-client-dropdown');
        disableNHide('client-entity-dropdown');
         return false;
    };
}
$('#userform-roles').change(roleChange);

//onload
$(function(){
    $('#userform-roles').trigger('change');
})

function enableNShow(inputId){
    console.log("Enable :",inputId);
        $('#'+inputId).attr('disabled',false);
        $('.field-'+inputId).show();
        $('#'+inputId).show();
        // $('#userform-'+inputId).val(null);
}
function disableNHide(inputId){
    console.log("Disable :",inputId);
    $('#'+inputId).attr('disabled',true);
        $('.field-'+inputId).hide();
        $('#'+inputId).hide();
        if('$isNewRecord')
            $('#'+inputId).val(null);
}
function disableNShow(inputId){
    console.log("Disable :",inputId);
    $('#'+inputId).attr('disabled',true);
        $('.field-'+inputId).show();
        $('#'+inputId).show();
        if('$isNewRecord')
            $('#'+inputId).val(null);
}
$('#all-client-dropdown').on('change', function() {
    var clientId = $(this).val();

    if ($('#userform-roles').val() == 'Client Entity Manager') {
        $.ajax({
            url: 'get-client-entities',
            data: { clientId: clientId },
            type: 'GET',
            dataType: 'json',
            success: function(data, textStatus) {
                if (data) {
                    var jsondata = JSON.parse(JSON.stringify(data));
                    $('#client-entity-dropdown').empty();                    
                    // Fix: Use traditional function instead of arrow function
                    Object.keys(jsondata).forEach(function(key) {
                        $('#client-entity-dropdown').append('<option value="' + key + '">' + jsondata[key] + '</option>');
                    });

                    enableNShow('client-entity-dropdown');
                }
            }
        });
    }
});

   

         
JS;
$this->registerJs($script);
?>



