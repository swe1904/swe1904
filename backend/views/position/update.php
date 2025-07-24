<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Update Position';
$this->params['breadcrumbs'][] = ['label' => 'Positions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="position-update container mt-4">
    <!-- Ribbon Section -->
    <div class="ribbon-wrapper">
        <div class="ribbon"><b><?= Html::encode($this->title) ?></b></div>
    </div>

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'form-horizontal',
            'style' => 'max-width: 600px; margin: 0 auto;',
        ]
    ]); ?>

    <div class="form-group">
        <?= $form->field($model, 'name')->textInput([
            'id' => 'position-name',
            'class' => 'form-control',
            'placeholder' => 'Enter position name'
        ]) ?>
    </div>

    <div class="form-group text-center">
        <?= Html::submitButton('Update', ['class' => 'btn btn-warning btn-sm']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<!-- Script: Uppercase Conversion -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const positionInput = document.querySelector('#position-name');
        if (positionInput) {
            positionInput.addEventListener('input', function () {
                positionInput.value = positionInput.value.toUpperCase();
            });
        }
    });
</script>

<!-- Custom CSS -->
<style>
/* Responsive Ribbon */
.ribbon-wrapper {
    width: 100%;
    display: flex;
    justify-content: flex-start;
    margin-bottom: 20px;
}

.ribbon {
    background-color: rgb(52, 52, 53);
    color: white;
    padding: 10px 20px;
    font-size: 18px;
    font-weight: bold;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Form and Inputs */
.form-group {
    margin-bottom: 20px;
}

.form-control {
    font-size: 16px;
    padding: 10px;
}

/* Buttons */
.btn-sm {
    padding: 6px 14px;
}

/* Mobile Responsive */
@media (max-width: 576px) {
    .position-update.container {
        padding: 15px;
    }

    .form-control {
        font-size: 15px;
        padding: 8px;
    }

    .ribbon {
        font-size: 16px;
        padding: 8px 16px;
    }
}
</style>
