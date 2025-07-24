<?php
/* @var $this \yii\web\View */
use yii\helpers\ArrayHelper;
use yii\widgets\Breadcrumbs;

/* @var $content string */

$this->beginContent('@frontend/modules/mii/views/layouts/_base.php')
?>
    <div class="container">

        <?php echo $content ?>

    </div>
<?php $this->endContent() ?>