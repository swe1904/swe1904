<?php

namespace frontend\modules\messagesystem\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "message_system".
 *
 * @property integer $id
 * @property string $private_id
 * @property integer $sender_id
 * @property integer $receiver_id
 * @property string $message
 *
 * @property User $sender
 * @property User $receiver
 */
class MessageSystem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message_system';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['private_id', 'message'], 'string'],
            [['sender_id', 'receiver_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'private_id' => 'Private ID',
            'sender_id' => 'Sender ID',
            'receiver_id' => 'Receiver ID',
            'message' => 'Message',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'receiver_id']);
    }
}
