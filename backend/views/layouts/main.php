<?php
/**
 * @var $this yii\web\View
 */

use yii\helpers\Html;

?>
<?= Html::csrfMetaTags() ?>

<?php $this->beginContent('@backend/views/layouts/common.php'); ?>
    <div class="box">
        <div class="box-body">
            <?php echo $content ?>
        </div>
    </div>
<?php $this->endContent(); ?>