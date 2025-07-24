<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor; // For rich text editor. Remember to `composer require dosamigos/yii2-ckeditor`

/* @var $this yii\web\View */
/* @var $model common\models\DocumentTemplate */
/* @var $form yii\widgets\ActiveForm */

// Get lists from the model helper methods
$documentTypes = \common\models\DocumentTemplate::getDocumentTypes();
$languages = \common\models\DocumentTemplate::getLanguages();

// List of common placeholders for guidance
$commonPlaceholders = [
    '{{DATE}}', '{{COMPANY_NAME}}', '{{COMPANY_ADDRESS}}',
    '{{EMPLOYEE_FULL_NAME}}', '{{EMPLOYEE_PREFERRED_FULL_NAME}}', '{{EMPLOYEE_ID}}',
    '{{POSITION}}', '{{DEPARTMENT}}', '{{JOINING_DATE}}',
    '{{PROBATION_PERIOD}}', '{{EMAIL}}', '{{WORK_EMAIL}}',
    '{{SALARY}}', '{{MONTHLY_SALARY_BASIC}}', '{{MONTHLY_SALARY_HOUSING}}',
    '{{MONTHLY_SALARY_TRANSPORTATION}}', '{{TOTAL_MONTHLY_SALARY}}',
    '{{GENDER_PRONOUN_HE_SHE}}', '{{GENDER_PRONOUN_HIM_HER}}', '{{GENDER_PRONOUN_HIS_HER}}',
    '{{PURPOSE}}', '{{ADDRESS}}', '{{COUNTRY}}',
    // Specific placeholders from additional_data (HR should know these based on document type)
    // You might want to categorize these or provide dynamic help based on selected document_type
    '{{VISA_COUNTRY}}', '{{TRAVEL_DATES}}', '{{REFEREE_NAME}}', '{{REFEREE_TITLE}}',
];
?>

<div class="document-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'document_type')->dropDownList($documentTypes, ['prompt' => 'Select Document Type']) ?>

    <?= $form->field($model, 'language')->dropDownList($languages, ['prompt' => 'Select Language']) ?>

    <?= $form->field($model, 'version')->textInput(['maxlength' => true, 'readonly' => true, 'disabled' => !$model->isNewRecord]) ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <?= $form->field($model, 'content')->widget(CKEditor::class, [
        'options' => ['rows' => 10],
        'preset' => 'basic', // You can change to 'standard' or 'full' for more features
        'clientOptions' => [
            // CKEditor options, e.g., to customize toolbar
            // 'toolbarGroups' => [
            //     ['name' => 'clipboard', 'groups' => ['clipboard', 'undo']],
            //     ['name' => 'editing', 'groups' => ['find', 'selection', 'spellchecker']],
            //     // etc.
            // ],
        ],
    ]) ?>

    <div class="panel panel-info">
        <div class="panel-heading">Available Placeholders</div>
        <div class="panel-body">
            <p>Use these placeholders in the content. They will be replaced with actual data:</p>
            <div style="column-count: 2; column-gap: 20px;">
                <ul>
                    <?php foreach ($commonPlaceholders as $placeholder): ?>
                        <li><code><?= Html::encode($placeholder) ?></code></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <hr>
            <p>
                <b>Important:</b> For document types like "Visa Letter" or "Reference Letter", you can use
                additional placeholders that come from the employee's specific request details
                (stored in the `additional_data` JSON field). Ensure your HR team understands which
                placeholders are valid for which document type.
                <br>
                **Example:** For Visa Letter, `{{VISA_COUNTRY}}`, `{{TRAVEL_DATES}}`.
                For Reference Letter, `{{REFEREE_NAME}}`, `{{REFEREE_TITLE}}`.
            </p>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>