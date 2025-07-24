<?php

namespace backend\modules\polling\models;


//use backend\modules\handyrecruiter\models\User;
use common\models\User;
use Yii;


/**
 * This is the model class for table "email_template".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $status_id
 * @property string $name
 * @property string $from_email
 * @property string $to_email
 * @property string $to_name
 * @property string $subject
 * @property string $body
 * @property integer $sent_after_day
 * @property string $attachment
 *

 * @property User $user
 */
class EmailTemplate extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_email_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'subject', 'body'], 'required'],
            [['body'], 'string'],
            [['user_id'], 'integer'],
            [['name','from_email', 'to_email', 'to_name', 'attachment'], 'string', 'max' => 100],
            [['subject'], 'string', 'max' => 512],
            //[['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status_id' => Yii::t('app', 'Change Status To'),
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Name'),
            'to_email' => Yii::t('app', 'To Email'),
            'to_name' => Yii::t('app', 'To Name'),
            'subject' => Yii::t('app', 'Subject'),
            'body' => Yii::t('app', 'Body'),
            'attachment' => Yii::t('app', 'Attachment'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }*/

    /**
     * @return \yii\db\ActiveQuery
     */
 /*   public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }*/

    public static function replaceString($search, $replace, $data)
    {
        return str_replace($search, $replace, $data);
    }
}
