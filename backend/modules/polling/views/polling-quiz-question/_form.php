<?php

use backend\modules\mii\jsonData\Applicant;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\modules\polling\models\PollingQuizQuestion;
use backend\modules\polling\models\base\PollingQuizQuestionType;
use yii\helpers\ArrayHelper;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuizQuestion */
/* @var $form yii\bootstrap\ActiveForm */
?>
<style>
    .input_style {
        cursor: pointer;
    }
</style>

<script>
    function checkBoxHtmlRadio(addCheck, auto_generated) {
        var x = $('.field_wrapper_option div.multiple_parent').length;

        var checked = "";
        if (addCheck) {
            checked = "checked"
        } else {
            if (!auto_generated) {

                checked = "checked";
            }
        }
        var html_str = '<div class="radio">' +
            '<label>' +
            '<input type="radio" name="question_option_correct" value="' + x + '" ' + checked + ' onclick="setCorrectMultipleForOption(this)">' +
            '</label>' +
            '</div>';
        return html_str;
    }
    function checkBoxHtmlOption(correct_option_id) {
        var x = $('.field_wrapper_response div.multiple_parent').length;
        var checked = "";
        if (correct_option_id == 1) {
            checked = "checked"
        }
        var html_str = '<div class="radio">' +
            '<label>' +
            '<input type="checkbox" name="question_response_correct[]" value="' + x + '" ' + checked + ' onchange="setCorrectMultipleForResponse(this)">' +
            '</label>' +
            '</div>';
        return html_str;
    }
    function setCorrectMultipleForResponse(obj) {
        var t = $(obj).parents('div.multiple_parent').index();
        $(obj).val(t);
    }
    function setCorrectMultipleForOption(obj) {
        var t = $(obj).parents('div.multiple_parent').index();
        $(obj).val(t);
    }
    function removeMultipleResponse(obj) {
        $(obj).parents('div.multiple_parent').remove();
    }
    function addMultipleListing(input_val, type, correct_option_id, option_id, auto_generated) {


        var input_name = "";
        var radio_group_string = "";
        if (type == 1) {
            // type multiple response
            radio_group_string = checkBoxHtmlOption(correct_option_id);
            input_name = "question_type_response[]";
        } else {
            // type multiple option
            var addCheck = false;
            var correct_option_id = parseInt(correct_option_id);
            var option_id = parseInt(option_id);
            if (correct_option_id != -1) {
                if (option_id == correct_option_id) {
                    addCheck = true;
                }
            }
            radio_group_string = checkBoxHtmlRadio(addCheck, auto_generated);
            input_name = "question_type_option[]";
        }
        var fieldHTML = '<div class="multiple_parent row" style="padding: 10px !important;">' +
            '<div class="col-lg-12">' +
            '<div class="col-lg-6">' +
            '<input type="text" class="formInput" name="' + input_name + '" value="' + input_val + '"/>' +
            '</div>' +
            '<div class="col-lg-6">' +
            '<a href="javascript:void(0);" class="btn btn-labeled btn-danger" style="float:right" onclick="removeMultipleResponse(this)">Remove</a>' +
            '</div>' +
            '<div class="col-lg-3">' +
            <!--Commented-pangea-->
            //   radio_group_string+
            <!--Commented-pangea-->
            '</div>' +
            '</div>' +
            '</div>';
        return fieldHTML;
    }
    function addMultipleResponse() {
        var maxField = 10; //Input fields increment limitation
        var wrapper = $('.field_wrapper_response'); //Input field wrapper
        var fieldHTML = addMultipleListing("", 1, -1, -1, false);

        var x = $('.field_wrapper_response div.multiple_parent').length; //Initial field counter is 1

        if (x < maxField) { //Check maximum number of input fields
            $(wrapper).append(fieldHTML); // Add field html
        }
    }
    function addMultipleOption() {
        var maxField = 10; //Input fields increment limitation
        var wrapper = $('.field_wrapper_option'); //Input field wrapper
        var fieldHTML = addMultipleListing("", 2, -1, -1, false);

        var x = $('.field_wrapper_option div.multiple_parent').length; //Initial field counter is 1

        if (x < maxField) { //Check maximum number of input fields
            $(wrapper).append(fieldHTML); // Add field html
        }
    }
    /*$(document).ready(function () {
     var maxField = 10; //Input fields increment limitation
     var addButton = $('.add_button'); //Add button selector
     var wrapper = $('.field_wrapper'); //Input field wrapper
     var fieldHTML = '<div class="row" style="padding: 10px !important;">' +
     '<div class="col-lg-12">' +
     '<div class="col-lg-6">' +
     '<input type="text" class="form-control" name="question_type[]" value=""/>'+
     '</div>'+
     '<div class="col-lg-3">' +
     '<a href="javascript:void(0);" class="btn btn-labeled btn-danger remove_button" style="float:right">Remove</a>'+
     '</div>'+
     '<div class="col-lg-3">' +
     checkBoxHtml()+
     '</div>'+
     '</div>' +
     '</div>';

     var x = 1; //Initial field counter is 1
     $(addButton).click(function () { //Once add button is clicked
     if (x < maxField) { //Check maximum number of input fields
     x++; //Increment field counter
     $(wrapper).append(fieldHTML); // Add field html
     }
     });
     $(wrapper).on('click', '.remove_button', function (e) { //Once remove button is clicked
     e.preventDefault();
     $(this).parent('div').remove(); //Remove field html
     x--; //Decrement field counter
     });
     });*/
    function openQuestion(obj) {
        window.open($(obj).val());
    }
    function jk(obj) {
        if ($(obj).is(':checked')) {
            $(".correct_rating").css('display', 'block');
        } else {
            $(".correct_rating").css('display', 'none');
        }
    }
</script>
<div class="row clearfix">
    <div class="col-md-6 col-md-offset-3">

        <div class="card">
            <div class="header">
                <div class="row clearfix">

                    <?php $form = ActiveForm::begin(['enableClientValidation' => true, 'fieldConfig' => [
                        'options' => [
                            'options' => ['class' => 'form-group invisible']
                        ],
                    ],]); ?>

                    <?php echo $form->errorSummary($model); ?>

                    <?php /*echo $form->field($model, 'polling_quiz_id')->textInput() */ ?>

                    <?php /*echo $form->field($model, 'title')->textInput(['maxlength' => true]) */ ?>
                    <div class="col-md-12">
                    <?php echo $form->field($model, 'question')->textarea(['rows' => 6]) ?>

                    <label class="control-label custom-label" for="type">
                        <?php echo $model->getAttributeLabel('type'); ?>
                    </label>
                    <?php echo $form->field($model, 'type',['template'=>'<label>{label}</label>{input}{hint}'])->dropDownList(ArrayHelper::map(PollingQuizQuestionType::find()->all(), 'id', 'name'), ['onchange' => 'onMultipleSelection();'])->label(false); ?>

                    <label class="control-label custom-label" for="applicant_attribute">
                        <?php echo $model->getAttributeLabel('applicant_attribute'); ?>
                    </label>
                    <?php $attribute = $model->getAttributeFields();
                    if (PollingQuizQuestion::find()->where(['polling_quiz_id' => $model->polling_quiz_id, 'applicant_attribute' => 'email'])->count() > 0 && $model->applicant_attribute != 'email') {
                        //  ArrayHelper::remove( $attribute,'email');
                    }
                    echo $form->field($model, 'applicant_attribute',['template'=>'<label>{label}</label>{input}{hint}'])->dropDownList($attribute, ['prompt' => 'select', ])->label(false);
                    // set default required to false
                    //  echo $form->field($model, 'required')->hiddenInput(['value'=>0])->label(false);
                    ?>
                    <?php //echo $form->field($model, 'polling_quiz_question_direct_id')->hiddenInput([])->label(false) ?>
                    <?php //echo $form->field($model, 'show_question_url_result')->textInput(['maxlength' => true,'readonly' =>true,'class'=>'form-control input_style','onClick'=>'openQuestion(this)']) ?>
                    <div class="clearfix"></div>
                    <!-- Options for case, Quiz Question Type = Checkbox: start-->
                    <div class='multiple-options col-lg-12' style="display: none">
                        <!--container add options-->
                        <div class='form-group col-lg-6 '>
                            <div class="row" style="padding: 10px !important;">
                                <div class="col-lg-12">
                                    <div class="col-lg-10">
                                        <h4 class='modal-title'><u><?= Yii::t('app', 'Add multiple choices') ?></u></h4>
                                    </div>
                                    <div class="col-lg-2">
                                        <a href="javascript:void(0);"
                                           class='btn btn-labeled btn-primary add_button_response'
                                           style="float:right" onclick="addMultipleOption()">
                                            Add
                                        </a>
                                    </div>
                                </div>

                            </div>
                            <div class="field_wrapper_option"></div>
                            <?php
                            if ($model->type == PollingQuizQuestion::MULTIPLE_CHOICE_QUESTION) {
                                // get correct answer option id
                                $correctOptionId = -1;
                                if (!empty($model->pollingQuizQuestionCorrectAnswer)) {
                                    $correctOptionId = $model->pollingQuizQuestionCorrectAnswer->answer;
                                }
                                if (isset($pollingQuizQuestionOption)) {
                                    foreach ($pollingQuizQuestionOption as $questionType) {
                                        $typeValue = $questionType->value;
                                        $optionId = $questionType->id;
                                        ?>
                                        <script>
                                            var html = addMultipleListing("<?= $typeValue; ?>", 2, "<?= $correctOptionId; ?>", "<?= $optionId; ?>", true);
                                            $(".field_wrapper_option").append(html);
                                        </script>
                                        <!--<div class="row" style="padding: 10px !important;">
                            <div class="col-lg-10"><input type="text" class="form-control" name="question_type_response[]"
                                                          value="<?/*= $typeValue; */ ?>"/></div>
                            <a href="javascript:void(0);" class="btn btn-labeled btn-danger remove_button"
                               style="float:right">Remove</a>
                        </div>-->

                                        <?php
                                    }
                                }
                            }
                            ?>

                        </div>
                        <!--end container -->
                    </div>
                    <div class='multiple-response col-lg-12' style="display: none">
                        <!--container add options-->
                        <div class='form-group col-lg-6 '>
                            <div class="row" style="padding: 10px !important;">
                                <div class="col-lg-10">
                                    <h4 class='modal-title'><u><?= Yii::t('app', 'Add multiple choices') ?></u></h4>
                                </div>
                                <div class="col-lg-2">
                                    <a href="javascript:void(0);" class='btn btn-labeled btn-primary add_button'
                                       style="float:right" onclick="addMultipleResponse()">
                                        Add
                                    </a>
                                </div>

                            </div>
                            <div class="field_wrapper_response"></div>
                            <?php
                            if ($model->type == PollingQuizQuestion::MULTIPLE_RESPONSE) {
                                $correctOptionIdArray = array();
                                $correctOptionId = -1;
                                if (!empty($model->pollingQuizQuestionCorrectAnswer)) {
                                    $correctOptionId = $model->pollingQuizQuestionCorrectAnswer->answer;
                                    $correctOptionIdArray = explode(',', $correctOptionId);

                                }
                                if (isset($pollingQuizQuestionOption)) {
                                    foreach ($pollingQuizQuestionOption as $questionType) {
                                        $isCheck = 0;
                                        if (in_array($questionType->id, $correctOptionIdArray)) {
                                            $isCheck = 1;
                                        }
                                        $typeValue = $questionType->value;
                                        ?>
                                        <script>
                                            var html = addMultipleListing("<?= $typeValue; ?>", 1, "<?= $isCheck; ?>", -1, true);
                                            $(".field_wrapper_response").append(html);
                                        </script>
                                        <!--<div class="row" style="padding: 10px !important;">
                                <div class="col-lg-10"><input type="text" class="form-control" name="question_type[]"
                                                              value="<?/*= $typeValue; */ ?>"/></div>
                                <a href="javascript:void(0);" class="btn btn-labeled btn-danger remove_button"
                                   style="float:right">Remove</a>
                            </div>-->

                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                        <!--end container -->
                    </div>

                    <!--choose rating wizard-->
                    <!--Commented-pangea-->
                    <!--    <div class='question-number col-lg-12' style="display: none">
        <div class="row" style="padding: 10px !important;">
            <div class="col-lg-4">
                <h4 class='modal-title'>Choose correct number</h4>
                <div class="col-lg-12">
                    <?php
                    /*                    $typeValueRating=PollingQuizQuestion::DEFAULT_NUMBER_COUNT;
                                        if($model->type==PollingQuizQuestion::NUMBER){
                                            if(!empty($model->pollingQuizQuestionCorrectAnswer)){
                                                    $typeValueRating=$model->pollingQuizQuestionCorrectAnswer->answer;
                                            }
                                        }
                                        */ ?>
                    <input type="number" class="form-control" name="question_type_number" value="<? /*=$typeValueRating; */ ?>"  />
                </div>
            </div>
        </div>

    </div>-->
                    <!--Commented-pangea end-->
                    <!-- choose rating wizard end-->

                    <!--choose rating wizard-->
                    <!--Commented-pangea-->
                    <!--    <div class='question-rating col-lg-12' style="display: none">
        <div>
            <?php /*echo $form->field($model, 'is_correct')->widget(SwitchInput::classname(), [
                'type' => SwitchInput::CHECKBOX,
                'pluginOptions' => [
                    'onText' => 'Yes',
                    'offText' => 'No',
                ],
                'options'=>['onChange'=>'jk(this)']
            ]); */ ?>
        </div>
        <div class="row" style="padding: 10px !important;">
            <div class="col-lg-4">
                <h4 class='modal-title'>Add Ratings</h4>
                <div class="col-lg-12">
                    <?php
                    /*                      $typeValueRating=PollingQuizQuestion::DEFAULT_RATING_COUNT;
                                          if($model->type==PollingQuizQuestion::RATING){
                                              if(!empty($pollingQuizQuestionOption)){
                                                  if(!empty($pollingQuizQuestionOption[0])){
                                                        $typeValueRating=$pollingQuizQuestionOption[0]->value;
                                                  }
                                              }
                                          }
                                        */ ?>
                    <input type="number" class="form-control" name="question_type_rating" value="<? /*=$typeValueRating; */ ?>"  />
                </div>
            </div>
            <div class="col-lg-4 correct_rating">
                <h4 class='modal-title'>Choose correct Ratings</h4>
                <div class="col-lg-12">
                    <?php
                    /*                    $typeValueRatingCorrect=PollingQuizQuestion::DEFAULT_RATING_COUNT;
                                        if($model->type==PollingQuizQuestion::RATING){
                                            if(!empty($model->pollingQuizQuestionCorrectAnswer)){
                                                    $typeValueRatingCorrect=$model->pollingQuizQuestionCorrectAnswer->answer;
                                            }
                                        }
                                        */ ?>
                    <input type="number" class="form-control" name="question_type_rating_correct" value="<? /*=$typeValueRatingCorrect; */ ?>"  />
                </div>
            </div>

        </div>

    </div>-->
                    <!--Commented-pangea end-->
                    <!-- choose rating wizard end-->
                    <!--choose true false wizard-->
                    <!--Commented-pangea-->
                    <!--    <div class='question-true-false col-lg-12' style="display: none">
        <div class="row" style="padding: 10px !important;">
            <div class="col-lg-4">
                <h4 class='modal-title'>Correct choice</h4>
                <div class="col-lg-10">
                    <?php
                    /*                    $checked="";
                                        $hidden_val=false;
                                        if($model->type==PollingQuizQuestion::TRUE_FALSE){
                                            if(!empty($model->pollingQuizQuestionCorrectAnswer)){
                                                    if($model->pollingQuizQuestionCorrectAnswer->answer=="true"){
                                                       $hidden_val="true";
                                                        $checked="checked";
                                                    }
                                            }
                                        }
                                        */ ?>
                    <input onChange="checkTF(this)" type="checkbox" name="question_type_tf_correct_g" <?php /*echo $checked */ ?> class="form-control"  value="<? /*=$typeValueRating; */ ?>" data-toggle="toggle" data-on="True" data-off="False" data-onstyle="success" data-offstyle="danger"  />
                    <input id="true_false" type="hidden" name="question_type_tf_correct" value=""<? /*= $hidden_val; */ ?> />
                </div>
            </div>
        </div>

    </div>-->
                    <!--Commented-pangea end-->
                    <!-- choose true false wizard end-->

                    <!-- Options for case, Quiz Question Type = Checkbox: start-->
                    <?php /*echo $form->field($model, 'order')->textInput() */ ?>

                    <?php /*echo $form->field($model, 'action')->textInput() */ ?>

                    <?php /*echo $form->field($model, 'action_compare')->textInput() */ ?>

                    <?php /*echo $form->field($model, 'action_compare_radio')->textInput() */ ?>

                    <?php /*echo $form->field($model, 'action_compare_text')->textInput() */ ?>

                    <?php /*echo $form->field($model, 'action_value')->textInput(['maxlength' => true]) */ ?>

                    <?php /*echo $form->field($model, 'visible')->textInput() */ ?>

                    <?php /*echo $form->field($model, 'visible_quiz_question_id')->textInput() */ ?>

                    <?php /*echo $form->field($model, 'visible_compare')->textInput() */ ?>

                    <?php /*echo $form->field($model, 'visible_value')->textInput(['maxlength' => true]) */ ?>

                    <!--<div class="form-group">
                        <label for="" class="control-label">Option</label>
                        <input type="text" maxlength="255" name="PollingQuizQuestion[title]" class="form-control" id="pollingquizquestion-title">
                        <p class="help-block help-block-error"></p>
                    </div>-->
                    <!--Commented-pangea-->
                    <!--    <div class="row">
        <div class="col-md-2">
            <?php /*echo $form->field($model, 'team_based')->widget(SwitchInput::classname(), [
                 'type' => SwitchInput::CHECKBOX
                ]); */ ?>
        </div>

        <div class="col-md-2 only-for-short-answer" style="display: none;">
            <?php /*echo $form->field($model, 'required')->widget(SwitchInput::classname(), [
                'type' => SwitchInput::CHECKBOX
            ]); */ ?>
        </div>

    </div>
     <!--Commented-pangea-->
                    <?php /*echo $form->field($model, 'required_error_message')->textInput(['maxlength' => true]); */ ?>

                    <div class="form-group">
                        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary btn-lg waves-effect' : 'btn btn-primary btn-lg waves-effect']) ?>
                    </div>
                    </div>
                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var is_correct = parseInt('<?= $model->is_correct ?>');
        if (is_correct == 1) {
            $(".correct_rating").css('display', 'block');
        } else {
            $(".correct_rating").css('display', 'none');
        }
    });
    function onMultipleSelection() {
        var type = $("#pollingquizquestion-type").val();
        $('.only-for-short-answer').hide();
        console.log(type);

        if (type ==<?= PollingQuizQuestion::MULTIPLE_CHOICE_QUESTION; ?>) {
            addWizardOptionIfNewRecord();
        }
        else if (type ==<?= PollingQuizQuestion::MULTIPLE_RESPONSE; ?>) {
            addWizardResponseIfNewRecord();
        }
        else if (type ==<?= PollingQuizQuestion::RATING; ?>) {
            addRatingWizardIfNewRecord();
        }
        else if (type ==<?= PollingQuizQuestion::TRUE_FALSE; ?>) {
            addTrueFalseWizardIfNewRecord();
        }
        else if (type ==<?= PollingQuizQuestion::NUMBER; ?>) {
            addNumberWizardIfNewRecord();
        }
        else {
            hideAllModals();
            $('.only-for-short-answer').show();
        }
    }
    function addNumberWizardIfNewRecord() {
        hideAllModals();
        $(".question-number").show();
    }
    function addWizardResponseIfNewRecord() {
        hideAllModals();
        $(".multiple-response").show();
    }
    function addWizardOptionIfNewRecord() {
        hideAllModals();
        $(".multiple-options").show();
    }

    function hideAllModals() {
        $(".multiple-response").hide();
        $(".multiple-options").hide();
        $(".question-rating").hide();
        $(".question-true-false").hide();
        $(".question-number").hide();
    }
    function addRatingWizardIfNewRecord() {
        hideAllModals();
        $(".question-rating").show();
    }
    function addTrueFalseWizardIfNewRecord() {
        hideAllModals();
        $(".question-true-false").show();
    }
    function checkTF(obj) {
        if ($(obj).is(':checked')) {
            $("#true_false").val("true");
        } else {
            $("#true_false").val("false");
        }
    }

    onMultipleSelection();
</script>

