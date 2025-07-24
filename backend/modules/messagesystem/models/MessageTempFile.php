<?php

namespace backend\modules\messagesystem\models;

use Yii;

/**
 * This is the model class for table "tbl_message_temp_file".
 *
 * @property integer $id
 * @property string $session_id
 * @property string $attachment
 * @property string $name
 * @property string $extension
 * @property string $created_at
 * @property string $updated_at
 */
class MessageTempFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_message_temp_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attachment'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['session_id'], 'string', 'max' => 50],
            [['name', 'extension'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'session_id' => 'Session ID',
            'attachment' => 'Attachment',
            'name' => 'Name',
            'extension' => 'Extension',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
