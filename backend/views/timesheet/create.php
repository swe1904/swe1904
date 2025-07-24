<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use backend\models\Employee as ModelsEmployee;

/** @var yii\web\View $this */
/** @var app\models\Timesheet $model */

$this->title = 'Add Timesheet Entry';
$this->params['breadcrumbs'][] = $this->title;

// Get all employees dynamically
$employees = ArrayHelper::map(ModelsEmployee::find()->all(), 'user_id', 'preferred_full_name'); // Adjust field name if needed
?>

<div class="container mt-4">
    <div class="card shadow p-4">
        <h3 class="mb-4 text-primary"><?= Html::encode($this->title) ?></h3>

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'employee_id')->dropDownList(
                    $employees,
                    ['prompt' => 'Select Employee', 'class' => 'form-control']
                ) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'date')->input('date', ['class' => 'form-control']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'start_time')->input('time', ['class' => 'form-control', 'id' => 'start-time']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'end_time')->input('time', ['class' => 'form-control', 'id' => 'end-time']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'total_duration')->textInput([
                    'readonly' => true,
                    'class' => 'form-control',
                    'id' => 'total-duration',
                    'placeholder' => 'Will auto-calculate'
                ]) ?>
            </div>
           
        </div>

        <div class="form-group mt-3">
            <?= Html::submitButton('Save Entry', ['class' => 'btn btn-success w-100']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$script = <<<JS
    function calculateDuration() {
        let start = document.getElementById('start-time').value;
        let end = document.getElementById('end-time').value;

        if (start && end) {
            const startDate = new Date("1970-01-01T" + start + "Z");
            const endDate = new Date("1970-01-01T" + end + "Z");

            let diff = (endDate - startDate) / 1000; // seconds
            if (diff < 0) diff += 24 * 3600; // handle overnight

            const hours = Math.floor(diff / 3600).toString().padStart(2, '0');
            const minutes = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
            const seconds = (diff % 60).toString().padStart(2, '0');

            document.getElementById('total-duration').value = `\${hours}:\${minutes}:\${seconds}`;
        }
    }

    document.getElementById('start-time').addEventListener('change', calculateDuration);
    document.getElementById('end-time').addEventListener('change', calculateDuration);
JS;

$this->registerJs($script);

?>
