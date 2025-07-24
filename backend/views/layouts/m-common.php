<?php
use backend\assets\BackendAsset;

$bundle = BackendAsset::register($this);

$this->beginContent('@backend/views/layouts/m-base.php');
?>
<section class="content">
    <?php echo $content ?>
</section>

<?php $this->endContent();


?>
