<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client_multi_select".
 *
 * @property integer $id
 * @property string $select_id
 * @property string $name
 */
class ClientMultiSelect extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client_multi_select';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['select_id'], 'required'],
            [['select_id'], 'string'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'select_id' => 'Select ID',
            'name' => 'Name',
        ];
    }
}
