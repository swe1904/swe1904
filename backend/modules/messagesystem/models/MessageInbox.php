<?php

namespace backend\modules\messagesystem\models;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_message_inbox".
 *
 * @property integer $id
 * @property string $thread_id
 * @property integer $sender_id
 * @property integer $receiver_id
 * @property string $receiver_string
 * @property string $message
 * @property string $subject
 * @property string $created_at
 * @property string $updated_at
 * @property integer $session_id
 * @property User $sender
 * @property User $receiver
 * @property MessageReadStatus[] $messageReadStatuses
 * @property MessageFileUpload[] $messageFileUploads
 * @property MessageReadStatus $messageReadStatusReceiver
 */
class MessageInbox extends \yii\db\ActiveRecord
{
    const READ=1;
    const UNREAD=0;
    const DELETE=1;
    public $receiver_string;
    public $session_id;
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    public $user_name,$user_email;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_message_inbox';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['thread_id', 'message','subject','receiver_string','session_id'], 'string'],
            [['sender_id', 'receiver_id'], 'integer'],
            [['message', 'subject', 'receiver_id'], 'required','on' => 'compose'],
            [['created_at','updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thread_id' => 'Private ID',
            'sender_id' => 'Sender ID',
            'receiver_id' => 'To',
            'message' => 'Message',
            'created_at' => 'Created At',
            'user_email' => 'To',
            'subject' => 'Subject',
            'receiver_string' => 'To',
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
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessageReadStatuses()
    {
        return $this->hasMany(MessageReadStatus::className(), ['thread_id' => 'thread_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessageFileUploads()
    {
        return $this->hasMany(MessageFileUpload::className(), ['message_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessageReadStatusReceiver()
    {
        return $this->hasOne(MessageReadStatus::className(), ['thread_id' => 'thread_id','receiver_id'=>'receiver_id']);
    }
    public function getUserName(){
        return $this->receiver->username;
    }
    public function getUserEmail(){
        return $this->receiver->email;
    }
    public  function toModel(array $rows=[]){

        foreach ($rows as $attribute=>$value){
            $this->$attribute=$value;
        }
        return $this;
    }
    public function returnThreadId(){
//       return self::getThreadId($this->sender_id,$this->receiver_id,$this->room_listing_id);
        $milliseconds = round(microtime(true) * 1000);
        $thread_id="_".$milliseconds."_";
        return $thread_id;
    }
    public static function getThreadId($senderId,$receiverId,$roomListingId){
        $idArray=[$senderId,$receiverId];
        sort($idArray);
        $privateId=implode($idArray,'_');
        return $privateId."_".$roomListingId;
    }
    public function modelOwner(){
        if($this->sender_id==Yii::$app->user->id){
            return true;
        }
        return false;
    }
    public function afterSave($insert, $changedAttributes)
    {

        // fetch attachments
        $messageTempFileModels=MessageTempFile::find()->where('session_id=:session_id',[':session_id'=>$this->session_id])->all();
        if(!empty($messageTempFileModels)){
            foreach ($messageTempFileModels as $tempFileModel){
                // create file uploads
                $fileUploadModel=new MessageFileUpload();
                $fileUploadModel->message_id=$this->id;
                $fileUploadModel->attachment=$tempFileModel->attachment;
                $fileUploadModel->name=$tempFileModel->name;
                $fileUploadModel->extension=$tempFileModel->extension;
                if($fileUploadModel->save()){
                    $tempFileModel->delete();
                }
            }
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

}
