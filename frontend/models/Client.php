<?php

namespace frontend\models;

use Yii;
use backend\models\clientFixed\ClientFixed;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $first_name_fixed
 * @property string $last_name_fixed
 * @property string $phone_fixed
 * @property string $address_fixed
 * @property string $text_1528873639782
 * @property string $file_1528880611250
 * @property string $file_1528887587904
 */
class Client extends ClientParent
{

   
    public static function select_1528809495736()
    {
    return array (
  'Option 1' => 'Option 1',
  'Option 2' => 'Option 2',
  'Option 3' => 'Option 3',
);
    }

    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['first_name_fixed', 'last_name_fixed', 'phone_fixed', 'address_fixed'], 'required'],
            [['address_fixed'], 'string'],
            [['first_name_fixed', 'last_name_fixed', 'phone_fixed', 'text_1528873639782', 'file_1528880611250', 'file_1528887587904'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'user_id' => '',
            'first_name_fixed' => 'First Name',
            'last_name_fixed' => 'Last Name',
            'phone_fixed' => 'Phone',
            'address_fixed' => 'Address',
            'text_1528873639782' => '',
            'file_1528880611250' => '',
            'file_1528887587904' => '',
        ];
    }
}
