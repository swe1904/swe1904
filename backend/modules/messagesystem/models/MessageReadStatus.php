<?php

namespace backend\modules\messageSystem\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "tbl_message_read_status".
 *
 * @property integer $id
 * @property string $thread_id
 * @property integer $receiver_id
 * @property integer $status
 * @property integer $delete
 * @property User $receiver
 */
class MessageReadStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_message_read_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['receiver_id', 'status', 'delete'], 'integer'],
            [['thread_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thread_id' => 'Thread ID',
            'receiver_id' => 'Receiver ID',
            'status' => 'Status',
            'delete' => 'Delete',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'receiver_id']);
    }
}
