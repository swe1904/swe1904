<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EnableTwoFactorForm */
/* @var $qrCodeUrl string */

$this->title = 'Enable Two-Factor Authentication';
?>
<div class="site-enable-two-factor">

    <p>Scan the QR code with your Google Authenticator app:</p>
    <?php echo $qrCodeUrl;
 ?>
    <img src="<?= $qrCodeUrl ?>" alt="QR Code">
    <img class="google_qrcode" src="data:image/gif;base64,<?= $qrCodeUrl ?>" alt="QR Code" />
    <p>Enter the verification code from the app:</p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Verify', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
