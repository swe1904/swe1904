<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Organisation */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Organisations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organisation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'name',
            'tagline',
            'address',
            'landline',
            'mobile',
            'email:email',
            'website',
            'logo',
            'service_tax_number',
            'service_tax_percentage',
            'currency_id',
            'receipt_increment_alpahabetic_part',
            'receipt_increment_number_part',
            'date_format',
            'logo_to_be_printed',
        ],
    ]) ?>

</div>
