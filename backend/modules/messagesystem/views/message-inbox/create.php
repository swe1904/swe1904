<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\modules\messagesystem\models\MessageInbox */

$this->title = 'Create Message Inbox';
$this->params['breadcrumbs'][] = ['label' => 'Message Inboxes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-inbox-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
