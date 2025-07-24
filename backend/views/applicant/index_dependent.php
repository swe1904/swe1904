<?php
use app\components\GlobalConstant;
use yii\helpers\Html;
use backend\models\CaseType;
use yii\widgets\ActiveForm;
use backend\models\Cases;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ApplicantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



?>
<div class="applicant-index">

<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-hading">

<!--    <h6>--><!--Html::encode($this->title) ?></h6>-->
<!--echo $this->render('_search', ['model' => $searchModel]); ?>-->
<!---->
<!--$form = ActiveForm::begin(['fieldConfig' => [ -->
<!--                    'options' => [-->
<!--                        'options' => ['class' => 'form-group invisible']-->
<!--                    ],-->
<!--                ],-->
<!--        ]); ?>-->
<!--    -->
<!--    <div class="col-md-4">-->
<!--    --><!--        echo $form->field($model, 'case_type_id')-->
<!--             ->dropDownList(ArrayHelper::map(CaseType::find()->all(), 'id', 'name'), ['prompt' => 'Select Case Type', 'class' => 'case_type_dropdown'])-->
<!--             ->label(false); ?>-->
<!--    </div> -->
<!--            --><!--            if(Yii::$app->user->identity->getRole() != GlobalConstant::ROLE_SUPERADMIN){ ?>-->
<!--    <p>-->
<!--        --><!--Html::a(Yii::t('backend', 'Create'),['applicant/create'], ['class' => 'btn btn-rounded btn-success mr-10 mb-20']) ?>-->
<!--    </p>-->
<!--            -->    <div class="panel-heading">
                Dependents
            </div>
<!--    --><!-- ActiveForm::end(); ?>-->
                <?php  if(Yii::$app->user->identity->getRole()=='organisation-admin'){
    $clients= \backend\models\Client::find()->where(['user_id'=>Yii::$app->user->id])->all();
    }else if(Yii::$app->user->identity->getRole()== GlobalConstant::ROLE_CASE_WORKER){
        $clients= \backend\models\Client::find()->where(['user_id'=>Yii::$app->user->identity->organisation->user_id])->all();
        }
    else{
        $clients= \backend\models\Client::find()->where(['id'=>Yii::$app->user->id])->all();
    }
            $clientFilter=count( $clients)> 0 ? \yii\helpers\ArrayHelper::map( $clients,'id','client_name'): [];
  ?>
                <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn',
             'headerOptions' => ['class' => 'abc']
    ],

            ['attribute'=>'select_1717755396737',
                         'filterInputOptions' => [
            'class' => 'form-control search border',
            'placeholder' => (new backend\models\Applicant)->getAttributeLabel('select_1717755396737'),
                         ],
                     ],
           ['attribute'=>'first_name',
                         'filterInputOptions' => [
            'class' => 'form-control search border',
            'placeholder' => (new backend\models\Applicant)->getAttributeLabel('first_name'),
                         ],
                     ],
           ['attribute'=>'last_name',
                         'filterInputOptions' => [
            'class' => 'form-control search border',
            'placeholder' => (new backend\models\Applicant)->getAttributeLabel('last_name'),
                         ],
                     ],
              ['attribute'=>'',
            'label' => 'Client',
            'value' => function ($dataProvider) {
            $Client=\backend\models\Client::findOne($dataProvider->client_id);
                if($Client)
                    return $Client->client_name;
                else
                return '';
            },
            'contentOptions' => ['style' => 'width: 15%;'],
            'filter' => Html::activeDropDownList($searchModel, 'client_id',$clientFilter,['prompt'=>'- Client -','class' => 'form-control search border']),
            'filterInputOptions' => [
            'class' => 'form-control search border',
            'placeholder' => 'Client',
        ],
        ],
           ['attribute'=>'mobile_number',
                         'filterInputOptions' => [
            'class' => 'form-control search border',
            'placeholder' => (new backend\models\Applicant)->getAttributeLabel('mobile_number'),
                         ],
                     ],
           ['attribute'=>'email',
                         'filterInputOptions' => [
            'class' => 'form-control search border',
            'placeholder' => (new backend\models\Applicant)->getAttributeLabel('email'),
                         ],
                     ],

            ['class' => 'yii\grid\ActionColumn',
            'headerOptions' => ['class' => 'abc'],
    'contentOptions' => ['style' => 'width:200px;'],
    'buttons'=>[
    'delete' => function($url, $model){
    $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/delete', 'id' => $model->id]);
    return '<a class="btn btn-default edit" href="'.$url.'" data-method="post" data-confirm = "'.Yii::t('yii', 'Are you sure you want to delete this item?').'",  title="Delete"><i class="fa fa-trash"></i></a>';
    },
   'update' => function($url, $model){
   $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/update', 'id' => $model->id]);
   return'<a class="btn btn-default edit" href="'.$url.'" data-method="post" title="Update"><i class="fa fa-pencil-square-o"></i></a>';
    },
    'dependent'=>function($url, $model){
                            
                            $url=Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/create','parent_id'=> $model->id]);
                            return'<a class="mr-15" href="'.$url.'" title="Add Dependents"><i class="fa fa-users text-primary"></i></a>';
                        },
    'link'=>function($url, $model){
$url=Yii::$app->urlManager->createUrl(['/cases/index','CasesSearch[applicant_id]'=> $model->id]);
return'<a class="btn btn-default edit" href="'.$url.'" title="Cases"><i class="fa fa-suitcase"></i></a>';
}    ],
            'template' => Yii::$app->user->identity->getRole() != GlobalConstant::ROLE_SUPERADMIN ? '{view}{update} {delete}': '{view}',
            ],
        ],
    ]); ?>
    </div>
</div>


