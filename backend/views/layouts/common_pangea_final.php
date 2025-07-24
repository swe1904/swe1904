<?php
/**
 * @var $this yii\web\View
 */
use app\components\GlobalConstant;
use backend\models\Attendance;
use backend\models\Notification;
use backend\widgets\Menu;
use common\models\TimelineEvent;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\models\Organisation;
//die(var_dump($this->context->route));
?>
<?php
// echo "<pre>";
// print_r(GlobalConstant::ROLE_SUPERADMIN);
// echo "<pre>";
// var_dump(Yii::$app->user->identity->getRole());
//$userId=Yii::$app->user->id;
 
// die();
// print_r($_SESSION);
$organisationCreate = false;
$organisationUpdate = false;
$organisationId = false;
//$organisationModel = \backend\models\Organisation::find()->where(['user_id' => Yii::$app->user->identity->id])->one();
if (!empty(Yii::$app->user->identity->organisation_id)) {
    $organisationUpdate = true;
    $organisationId = Yii::$app->user->identity->organisation_id;
} else {
    $organisationCreate = true;
}
 //$linjk= Yii::$app->urlManager->createUrl(['leave-request/update-notification-read']);

?>
<?php $this->beginContent('@backend/views/layouts/base_pangea_final.php'); ?>

    <body>
<!-- Preloader -->
<!--<div class="preloader-it">
    <div class="la-anim-1"></div>
</div>-->
<!-- /Preloader -->
 <?php $imageUrl =  getenv('BACKEND_URL').'images/Northman-logo.png'; ?>
<div class="wrapper theme-2-active navbar-top-light horizontal-nav test-class">
    <div class="l-sidebar" id="side-bar">
        <nav class="sidebar d-flex justify-content-between">
            <div>
                <div class="logo">
                    <img
                            src="<?php echo $imageUrl;?>" alt="no image">
                    <hr>
                </div>
                <ul class="sidebar_list">

                    <!-- CHECK START-->
                    <li style="display:none" class=""><a class="<?= $this->context->route == 'employee/profile' ? 'active':'' ?>" href="<?= getenv('BACKEND_URL') ?>employee/profile">  My Profile</a>
                    </li>


                    <li style="display:none" class=""><a class="<?= $this->context->route == 'sign-in/account' ? 'active':'' ?>" href="<?= getenv('BACKEND_URL')?>sign-in/account">  Account</a>
                    </li>
                    <!-- CHECK END-->
                    
                    <!-- <?php 
                  
                    if (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_WORKER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_CASE_MANAGER ||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER||
                    Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_SUPERVISOR ||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_COUNTRY_MANAGER ||
                    Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_PAYROLL_MANAGER ||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_DEPARTMENT_MANAGER ||
                    Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_PAYROLL_MANAGER ||Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_HR_MANAGER ||
                    Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_PAYROLL_MANAGER 
                    ):
                    ?>
                    
                            <a href="<?= getenv('BACKEND_URL')?>dashboard/index" class="sidebar_link">
                                <span class="sidebar_link_icon"><i class="ti-dashboard"></i></span>
                                <span class="sidebar_name">Dashboard</span>
                            </a>
                        </li>
                    <?php endif; ?> -->
 <?php
$auth = Yii::$app->authManager;
$roles = $auth->getRolesByUser(Yii::$app->user->id);
$userRole = key($roles); // Gets first role name

if (in_array($userRole, [GlobalConstant::ROLE_ORGANISATION_ADMIN, GlobalConstant::ROLE_HR_MANAGER])) :
?>
    <li>
        <a href="<?= getenv('BACKEND_URL') ?>organisation/create" class="sidebar_link <?= $this->context->route == 'organisation/create' ? 'active':'' ?>">
            <i class="ti-image"></i>
            <span class="sidebar_name">Office Setup</span>
        </a>
    </li>
<?php endif; ?>
                    
                    <?php if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT): ?>
                        <a href="<?= getenv('BACKEND_URL') ?>client-entity/index" class="sidebar_link <?= in_array(Yii::$app->controller->id, ['client-entity']) ? 'active':'' ?>">
                                <i class="ti-user"></i>
                                <span class="sidebar_name">Client Entity</span>
                            </a>
                    <?php endif; ?>
                  

                    <?php if(Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN) || Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_ADMIN)||Yii::$app->user->can(GlobalConstant::ROLE_CLIENT) || Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_MANAGER)|| Yii::$app->user->can(GlobalConstant::ROLE_HR_MANAGER) || Yii::$app->user->can(GlobalConstant::ROLE_DEPARTMENT_MANAGER)): ?>
                        <li>
                            <a href="<?= getenv('BACKEND_URL')?>user/index" class="sidebar_link <?= ($this->context->route == 'user/index') ? 'active' : '' ?>">
                                <i class="<?= Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN)?'fa fa-sitemap':'ti-user' ?>"></i>
                                <span class="sidebar_name"><?= Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN)?'Offices':'Users' ?></span>
                            </a>
                           
                        </li>
                    <?php endif; ?>
        <?php if (Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN)): ?>
    <li>
        <a href="<?= \yii\helpers\Url::to(['user/indexsuperadminusers']) ?>"
           class="sidebar_link <?= ($this->context->route == 'user/indexsuperadminusers') ? 'active' : '' ?>">
            <i class="fa ti-user"></i>
            <span class="sidebar_name">All Users</span>
        </a>
    </li>
    <li class="sidebar_link <?= ($this->context->route == 'leave-request/calendar') ? 'active' : '' ?>">
        <a href="<?= \yii\helpers\Url::to(['leave-request/calendar']) ?>">
            <i class="fa fa-calendar"></i>
            <span class="sidebar_name">Leave Calendar</span>
        </a>
    </li>
<?php endif; ?>



                 

                         
                    <?php
// Get the current user's role
$userRole = Yii::$app->user->identity->getRole();

// HR roles array 
$hrRoles = [
    GlobalConstant::ROLE_SUPERVISOR,
    GlobalConstant::ROLE_COUNTRY_MANAGER,
    GlobalConstant::ROLE_PAYROLL_MANAGER,
    GlobalConstant::ROLE_HR_MANAGER,
    GlobalConstant::ROLE_DEPARTMENT_MANAGER
];
?>

<?php
// Get the current user's role
$userRole = Yii::$app->user->identity->getRole();

// HR Roles Array
$hrRoles = [
    GlobalConstant::ROLE_SUPERVISOR,
    GlobalConstant::ROLE_COUNTRY_MANAGER,
    GlobalConstant::ROLE_PAYROLL_MANAGER,
    GlobalConstant::ROLE_HR_MANAGER,
    GlobalConstant::ROLE_DEPARTMENT_MANAGER
];

// Other Roles
$otherRoles = [
    GlobalConstant::ROLE_ORGANISATION_ADMIN,
    GlobalConstant::ROLE_CLIENT,
    GlobalConstant::ROLE_ORGANISATION_MANAGER,
    GlobalConstant::ROLE_CASE_WORKER
];
?>

<!-- HR Menu - Only for HR Roles -->
<?php if (in_array($userRole, $hrRoles)): ?>
    <li>
        <a href="#HR" class="sidebar_link <?php if (in_array(Yii::$app->controller->id, ['default', 'employee', 'slip', 'department', 'team', 'position', 'role'])) echo 'active'; ?>">
            <i class="ti-briefcase"></i>
            <span class="sidebar_name">HR</span>
        </a>
        <ul class="notika-main-menu-dropdown">
            <li class="<?php if (Yii::$app->requestedRoute == 'payroll/default/index') echo 'active'; ?>">
                <a href="<?= getenv('BACKEND_URL') ?>payroll/default/index">Settings</a>
            </li>
            <li class="<?php if (Yii::$app->requestedRoute == 'employee/index') echo 'active'; ?>">
                <a href="<?= getenv('BACKEND_URL') ?>employee/index">Employees</a>
            </li>
            <li class="<?php if (Yii::$app->requestedRoute == 'department/index') echo 'active'; ?>">
                <a href="<?= getenv('BACKEND_URL') ?>department/index">Departments</a>
            </li>
            <li class="<?php if (Yii::$app->requestedRoute == 'team/index') echo 'active'; ?>">
                <a href="<?= getenv('BACKEND_URL') ?>team/index">Teams</a>
            </li>
            <li class="<?php if (Yii::$app->requestedRoute == 'position/index') echo 'active'; ?>">
                <a href="<?= getenv('BACKEND_URL') ?>position/index">Positions</a>
            </li>
            <li class="<?php if (Yii::$app->requestedRoute == 'role/index') echo 'active'; ?>">
                <a href="<?= getenv('BACKEND_URL') ?>role/index">Roles</a>
            </li>
        </ul>
    </li>
<?php endif; ?>

<!-- Payroll Menu - Visible to HR, Admin, Client, and Case Worker Roles -->
<?php if (in_array($userRole, $hrRoles) || in_array($userRole, $otherRoles)): ?>
    <li>
        <a href="#Payslips" class="sidebar_link <?php if (in_array(Yii::$app->controller->id, ['payroll', 'slip', 'salary-structure', 'payroll-report', 'payroll-run', 'payslip'])) echo 'active'; ?>">
            <i class="ti-wallet"></i>
            <span class="sidebar_name">Payslips</span>
        </a>
        <ul class="notika-main-menu-dropdown">
        <li class="<?php if(Yii::$app->requestedRoute == 'payroll-run/index') echo 'active'; ?>">
        <a href="<?= Yii::$app->urlManager->createUrl('payroll-run/index') ?>">Payroll Run</a>
    </li>
    <li class="<?php if(Yii::$app->requestedRoute == 'payslip/index') echo 'active'; ?>">
        <a href="<?= Yii::$app->urlManager->createUrl('payslip/index') ?>">Payslips</a>
    </li>
    <li class="<?php if(Yii::$app->requestedRoute == 'salary-structure/index') echo 'active'; ?>">
        <a href="<?= Yii::$app->urlManager->createUrl('salary-structure/index') ?>">Salary Structure</a>
    </li>
    <li class="<?php if(Yii::$app->requestedRoute == 'payroll-report/index') echo 'active'; ?>">
        <a href="<?= Yii::$app->urlManager->createUrl('payroll-report/index') ?>">Payroll Reports</a>
    </li>
        </ul>
    </li>

    
<?php endif; ?>

<!-- Payroll Menu - Visible to Employees -->
<?php if ($userRole == GlobalConstant::ROLE_EMPLOYEE || $userRole == GlobalConstant::ROLE_TEAM_MANAGER): ?>
    <li>
        <a href="#Payroll" class="sidebar_link <?php if (in_array(Yii::$app->controller->id, ['payroll', 'slip', 'salary-structure', 'payroll-report', 'payroll-run', 'payslip'])) echo 'active'; ?>">
            <i class="ti-wallet"></i>
            <span class="sidebar_name">Payroll</span>
        </a>
        <ul class="notika-main-menu-dropdown">
            <li class="<?php if (Yii::$app->requestedRoute == 'payslip/index') echo 'active'; ?>">
                <a href="<?= Yii::$app->urlManager->createUrl('payslip/index') ?>">Payslips</a>
            </li>
        </ul>
    </li>
<?php endif; ?>

<!-- Attendance Menu - Visible to both HR Roles and Employees -->
<?php if (in_array($userRole, $hrRoles) || $userRole == GlobalConstant::ROLE_EMPLOYEE || $userRole == GlobalConstant::ROLE_TEAM_MANAGER): ?>
    <li>
        <a href="#Attendance" class="sidebar_link <?php if (Yii::$app->controller->id == 'attendance') echo 'active'; ?>">
            <i class="ti-check-box"></i>
            <span class="sidebar_name">Attendance</span>
        </a>
        <ul class="notika-main-menu-dropdown">
            <li class="<?php if (Yii::$app->requestedRoute == 'attendance/index') echo 'active'; ?>">
                <a href="<?= Yii::$app->urlManager->createUrl('attendance/index') ?>">Daysheet Login/Logout</a>
            </li>
        </ul>
    </li>
<?php endif; ?>


                        <?php 


$role = Yii::$app->user->identity->getRole();

if(in_array($role, [GlobalConstant::ROLE_ORGANISATION_ADMIN, GlobalConstant::ROLE_EMPLOYEE,GlobalConstant::ROLE_TEAM_MANAGER, GlobalConstant::ROLE_HR_MANAGER, GlobalConstant::ROLE_DEPARTMENT_MANAGER])): 
?>
    <li>
        <a href="#Payroll" class="sidebar_link <?php if(in_array(Yii::$app->controller->id, ['default', 'employee', 'slip', 'department'])) echo 'active'; ?>">
            <i class="ti-book"></i>
            <span class="sidebar_name">Leave Management</span>
        </a>
        <ul class="notika-main-menu-dropdown">
            <!-- Leave Request - visible to all roles -->
            <li class="<?php if(Yii::$app->requestedRoute == 'leave-request/index') echo 'active'; ?>">
                <a href="<?= getenv('BACKEND_URL') ?>leave-request/index">Leave Request</a>
            </li>
             <li class="<?php if(Yii::$app->requestedRoute == 'leave-request/calendar') echo 'active'; ?>">
            <a href="<?= getenv('BACKEND_URL') ?>leave-request/calendar">Leave Calendar</a>
        </li> 
             <li class="<?php if(Yii::$app->requestedRoute == 'leave-request/history') echo 'active'; ?>">
                    <a href="<?= getenv('BACKEND_URL') ?>leave-request/history">Leave History</a>
                </li>
            <li class="<?php if(Yii::$app->requestedRoute == 'leave-request/index') echo 'active'; ?>">
                    <a href="<?= getenv('BACKEND_URL') ?>leave-balance/index">Leave Balance</a>
                </li>
                  <li class="<?php if(Yii::$app->requestedRoute == 'leave-request/wfh-index') echo 'active'; ?>">
                    <a href="<?= getenv('BACKEND_URL') ?>leave-request/wfh-index">Work From Home</a>
                </li>
           
            <?php if(in_array($role, [GlobalConstant::ROLE_ORGANISATION_ADMIN, GlobalConstant::ROLE_HR_MANAGER, GlobalConstant::ROLE_DEPARTMENT_MANAGER, GlobalConstant::ROLE_TEAM_MANAGER])): ?>
    <?php if(in_array($role, [GlobalConstant::ROLE_ORGANISATION_ADMIN, GlobalConstant::ROLE_HR_MANAGER, GlobalConstant::ROLE_DEPARTMENT_MANAGER])): ?>
       
        <li class="<?php if(Yii::$app->requestedRoute == 'business-travel/index') echo 'active'; ?>">
            <a href="<?= getenv('BACKEND_URL') ?>business-travel/index">BUSINESS TRAVEL</a>
        </li>
    <?php endif; ?>

    <!-- Leave Approval: visible to Admin, HR Manager, Dept Manager, and Team Manager -->
    <li class="<?php if(Yii::$app->requestedRoute == 'leave-request/approve') echo 'active'; ?>">
        <a href="<?= getenv('BACKEND_URL') ?>leave-request/approve">Leave Approval</a>
    </li>
<?php endif; ?>

        </ul>
    </li>
<?php endif; ?>

                  
                    <?php
$role = Yii::$app->user->identity->getRole();

// Show full "Documents Services" menu only to higher roles
        if (in_array($role, [
        GlobalConstant::ROLE_ORGANISATION_ADMIN,
        GlobalConstant::ROLE_SUPERVISOR,
        GlobalConstant::ROLE_COUNTRY_MANAGER,
        GlobalConstant::ROLE_PAYROLL_MANAGER,
        GlobalConstant::ROLE_HR_MANAGER,
        GlobalConstant::ROLE_DEPARTMENT_MANAGER
        ])): ?>
                       <li>
        <a href="#Documents" class="sidebar_link <?php if(in_array(Yii::$app->controller->id, ['document-request', 'hr-setup', 'document-templates'])) echo 'active'; ?>">
            <i class="ti-book"></i>
            <span class="sidebar_name">Documents Services</span>
        </a>
        <ul class="notika-main-menu-dropdown">
            <li class="<?php if(Yii::$app->requestedRoute == 'document-request/index') echo 'active'; ?>">
                <a href="<?= getenv('BACKEND_URL') ?>document-request/index">Document Request</a>
            </li>
            <li class="<?php if(Yii::$app->requestedRoute == 'hr-setup/create') echo 'active'; ?>">
                <a href="<?= getenv('BACKEND_URL')?>hr-setup/create">Documents Manager</a>
            </li>
            <li class="<?php if(Yii::$app->requestedRoute == 'document-templates/index') echo 'active'; ?>">
                <a href="<?= getenv('BACKEND_URL')?>document-templates/index">Documents Template</a>
            </li>
        </ul>
    </li>
<?php endif; ?>

<!-- Employee-only access to personal documents -->
<?php if ($role == GlobalConstant::ROLE_EMPLOYEE || $role == GlobalConstant::ROLE_TEAM_MANAGER): ?>
    <li>
        <a href="#MyDocs" class="sidebar_link <?php if(Yii::$app->controller->id == 'employee-docs') echo 'active'; ?>">
            <i class="ti-file"></i>
            <span class="sidebar_name">My Documents</span>
        </a>
        
        <ul class="notika-main-menu-dropdown">
            <li class="<?php if(Yii::$app->requestedRoute == 'document-request/index') echo 'active'; ?>">
                <a href="<?= getenv('BACKEND_URL') ?>document-request/index">Documents Request</a>
            </li>
            <li class="<?php if(Yii::$app->requestedRoute == 'employee-docs/index') echo 'active'; ?>">
                <a href="<?= getenv('BACKEND_URL') ?>employee-docs/index">View My Documents</a>
            </li>
        </ul>
    </li>
<?php endif; ?>



                    <?php if(Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN)): ?>


                        <li >
                            <a href="#Admin" class="sidebar_link <?= $this->context->route == 'admin/index' ? 'active':'' ?>"> <i class="ti-user"></i>
                                <span class="sidebar_name">Admin</span>
                            </a>
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="<?= getenv('BACKEND_URL')?>admin/route">Route</a></li>
                                <li><a href="<?= getenv('BACKEND_URL')?>admin/permission">Permissions</a></li>
                                <li><a href="<?= getenv('BACKEND_URL')?>admin/role">Role</a></li>
                                <li><a href="<?= getenv('BACKEND_URL')?>admin/assignment">Assignment</a></li>
                                <li><a href="<?= getenv('BACKEND_URL')?>admin/user">User</a></li>

                               
                            </ul>

                        </li>

                    <?php endif; ?>

                  
                 

                    <?php if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER): ?>
                        <li >
                            <a href="<?= getenv('BACKEND_URL')?>report/view?id=<?= Yii::$app->user->id ?>" class="sidebar_link <?= Yii::$app->controller->id == 'report' ? 'active':'' ?>"> <i class="ti-book"></i>
                                <span class="sidebar_name">Report</span>
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>

        </nav>
    </div>
    <!-- Sidebar end -->
    <!-- Main content start -->
    <main class="page-wrapper" style="position:relative; padding-bottom:8rem;">

        <nav class="navbar fixed-top navbar-expand-lg header header-white">
            <div style="display:flex; justify-content:space-between;align-items:center;">
                <!-- CHECK START -->
                <?php echo $this->render('_impersonate'); ?>
                <!-- CHECK END -->
                <div class="me-1 text-sm header__left">

                    <a id="toggle_nav_btn" class="toggle-left-nav-btn inline-block pull-left" href="javascript:void(0);"><i class="ti-align-left"></i></a>
                    <?php if(!Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN)) {
                        if(isset(Yii::$app->user->identity->organisation_id))
                            $organisation = Organisation::findOne(Yii::$app->user->identity->organisation_id);
                        else
                            $organisation = Organisation::findOne(['user_id' => Yii::$app->user->identity->id]);

                        ?>
                        <div class="logo-wrap">
                            <a class="brand-logo" href="<?= getenv('BACKEND_URL') ?>">
                                <div><img src="<?php if(!empty($organisation->avatar_base_url) && !empty($organisation->avatar_path)) echo $organisation->avatar_base_url.'/'.$organisation->avatar_path;?>" alt=""></div>
                                <?php if($organisation) {?>
                                    <span class="brand-logo-text"><?= $organisation->name?></span>
                                    <?php
                                }
                                ?>
                            </a>
                        </div>
                    <?php } ?>
                    <div>
                        <div><i class="ti-image mr-5"></i>
                            <span class="text-extra-sm text-light">
                             <?php
                             if (isset($this->params['breadcrumbs'])) {
                                 $breadcrumbs = [];
                                 foreach ($this->params['breadcrumbs'] as $breadcrumb) {
                                     // If the breadcrumb is an array, use its value directly
                                     if (is_array($breadcrumb)) {
                                         $breadcrumbs[] = reset($breadcrumb); // Get the first element of the array
                                     } else {
                                         $breadcrumbs[] = $breadcrumb; // Otherwise, use the breadcrumb directly
                                     }
                                 }
                                 echo implode(' / ', $breadcrumbs);
                             } else {
                                 echo 'Dashboard / Home'; // Fallback if breadcrumbs are not set
                             }
                             ?>
                             </span></div>
                        <div><span class=" mt-4 text-md text-bold" href="#"><?= Html::encode($this->title) ?></span></div>
                    </div>
                </div>

                <div class="header__right align-items-center dropdown auth-drp" style="display: flex; align-items: center;">
    
               

                <?php 
                  
$notifications = Notification::find()
    ->with(['fromUser', 'toUser']) // Eager load related users
    ->where([
        'to_user_id' => Yii::$app->user->id,
        'is_read' => 0, // Only unread notifications
    ])
    ->orderBy(['created_at' => SORT_DESC])
    ->limit(10)
    ->all();


$notificationCount = count($notifications);
                    ?>

                    <div class="header__right align-items-center dropdown auth-drp" style="display: flex; align-items: center;">
                    <!-- Notification Icon -->
                    <div class="notification-icon mr-3" style="margin-right: 10px;">
                        <a href="#" id="notificationDropdown" data-toggle="modal" data-target="#notificationModal">
                        <i class="fa fa-bell" style="font-size: 20px; color: red;"></i>
                            <span class="badge badge-danger" id="notificationCount"><?= $notificationCount; ?></span>
                        </a>
                    </div>
<!-- Mark Attendance Button -->
<!-- Attendance Button -->
<?php
// Assume you have a variable $hasCheckedIn that tells if the user already checked in
// For example, this can come from your model or database query for current user attendance status
$userId = Yii::$app->user->id;
$today = date('Y-m-d');

// Find today's attendance record where the user has checked in but not yet checked out
$attendance = Attendance::find()
    ->where([
        'employee_id' => $userId,
        'date' => $today,
    ])
    ->andWhere(['out_time' => null]) // this is important
    ->one();

// Determine check-in status
$hasCheckedIn = $attendance != null;

// Set button label and link
$buttonLabel = $hasCheckedIn ? 'Checkout' : 'Checkin';
$buttonUrl   = $hasCheckedIn ? ['attendance/create'] : ['attendance/create'];

?>

<!-- Attendance Button -->
<div class="ml-3">
    <?= Html::a(
        $buttonLabel,
        $buttonUrl,
        ['class' => 'btn btn-outline-primary btn-sm']
    ) ?>
</div>

                 <?php
$userProfile = null;
$avatarPath = null;
$avatarBaseUrl = null;

if (!Yii::$app->user->isGuest && Yii::$app->user->identity && Yii::$app->user->identity->userProfile) {
    $userProfile = Yii::$app->user->identity->userProfile;
    $avatarPath = $userProfile->avatar_path;
    $avatarBaseUrl = $userProfile->avatar_base_url;
}
?>

<?php if ($avatarPath): ?>
    <img style="height: 43px; width: 43px; vertical-align: middle;"
         src="<?= $avatarBaseUrl . '/' . $avatarPath ?>"
         alt="user_auth"
         class="user-auth-img img-circle"/>
    <span class="user-online-status"></span>
<?php else: ?>
    <i class="user-image fa fa-user" style="padding-top: 5px; vertical-align: middle;"></i>
    <span class="user-online-status"></span>
<?php endif; ?>


                    <!-- Notification Modal -->
                    <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <ul class="list-group">
                                        <?php if (!empty($notifications)): ?>
                                            <?php foreach ($notifications as $notification): ?>
                    <li class="list-group-item">
                 
                    <a href="<?= Yii::$app->urlManager->createUrl(['leave-request/redirect', 'id' => $notification->id]) ?>"
                    style="color: #333; text-decoration: none;">
                    <?= htmlspecialchars($notification->message) ?>
                    </a>


                    </li>
                    <?php endforeach; ?>
                                        <?php else: ?>
                                            <li class="list-group-item">No new notifications.</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>


                    <!-- User Profile Section -->


                    <a class="ml-5 dropdown-toggle" data-toggle="dropdown" href="#">
                        <?= Yii::$app->user->identity->getPublicIdentity() ?> <i class="fa fa-caret-down"></i>
                    </a>

                    <ul class="dropdown-menu user-auth-dropdown" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
                        <li><a href="<?= getenv('BACKEND_URL')?>employee/profile"><i class="zmdi zmdi-account"></i><span>Profile</span></a></li>
                        <li><a href="<?= getenv('BACKEND_URL')?>sign-in/account"><i class="zmdi zmdi-email"></i><span>Update Password</span></a></li>
                        <li><a href="<?= getenv('BACKEND_URL')?>sign-in/logout" data-method="post"><i class="zmdi zmdi-settings"></i><span>Logout</span></a></li>
                    </ul>
                    </div>


            </div>
        </nav>

        <div class="container" style="padding:.5rem;">

            <!-- Row -->
            <?php echo $content ?>
            <!-- /Row -->
        </div>

        <!-- Footer -->
        <footer class="footer">

            <div class="row">
                <div class="col-sm-12 text-right">
                    <p>Follow Us</p>
                    <a href="https://www.facebook.com/northmansterling" target="_blank"><i class="fa fa-facebook"></i></a>
                    <a href="https://twitter.com/northmansterlin" target="_blank"><i class="fa fa-twitter"></i></a>
                    <a class="mr-20" href="https://www.linkedin.com/company/northman-sterling/" target="_blank"><i class="fa fa-linkedin"></i></a>
                </div>
            </div>

        </footer>
        <!-- /Footer -->

    </main>




    <!-- /Main Content -->

</div>
<!-- /#wrapper -->


<!--Display success message-->
<?php
//*** uncomment following to test success message UI
//Yii::$app->session->setFlash('success', "This is a sample success message.");
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <?= backend\components\CustomToaster::render('success', Yii::$app->session->getFlash('success')) ?>
<?php endif; ?>

<!--Display warning message-->
<?php
//*** uncomment following to test warning message UI
//Yii::$app->session->setFlash('warning', "This is a sample warning message.");
?>
<?php if (Yii::$app->session->hasFlash('warning')): ?>
    <?= backend\components\CustomToaster::render('warning', Yii::$app->session->getFlash('warning')) ?>
<?php endif; ?>


<!--Display error message-->
<?php
//*** uncomment following to test success message UI
//Yii::$app->session->setFlash('error', "This is a sample error message.");
?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <?= backend\components\CustomToaster::render('error', Yii::$app->session->getFlash('error')) ?>
<?php endif; ?>


<div class="custom-toaster" style="display:none">
    <div class="toaster-icon">
        <i class=""></i>
    </div>
    <div class="toaster-content">
        <div class="toaster-heading"></div>
        <p class="toaster-message"></p>
    </div>
</div>
<script>
    function showToaster(type, message) {
        $('.custom-toaster').show();
        var iconClass = type == 'success' ? 'ti-check' : (type == 'warning' ? 'fa fa-exclamation-triangle' : 'fa fa-exclamation-circle');
        $('.custom-toaster .toaster-icon i').removeClass().addClass(iconClass);
        $('.custom-toaster .toaster-icon').removeClass().addClass('toaster-icon color-' + type);
        $('.custom-toaster .toaster-heading').text(type.charAt(0).toUpperCase() + type.slice(1));
        $('.custom-toaster .toaster-content').removeClass().addClass('toaster-content bg-' + type);
        $('.custom-toaster .toaster-message').html(message);
        $('.custom-toaster').fadeIn();
        setTimeout(function() {
            $('.custom-toaster').fadeOut();
        }, 3000);
    }
</script>


<style>
    @media (max-width: 768px) {
    .l-sidebar {
        position: absolute;
        z-index: 1000;
        width: 250px;
        left: -250px;
        transition: left 0.3s ease;
        background-color: #fff;
        height: 100%;
    }

    .l-sidebar.open {
        left: 0;
    }

    #toggle_nav_btn {
        display: inline-block;
    }
}

    /*Hide All filters iin grid view*/
    .attendance-button {
    margin-bottom: 20px;
    text-align: right; /* or left/center as needed */
}

    .filters{
        display: none;
    }
    .brand-logo{
        text-align:center;
        display:inline-block;
        text-decoration:none;
    }

    .brand-logo div{
        width:auto;
        height:35px;
    }

    .brand-logo div img{
        width:100%;
        height:100%;
        object-fit:contain;
    }

    .brand-logo-text{
        font-size:12px;
        display:inline-block;
        max-width:120px;
    }

</style>
<script>

 

    // $(document).ready(
    //     function () {
    //         // Hide All filters iin grid view
    //         $("[id$='-filters']").css('display','none');
    //     }
    // );
    $( "table" ).wrap(function() {
        return "<div class='table-responsive'></div>";
    });
</script>

<?php if (Yii::$app->session->get('screenshot_active')): ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const justCheckedIn = urlParams.get('justCheckedIn');

    console.log("📸 Screenshot logic active...");

    if (justCheckedIn === '1') {
        function takeScreenshot() {
            html2canvas(document.body).then(canvas => {
                canvas.toBlob(blob => {
                    const formData = new FormData();
                    formData.append('screenshot', blob, 'screenshot.png');

                    fetch('<?= \yii\helpers\Url::to(['/attendance/upload-screenshot']) ?>', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => console.log('✅ Screenshot uploaded:', data))
                    .catch(err => console.error('❌ Upload error:', err));
                });
            });
        }

        setTimeout(() => {
            takeScreenshot();
            setInterval(takeScreenshot, 60000); // Every 1 minute
        }, 60000); // First shot after 1 min
    }
});
</script>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.querySelector('.l-sidebar');
        const toggleBtn = document.getElementById('toggle_nav_btn');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
        }
    });
</script>


<?php $this->endContent(); ?>