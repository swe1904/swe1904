<?php

use yii\widgets\DetailView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\DocumentRequest */

$this->title = 'Document Request #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Document Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="document-request-view">
  
    <div class="ribbon">
        <span><b><?php echo $this->params['breadcrumbs'][] = $this->title; ?>:</b></span>
    </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'employee_id',
            'document_type',
            'language_of_document',
        ],
    ]) ?>

</div>

<?php
$this->registerCss(" 
    .ribbon {
        display: flex;
        justify-content: space-between;
        background: rgb(39, 38, 38);
        color: white;
        padding: 10px;
        border-radius: 5px;
        font-weight: bold;
        margin-bottom: 15px;
    }

   
        .ribbon {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }
");
?>
