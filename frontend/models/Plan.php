<?php

namespace frontend\models;

use Yii;

class Plan extends \common\models\Plan
{
    const FREE_PLAN = 1;
    const MONTHLY_PLAN = 2;
    const YEARLY_PLAN = 3;
    const ACTIVE_PLAN = 1;
    const INACTIVE_PLAN = 0;
    const FREE_RECEIPT_LIMIT = 10;

}
