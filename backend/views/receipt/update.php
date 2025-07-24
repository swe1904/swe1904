<?php

use app\components\GlobalConstant;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Receipt */

if($model->is_receipt==-1)
    $this->title = 'Update Quote' . $model->receipt_increment_alphabetic_part . $model->receipt_increment_number_part;
    elseif($model->is_receipt==0)
    $this->title = 'Update Invoice' . $model->receipt_increment_alphabetic_part . $model->receipt_increment_number_part;
else
    $this->title = 'Update Receipt' . $model->receipt_increment_alphabetic_part . $model->receipt_increment_number_part;

$this->params['breadcrumbs'][] = ['label' => 'Receipts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<!-- Basic design -->
<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-hading">
            <div class="receipt-update">
                <?= $this->render('_form', [
                    'vatRate' => $organisationModel->vat_rate,
                    'organisationModel' =>$organisationModel,
                    'model' => $model,
                    'drawnArray'=>$drawnArray,
                    'clientArray'=>$clientArray,
                    'receiptServiceModel'=>$receiptServiceModel,
                    'currencyArray'=>$currencyArray,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]) ?>
            </div>
        </div>
    </div>
</div>


