<?php
/**
 * @var $this yii\web\View
 */
?>
<?php $this->beginContent('@backend/views/layouts/common_pangea.php'); ?>
    <div class="row">
    <div class="col-md-12">
    <div class="panel-default">
        <div class="panel panel-body">
            <?php echo $content ?>
        </div>
    </div>
    </div>
    </div>
<?php $this->endContent(); ?>