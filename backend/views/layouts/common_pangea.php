<?php
/**
 * @var $this yii\web\View
 */
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
$organisationModel = \backend\models\Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
if(!empty($organisationModel)){
    $organisationUpdate = true;
    $organisationId = $organisationModel->id;
}else{
    $organisationCreate = true;
}
?>
<?php $this->beginContent('@backend/views/layouts/base_pangea.php'); ?>
<!--<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700,800" rel="stylesheet">-->
<!--custom css-->
<style>
    .bg-yellow,
    .callout.callout-warning,
    .alert-warning,
    .label-warning,
    .modal-warning .modal-body {
        background-color: #fcf8e3 !important;
    }
    .alert-warning {
        color: #8a6d3b !important;
        border-color: #faebcc !important;
    }

    .formInput{margin-bottom: 0%!important;}
    .checkbox{padding: 10px;}
    .form-group {
        margin-bottom: 5%;
    }
    .abc{display:none}
    .form-control { border: none !important;}
    span.fa-times {
        right: 35px;
        position: absolute;
        z-index: 0;
        transition: all .2s;
        color: #dd4b39 !important;
        font-size: 20px;}
    .panel{padding-top: 10px;}
    .custom-label{position: relative;}

    /*form textarea:focus{*/
    /*outline:none;*/
    /*color:#6d160f;*/
    /*border-bottom:2px solid #6d160f!important;*/
    /*-webkit-transition:border-bottom .5s;*/
    /*-moz-transition:border-bottom .5s;*/
    /*-ms-transition:border-bottom .5s;*/
    /*-o-transition:border-bottom .5s;*/
    /*transition:border-bottom .5s;*/
    /*}*/
    /*form input:focus{*/
    /*outline:none;*/
    /*color:#6d160f;*/
    /*border-bottom:2px solid #6d160f!important;*/
    /*-webkit-transition:border-bottom .5s;*/
    /*-moz-transition:border-bottom .5s;*/
    /*-ms-transition:border-bottom .5s;*/
    /*-o-transition:border-bottom .5s;*/
    /*transition:border-bottom .5s;*/
    /*}*/
    span.fa-check,.fa-times {
        z-index: 5!important;
    }
    .panel{
        padding-top: 20px;
    }
    .btn-primary,.btn-success {
        background-color: #6d160f!important;
        border-color: #6d160f!important;
    }
    .content-header{
        padding: 15px 15px 15px 15px;!important;
    }
</style>
<div id="scoop" class="scoop" style="display: none;">
    <div class="scoop-overlay-box"></div>
    <div class="scoop-container">
        <!--------header area start------->
        <header class="scoop-header">
            <div class="scoop-wrapper">
                <div class="scoop-left-header">
                    <div class="scoop-logo">
                        <a href="#">
                            <span class="logo-text"><?php echo Yii::$app->name ?><span class="hide-in-smallsize"></span></span></a>

                        <!--<a href="#"><span class="logo-icon"><img src="<?php /*echo getenv('BACKEND_URL')*/?>/img/pangea_img/logo.png"></span>
                            <span class="logo-text"><?php /*echo Yii::$app->name */?><span class="hide-in-smallsize"></span></span></a>-->
                        <!--<a href="#"><span class="logo-icon"><img src="<?php /*echo getenv('BACKEND_URL')*/?>/img/pangea_img/logo.png">
                            <span class="logo-text">
                                Pangea
                                <span class="hide-in-smallsize"></span>
                            </span>
                        </a>-->
                    </div>
                </div>
                <div class="scoop-right-header">
                    <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="fa fa-bars"></i></a></div>
                    <div class="scoop-rr-header">
                        <ul><li>
                                <?php  echo $this->render('_impersonate'); ?>
                            </li>
                            <li class="icons">

                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <span><?php echo Yii::t('backend', '{username}', ['username' => Yii::$app->user->identity->getPublicIdentity()]) ?></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?php echo Url::to(['/sign-in/profile'])?>">Profile</a></li>
                                    <li><a href="<?php echo Url::to(['/sign-in/account'])?>">Change Password</a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo Url::to(['/sign-in/logout'])?>" data-method="post">Logout</a></li>
                                </ul>
                            </li>
                            <li class="icons">
                                <a href="<?php echo Url::to(['/sign-in/logout'])?>" data-method="post">
                                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <!--------header area end------->

        <div class="scoop-main-container">
            <div class="scoop-wrapper">
                <!--------nav area start------->
                <nav class="scoop-navbar">
                    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
                    <div class="scoop-inner-navbar" >
                        <ul class="scoop-item scoop-brand">
                            <?php
                            echo Menu::widget([
                                'options' => ['class'=>'scoop-item scoop-left-item'],
                                'labelTemplate' => '<a href="#"><span class="scoop-micon">{icon}</span><span class="scoop-mtext">{label}</span><span class="scoop-mcaret"></span></a>',
                                'linkTemplate' => '<a href="{url}" ><span class="scoop-micon">{icon}</span><span class="scoop-mtext">{label}</span><span class="scoop-mcaret"></span></a>' ,
                                'submenuTemplate' => "\n<ul class=\"scoop-submenu down-nav\">\n{items}\n</ul>\n",
                                'activateParents' => true,
                                'items' => [
                                    ['label' => Yii::t('backend', 'My Profile'), 'url' => ['/sign-in/profile'], 'icon' => '<i class="fa fa-user" aria-hidden="true"></i>'],
                                    ['label' => Yii::t('backend', 'Organisation Information'), 'url' => ['/organisation/create'], 'icon' => '<i class="fa fa-book"></i>','visible'=>$organisationCreate&&!Yii::$app->user->can('administrator')],
                                    ['label' => Yii::t('backend', 'Organisation Information'), 'url' => ['/organisation/update', 'id'=>$organisationId], 'icon' => '<i class="fa fa-book"></i>','visible'=>$organisationUpdate&&!Yii::$app->user->can('administrator')],
                                    ['label' => 'Admin',
                                        'options'=>['class'=>'scoop-hasmenu'],
                                        //   'template' => '<a href="{url}" class="">{label} </a>',
                                        'icon'=>'<i class="fa fa-user"></i>',
                                        'visible' => Yii::$app->user->can('administrator'),
                                        //  'active' => in_array(\Yii::$app->controller->getRoute(),['/admin/route','/admin/permission','/admin/menu','/adminrole','/admin/assignment','/admin/user']),
                                        'items' => [
                                            ['label' => 'Route', 'url' => ['/admin/route']],
                                            ['label' => 'Permissions', 'url' => ['/admin/permission']],
                                            ['label' => 'Role', 'url' => ['/admin/role']],
                                            ['label' => 'Assignment', 'url' => ['/admin/assignment']],
                                            ['label' => 'User', 'url' => ['/admin/user']],
                                        ]
                                    ],
                                    ['label' => 'cases',
                                        'options'=>['class'=>'scoop-hasmenu'],
                                        //   'template' => '<a href="{url}" class="">{label} </a>',
                                        'icon'=>'<i class="fa fa-user"></i>',
                                       // 'visible' => Yii::$app->user->can('administrator'),
                                        'items' => [
                                            ['label' => 'Case Type', 'url' => ['/case-type/index']],
//                                            ['label' => 'Case Type Step', 'url' => ['/case-type-step/index']],
//                                            ['label' => 'Case Steps ', 'url' => ['/case-steps/index']],
                                            ['label' => 'Cases ', 'url' => ['/cases/index']]

                                        ]
                                    ],
                                    ['label' => 'Billing System',
                                        'url' => ['/receipt/index'],
                                        'icon'=>'<i class="fa fa-money"></i>',
                                        'visible' => !Yii::$app->user->can('administrator'),
                                        //   'active' => in_array(\Yii::$app->controller->id,['receipt','client','service']),
//                                        'items' => [
//                                            ['label' => 'Receipts', 'url' => ['/receipt/index']],
//                                            ['label' => 'Invoices', 'url' => ['/receipt/index', 'invoices'=>'true']],
//                                            /*['label' => 'Services', 'url' => ['service/index'],'icon' => '<i class="fa fa-circle-o text-aqua"></i>'],*/
//                                        ]
                                    ],
                                    ['label' => 'Clients',
                                        'url' => ['/client/index'],
                                        'icon' => '<i class="fa fa-users"></i>',
                                         'visible' => Yii::$app->user->identity->getRole()=='organisation-admin',
                                    ],
                                    ['label' => 'Applicants',
                                        'url' => ['/applicant/index'],
                                        'icon' => '<i class="fa fa-users"></i>',
                                        'visible' => Yii::$app->user->identity->getRole()=='organisation-admin'||Yii::$app->user->identity->getRole()==GlobalConstant::ROLE_CLIENT,
                                    ],
                                    ['label' => Yii::t('backend', 'Account'), 'url' => ['/sign-in/account'], 'icon' => '<i class="fa fa-lock" aria-hidden="true"></i>'],
                                    ['label' => Yii::t('backend', 'Users'), 'url' => ['/user/index'], 'icon' => '<i class="fa fa-users" aria-hidden="true"></i>','visible' => Yii::$app->user->can('administrator')||Yii::$app->user->can('organisation-admin')],
                                    ['label' => Yii::t('backend', 'Create Client Fields'), 'url' => ['/mii/default/custom-builder'], 'icon' => '<i class="fa fa-user" aria-hidden="true"></i>','visible' => Yii::$app->user->can('administrator')],
                                    ['label' => Yii::t('backend', 'Create Applicant Fields'), 'url' => ['/mii/applicant/custom-builder'], 'icon' => '<i class="fa fa-user" aria-hidden="true"></i>','visible' => Yii::$app->user->can('administrator')],
                                    ['label' => Yii::t('backend', 'Messages'), 'url' => ['/messageSystem/message/inbox'], 'icon' => '<i class="fa fa-envelope" aria-hidden="true"></i>'],
                                    /*['label' => Yii::t('backend', 'Frontend'), 'url' => '@frontendUrl/site/index', 'icon' => '<i class="fa fa-circle-thin" aria-hidden="true"></i>'],*/
                                    [
                                        'label' => Yii::t('backend', 'Questionnaire'),
                                        'url' => ['/polling/polling-quiz/index'],
                                        'icon' => '<i class="fa fa-question" aria-hidden="true"></i>',
                                        //'template' => '<a href="{url}" data-method="post">{icon} {label}</a>',
                                        'visible' => Yii::$app->user->can('administrator'),

                                    ],
                                    [
                                        'label' => Yii::t('backend', 'Invite Applicant'),
                                        'url' => ['/invite-applicant'],
                                        'icon' => '<i class="fa fa-share" aria-hidden="true"></i>',
                                        // 'template' => '<a href="{url}" data-method="post">{icon} {label}</a>',
                                        'visible' => !Yii::$app->user->can('administrator')&&Yii::$app->user->can(GlobalConstant::ROLE_CLIENT),

                                    ],
                                    [
                                        'label' => Yii::t('backend', 'Email Template'),
                                        'url' => ['/polling/email-template'],
                                        'icon' => '<i class="fa fa-edit" aria-hidden="true"></i>',
                                        //  'template' => '<a href="{url}" data-method="post">{icon} {label}</a>',
                                        'visible' => Yii::$app->user->can('administrator'),

                                    ],
                                       ['label' => 'Translation',
                                        'url' => ['/i18n/i18n-source-message/'],
                                        'icon'=>'<i class="fa fa-language"></i>',
                                        'visible' => Yii::$app->user->can('administrator'),

//                                        'items' => [
//                                            ['label' => 'Add Source Message', 'url' => ['/i18n/i18n-source-message/create']],
//                                            ['label' => 'View Source Message', 'url' => ['/i18n/i18n-source-message/']],
////                                            ['label' => 'Add Translation', 'url' => ['/i18n/i18n-message/create']],
////                                            ['label' => 'View Translation', 'url' => ['/i18n/i18n-message']],
//
//                                        ]
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

                        </ul>
                    </div>
                </nav>
                <!--------nav area end------->

                <!--------content area start------->
                <div class="scoop-content">
                    <div class="scoop-inner-content">
                        <!-- Content Header (Page header) -->
                        <section class="content-header">
                            <h1>
                                <?php echo $this->title ?>
                                <?php if (isset($this->params['subtitle'])): ?>
                                    <small><?php echo $this->params['subtitle'] ?></small>
                                <?php endif; ?>
                            </h1>

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
                </div>

                <!--------content area end------->
            </div>
        </div>
    </div>
</div>
<!---->


<?php $this->endContent(); ?>
<!-- Need to register form.js at end-->
<script src="<?php echo Yii::$app->request->baseUrl?>/js/pangea_js/form.js"></script>




