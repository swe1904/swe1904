<?php

use common\models\UserProfile;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserProfile */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = Yii::t('backend', 'Edit profile')
?>


<div class="col-md-12">
    <div class="panel panel-default border-panel card-view">
        <div class="panel-heading">
            <div class="pull-left">
                <h6 class="panel-title txt-dark">Edit Profile</h6>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-wrapper collapse in">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-wrap">
                            <?php $form = ActiveForm::begin(['fieldConfig' => [
                                'options' => [
                                    'options' => ['class' => 'form-group invisible']
                                ],
                            ],
                            ]); ?>
                                <div class="form-body">
                                    <div>
                                        <?php echo $form->field($model, 'picture')->widget(\trntv\filekit\widget\Upload::classname(), [
                                            'url' => ['avatar-upload']
                                        ]) ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?php echo $form->field($model, 'firstname', ['template' => '<label>{label}</label><div class="form-group"><div class="form-line">{input}</div></div>']); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?php echo $form->field($model, 'middlename', ['template' => '<label>{label}</label><div class="form-group"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => 255, 'class' => 'form-control']) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?php echo $form->field($model, 'lastname', ['template' => '<label>{label}</label><div class="form-group"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => 255, 'class' => 'form-control']) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php echo $form->field($model, 'locale')->dropDownlist(Yii::$app->params['availableLocales'], ['class' => 'show-tick form-control']) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?php echo $form->field($model, 'gender')->dropDownlist([
                                                UserProfile::GENDER_FEMALE => Yii::t('backend', 'Female'),
                                                UserProfile::GENDER_MALE => Yii::t('backend', 'Male')], ['class' => 'show-tick form-control']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions mt-10">
                                    <button type="submit" class="btn btn-rounded btn-success mr-10"> Update</button>
                                </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



