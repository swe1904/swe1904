<?php

use yii\helpers\Html;
use app\components\GlobalConstant;
use backend\models\Client;

/** @var yii\web\View $this */
/** @var app\models\ClientEntity $model */

$this->title = Yii::t('backend', 'Create Client Entity');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Client Entities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$client = null;

?>
<div class="client-entity-create">
<?php if((!(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT))) {
    
        if(isset($_GET['client_id']) && !empty($_GET['client_id'])){
            $client = Client::findOne($_GET['client_id']);
            if(!$client)    
            {
                Yii::$app->session->setFlash('error', 'Client not found');
                return $this->redirect(['client/index']);
            }
        }
 
        $heading = Html::encode($this->title).' for client :'.$client->client_name;
    ?>
        <h2><?= $heading?></h2>
<?php }
else{
    ?>
    <h2><?= Html::encode($this->title)?></h2>
<?php }?>
    <?= $this->render('_form', [
        'model' => $model,
        'client' => $client,
    ]) ?>

</div>
