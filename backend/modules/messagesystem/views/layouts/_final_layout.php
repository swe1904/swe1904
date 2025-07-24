<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/* @var $this \yii\web\View */
/* @var $content string */
\backend\modules\messagesystem\MessageAsset::register($this);
$this->beginContent('@backend/views/layouts/main_pangea_final.php')
?>
<?php echo $content; ?>


<?php $this->endContent() ?>
<!--end modals-->
<!-- Modal compose message -->
<div class="modal fade" id="compose_message" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Compose message</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!-- created By Nemanja -->
<div class="modal fade case_step" id="compose_message_case_step" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Compose message</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<!-- ended By Nemanja -->

<!--end modals-->
<script>
    // contact user
    function composeMessage(){
        $.ajax({
            type: 'POST',
            url: '<?php echo \yii\helpers\Url::to(['/messageSystem/message/compose-message']); ?>',
            success: function(data) {
                $("#compose_message").find(".modal-body").html(data.html);
                $("#compose_message").modal('show');

            },

        });
        return false;
    }
    
    // created By Nemanja 2021-01-12
    function composeMessageForCasestep(caseID){
        $.ajax({
            type: 'POST',
            url: '<?php echo \yii\helpers\Url::to(['/messageSystem/message/compose-message-casestep']); ?>',
            data: { caseID: caseID },
            success: function(data) {
                $("#compose_message_case_step").find(".modal-body").html(data.html);
                $("#compose_message_case_step").modal('show');

            },

        });
        return false;
    }
    // ended
    
    function removeUploadedFile(name,session_id){
        $.ajax({
            type: 'POST',
            url: '<?php echo \yii\helpers\Url::to(['/messageSystem/attachment/delete-temp-file']); ?>',
            data: {name:name,session_id:session_id},
            dataType:'html',

            success: function(data) {
                console.log(data);
            },

        });
    }
</script>
