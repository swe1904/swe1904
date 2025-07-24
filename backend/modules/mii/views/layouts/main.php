<?php
/* @var $this \yii\web\View */
use yii\helpers\ArrayHelper;
use yii\widgets\Breadcrumbs;

/* @var $content string */
\backend\modules\mii\MiiAsset::register($this);
$this->beginContent('@backend/views/layouts/common_pangea_final.php')
?>
    <div class="container">

        <?php echo $content ?>

    </div>
<?php $this->endContent() ?>