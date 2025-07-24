<?php

use backend\modules\polling\models\base\PollingQuizQuestionType;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>
<div class="quiz-container">
    <div class="form-group mt-20">

    <?php
        $form = ActiveForm::begin(['enableClientValidation' => true, 'fieldConfig' => [
                'template' => "{input}{hint}",
                'options' => [
                    'options' => ['class' => 'form-group invisible']
                ],
            ],
        ]);
    ?>

    <?php echo $form->field($model, 'question')
        ->textarea(['rows' => 6, 'class' => 'form-control border', 'placeholder' => 'Type Question'])
        ->label(false)
    ?>

    </div>
    <div class="topic">
        <h4>
            <input type="text" value="Type" disabled>
        </h4>

    </div>

    <div class="questionaire">
        <div class="fill" draggable="true">
            <ul class="que edit">
                <li class="question">
                    <p class="num"></p>
                    <input type="text" name="topic" disabled value="Short Answer">

                    <?= $form->field($model, 'type', ['template'=>'{input}{hint}'])
                        ->dropDownList(ArrayHelper::map(PollingQuizQuestionType::find()->all(), 'id', 'name'),
                        [
                            //'onchange' => 'optionChange(this);',
                            'class' => "d-none",
                            'id' => "answerOptions",
                            'disabled' => true,
                        ]);
                    ?>

                </li>
                <li>
                    <input type="radio" name="option1">
                    <label for="option">
                        <input disabled type="text" name="option" value="Option 1" />
                    </label>
                    <button name="delete-li" title="" class="btn far fa-times-circle"></button>
                </li>
                <li>
                    <input type="radio" name="option1">
                    <label for="option">
                        <input disabled type="text" name="option" value="Option 2" />
                    </label>
                    <button name="delete-li" title="" class="btn far fa-times-circle"></button>
                </li>
                <li>
                    <input type="radio" name="option1">
                    <label for="option">
                        <input disabled type="text" name="option" value="Option 3" />
                    </label>
                    <button name="delete-li" title="" class="btn far fa-times-circle"></button>
                </li>
                <li>
                    <a class="add-option" title=""><i class="fa fa-plus"></i> Add</a>
                </li>
            </ul>
            <div class="btns">
                <button name="edit-que" class="btn fa fa-pencil" type="button"></button>
            </div>
        </div>

        <div class="topic">
            <h4>
                <input type="text" value="Applicant Attribute" disabled>
            </h4>

        </div>
        <div class="questionaire">
            <div class="fill" draggable="true">
                <p class="d-none">Title</p>
                <ul class="que edit">
                    <li class="question">
                        <p class="num"></p>
                        <input type="text" name="topic" disabled value="Email">
                        <button name="necessary" class="btn btn-sm">Required</button>
                        <select id="answerOptions" name="" onchange="optionChange(this)" class="d-none" disabled>
                            <option value="0">Select</option>
                            <option value="1">Email</option>
                            <option value="2">Sending Country</option>
                            <option value="3">Date of Birth</option>
                            <option value="4">Passport Number</option>
                            <option value="5">Mobile Number</option>
                            <option value="6">Office Address</option>
                        </select>
                    </li>
                    <li>
                        <input type="text" name="option" placeholder="Email" />
                        <button name="delete-li" title="" class="btn far fa-times-circle"></button>
                    </li>

                    <li>
                        <a class="add-option" title=""><i class="fa fa-plus"></i> Add</a>
                    </li>
                </ul>
                <div class="btns">
                    <button name="edit-que" class="btn fa fa-pencil" type="button"></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content" style="display: none"></div>
<?php ActiveForm::end(); ?>