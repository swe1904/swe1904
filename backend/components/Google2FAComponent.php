<?php

namespace backend\components;

use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class Google2FAComponent extends \yii\base\Component
{
    private $google2fa;

    public function __construct($config = [])
    {
        $this->google2fa = new Google2FA();
        parent::__construct($config);
    }

    public function generateSecretKey()
    {
        return $this->google2fa->generateSecretKey();
    }

    public function getQRCodeUrl($companyName, $companyEmail, $secretKey)
    {
        return $this->google2fa->getQRCodeUrl(
            $companyName,
            $companyEmail,
            $secretKey
        );
    }

    public function generateQRCode($url)
    {
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        return $writer->writeString($url);
    }

    public function verifyKey($secret, $key)
    {
        return $this->google2fa->verifyKey($secret, $key);
    }
}
