<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $categories common\models\ArticleCategory[] */
/* @var $form yii\bootstrap\ActiveForm */
?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl . '/js/jquery.min.js', array('position' => $this::POS_HEAD), 'jquery'); ?>

<script src="<?php echo Yii::$app->request->baseUrl ?>/js/jquery.countdown.js"></script>
<script src="<?php echo Yii::$app->request->baseUrl ?>/js/jquery.countdown.min.js"></script>
<!--<link rel="stylesheet" href="--><?php //echo Yii::$app->request->baseUrl ?><!--/redactor/redactor.css" />-->
<script src="<?php echo Yii::$app->request->baseUrl ?>/assets/d9523f22/redactor.min.js"></script>
<div class="article-form">

    <?php $form = ActiveForm::begin();
    if (isset($id) && $id != '') {

    } else {
        $id = $model->article_request_id;
    }

    $article_data = \common\models\ArticleRequest::findOne($id);
    $author_data = \common\models\User::findOne($article_data->author_id);
    $claim_at = $article_data->claim_at;
    $time = $article_data->time;
    $unclaimed_date = date('Y-m-d H:i:s', strtotime("$claim_at + $time hours"));
    ?>
    <table class="table table-striped detail-view">
        <tbody>
        <tr>
            <th width="200px;">Title</th>
            <td><?php echo $article_data->title; ?>
            </td>
            <th>USD</th>
            <td><?php echo $article_data->client_usd; ?></td>
            <th>Status</th>
            <td><?php echo $article_data->status; ?></td>
        </tr>
        <tr>
            <th>Article In Words</th>
            <td><?php echo $article_data->no_of_line; ?></td>
            <th>Author</th>
            <td><?php echo $author_data->username; ?></td>
        </tr>
        <tr>
            <th>Instructions</th>
            <td colspan="5"><?php echo $article_data->instructions; ?></td>
        </tr>
        <tr>
            <th>Description</th>
            <td colspan="5"><?php echo $article_data->description; ?></td>
        </tr>
        </tbody>
    </table>
    <div style="float: right; ">
        <div id="getting-started" style="font-size: 20px;"></div>
        <?php
        $url = Yii::$app->urlManager->createUrl(['article-request/change-status-claim', 'id' => $id]);
        echo Html::a('Unclaim Article', $url, ['title' => 'Unclaimed Article', 'class' => ' btn btn-primary btn-sm change-status ']);
        ?>
    </div>
    <div style="clear: both;"></div>
    <?php

    /*
     * update article data
     */
    $tmp_data = \common\models\ArticleTmp::find()->where(['article_request_id' => $id])->one();
    //print_r($tmp_data);
    if (count($tmp_data) > 0) {
        $model->body = $tmp_data->body;
    }

    ?>
    <?php echo $form->field($model, 'body')->widget(
        \yii\imperavi\Widget::className(),
        [
            'id' => Html::getInputId($model, 'body'),
            'model' => $model,
            'attribute' => 'body',

            'plugins' => ['fullscreen', 'fontcolor', 'video'],
            'options' => [
                'minHeight' => 400,
                'maxHeight' => 400,
                'buttonSource' => true,
                'convertDivs' => false,
                'removeEmptyTags' => false,
                'imageUpload' => Yii::$app->urlManager->createUrl(['/file-storage/upload-imperavi'])
            ]
        ]
    ) ?>


    <div class="form-group">
        <?php echo Html::submitButton(
            $model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<!-- common modal -->
<div class="modal fade" id="common-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="common-modal-content">

            <div id="common-modal-body">
                <div class="modal-body">
                </div>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- common modal -->
<script>
    <?php $this->registerJs("    $(document).ready(function(){
         $('.change-status').on('click',function(e){
             e.preventDefault();
             $.ajax({
                 url:$(this).attr('href'),
                 data: {id:$(this).attr('id')},
                 success:function(data){
                     $('#common-modal-body').html(data);
                     $('#common-modal').modal('show');
                 }
             })
         })
     }); ", View::POS_READY); ?>
</script>

<script type="text/javascript">

    $('#getting-started').countdown('<?php echo $unclaimed_date; ?>', function (event) {
        $(this).html(event.strftime('%H:%M:%S'));
    }).on('finish.countdown', finishedTime);

    function finishedTime() {
        $.ajax({
            url: '<?php echo Yii::$app->request->baseUrl. '/article-request/status-save-claim-ajax' ?>',
            type: 'GET',
            data: {id:<?php echo $id; ?>},
            success: function (data) {
                if (data == 'success') {
                    window.location = "<?php echo Yii::$app->request->baseUrl. '/article-request/available?success=1' ?>";
                }
                else {
                    alert('Something error.. ');
                }
                console.log(data.search);
            }
        });
    }


</script>
<script>

    //    window.onbeforeunload = function(){
    //        var body=$('#w1').val();
    //           $.ajax({
    //            url: '<?php //echo Yii::$app->request->baseUrl. '/article/article-data-save' ?>//',
    //            type: 'POST',
    //            data: {id:<?php //echo $id; ?>//,body:body},
    //            success: function (data) {
    //         // return;
    //            }
    //        });
    //
    //        return 'Are you sure you want to leave?';
    //
    //    };

//
//    $(".redactor-editor").on("keyup", function() {
//        var v = this.id;
//        alert('dsdfs');
//    });


</script>

