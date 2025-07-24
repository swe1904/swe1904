<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/* @var $this \yii\web\View */
/* @var $content string */

//\frontend\assets\FrontendAsset::register($this);
\backend\assets\PangeaFinalAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language ?>">
<head>
    <meta charset="<?php echo Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo Html::encode($this->title) ?></title>
    <!-- Fevicon Icon Added -->
    <?php // Html::favicon('@frontend/web/img/fevicon-icon.ico') ?>
    <link rel="icon" type="image/x-icon" href="<?= Yii::getAlias('@web') ?>/favicon.ico">

    <?php $this->head() ?>
    <?php echo Html::csrfMetaTags() ?>
    <!-- Piwik -->
    <script type="text/javascript">
        var _paq = _paq || [];
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="//www.agroaktiv.de/extras/piwik/";
            _paq.push(['setTrackerUrl', u+'piwik.php']);
            _paq.push(['setSiteId', 1]);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
        })();
    </script>
    <noscript><p><img src="//www.agroaktiv.de/extras/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
    <!-- End Piwik Code -->
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => Yii::t('frontend', 'Home'), 'url' => ['/site/index']],
//                ['label' => Yii::t('frontend', 'About'), 'url' => ['/page/view', 'slug'=>'about']],
//                ['label' => Yii::t('frontend', 'Articles'), 'url' => ['/article/index']],
//                ['label' => Yii::t('frontend', 'Contact'), 'url' => ['/site/contact']],
//                ['label' => Yii::t('frontend', 'Pricing'), 'url' => ['/site/pricing']],
                ['label' => Yii::t('frontend', 'Signup'), 'url' => ['/user/sign-in/signup'], 'visible'=>Yii::$app->user->isGuest],
                ['label' => Yii::t('frontend', 'Login'), 'url' => ['/user/sign-in/login'], 'visible'=>Yii::$app->user->isGuest],
                ['label' => Yii::t('frontend', 'Dashboard'), 'url' => Yii::getAlias('@backendUrl'), 'visible'=>!Yii::$app->user->isGuest],
                [
                    'label' => Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->getPublicIdentity(),
                    'visible'=>!Yii::$app->user->isGuest,
                    'items'=>[
                        [
                            'label' => Yii::t('frontend', 'Settings'),
                            'url' => ['/user/default/index']
                        ],
                      /*  [
                            'label' => Yii::t('frontend', 'Backend'),
                            'url' => Yii::getAlias('@backendUrl'),
                            'visible'=>Yii::$app->user->can('manager')
                        ],*/
                        [
                            'label' => Yii::t('frontend', 'Logout'),
                            'url' => ['/user/sign-in/logout'],
                            'linkOptions' => ['data-method' => 'post']
                        ]
                    ]
                ],
                /*[
                    'label'=>Yii::t('frontend', 'Language'),
                    'items'=>array_map(function ($code) {
                        return [
                            'label' => Yii::$app->params['availableLocales'][$code],
                            'url' => ['/site/set-locale', 'locale'=>$code],
                            'active' => Yii::$app->language === $code
                        ];
                    }, array_keys(Yii::$app->params['availableLocales']))
                ]*/
            ]
        ]);
        NavBar::end();
        ?>

        <?php echo $content ?>

    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; Handyreceipts <?php echo date('Y') ?></p>
            <p class="pull-right"><?php echo Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
