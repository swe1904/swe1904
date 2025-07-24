<?php
use yii\widgets\ListView;
/* @var \backend\modules\messagesystem\models\MessageInbox $model */
?>
<div class="panel panel-default flex_outer detail_cont">

    <div class="panel-body message flex_outer">

        <div class="row margin_unset">
             <!--<span class="btn-group">
							  	<button class="btn btn-default"><span class="fa fa-star"></span></button>
							  	<button class="btn btn-default"><span class="fa fa-star-o"></span></button>
								<button class="btn btn-default"><span class="fa fa-bookmark-o"></span></button>
							</span>

            <span class="btn-group">
							  	<button class="btn btn-default"><span class="fa fa-mail-reply"></span></button>
							  	<button class="btn btn-default"><span class="fa fa-mail-reply-all"></span></button>
							  	<button class="btn btn-default"><span class="fa fa-mail-forward"></span></button>
							</span>

            <button class="btn btn-default"><span class="fa fa-trash-o"></span></button>

            <span class="btn-group">
								<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="fa fa-tags"></span> <span class="caret"></span></button>
								<ul class="dropdown-menu">
									<li><a href="page-inbox-message.html#">add label <span class="label label-danger"> Home</span></a></li>
									<li><a href="page-inbox-message.html#">add label <span class="label label-info">Job</span></a></li>
									<li><a href="page-inbox-message.html#">add label <span class="label label-success">Clients</span></a></li>
									<li><a href="page-inbox-message.html#">add label <span class="label label-warning">News</span></a></li>
								</ul>
							</span>-->

            <div class="row margin_unset _receivers_data">
                <?= $this->render('_receiver_detail',['model'=>$model]) ?>
            </div>
            <div class="message-title">
                <h5>Subject: <?=$model->subject?></h5>
            </div>
        </div>

        <div class="row margin_unset all_messages inbox">
            <?php
            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => 'inbox_detail_row',
                'viewParams' => [
                    'fullView' => true,
                    'context' => 'main-page',
                    // ...
                ],
                'pager' => [
                    'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
                    'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
                    'maxButtonCount' => 0,
                ],
            ]);
            ?>
        </div>
        <div class="row margin_unset flex_in">
            <div class="col-md-10 col-md-offset-1">
               <!-- <form method="post" action="">

                    <div class="form-group">

                        <textarea class="form-control" id="message" name="body" rows="3" placeholder="Click here to reply"></textarea>

                    </div>

                    <div class="form-group">

                        <button tabindex="3" type="submit" class="btn btn-success">Send message</button>

                    </div>

                </form>-->
                <?= $this->render('_send_message_form',['model'=>$model]) ?>
            </div>
        </div>





    </div>

</div>
<script>
    jQuery("time.timeago").timeago();
</script>