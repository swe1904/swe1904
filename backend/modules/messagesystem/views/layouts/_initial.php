<?php
use yii\helpers\Html;
/* @var $this \yii\web\View */
/* @var $content string */

\backend\modules\messagesystem\MessageAsset::register($this);
$this->beginContent('@backend/views/layouts/main_pangea_new.php')
?>
<?php //$this->beginPage() ?>
<!--<!DOCTYPE html>-->
<!--<html lang="--><?php //echo Yii::$app->language ?><!--">-->
<!--<head>-->
<!--    <meta charset="--><?php //echo Yii::$app->charset ?><!--"/>-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1">-->
<!--    <title>--><?php //echo Html::encode($this->title) ?><!--</title>-->
<!--    --><?php //$this->head() ?>
<!--    --><?php //echo Html::csrfMetaTags() ?>
<!--</head>-->
<!--<body>-->
<?php //$this->beginBody() ?>


<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,700' rel='stylesheet' type='text/css'>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<!--<body class="page-homepage navigation-fixed-top map-google" id="page-top" data-spy="scroll" data-target=".navigation" data-offset="90">-->
<body class="page-homepage" id="page-top" >

<?php echo $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
