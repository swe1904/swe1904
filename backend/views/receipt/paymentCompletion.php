<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<?php $form = ActiveForm::begin(['method' => 'POST', 'action' => 'express',]); ?>
                    <div class="form-group">
                        <?=  Html::hiddenInput('token', $_GET['token']); ?>
                        <?=  Html::hiddenInput('payerID', $_GET['PayerID']); ?>
                        <?=  Html::hiddenInput('receipt_id', $_GET['receipt_id']); ?>
                        <?=  Html::hiddenInput('paymentAction', 'Sale'); ?>
                        <?=  Html::hiddenInput('amt', $receiptModel->amount); ?>
                        <?=  Html::hiddenInput('currencyCode', 'USD'); ?>
                        <?=  Html::hiddenInput('notifyURL', 'https://www.google.co.in'); ?>
                    </div>
                    <div class="form-g  roup">
                        <?= Html::submitButton('Confirm', [''=>'','class' => 'btn btn-info', 'style' => 'margin-top:px;']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>