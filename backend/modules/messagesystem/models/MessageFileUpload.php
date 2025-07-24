<?php

namespace backend\modules\messagesystem\models;

use Yii;

/**
 * This is the model class for table "tbl_message_file_upload".
 *
 * @property integer $id
 * @property integer $message_id
 * @property string $attachment
 * @property string $name
 * @property string $extension
 *
 * @property MessageInbox $message
 */
class MessageFileUpload extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_message_file_upload';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message_id'], 'integer'],
            [['attachment'], 'string'],
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
            'message_id' => 'Message ID',
            'attachment' => 'Attachment',
            'name' => 'Name',
            'extension' => 'Extension',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessage()
    {
        return $this->hasOne(MessageInbox::className(), ['id' => 'message_id']);
    }
}
