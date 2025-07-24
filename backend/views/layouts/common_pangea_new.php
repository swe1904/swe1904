<?php
/**
 * @var $this yii\web\View
 */
use app\components\GlobalConstant;
use backend\widgets\Menu;
use common\models\TimelineEvent;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

?>
<?php

$organisationCreate = false;
$organisationUpdate = false;
$organisationId = false;
$organisationModel = \backend\models\Organisation::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
if (!empty($organisationModel)) {
    $organisationUpdate = true;
    $organisationId = $organisationModel->id;
} else {
    $organisationCreate = true;
}
?>
<?php $this->beginContent('@backend/views/layouts/base_pangea_new.php'); ?>
<!-- Jquery Core Js -->
<!--<script src="--><?php //echo Yii::$app->request->baseUrl ?><!--/js/pangea_js/jquery.min.js"></script>-->
<link href="<?php echo Yii::$app->request->baseUrl ?>/css/pangea_css/bootstrap.css" rel="stylesheet">
<!-- Waves Effect Css -->
<link href="<?php echo Yii::$app->request->baseUrl ?>/css/pangea_css/waves.css" rel="stylesheet"/>
<!-- Custom Css -->
<!--<link href="--><?php //echo Yii::$app->request->baseUrl?><!--/css/pangea_css/style2.css" rel="stylesheet">-->
<!-- Colors themes -->
<link href="<?php echo Yii::$app->request->baseUrl ?>/css/pangea_css/all-themes.css" rel="stylesheet"/>
<!-- Bootstrap Select Css -->
<link href="<?php echo Yii::$app->request->baseUrl ?>/css/pangea_css/bootstrap-select.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
      type="text/css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- END Overlay For Sidebars -->

<!-- Search Bar -->
<div class="search-bar">
    <div class="search-icon">
        <i class="material-icons">search</i>
    </div>
    <input type="text" placeholder="START TYPING...">
    <div class="close-search">
        <i class="material-icons">close</i>
    </div>
</div>
<!-- END Search Bar -->

<!-- Top Bar -->
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse"
               data-target="#navbar-collapse" aria-expanded="false"></a>
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="#">Pangea Worldwide</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <?php echo $this->render('_impersonate'); ?>
                </li>
                <!-- Call Search -->
                <li><a href="javascript:void(0);" class="js-search" data-close="true"><i
                                class="material-icons">search</i></a></li>
                <!-- END Call Search -->

                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                        <i class="material-icons pull-left">account_circle</i>
                        <span style="float: left;margin:3px 0 0 5px"><?= ucwords(Yii::$app->user->identity->username); ?></span>
                    </a>
                </li>
                <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i
                                class="material-icons">more_vert</i></a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Top Bar -->
<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <?php if(isset(Yii::$app->user->identity->userProfile->avatar_base_url)){ ?>
                    <img src="<?php echo Yii::$app->user->identity->userProfile->avatar_base_url.'/'.Yii::$app->user->identity->userProfile->avatar_path;?>" width="48" height="48"
                         alt="User"/>
                <?php }else{?>
                <img src="<?php echo Yii::$app->request->baseUrl ?>/img/pangea_img/user.png" width="48" height="48"
                     alt="User"/>
                <?php }?>
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true"
                     aria-expanded="false"><?= ucwords(Yii::$app->user->identity->username); ?></div>
                <div class="email"><?= Yii::$app->user->identity->email; ?></div>
            </div>
        </div>
        <!-- User Info -->

        <!-- Menu -->
        <div class="menu">
            <?php
            echo Menu::widget([
                'options' => ['class' => 'list'],
                'labelTemplate' => '<a href="#" class="menu-toggle"><i class="material-icons">layers</i></span><span>{label}</span></a>',
                'linkTemplate' => '<a href="{url}"><i class="material-icons">layers</i><span>{label}</span></a>',
                'submenuTemplate' => "\n<ul class=\"ml-menu\">\n{items}\n</ul>\n",
                'activateParents' => true,
                'items' => [
                    ['label' => Yii::t('backend', 'My Profile'), 'url' => ['/sign-in/profile'], 'icon' => '<i class="fa fa-user" aria-hidden="true"></i>'],
                    ['label' => Yii::t('backend', 'Organisation Information'), 'url' => ['/organisation/create'], 'icon' => '<i class="fa fa-book"></i>', 'visible' => $organisationCreate && !Yii::$app->user->can('administrator')],
                    ['label' => Yii::t('backend', 'Organisation Information'), 'url' => ['/organisation/update', 'id' => $organisationId], 'icon' => '<i class="fa fa-book"></i>', 'visible' => $organisationUpdate && !Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN)],

                    ['label' => 'Cases',
                        'options' => ['class' => 'scoop-hasmenu'],
                        //   'template' => '<a href="{url}" class="">{label} </a>',
                        'icon' => '<i class="fa fa-user"></i>',
                        // 'visible' => Yii::$app->user->can('administrator'),
                        'items' => [
                            ['label' => 'Case Type', 'url' => ['/case-type/index']],
//                                            ['label' => 'Case Type Step', 'url' => ['/case-type-step/index']],
//                                            ['label' => 'Case Steps ', 'url' => ['/case-steps/index']],
                            ['label' => 'Cases ', 'url' => ['/cases/index']]
                        ],
                        'visible' => Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN) || Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_ADMIN) || Yii::$app->user->can(GlobalConstant::ROLE_CASE_WORKER),
                    ],
                    ['label' => 'Billing System',
                        'url' => ['/receipt/index'],
                        'icon' => '<i class="fa fa-money"></i>',
                        'visible' => !Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN),
                        //   'active' => in_array(\Yii::$app->controller->id,['receipt',GlobalConstant::ROLE_CLIENT,'service']),
//                                        'items' => [
//                                            ['label' => 'Receipts', 'url' => ['/receipt/index']],
//                                            ['label' => 'Invoices', 'url' => ['/receipt/index', 'invoices'=>'true']],
//                                            /*['label' => 'Services', 'url' => ['service/index'],'icon' => '<i class="fa fa-circle-o text-aqua"></i>'],*/
//                                        ]
                    ],
                    ['label' => 'Clients',
                        'url' => ['/client/index'],
                        'icon' => '<i class="fa fa-users"></i>',
                        'visible' => Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN,
                    ],
                    ['label' => 'Applicants',
                        'url' => ['/applicant/index'],
                        'icon' => '<i class="fa fa-users"></i>',
                        'visible' => Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT,
                    ],
                    ['label' => Yii::t('backend', 'Account'), 'url' => ['/sign-in/account'], 'icon' => '<i class="fa fa-lock" aria-hidden="true"></i>'],
                    ['label' => Yii::t('backend', 'Users'), 'url' => ['/user/index'], 'icon' => '<i class="fa fa-users" aria-hidden="true"></i>', 'visible' => Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN) || Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_ADMIN)],
                    ['label' => Yii::t('backend', 'Create Client Fields'), 'url' => ['/mii/default/custom-builder'], 'icon' => '<i class="fa fa-user" aria-hidden="true"></i>', 'visible' => Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN)],
                    ['label' => Yii::t('backend', 'Create Applicant Fields'), 'url' => ['/mii/applicant/custom-builder'], 'icon' => '<i class="fa fa-user" aria-hidden="true"></i>', 'visible' => Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN)],
                    ['label' => Yii::t('backend', 'Messages'), 'url' => ['/messageSystem/message/inbox'], 'icon' => '<i class="fa fa-envelope" aria-hidden="true"></i>'],
                    /*['label' => Yii::t('backend', 'Frontend'), 'url' => '@frontendUrl/site/index', 'icon' => '<i class="fa fa-circle-thin" aria-hidden="true"></i>'],*/
                    [
                        'label' => Yii::t('backend', 'Questionnaire'),
                        'url' => ['/polling/polling-quiz/index'],
                        'icon' => '<i class="fa fa-question" aria-hidden="true"></i>',
                        //'template' => '<a href="{url}" data-method="post">{icon} {label}</a>',
                        'visible' => Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN),

                    ],
                    [
                        'label' => Yii::t('backend', 'Invite Applicant'),
                        'url' => ['/invite-applicant'],
                        'icon' => '<i class="fa fa-share" aria-hidden="true"></i>',
                        // 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>',
                        'visible' => !Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN) && Yii::$app->user->can(GlobalConstant::ROLE_CLIENT),

                    ],
                    [
                        'label' => Yii::t('backend', 'Email Template'),
                        'url' => ['/polling/email-template'],
                        'icon' => '<i class="fa fa-edit" aria-hidden="true"></i>',
                        //  'template' => '<a href="{url}" data-method="post">{icon} {label}</a>',
                        'visible' => Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN),

                    ],
                    ['label' => 'Translation',
                        'url' => ['/i18n/i18n-source-message/'],
                        'icon' => '<i class="fa fa-language"></i>',
                        'visible' => Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN),

//                                        'items' => [
//                                            ['label' => 'Add Source Message', 'url' => ['/i18n/i18n-source-message/create']],
//                                            ['label' => 'View Source Message', 'url' => ['/i18n/i18n-source-message/']],
////                                            ['label' => 'Add Translation', 'url' => ['/i18n/i18n-message/create']],
////                                            ['label' => 'View Translation', 'url' => ['/i18n/i18n-message']],
//
//                                        ]
                    ],
                    ['label' => 'Admin',
                        'options' => ['class' => 'scoop-hasmenu'],
                        //   'template' => '<a href="{url}" class="">{label} </a>',
                        'icon' => '<i class="fa fa-user"></i>',
                        'visible' => Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN),
                        //  'active' => in_array(\Yii::$app->controller->getRoute(),['/admin/route','/admin/permission','/admin/menu','/adminrole','/admin/assignment','/admin/user']),
                        'items' => [
                            ['label' => 'Route', 'url' => ['/admin/route']],
                            ['label' => 'Permissions', 'url' => ['/admin/permission']],
                            ['label' => 'Role', 'url' => ['/admin/role']],
                            ['label' => 'Assignment', 'url' => ['/admin/assignment']],
                            ['label' => 'User', 'url' => ['/admin/user']],
                        ]
                    ],
                    [
                        'label' => Yii::t('backend', 'Log Out'),
                        'url' => ['/sign-in/logout'],
                        'icon' => '<i class="fa fa-sign-out" aria-hidden="true"></i>',

                        'template' => '<a href="{url}" data-method="post"><span class="scoop-micon">{icon}</span><span class="scoop-mtext">{label}</span><span class="scoop-mcaret"></span></a>'

                    ],

                ],

            ]);
            ?>

        </div>
        <!-- #Menu -->

        <!-- Footer -->
        <div class="legal">
            <div class="copyright">
                &copy; 2017 - 2018 <a href="javascript:void(0);">Pangea</a>.
            </div>
        </div>
        <!-- #Footer -->
    </aside>
    <!-- END Left Sidebar -->

    <!-- Right Sidebar -->
    <aside id="rightsidebar" class="right-sidebar">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                <ul class="demo-choose-skin">
                    <li data-theme="red" class="active">
                        <div class="red"></div>
                        <span>Red</span>
                    </li>
                    <li data-theme="blue">
                        <div class="blue"></div>
                        <span>Blue</span>
                    </li>
                    <li data-theme="teal">
                        <div class="teal"></div>
                        <span>Teal</span>
                    </li>
                    <li data-theme="grey">
                        <div class="grey"></div>
                        <span>Grey</span>
                    </li>
                    <li data-theme="blue-grey">
                        <div class="blue-grey"></div>
                        <span>Blue Grey</span>
                    </li>
                </ul>
            </div>
        </div>
    </aside>
    <!-- END Right Sidebar -->
</section>

<section class="content">
    <div class="container-fluid">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="block-header">
                <!--    <h2>EDIT PROFILE</h2>-->
                <h2>
                    <?php echo $this->title ?>
                    <?php if (isset($this->params['subtitle'])): ?>
                        <small><?php echo $this->params['subtitle'] ?></small>
                    <?php endif; ?>
                </h2>
            </div>
            <?php echo Breadcrumbs::widget([
                'tag' => 'ol',
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </section>


        <!-- Main content -->
        <!--                        <section class="content">-->
        <?php if (Yii::$app->session->hasFlash('alert')): ?>
            <?php echo \yii\bootstrap\Alert::widget([
                'body' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
                'options' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
            ]) ?>
        <?php endif; ?>
        <?php echo $content ?>

        <!--                        </section>-->
    </div>
</section>
<?php $this->endContent(); ?>

<!--***** common modal: START *****-->
<?php
yii\bootstrap\Modal::begin([
    'id' => 'modal',
    'size' => 'modal-lg',
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => true, 'data-show' => true]
]);
echo "<div id='modalContent'></div>";
yii\bootstrap\Modal::end();
?>
<!--***** common modal: END *****-->

<!-- Bootstrap Core Js -->
<script src="<?php echo Yii::$app->request->baseUrl ?>/js/pangea_js/bootstrap.js"></script>
<!-- Slimscroll Plugin Js -->
<script src="<?php echo Yii::$app->request->baseUrl ?>/js/pangea_js/jquery.slimscroll.js"></script>
<!-- Waves Effect Plugin Js -->
<script src="<?php echo Yii::$app->request->baseUrl ?>/js/pangea_js/waves.js"></script>
<!-- Custom Js -->
<script src="<?php echo Yii::$app->request->baseUrl ?>/js/pangea_js/admin.js"></script>
<script src="<?php echo Yii::$app->request->baseUrl ?>/js/pangea_js/demo.js"></script>
<!------bootstrap select js----->
<!--<script src="<?php /*echo Yii::$app->request->baseUrl */?>/js/pangea_js/bootstrap-select.js"></script>-->


<!----tooltip---->
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>

<?php
//Fixing issue of dropdowns not closing after opening
$this->registerJs(
    <<<JS
       $('html').on('click', function (e) {
        if (!$('.dropdown-animating').is(e.target)
            && $('.dropdown-animating').has(e.target).length === 0
            && $('.open').has(e.target).length === 0
        ) {
            $('.dropdown-animating').removeClass('open');
        }
    });
JS
);
?>
