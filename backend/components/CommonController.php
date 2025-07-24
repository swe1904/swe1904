<?php
/**
 * Created by PhpStorm.
 * User: rahulsinghmatharu
 * Date: 12/02/15
 * Time: 1:07 PM
 */

namespace app\components;

use Yii;
use yii\web\Controller;

class CommonController extends Controller {
    public $adminMenuOptions = [];
    /**
     * @inheritdoc
     */
    public function init()
    {
        // check for admin permission (`tbl_role.can_admin`)
        // note: check for Yii::$app->user first because it doesn't exist in console commands (throws exception)

        if (!empty(Yii::$app->user) && Yii::$app->user->can("admin")) {
            $this->adminMenuOptions = [
                ['label' => 'Trips', 'url' => ['/trip/admin'], 'options' =>[/*'class' =>'active'*/]],
                ['label' => 'Settings', 'url' => ['/setting/index']],
                ['label' => 'Upload', 'url' => ['/trip/upload']],
                ['label' => 'Promocodes', 'url' => ['/promocode/admin']],
                ['label' => 'Bookings', 'url' => ['/booking/admin']],
                ['label' => 'Ports', 'url' => ['/port/index']],
                ['label' => 'Paypal Orders', 'url' => ['/paypal-order/index']],
                ['label' => 'Users', 'url' => ['/user/admin']],
            ];
        }

        parent::init();
    }
} 