<?php

namespace frontend\modules\messagesystem\models;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "message_outbox".
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
class MessageOutbox extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message_outbox';
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
