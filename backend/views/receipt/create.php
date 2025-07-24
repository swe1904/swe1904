<?php

use app\components\GlobalConstant;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Receipt */


if(isset($_GET['Receipt']['quotes']))
    $this->title = 'Create Quote';
    elseif(isset($_GET['Receipt']['invoices']))
    $this->title = 'Create Invoice';
else
    $this->title = 'Create Receipt';

$this->params['breadcrumbs'][] = ['label' => 'Receipts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="col-md-12">
    <div class="panel panel-default card-view panel-refresh">
        <div class="panel-hading">

        </div>
        <div class="receipt-create">
            <?= $this->render('_form', [
                'model' => $model,
                'drawnArray'=>$drawnArray,
                'clientArray'=>$clientArray,
                'currencyArray'=>$currencyArray,
                'organisationModel'=>$organisationModel,
            ]) ?>
        </div>
    </div>
</div>
</div>

