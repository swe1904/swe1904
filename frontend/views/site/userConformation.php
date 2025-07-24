<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<style>
    .col-md-12 .time{
        display: flex;
        -webkit-box-align: center;
        align-items: center;
        justify-content: center;
        margin-bottom: 5px;
        font-weight: 400;
        font-size: 20px;
    }
    .MembershipPlan-price {
        display: block;
        margin-bottom: 5px;
        /* font-size: 48px; */
        font-size: 4rem;
        text-align: center;
        color: #1f2836;
    }
    .time-block a{
        width:80%;
        margin-left:10%;
        text-align: center;
        display: flex;
        -webkit-box-align: center;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-top: 150px;
    }
</style>
<!--free subscription content start here -->
<?php if(isset($_GET['subscription']) && $_GET['subscription']=='Free'): ?>
    <div class="container">
        <br/><br/><br/><br/>
        <div class="col-md-12">
            <h3 class="text-center">
               Are you want to sure activate the <b>free</b> subscription.
            </h3>
            <div class="col-md-12">
                <div class="col-md-12 time-block">
                    <br/>
                    <span class="time">10 Receipt/Month</span>
                    <br/>
                <span class="MembershipPlan-price">
                    ₹ 00<sup>.00</sup>
                </span>
                    <span class="time">Per month</span>
                    <?php $form = ActiveForm::begin(['method' => 'POST', 'action' => '@frontendUrl/paypal-recurring-payments-profile/create-recurring-profile',]); ?>
                    <div class="form-group">
                        <?=  Html::hiddenInput('amt', '0.00'); ?>
                    </div>
                    <div class="form-group text-center">
                        <?= Html::submitButton('Confirm & Activate', [''=>'','class' => 'btn btn-primary', 'style' => 'margin-top:px;']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <br/>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<!--free subscription content end here -->

<!--Monthly subscription content start here -->
<?php if(isset($_GET['subscription']) && $_GET['subscription']=='Monthly'): ?>
    <div class="container">
        <br/><br/><br/><br/>
        <div class="col-md-12">
            <h3 class="text-center">
                Are you want to sure activate the <b>Monthly</b> subscription.
            </h3>
            <div class="col-md-12">
                <div class="col-md-12 time-block">
                    <br/>
                    <span class="time">Unlimited Receipt/Month</span>
                    <br/>
                <span class="MembershipPlan-price">
                    ₹ 300<sup>.00</sup>
                </span>
                    <span class="time">Per month</span>
                    <?php $form = ActiveForm::begin(['method' => 'POST', 'action' => '@frontendUrl/paypal-recurring-payments-profile/create-recurring-profile',]); ?>
                    <div class="form-group">
                        <?=  Html::hiddenInput('amt', '300.00'); ?>
                    </div>
                    <div class="form-group text-center">
                        <?= Html::submitButton('Confirm & Activate', [''=>'','class' => 'btn btn-primary', 'style' => 'margin-top:px;']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <br/>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<!--Monthly subscription content end here -->

<!--Yearly subscription content start here -->
<?php if(isset($_GET['subscription']) && $_GET['subscription']=='Yearly'): ?>
    <div class="container">
        <br/><br/><br/><br/>
        <div class="col-md-12">
            <h3 class="text-center">
                Are you want to sure activate the <b>Yearly</b> subscription.
            </h3>
            <div class="col-md-12">
                <div class="col-md-12 time-block">
                    <br/>
                    <span class="time">Unlimited</span>
                    <br/>
                <span class="MembershipPlan-price">
                    ₹ 200<sup>.00</sup>
                </span>
                    <span class="time">Per month</span>
                    <?php $form = ActiveForm::begin(['method' => 'POST', 'action' => '@frontendUrl/paypal-recurring-payments-profile/create-recurring-profile',]); ?>
                    <div class="form-group">
                        <?=  Html::hiddenInput('amt', '200.00'); ?>
                    </div>
                    <div class="form-group text-center">
                        <?= Html::submitButton('Confirm & Activate', [''=>'','class' => 'btn btn-primary', 'style' => 'margin-top:px;']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <br/>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<!--Yearly subscription content end here -->



