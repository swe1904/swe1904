<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Create Position';
$this->params['breadcrumbs'][] = ['label' => 'Positions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- Main Wrapper -->
<div class="container mt-4 position-create">

    <!-- Page Title -->
    <div class="mb-3">
        <h4><strong><?= Html::encode($this->title) ?></strong></h4>
    </div>

    <!-- Styled Card -->
    <div class="card position-card">
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <!-- Full Width: Position Name -->
                <div class="col-md-12">
                    <?= $form->field($model, 'name')->textInput([
                        'class' => 'form-control',
                        'placeholder' => 'Enter Position Name',
                        'maxlength' => true,
                        'style' => 'font-size: 15px; height: 42px;'
                    ])->label('Position Name', ['class' => 'form-label fw-semibold']) ?>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-end mt-3">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-success btn-sm']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!-- Responsive CSS -->
<style>
    /* Common Card Styling */
    .position-card {
        background-color: #f3f8fe;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 20px;
    }

    /* Mobile Styles */
    @media (max-width: 767px) {
        .position-card {
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px;
        }

        .form-label {
            font-size: 14px;
        }

        .form-control {
            font-size: 14px !important;
            height: 40px !important;
        }

        .btn-sm {
            width: 100%;
            padding: 10px;
        }

        .text-end {
            text-align: center !important;
        }
    }
</style>
