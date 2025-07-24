<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class EmergencyContactRelationship extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_emergency_contact_relationship'; // Table name
    }

    public static function getRelationshipList()
    {
        return self::find()->select('relationship_name')->indexBy('id')->column();
    }
}
