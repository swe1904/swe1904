<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Organisation */

//$this->title = 'Create Organisation';
//$this->params['breadcrumbs'][] = ['label' => 'Organisations', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<!--    <h1>--><?php //echo Html::encode($this->title) ?><!--</h1>-->


            <div class="panel-hading">
                <?= $this->render('_form', [
                    'model' => $model,
                    'currencyArray'=>$currencyArray,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]) ?>
            </div>


