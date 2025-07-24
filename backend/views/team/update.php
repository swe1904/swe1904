<?php

use app\components\GlobalConstant;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\User;
use backend\models\Team;

/** @var yii\web\View $this */
/** @var backend\models\Team $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Update Team: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Teams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Get parent teams excluding current record
$parentTeams = Team::find()->andWhere(['<>', 'id', $model->id])->all();
?>

<style>
.select2-container--krajee .select2-selection--single {
    height: 40px !important;
    display: flex;
    align-items: center;
    border-radius: 6px !important;
}
.select2-selection__rendered {
    padding-top: 6px !important;
    padding-left: 10px;
}
.card-custom {
    border-radius: 12px;
    background: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    padding: 2rem;
}
.section-wrapper {
    background-color: #eef5fa;
    padding: 2rem;
    border-radius: 14px;
}
</style>

<div class="container-fluid px-4">
    <h3 class="text-center text-dark mb-4 fw-bold">
        Update Team
    </h3>

    <div class="section-wrapper">
        <div class="card-custom">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row g-3">

                <!-- Team Name -->
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'Enter team name',
                        'class' => 'form-control'
                    ])->label('Team Name') ?>
                </div>

                <!-- Team Manager -->
                <div class="col-md-6">
                 <?= $form->field($model, 'team_manager')->widget(Select2::class, [
    'data' => ArrayHelper::map(
        \common\models\User::find()
            ->innerJoin('tbl_rbac_auth_assignment', 'tbl_rbac_auth_assignment.user_id = tbl_user.id')
            ->where(['tbl_rbac_auth_assignment.item_name' => GlobalConstant::ROLE_TEAM_MANAGER])
            ->all(),
        'id',
        'fullname' // Assumes getFullname() is defined in the User model
    ),
    'options' => ['placeholder' => 'Select team manager'],
    'pluginOptions' => ['allowClear' => true],
])->label('Team Manager') ?>

                </div>

                <!-- Parent Team -->
                <div class="col-md-6">
                    <?= $form->field($model, 'parent_dept_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map($parentTeams, 'id', 'name'),
                        'options' => ['placeholder' => 'Select parent team (optional)'],
                        'pluginOptions' => ['allowClear' => true],
                    ])->label('Parent Team') ?>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center mt-4">
                <?= Html::submitButton('Update', ['class' => 'btn btn-warning px-4 py-2', 'style' => 'border-radius: 6px;']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
