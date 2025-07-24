<?php

use app\components\GlobalConstant;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Department;
use common\models\User;

/** @var yii\web\View $this */
/** @var backend\models\Department $model */
/** @var yii\widgets\ActiveForm $form */
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
        <?= $model->isNewRecord ? 'Create Department' : 'Update Department' ?>
    </h3>
    <!-- <div class="action-buttons">
        <?= Html::a('Create Position', ['position/create'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Create Role', ['role/create'], ['class' => 'btn btn-primary']) ?>
    </div> -->
    <div class="section-wrapper">
        <div class="card-custom">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row g-3">

                <!-- Department Name -->
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'Enter department name',
                        'class' => 'form-control'
                    ])->label('Department Name') ?>
                </div>

                <!-- Department Manager -->
                <div class="col-md-6">
                        <?= $form->field($model, 'department_manager')->widget(Select2::class, [
                            'data' => ArrayHelper::map(
                                User::find()
                                    // Join the tbl_rbac_auth_assignment table to filter by role
                                    ->innerJoin('tbl_rbac_auth_assignment', 'tbl_rbac_auth_assignment.user_id = tbl_user.id')
                                    // Filter by 'Department Manager' role
                                    ->where(['tbl_rbac_auth_assignment.item_name' => GlobalConstant::ROLE_DEPARTMENT_MANAGER])
                                    // Fetch the users who have the Department Manager role
                                    ->all(),
                                'id', 
                                'fullname' // Display username in the dropdown
                            ),
                            'options' => ['placeholder' => 'Select Department Manager'],
                            'pluginOptions' => ['allowClear' => true],
                        ])->label('Department Manager') ?>
                    </div>


                <!-- Parent Department -->
                <div class="col-md-6">
                    <?= $form->field($model, 'parent_department_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map(
                            Department::find()->andWhere(['<>', 'id', $model->id ?? 0])->all(), 'id', 'name'
                        ),
                        'options' => ['placeholder' => 'Select parent department (optional)'],
                        'pluginOptions' => ['allowClear' => true],
                    ])->label('Parent Department') ?>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center mt-4">
                <?= Html::submitButton(
                    $model->isNewRecord ? 'Submit' : 'Update',
                    ['class' => 'btn btn-success px-4 py-2', 'style' => 'border-radius: 6px;']
                ) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
