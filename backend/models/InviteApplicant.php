<?php



namespace backend\models;



use backend\modules\mii\components\MiiGlobalConstants;

use backend\modules\polling\models\base\PollingQuiz;

use backend\modules\polling\models\PollingQuizQuestion;

use Yii;

use yii\helpers\ArrayHelper;



/**

 * This is the model class for table "invite_applicant".

 *

 * @property integer $id

 * @property integer $polling_id

 * @property integer $client_id

 * @property integer $template_id

 * @property string $to_email

 * @property string $subject

 * @property string $created_at

 */

class InviteApplicant extends \yii\db\ActiveRecord

{

    /**

     * @inheritdoc

     */

    public static function tableName()

    {

        return 'tbl_invite_applicant';

    }



    /**

     * @inheritdoc

     */

    public function rules()

    {

        return [

            [['polling_id','client_id','template_id'], 'integer'],

            [['to_email'], 'string'],

            [['created_at'], 'safe'],

            [['polling_id','client_id','to_email','template_id'], 'required'],

            [['subject'], 'string', 'max' => 512],

          //  ['polling_id','requiredEmailQuestion','skipOnEmpty' => false, 'skipOnError' => false],

        ];

    }



    /**

     * @inheritdoc

     */

    public function attributeLabels()

    {

        return [

            'id' => Yii::t('backend', 'ID'),

            'polling_id' => Yii::t('backend', 'Questionnaire ID'),

            'to_email' => Yii::t('backend', 'To Email'),

            'subject' => Yii::t('backend', 'Subject'),

            'created_at' => Yii::t('backend', 'created At'),

        ];

    }

//    public function requiredEmailQuestion($attribute){

//        $poll=PollingQuiz::find()->where(['polling_id'=>$this->polling_id])->one();

//        $emailFound=PollingQuizQuestion::find()->where(['polling_quiz_id'=>$poll->id,'applicant_attribute'=>'email'])->count();

//        if(!$emailFound){

//            $this->addError($attribute,'Please select a Questionnaire with Email.');

//        }

//

//    }



   //return only poll contain fixed Attributes

    public function getPolling_ids(){

        $polls=  \backend\modules\polling\models\PollingQuiz::find()->all();

        $array=\yii\helpers\ArrayHelper::map($polls, 'polling_id', 'name');

    //    foreach ($polls as $poll){

    //        $finalFixedValue=[];

    //             $applicantData= \backend\modules\mii\jsonData\Applicant::returnData();

    //        foreach ($applicantData as $applicantDatum) {

    //            $finalArray[$applicantDatum['name']] = $applicantDatum['name'];

    //        }

    //        foreach (MiiGlobalConstants::returnApplicantFixedFields() as $fixedField){

    //            array_push($finalFixedValue,$finalArray[$fixedField]);

    //        }



    //        $questionAttributes=\yii\helpers\ArrayHelper::getColumn($poll->getPollingQuizQuestions()->all(),'applicant_attribute');

    //        if (array_intersect($finalFixedValue, $questionAttributes) != $finalFixedValue) {

    //            ArrayHelper::remove($array,$poll->polling_id);

    //           // echo 'array 2 is  not an exact subset of array 1';

    //        }

    //     }

       return $array;

    }



}

