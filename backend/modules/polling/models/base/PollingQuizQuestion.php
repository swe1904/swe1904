<?php

namespace backend\modules\polling\models\base;

use Yii;

/**
 * This is the model class for table "tbl_polling_quiz_question".
 *
 * @property integer $id
 * @property integer $polling_quiz_id
 * @property integer $polling_quiz_question_direct_id
 * @property string $title
 * @property string $question
 * @property string $answer
 * @property integer $type
 * @property integer $order
 * @property integer $action
 * @property integer $action_compare
 * @property integer $action_compare_radio
 * @property integer $action_compare_text
 * @property string $action_value
 * @property integer $visible
 * @property integer $visible_quiz_question_id
 * @property integer $visible_compare
 * @property string $visible_value
 * @property string $applicant_attribute
 * @property integer $team_based
 * @property integer $is_correct
 * @property PollingQuiz $pollingQuiz
 * @property PollingQuizQuestionType $type0
 * @property PollingQuizQuestionAnswer[] $pollingQuizQuestionAnswers
 * @property PollingQuizQuestionCorrectAnswer[] $pollingQuizQuestionCorrectAnswers
 * @property PollingQuizQuestionOption[] $pollingQuizQuestionOptions
 * @property PollingQuizQuestionAnswer $applicantAnswerModel
 */
class PollingQuizQuestion extends \yii\db\ActiveRecord
{

    public $applicantAnswerModel;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_polling_quiz_question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['polling_quiz_id', 'question', 'type','applicant_attribute'], 'required'],
            [['polling_quiz_id', 'polling_quiz_question_direct_id' , 'type', 'order', 'action', 'action_compare', 'action_compare_radio', 'action_compare_text', 'visible', 'visible_quiz_question_id', 'visible_compare','team_based','is_correct'], 'integer'],
            [['question'], 'string'],
            [['title','required_error_message'], 'string', 'max' => 255],
            [['answer', 'action_value', 'visible_value','applicant_attribute'], 'string', 'max' => 50],
            ['applicant_attribute','applicantEmailValidation' ,'skipOnEmpty' => false, 'skipOnError' => false]

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'polling_quiz_id' => 'Questionnaire ID',
            'polling_quiz_question_direct_id'=>'Question ID',
            'title' => 'Title',
            'question' => 'Question',
            'answer' => 'Answer',
            'type' => 'Type',
            'order' => 'Order',
            'action' => 'Action',
            'action_compare' => 'Action Compare',
            'action_compare_radio' => 'Action Compare Radio',
            'action_compare_text' => 'Action Compare Text',
            'action_value' => 'Action Value',
            'visible' => 'Visible',
            'visible_quiz_question_id' => 'Visible Quiz Question ID',
            'visible_compare' => 'Visible Compare',
            'visible_value' => 'Visible Value',
            'team_based' => 'Team Based',
            'show_question_url_result'=>'Show result',
            'is_correct' => 'Is there any correct answer',
            'required_error_message' => 'Error Message',
            'applicant_attribute' => 'Applicant Attribute',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPollingQuiz()
    {
        return $this->hasOne(PollingQuiz::className(), ['id' => 'polling_quiz_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPollingQuizQuestionType()
    {
        return $this->hasOne(PollingQuizQuestionType::className(), ['id' => 'type']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPollingQuizQuestionCorrectAnswer()
    {
        return $this->hasOne(PollingQuizQuestionCorrectAnswer::className(), ['polling_quiz_question_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPollingQuizQuestionAnswers()
    {
        return $this->hasMany(PollingQuizQuestionAnswer::className(), ['polling_quiz_question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPollingQuizQuestionOptions()
    {
        return $this->hasMany(PollingQuizQuestionOption::className(), ['polling_quiz_question_id' => 'id']);
    }


}
