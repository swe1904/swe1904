<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this \yii\web\View */
/* @var $content string */

\backend\assets\PangeaNewAsset::register($this);


$this->params['body-class'] = 'theme-red';
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?php echo Yii::$app->language ?>">
    <head>
        <meta charset="<?php echo Yii::$app->charset ?>">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        <?php echo Html::csrfMetaTags() ?>
        <title><?php echo Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <style>
            .toast {
                opacity: 1 !important;
            }
        </style>

    </head>
    <?php echo Html::beginTag('body', [
        'class' => implode(' ', [
            ArrayHelper::getValue($this->params, 'body-class'),
            Yii::$app->keyStorage->get('backend.theme-skin', 'them-red'),
            Yii::$app->keyStorage->get('backend.layout-fixed') ? 'fixed' : null,
            Yii::$app->keyStorage->get('backend.layout-boxed') ? 'layout-boxed' : null,
            Yii::$app->keyStorage->get('backend.layout-collapsed-sidebar') ? 'sidebar-collapse' : null,
        ])
    ])?>
    <?php $this->beginBody() ?>
    <?php echo $content ?>
    <?php $this->endBody() ?>
    <?php echo Html::endTag('body') ?>
    </html>
<?php $this->endPage() ?>