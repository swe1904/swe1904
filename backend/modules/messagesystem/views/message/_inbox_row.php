<?php
use yii\helpers\Url;
$messageInboxModel=new \backend\modules\messageSystem\models\MessageInbox();
$model=$messageInboxModel->toModel($model);

// check unread status
$class="";
$showThread=true;
foreach ($model->messageReadStatuses as $messageReadStatus){
    if($messageReadStatus->receiver_id==Yii::$app->user->id){
        if($messageReadStatus->status==\backend\modules\messageSystem\models\MessageInbox::UNREAD){
            $class="msg_unread";
        }
        if($messageReadStatus->delete==\backend\modules\messagesystem\models\MessageInbox::DELETE){
            $showThread=false;
        }
        break;
    }
}

if(!$showThread)
    return false;
?>
<?php /*
<a href="<?php echo Url::to(['message/inbox/'.$model->thread_id])?>" class="list-group-item list-group-item-action flex-column align-items-start <?=$class?>">
    <div class="row margin_unset inbox_msg_row">
        <div class="col-md-1 pad_unset">
            <div class="row margin_unset">
                <div class="col-xs-12 col-md-12 col-sm-12 msg_img_cont pad_unset">
                    <img class=""  width="40" hieght="40" src="<?=$model->sender->userProfile->getAvatar()?>">
                </div>
                <div class="col-xs-12 col-md-12 col-sm-12 msg_img_cont pad_unset">
                    <input class="one-msg-check" type="checkbox" name="action-msg" value="<?=$model->id?>" data-value="<?= $model->thread_id ?>">
                </div>
            </div>


        </div>
        <div class="col-md-11">
            <span class="name"><?=$model->modelOwner()?"me":$model->sender->userProfile->getFullName()?></span>
            <p class="subject"><?=$model->message?></p>
            <p class="message"><?=$model->message?></p>
            <div class="msg_time_cont">
                <?=Yii::$app->formatter->format($model->created_at, 'dateTime'); ?>
            </div>
        </div>
    </div>
</a>
*/
?>
<tr class="unread" onclick="showMessages($(this).data('key'))" data-key="<?=$model->thread_id;?>">
<!--    <td class="inbox-small-cells">-->
<!--        <div class="checkbox checkbox-default inline-block">-->
<!--            <input type="checkbox" id="checkbox012"/>-->
<!--            <input class="one-msg-check" type="checkbox" name="action-msg" value="--><?php //echo $model->id?><!--" data-value="--><?php //echo $model->thread_id ?><!--">-->
<!--            <label for="checkbox012"></label>-->
<!--        </div>-->
<!--        <i class="zmdi zmdi-star inline-block font-16"></i>-->
<!--    </td>-->
    <td class="view-message dont-show"><a href="#"><?=$model->modelOwner()?"me":$model->sender->userProfile->getFullName()?></a>
<!--        <span class="label label-warning pull-right">new</span>-->
    </td>
    <td class="view-message"><?=$model->message?></a></td>
    <td class="view-message text-right">
<!--        <a target="_blank" href="" ><i class="zmdi zmdi-attachment inline-block mr-15 font-16"></i></a>-->
        <span class="time-chat-history inline-block"><?=Yii::$app->formatter->format($model->created_at, 'dateTime'); ?></span>
    </td>
</tr>
<script>
    function showMessages(threadId) {
        var url = 'http://pangeaportal.com/backend/web/messageSystem/message/inbox/'+threadId;
        console.log(url);
        location.replace(url);
    }
</script>