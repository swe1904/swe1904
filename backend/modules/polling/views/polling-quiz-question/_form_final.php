<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\modules\polling\models\PollingQuizQuestion;
use backend\modules\polling\models\base\PollingQuizQuestionType;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\polling\models\PollingQuizQuestion */
/* @var $form yii\bootstrap\ActiveForm */
?>

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
        var fieldHTML = '<div class="multiple_parent" style="padding: 10px !important;">' +
            '<div class="row">' +
            '<div class="col-lg-11">' +
            '<input type="text" class="form-control" name="' + input_name + '" value="' + input_val + '" placeholder="please add to this field">' +
            '</div>' +
            '<div class="col-lg-1">' +
            '<a href="javascript:void(0);" class="btn btn-link text-danger pull-right" style="float:right" onclick="removeMultipleResponse(this)"><i class="fa fa-times-circle"></a>' +
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

<div class="quiz-container">
    <?php $form = ActiveForm::begin(['enableClientValidation' => true, 'fieldConfig' => [
        'options' => [
            'options' => ['class' => 'form-group invisible']
        ],
    ],]); ?>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->field($model, 'question')
        ->textarea(['rows' => 6, 'class' => 'border form-control mt-20', 'placeholder' => 'Type Question'])
    ?>

    <div class="questionaire">
        <div class="fill" draggable="true">
            <?php echo $form->field($model, 'type')->dropDownList(ArrayHelper::map(PollingQuizQuestionType::find()->all(), 'id', 'name'), ['onchange' => 'onMultipleSelection();']); ?>
            <ul class="que edit">
                <li>
                    <div class='multiple-options' style="display: none">
                        <!--container add options-->
                        <div class='form-group'>
                            <div style="padding: 10px !important;">
                                <!-- <div class="col-lg-10">
                                <h4 class='modal-title'><u><?php /* echo Yii::t('app', 'Add multiple choices') */ ?></u></h4>
                                </div> -->
                                <div>
                                    <a href="javascript:void(0);" onclick="addMultipleOption()">
                                        <i class="fa fa-plus"></i> Add
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

                                <?php
                            }
                        }
                    }
                    ?>

                    </div>
                    <!--end container -->
        </div>
        </li>
        <li>
            <div class='multiple-response col-lg-12' style="display: none">
                <!--container add options-->
                <div class='form-group'>
                        <!-- <div class="col-lg-10">
                            <h4 class='modal-title'><u><?php /* echo Yii::t('app', 'Add multiple choices') */ ?></u></h4>
                        </div> -->
 
                    <a href="javascript:void(0);" onclick="addMultipleResponse()">
                        Add <i class="fa fa-plus"></i>
                    </a>


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

                            <?php
                        }
                    }
                }
                ?>
                </div>
                <!--end container -->
            </div>
        </li>
        </ul>



        <!-- WorkOnProgress -->
        <div class="clearfix"></div>
        <!-- Options for case, Quiz Question Type = Checkbox: start-->


        <!-- /WorkOnProgress -->

        <?php $attribute = $model->getAttributeFields();

        if (PollingQuizQuestion::find()->where(['polling_quiz_id' => $model->polling_quiz_id, 'applicant_attribute' => 'email'])->count() > 0 && $model->applicant_attribute != 'email') {
            //  ArrayHelper::remove( $attribute,'email');
        }

        echo $form->field($model, 'applicant_attribute')->dropDownList($attribute, ['prompt' => 'select',]);

        ?>

            <div class="form-group">
            </div>
            <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-rounded btn-primary mr-10' : 'btn btn-rounded btn-lg btn-primary mr-10']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
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

        if (type == <?= PollingQuizQuestion::MULTIPLE_CHOICE_QUESTION; ?>) {
            addWizardOptionIfNewRecord();
        } else if (type == <?= PollingQuizQuestion::MULTIPLE_RESPONSE; ?>) {
            addWizardResponseIfNewRecord();
        } else if (type == <?= PollingQuizQuestion::RATING; ?>) {
            addRatingWizardIfNewRecord();
        } else if (type == <?= PollingQuizQuestion::TRUE_FALSE; ?>) {
            addTrueFalseWizardIfNewRecord();
        } else if (type == <?= PollingQuizQuestion::NUMBER; ?>) {
            addNumberWizardIfNewRecord();
        } else {
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