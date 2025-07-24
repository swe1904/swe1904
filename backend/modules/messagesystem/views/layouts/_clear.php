<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$this->beginContent('@frontend/views/layouts/_initial.php')
?>
<?php echo $content; ?>
<!--end modals-->
<?php $this->endContent() ?>