<?php

use backend\modules\polling\models\PollingQuiz;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\switchinput\SwitchInput;
use backend\components\MultipleFields;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuiz */
/* @var $form yii\bootstrap\ActiveForm */
?>
<style>
    .input_style {
        cursor: pointer;
    }
</style>

<?php $form = ActiveForm::begin([
   // 'action' => '/backend/web/polling/polling-quiz/create   ',
    'fieldConfig' => [
        'options' => [
            'options' => [
                'class' => 'form-group invisible',
            ],
        ],
    ],
]);
?>

<div class="col-md-12">
    <?php echo $form->field($model, 'name', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textInput(['maxlength' => true,]) ?>
</div>

<?php echo $form->field($model, 'polling_id')->hiddenInput([])->label(false) ?>

<div class="col-md-12">
    <?php echo $form->field($model, 'description', ['template' => '<label>{label}</label><div class="form-group border"><div class="form-line">{input}</div></div>'])->textarea(['rows' => 6]) ?>
</div>


<div class="col-md-12 mb-15">
    <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-rounded btn-success mr-10']) ?>
</div>

<?php ActiveForm::end(); ?>

<script>

    function openQuestion(obj) {
        console.log($(obj).val());
        window.open($(obj).val());
    }

    function myFunction() {
        console.log('doing something!');
        var ll = $('#pollingquiz-show_result').bootstrapSwitch('state');
        console.log(ll);
        if (ll == true) {
            $('#lengthoftime').show();
        }
        else {
            $('#lengthoftime').hide();
        }
    }
</script>