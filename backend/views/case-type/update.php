<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\CaseType */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Case Type',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Case Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="case-type-update">

    <div class="col-md-12">
        <div class="panel panel-default card-view panel-refresh">
        <div class="panel-heading">
                <div class="pull-left">
                    <h6 class="panel-title txt-dark"><?= !empty($model->name) ? "Update" : "Create"?> Case Type</h6>
                </div>
                <div class="clearfix"></div>
            </div>
                <?= $this->render('_form', [
                    'model' => $model,
                    // 'model_case' => $model_case,
                ]) ?>
            
        </div>
    </div>

</div>

<script>

    function dropMenuChange()
    {
            // console.log("Function Detected");
            let list1 = $('#multiselect').val();
            let list2 = $('#multiselect2').val();
            $('#multiselect2').children().each(function () {
                                
                                        ($(this).attr("disabled",false));
            })
            $('#multiselect2').children().each(function () {
                                if(list1.includes($(this).attr('value')))
                                        ($(this).attr("disabled","disabled"));
            })
            $('#multiselect').children().each(function () {
                                
                                        ($(this).attr("disabled",false));
            })
            $('#multiselect').children().each(function () {
                                if(list2.includes($(this).attr('value')))
                                        ($(this).attr("disabled","disabled"));
            })  
    }

    // $(document).ready(dropMenuChange());
    $(document).ready(function () 
    {
            // console.log("Load detetcted");
            dropMenuChange();
            
    });
    $('#multiselect, #multiselect2').change(function()
        {
                // console.log("Change detetcted");
                dropMenuChange();
        });
        

    </script>
