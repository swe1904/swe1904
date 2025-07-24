<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\switchinput\SwitchInput;
use backend\components\MultipleFields;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuiz */
/* @var $form yii\bootstrap\ActiveForm */
?>
<style>
    .input_style {
        cursor: pointer;
    }
</style>
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

<?php $form = ActiveForm::begin([
        'action' => '/backend/web/polling/polling-quiz/create   ',
        'fieldConfig' => [
        'options' => [
            'options' => [
                'class' => 'form-group invisible',
                ],
            ],
        ],
    ]);
?>


<div class="row">
    <div class="col-md-12">
        <?php echo $form->field($model, 'name')->textInput(['maxlength' => true,]) ?>
    </div>

    <?php echo $form->field($model, 'polling_id')->hiddenInput([])->label(false) ?>

    <div class="col-md-12">
        <?php echo $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    </div>
<!--    --><?php //echo $form->field($model, 'polling_quiz_play_url')->textInput(['maxlength' => true, 'readonly' => true,  'class'=>'formInput', 'onClick' => 'openQuestion(this)']) ?>
<!--    --><?php //echo $form->field($model, 'show_question_url_result')->textInput(['maxlength' => true, 'readonly' => true, 'class'=>'formInput', 'onClick' => 'openQuestion(this)']) ?>
<!--    --><?php //echo $form->field($model, 'redirect_link')->textInput(['class'=>'formInput']); ?>
<!--Commented-pangea-->
<!-- <div class="row">
    <div class="col-md-2"><?php /*echo $form->field($model, 'show_result')->widget(SwitchInput::classname(), [
            'type' => SwitchInput::CHECKBOX,
            'pluginEvents' => [
                "switchChange.bootstrapSwitch" => "function() { myFunction(); }",
            ],]); */?>
    </div>

    <div class="col-md-6">

        <?php /*echo $form->field($model, 'show_btn_on_result_page')->widget(SwitchInput::classname(), [
            'type' => SwitchInput::CHECKBOX
        ]); */?></div>
</div>-->
<!---show when show_result is set to yes -->
<?php /*if($model->show_result=='1'){*/?><!--
<div class="lengthoftime" id="lengthoftime">
    <?php /*echo $form->field($model, 'lengthoftime')->textInput()->label('Length of Time(Days)'); */?>
</div>
<?php /*}else{*/?>
    <div class="lengthoftime" id="lengthoftime" style="display: none">
        <?php /*echo $form->field($model, 'lengthoftime')->textInput(['value' => '0'])->label('Length of Time(Days)'); */?>
    </div>
<?php /*}*/?>
--><?php /* MultipleFields::widget(['models' => $model->pollingQuizTeams]) */?>
<!--Commented-pangea-->
    <div class="col-md-12 mb-15 mt-10">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-rounded btn-success mr-10']) ?>
    </div>

</div>


<?php ActiveForm::end(); ?>
