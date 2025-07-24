<?php

use yii\helpers\Html;
use app\components\GlobalConstant;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $roles yii\rbac\Role[] */
if (Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN) && isset($_GET['role']) && $_GET['role'] == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER){
    $this->title =  Yii::t('backend', 'Update {modelClass}: ', ['modelClass' => 'Client-Group-Manager User']) . ' ' . $model->username;
}
elseif(Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN))
    $this->title =  Yii::t('backend', 'Update {modelClass}: ', ['modelClass' => 'Organisation-admin User']) . ' ' . $model->username;
else
    $this->title = Yii::t('backend', 'Update {modelClass}: ', ['modelClass' => 'User']) . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->email, 'url' => ['view', 'id' => $model->email]];
$this->params['breadcrumbs'][] = ['label'=>Yii::t('backend', 'Update')];
?>
<div class="user-update">

    <?php echo $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
        // 'clients'=>$clients
        'connectClients'=>$connectClients,
        'allClients'=>$allClients,
        'clientEntityArr' =>$clientEntityArr
    ]) ?>

</div>
