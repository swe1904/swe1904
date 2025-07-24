<?php

namespace backend\modules\polling\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "{{%polling_quiz}}".
 *
 * @property integer $id
 * @property string $polling_id
 * @property string $user_id
 * @property integer $show_result
 * @property string $name
 * @property string $description
 * @property integer $type
 * @property string $uuid
 * @property integer $quiz_reminder_is
 * @property integer $is_deleted
 * @property integer $master
 * @property integer $disable_edit
 * @property string $created_at
 *
 * @property PollingQuizQuestion[] $pollingQuizQuestions
 */
class PollingQuiz extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
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
    public static function tableName()
    {
        return '{{%polling_quiz}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name'], 'required'],
            [['polling_id','user_id', 'type','show_result', 'quiz_reminder_is', 'is_deleted', 'master', 'disable_edit','show_btn_on_result_page','lengthoftime'], 'integer'],
            [['description','redirect_link'], 'string'],
            /*['polling_id', 'unique'],*/
            [['created_at'], 'safe'],
            [['name', 'uuid'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'polling_id' => 'Questionnaire ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'description' => 'Description',
            'show_result' => 'Show Result',
            'type' => 'Type',
            'uuid' => 'Uuid',
            'quiz_reminder_is' => 'Quiz Reminder Is',
            'is_deleted' => 'Is Deleted',
            'master' => 'Master',
            'disable_edit' => 'Disable Edit',
            'created_at' => 'Date Created',
            'polling_quiz_play_url'=>'Questionnaire url',
            'show_question_url_result'=>'Show result',
            'show_btn_on_result_page'=>'Show button on result page',
            'redirect_link'=>'Redirect Link',
            'lengthoftime'=>'Length of Time(In Days)',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPollingQuizQuestions()
    {
        return $this->hasMany(PollingQuizQuestion::className(), ['polling_quiz_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPollingQuizTeams()
    {
        return $this->hasMany(PollingQuizTeam::className(), ['polling_quiz_id' => 'id']);
    }
}
