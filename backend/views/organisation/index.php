<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrganisationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Organisations';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-hading">
            <div class="organisation-index">

                <h6><?= Html::encode($this->title) ?></h6>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                <p>
                    <?= Html::a('Create Organisation', ['create'], ['class' => 'btn btn-rounded btn-success mr-10 mb-20 mt-20']) ?>
                </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['class' => 'abc']],


                        [ 'attribute' =>  'id',
                            'filterInputOptions' => [
                                'class' => 'form-control search',
                                'placeholder' => 'Id'
                            ]],
                        [ 'attribute' =>  'user_id',
                            'filterInputOptions' => [
                                'class' => 'form-control search',
                                'placeholder' => 'User Id'
                            ]],
                        [ 'attribute' =>  'name',
                            'filterInputOptions' => [
                                'class' => 'form-control search',
                                'placeholder' => 'Name'
                            ]],
                        [ 'attribute' =>  'tagline',
                            'filterInputOptions' => [
                                'class' => 'form-control search',
                                'placeholder' => 'Tagline'
                            ]],
                        [ 'attribute' =>  'address',
                            'filterInputOptions' => [
                                'class' => 'form-control search',
                                'placeholder' => 'Address'
                            ]],
                        // 'landline',
                        // 'mobile',
                        // 'email:email',
                        // 'website',
                        // 'logo',
                        // 'service_tax_number',
                        // 'service_tax_percentage',
                        // 'currency_id',
                        // 'receipt_increment_alpahabetic_part',
                        // 'receipt_increment_number_part',
                        // 'date_format',
                        // 'logo_to_be_printed',

                        ['class' => 'yii\grid\ActionColumn','headerOptions' => ['class' => 'abc','style' => 'width:30%'],],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>


