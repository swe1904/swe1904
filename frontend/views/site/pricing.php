<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<style>
    .time-block{
        border:2px solid #696969;
    }
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
<div class="container">
    <!--Row with three equal columns-->
    <div class="row">
        <div class="col-md-4">
            <h3 class="text-center">
                Free Forever
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
                    <?php
                    if(isset($paypalRecurringPaymentsModel) && !empty($paypalRecurringPaymentsModel)){
                        if($paypalRecurringPaymentsModel->plan_id=='1'){
                            echo \yii\helpers\Html::a(Yii::t('backend', 'Cancel'), ['/site/cancel-user-subscription','cancel-subscription'=>'00.00'], ['class' => 'btn btn-primary','data-confirm'=>"Are you sure you want to sure deactivate the subscription?"]);
                        }elseif(isset($paypalRecurringPaymentsModel) && $paypalRecurringPaymentsModel->plan_id == '2' || $paypalRecurringPaymentsModel->plan_id == '3'){
                            echo \yii\helpers\Html::a(Yii::t('backend', 'Enroll'), ['/site/user-conformation','subscription'=>'Free'], ['class' => 'btn btn-primary']);
                    }
                    }elseif(!isset($paypalRecurringPaymentsModel)){
                        echo \yii\helpers\Html::a(Yii::t('backend', 'Enroll'), ['/site/user-conformation','subscription'=>'Free'], ['class' => 'btn btn-primary']);
                    }
                    ?>
                <br/>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <h3 class="text-center">
                Monthly
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
                    <?php
                    if(isset($paypalRecurringPaymentsModel) && !empty($paypalRecurringPaymentsModel)){
                        if($paypalRecurringPaymentsModel->plan_id=='2'){
                            echo \yii\helpers\Html::a(Yii::t('backend', 'Cancel'), ['/site/cancel-user-subscription', 'cancel-subscription'=>'300.00'], ['class' => 'btn btn-primary','data-confirm'=>"Are you sure you want to sure deactivate the subscription?"]);
                        }elseif(isset($paypalRecurringPaymentsModel) && $paypalRecurringPaymentsModel->plan_id == '1' || $paypalRecurringPaymentsModel->plan_id == '3'){
                            echo \yii\helpers\Html::a(Yii::t('backend', 'Enroll'), ['/site/user-conformation','subscription'=>'Monthly'], ['class' => 'btn btn-primary']);
                        }
                    }elseif(!isset($paypalRecurringPaymentsModel)){
                        echo \yii\helpers\Html::a(Yii::t('backend', 'Enroll'), ['/site/user-conformation','subscription'=>'Monthly'], ['class' => 'btn btn-primary']);
                    }
                    ?>
                <br/>
            </div>
                </div>
        </div>
        <div class="col-md-4">
            <h3 class="text-center">
                Yearly
            </h3>
            <div class="col-md-12">
                <div class="col-md-12 time-block">
                    <br/>
                <span class="time">Unlimited</span>
                <br/>
                <span class="MembershipPlan-price">
                    ₹ 200<sup>.00</sup>
                    <span class="time">Per month</span>
                </span>
                    <?php
                    if(isset($paypalRecurringPaymentsModel) && !empty($paypalRecurringPaymentsModel)){
                        if($paypalRecurringPaymentsModel->plan_id=='3'){
                            echo \yii\helpers\Html::a(Yii::t('backend', 'Cancel'), ['/site/cancel-user-subscription','cancel-subscription'=>'200.00'], ['class' => 'btn btn-primary','data-confirm'=>"Are you sure you want to sure deactivate the subscription?"]);
                        }elseif(isset($paypalRecurringPaymentsModel) && $paypalRecurringPaymentsModel->plan_id == '1' || $paypalRecurringPaymentsModel->plan_id == '2'){
                            echo \yii\helpers\Html::a(Yii::t('backend', 'Enroll'), ['/site/user-conformation','subscription'=>'Yearly'], ['class' => 'btn btn-primary']);
                        }
                    }elseif(!isset($paypalRecurringPaymentsModel)){
                        echo \yii\helpers\Html::a(Yii::t('backend', 'Enroll'), ['/site/user-conformation','subscription'=>'Yearly'], ['class' => 'btn btn-primary']);
                    }
                    ?>
                <br/>
            </div>
                </div>
        </div>
    </div>
</div>