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
$organisationModel = \backend\models\Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
if(!empty($organisationModel)){
    $organisationUpdate = true;
    $organisationId = $organisationModel->id;
}else{
    $organisationCreate = true;
}
?>
<?php $this->beginContent('@backend/views/layouts/base.php'); ?>
    <div class="wrapper">
        <!-- header logo: style can be found in header.less -->
        <header class="main-header">
            <!--            <a href="--><?php //echo Yii::getAlias('@frontendUrl') ?><!--" class="logo">-->
            <a href="#" class="logo">

                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <?php echo Yii::$app->name ?>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only"><?php echo Yii::t('backend', 'Toggle navigation') ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li id="timeline-notifications" class="notifications-menu"  style="display: none">
                            <a href="<?php echo Url::to(['/timeline-event/index']) ?>">
                                <i class="fa fa-bell"></i>
                                <span class="label label-success">
                                    <?php echo TimelineEvent::find()->today()->count() ?>
                                </span>
                            </a>
                        </li>
                        <!-- Notifications: style can be found in dropdown.less -->
                        <li id="log-dropdown" class="dropdown notifications-menu" style="display: none">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-warning"></i>
                                <span class="label label-danger">
                                <?php echo \backend\models\SystemLog::find()->count() ?>
                            </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header"><?php echo Yii::t('backend', 'You have {num} log items', ['num' => \backend\models\SystemLog::find()->count()]) ?></li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <?php foreach (\backend\models\SystemLog::find()->orderBy(['log_time' => SORT_DESC])->limit(5)->all() as $logEntry): ?>
                                            <li>
                                                <a href="<?php echo Yii::$app->urlManager->createUrl(['/log/view', 'id' => $logEntry->id]) ?>">
                                                    <i class="fa fa-warning <?php echo $logEntry->level == \yii\log\Logger::LEVEL_ERROR ? 'text-red' : 'text-yellow' ?>"></i>
                                                    <?php echo $logEntry->category ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <li class="footer">
                                    <?php echo Html::a(Yii::t('backend', 'View all'), ['/log/index']) ?>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <?php  echo $this->render('_impersonate'); ?>
                        </li>

                        <li>
                            <?php echo Html::a('<i class="fa fa-cogs"></i>', ['/site/settings']) ?>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel" style="min-height: 75px;">
                    <div class="pull-left image">
                        <img
                            src="<?php echo Yii::$app->user->identity->userProfile->getAvatar() ?: '/img/anonymous.jpg' ?>"
                            class="img-circle"/>
                    </div>
                    <div class="pull-left info">
                        <p><?php echo Yii::t('backend', 'Hello, {username}', ['username' => Yii::$app->user->identity->getPublicIdentity()]) ?></p>
                        <a href="<?php echo Url::to(['/sign-in/profile']) ?>">
                            <i class="fa fa-circle text-success"></i>
                            <?php echo Yii::$app->formatter->asDatetime(time()) ?>
                        </a>
                    </div>

                </div>



                <!-- sidebar menu: : style can be found in sidebar.less -->




                <?php
                echo Menu::widget([
                    'options' => ['class' => 'sidebar-menu'],
                    'labelTemplate' => '<a href="#">{icon}<span>{label}</span>{right-icon}{badge}</a>',
                    'linkTemplate' => '<a  href="{url}">{icon}<span>{label}</span>{right-icon}{badge}</a>',
                    'submenuTemplate' => "\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n",
                    'activateParents' => true,
                    'items' => [



                        /* [
                             'label' => Yii::t('backend', 'Timeline'),
                             'icon' => '<i class="fa fa-bar-chart-o"></i>',
                             'url' => ['/timeline-event/index'],
                             'badge' => TimelineEvent::find()->today()->count(),
                             'badgeBgClass' => 'label-success',
                             'visible'=>true
                         ],*/

                        ['label' => Yii::t('backend', 'Organisation Information'), 'url' => ['organisation/create'], 'icon' => '<i class="fa fa-book"></i>','visible'=>$organisationCreate&&!Yii::$app->user->can('administrator')],
                        ['label' => Yii::t('backend', 'Organisation Information'), 'url' => ['organisation/update', 'id'=>$organisationId], 'icon' => '<i class="fa fa-book"></i>','visible'=>$organisationUpdate&&!Yii::$app->user->can('administrator')],
                    ],

                ]) ?>



                <?php
                echo Menu::widget([
                    'options' => ['class' => 'sidebar-menu'],

                    'labelTemplate' =>  '<i class="fa fa-edit"></i>',
                    'linkTemplate' => '<a  href="{url}">{icon}<span>{label}</span>{right-icon}{badge}</a>',
                    'submenuTemplate' => "\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n",
                    'activateParents' => true,

                    'items' => [
                        ['label' => 'Billing System',
                            'options'=>['class'=>'sidebar-menu treeview'],
                            'template' => '<a href="{url}" class="href_class">{label} </a>',
                            'visible' => !Yii::$app->user->can('administrator'),
                            'active' => in_array(\Yii::$app->controller->id,['receipt','client','service']),
                            'items' => [
                                ['label' => 'Receipts', 'url' => ['receipt/index'],'icon' => '<i class="fa fa-circle-o text-green"></i>'],
                                ['label' => 'Invoices', 'url' => ['receipt/index', 'invoices'=>'true'],'icon' => '<i class="fa fa-circle-o text-red"></i>'],
                                /*['label' => 'Services', 'url' => ['service/index'],'icon' => '<i class="fa fa-circle-o text-aqua"></i>'],*/
                            ]
                        ],


                    ],



                ]) ?>


                <?php
                echo Menu::widget([
                    'options' => ['class' => 'sidebar-menu'],
                    'labelTemplate' => '<a href="#">{icon}<span>{label}</span>{right-icon}{badge}</a>',
                    'linkTemplate' => '<a  href="{url}">{icon}<span>{label}</span>{right-icon}{badge}</a>',
                    'submenuTemplate' => "\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n",


                    'items' => [

                        ['label' => Yii::t('backend', 'My Profile'), 'url' => ['/sign-in/profile'], 'icon' => '<i class="fa fa-user" aria-hidden="true"></i>'],
                        ['label' => 'Clients',
                            'url' => ['client/index'],
                            'icon' => '<i class="fa fa-circle-o text-yellow"></i>',
                            'visible' => Yii::$app->user->can('organisation-admin'),
                            ],
                        ['label' => 'Applicants',
                            'url' => ['applicant/index'],
                            'icon' => '<i class="fa fa-circle-o text-green"></i>',
                            'visible' => Yii::$app->user->can(GlobalConstant::ROLE_CLIENT),
                        ],
                        ['label' => Yii::t('backend', 'Account'), 'url' => ['/sign-in/account'], 'icon' => '<i class="fa fa-user" aria-hidden="true"></i>'],
                        ['label' => Yii::t('backend', 'Users'), 'url' => ['/user'], 'icon' => '<i class="fa fa-user" aria-hidden="true"></i>','visible' => Yii::$app->user->can('administrator')||Yii::$app->user->can('organisation-admin')],
                        ['label' => Yii::t('backend', 'Create Client'), 'url' => ['/mii/default/custom-builder'], 'icon' => '<i class="fa fa-user" aria-hidden="true"></i>','visible' => Yii::$app->user->can('administrator')],
                        ['label' => Yii::t('backend', 'Create Applicant'), 'url' => ['/mii/applicant/custom-builder'], 'icon' => '<i class="fa fa-user" aria-hidden="true"></i>','visible' => Yii::$app->user->can('administrator')],
                        ['label' => Yii::t('backend', 'Messages'), 'url' => ['/messageSystem/message/inbox'], 'icon' => '<i class="fa fa-user" aria-hidden="true"></i>'],
                        /*['label' => Yii::t('backend', 'Frontend'), 'url' => '@frontendUrl/site/index', 'icon' => '<i class="fa fa-circle-thin" aria-hidden="true"></i>'],*/
                        [
                            'label' => Yii::t('backend', 'Questionnaire'),
                            'url' => ['/polling'],
                            'icon' => '<i class="fa fa-clipboard" aria-hidden="true"></i>',
                            'template' => '<a href="{url}" data-method="post">{icon} {label}</a>',
                            'visible' => Yii::$app->user->can('administrator'),

                        ],
                           [
                            'label' => Yii::t('backend', 'Invite Applicant'),
                            'url' => ['/invite-applicant'],
                            'icon' => '<i class="fa fa-share" aria-hidden="true"></i>',
                            'template' => '<a href="{url}" data-method="post">{icon} {label}</a>',
                            'visible' => !Yii::$app->user->can('administrator')&&Yii::$app->user->can(GlobalConstant::ROLE_CLIENT),

                        ],
                        [
                            'label' => Yii::t('backend', 'Email Template'),
                            'url' => ['polling/email-template'],
                            'icon' => '<i class="fa fa-edit" aria-hidden="true"></i>',
                            'template' => '<a href="{url}" data-method="post">{icon} {label}</a>',
                            'visible' => Yii::$app->user->can('administrator'),

                        ],

                        [
                            'label' => Yii::t('backend', 'Log Out'),
                            'url' => ['/sign-in/logout'],
                            'icon' => '<i class="fa fa-sign-out" aria-hidden="true"></i>',
                            'template' => '<a href="{url}" data-method="post">{icon} {label}</a>'

                        ],


                    ],


                ]) ?>

                <?php
                echo Menu::widget([
                    'options' => ['class' => 'sidebar-menu'],

                    'labelTemplate' =>  '<i class="fa fa-edit"></i>',
                    'linkTemplate' => '<a  href="{url}"><span>{label}</span>{right-icon}{badge}</a>',
                    'submenuTemplate' => "\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n",
                    'activateParents' => true,




                    'items' => [
                        ['label' => 'Admin',
                            'options'=>['class'=>'sidebar-menu treeview'],
                            'icon' => '<i class="fa fa-user" aria-hidden="true"></i>',
                            'template' => '<a href="{url}" class="href_class">{icon}<span>{label}</span> </a>',
                            'visible' => Yii::$app->user->can('administrator'),
                            'active' => in_array(\Yii::$app->controller->getRoute(),['/admin/route','/admin/permission','/admin/menu','/adminrole','/admin/assignment','/admin/user']),
                            'items' => [
                                ['label' => 'Route', 'url' => ['/admin/route'],'icon' => '<i class="fa fa-circle-o text-yellow"></i>'],
                                ['label' => 'Permissions', 'url' => ['/admin/permission'],'icon' => '<i class="fa fa-circle-o text-green"></i>'],
                                ['label' => 'Role', 'url' => ['/admin/role'],'icon' => '<i class="fa fa-circle-o text-red"></i>'],
                                ['label' => 'Assignment', 'url' => ['/admin/assignment'],'icon' => '<i class="fa fa-circle-o text-aqua"></i>'],
                                ['label' => 'User', 'url' => ['/admin/user'],'icon' => '<i class="fa fa-circle-o text-green"></i>'],
                            ]
                        ],


                    ],



                ]) ?>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="content-wrapper">
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
            <section class="content">
                <?php if (Yii::$app->session->hasFlash('alert')): ?>
                    <?php echo \yii\bootstrap\Alert::widget([
                        'body' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
                        'options' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
                    ]) ?>
                <?php endif; ?>
                <?php echo $content ?>
            </section>
            <!-- /.content -->
        </aside>
        <!-- /.right-side -->
    </div><!-- ./wrapper -->
    

<?php $this->endContent(); ?>

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
</style>
