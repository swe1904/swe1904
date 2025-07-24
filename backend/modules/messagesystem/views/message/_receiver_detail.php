<?php
foreach ($model->messageReadStatuses as $messageReadStatus){
    if($messageReadStatus->receiver_id!=Yii::$app->user->id){
        ?>
        <div class="_float_l _receiver">
            <div class="main_cont">
                <div >
                    <img class="msg_inbox_img" src="<?=$messageReadStatus->receiver->userProfile->getAvatar()?>">
                </div>
                <div class="_email">
                    <span>
                        <?= $messageReadStatus->receiver->email ?>
                    </span>
                </div>
            </div>

        </div>
<?php
    }
}
?>
