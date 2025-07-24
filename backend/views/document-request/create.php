<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper; // Needed for dropdown data
use backend\models\DocumentTemplates; // Assuming this model holds document types and languages

/* @var $this yii\web\View */
/* @var $model backend\models\DocumentRequest */
/* @var $form yii\widgets\ActiveForm */

// Fetch unique document types and languages from DocumentTemplates
$documentTypes = ArrayHelper::map(
    DocumentTemplates::find()->select('document_type')->distinct()->all(),
    'document_type',
    'document_type'
);

$languages = ArrayHelper::map(
    DocumentTemplates::find()->select('language')->distinct()->all(),
    'language',
    'language'
);

?>

<div class="document-request-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'document_type')->dropDownList(
                $documentTypes,
                ['prompt' => 'Select Document Type', 'id' => 'document-type-select'] // Add an ID for easy JavaScript access
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'language_of_document')->dropDownList(
                $languages,
                ['prompt' => 'Select Language', 'id' => 'language-select'] // Add an ID for easy JavaScript access
            ) ?>
        </div>
    </div>

    <hr>
    <h3>Document Preview:</h3>
    <div id="document-preview-container" style="border: 1px solid #ccc; padding: 15px; min-height: 200px; background-color: #f9f9f9;">
        <p>Select a Document Type and Language to see a preview.</p>
    </div>
    <hr>

    <div class="form-group">
        <?= Html::submitButton('Submit Request', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// Register JavaScript for dynamic preview
$this->registerJs(<<<JS
    function updateDocumentPreview() {
        var documentType = $('#document-type-select').val();
        var language = $('#language-select').val();
        var previewContainer = $('#document-preview-container');

        if (documentType && language) {
            // Show a loading message
            previewContainer.html('<p>Loading preview...</p>');

            $.ajax({
                url: '/hrandpayroll/backend/web/document-request/get-preview-content', // Adjust this URL if your base URL is different
                type: 'GET',
                data: {
                    document_type: documentType,
                    language: language
                },
                success: function(response) {
                    previewContainer.html(response);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error, xhr.responseText);
                    previewContainer.html('<p class="text-danger">Error loading preview. Please try again. (Details: ' + xhr.responseText + ')</p>');
                }
            });
        } else {
            previewContainer.html('<p>Select a Document Type and Language to see a preview.</p>');
        }
    }

    // Attach event listeners to the dropdowns
    $('#document-type-select').on('change', updateDocumentPreview);
    $('#language-select').on('change', updateDocumentPreview);

    // Call it once on page load if values are pre-selected (e.g., on update form)
    $(document).ready(function() {
        updateDocumentPreview();
    });

JS
);
?>