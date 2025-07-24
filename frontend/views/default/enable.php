<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 16-01-2020
 * Time: 15:58
 */
use \yii\widgets;

widgets\Pjax::begin(['id'=>'two_factor_authentication']); ?>
<div>
    <br>
    <p>Two factor authentication gives us an extra layer of security.</p>
    <br>
    <?php $form =  widgets\ActiveForm::begin(['id'=>'enable_two_factor_authentication']);?>
    <div class="row" style="margin-top: 15px;">
        <div class="col-md-1" style="margin-top: 3px;">
            <?= $form->field($model, 'enable_two_factor_auth')->widget(\backend\widgets\prototoggle\ProtoToggle::className(),
                [
                    'options'=>[
                        'label'=>'proto-onoffswitch-label',
                        'style'=>'margin-top: -28px',
                        'class'=>'preferences-enable_two_factor_auth proto-onoffswitch-checkbox'
                    ],
                ]
            )->label(false);?>
        </div>
        <div class="col-md-5" style="margin-left: -30px;margin-top: 10px;">
            <?= Yii::t('backend','Enable Two Factor Authorization (2FA)'); ?>
        </div>
    </div>

    <div class="row factor-auth" style="<?php if(empty($model->enable_two_factor_auth)){ echo "display: none;"; }?>">
<!--        <div class="col-md-12">-->
<!--            <p>To enable two factor auth, please scan the below QR code with <a href="https://support.google.com/accounts/answer/1066447?co=GENIE.Platform%3DAndroid&hl=en">Google Authenticator App</a>. </p>-->
<!--            <div class="qr">-->
<!--                <img src="--><?//= $qrCodeUrl; ?><!--" class="qr-code" title="Scan QR code"/>-->
<!--                <h5 class="qr-secret">If you are enable to scan QR code, then enter this secret in Authenticator App: <strong>--><?//= $secret; ?><!--</strong></h5>-->
<!--                <h5 class="one-code">Please make sure if Authenticator App show this code after scan: <strong>--><?//= $oneTimeCode; ?><!--</strong>, refresh page to check again.</h5>-->
<!--            </div>-->
<!--            <a href="#" class="btn btn-primary" onclick="refreshQr()"> Get new QR code </a>-->
<!--        </div>-->
        <div style="margin-left: 15px"> <p>Now, at the time of login, you will receive an email with <span style="color: red">Security Pin</span> for login.</p></div>
    </div>

    <?php widgets\ActiveForm::end();?>


<script>
    $(function () {
        $('#enable_two_factor_auth').change(function () {
//            console.log("checked");
            var check = 0;
            $('.factor-auth').hide();
            if (this.checked){
                check = 1;
//                console.log(check);
                $('.factor-auth').show();
            }
            $.ajax({
                "type":"POST",
                "url": "<?php echo Yii::$app->urlManager->createUrl(['/auth/default/save-auth'])?>",
                'data': {'enable':check, _csrf: "<?= Yii::$app->request->csrfToken; ?>"},
                success:function (data) {
                    console.log('Null');
                }
            })
        })
    })

//    function refreshQr() {
//        $.ajax({
//            'type':'POST',
//            'url' : '<?//= Yii::$app->urlManager->createUrl(['/auth/default/refresh-qr'])?>//',
//            'data': {'refresh':true, _csrf: "<?//= Yii::$app->request->csrfToken; ?>//"},
//            success:function (data) {
//                $.pjax.reload({container: '#two_factor_authentication', timeout: 2000});
//            }
//        })
//    }
</script>
</div>
<?php widgets\Pjax::end();?>
