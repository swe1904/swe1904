<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;    //Nemanja
use yii\db\Expression;    //Nemanja
use common\models\User;
use backend\components\CustomTimestampBehavior;
use backend\components\Helper;
use backend\models\Organisation;
use backend\models\Client;
use backend\models\ClientEntity;
use backend\models\Applicant;        //on dev


/**
 * This is the model class for table "tbl_cases".
 *
 * @property integer $id
 * @property string $case_number
 * @property integer $CASE_TYPE_ID
 * @property integer $applicant_id
 * @property string $target_completion_date
 * @property string $sending_country
 * @property string $receiving_country
 * @property string $applicant_last_name
 * @property string $applicant_first_name
 * @property string $date_of_birth
 * @property string $passport_number
 * @property integer $mobile_number
 * @property string $office_address
 * @property int|null $assigned_to
 * @property int|null $case_manager_id
 * @property json|null $case_applicant_info
 * @property string $raised_by_id //stores the user id of whoever raised the case
 * @property boolean $is_sent_for_billing
 * @property boolean $is_billed
 * @property integer $case_status
 * @property string additional_attachments
 * @property integer $client_entity
 * @property int|null $organisation_id
 * @property int|null $client_id
 *
 * @property CaseType $caseType
 * @property Organisation $organisation
 * @property Client $client
 * @property ClientEntity $clientEntity
 * @property User $caseWorker
 */
class Cases extends \yii\db\ActiveRecord
{ public $status;
    public $attachment_ids_additional_attachments, $additional_attachments_upload, $client_id_display;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_cases';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['case_number', 'case_type_id', 'client_name', 'mID', 'organisation_id'], 'required'],
//            [['organisation_id', 'client_id','applicant_id', 'case_type_id'], 'required', 'message' => 'Value cannot be null'],
            [['case_type_id', 'mobile_number','applicant_id','status','over_all_status', 'mID', 'assigned_to', 'case_manager_id', 'raised_by_id', 'case_status', 'client_entity','client_case_manager_id','client_case_worker_id','case_work_office_id'], 'integer'],
            [['target_completion_date', 'date_of_birth', 'created_at', 'updated_at', 'last_status_update', 'organisation_id', 'client_id'], 'safe'],
            [['case_number','client_billing_entity'], 'string', 'max' => 255],
            [['sending_country', 'receiving_country', 'applicant_last_name', 'applicant_first_name', 'office_address', 'client_name', 'last_status_update'], 'string', 'max' => 255],
            [['passport_number'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'case_number' => Yii::t('backend', 'Case Number'),
            'mID' => Yii::t('backend', 'Case Sequence Number'),    //Nemanja
            'client_name' => Yii::t('backend', 'Client Name'),    //Nemanja
            'last_status_update' => Yii::t('backend', 'LAST STATUS UPDATE'),    //Nemanja
            'case_type_id' => Yii::t('backend', 'Case Type'),
            'applicant_id' => Yii::t('backend', 'Applicant'),
            'target_completion_date' => Yii::t('backend', 'Target Completion Date'),
            'sending_country' => Yii::t('backend', 'Sending Country'),
            'receiving_country' => Yii::t('backend', 'Receiving Country'),
            'applicant_last_name' => Yii::t('backend', 'Applicant Last Name'),
            'applicant_first_name' => Yii::t('backend', 'Applicant First Name'),
            'date_of_birth' => Yii::t('backend', 'Date Of Birth'),
            'passport_number' => Yii::t('backend', 'Passport Number'),
            'mobile_number' => Yii::t('backend', 'Mobile Number'),
            'office_address' => Yii::t('backend', 'Office Address'),
            'assigned_to' => Yii::t('backend','Case Worker'),
            'case_manager_id' => Yii::t('backend','Case Manager'),
            'client_case_worker_id' => Yii::t('backend','Client Case Worker'),
            'client_case_manager_id' => Yii::t('backend','Client Case Manager'),
            'case_work_office_id' => Yii::t('backend','Case Work Office'),
            'case_applicant_info' => Yii::t('backend', 'Case Applicant Info'),
            'client_billing_entity' => Yii::t('backend', 'Client Billing Entity'),
            'raised_by_id' => Yii::t('backend', 'Raised By'),
            'is_sent_for_billing' => Yii::t('backend', 'Is Sent For Billing?'),
            'is_billed' => Yii::t('backend', 'Is Billed?'),
            'case_status' => Yii::t('backend', 'Case Status'),
            'addiditional_attachments' => Yii::t('backend', 'Additional Attachments'),
            'client_entity' => Yii::t('backend', 'Client Entity'),
            'organisation_id' => Yii::t('backend', 'Organisation ID'),
            'client_id' => Yii::t('backend', 'Client ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseSteps()
    {
        return $this->hasMany(CaseSteps::className(), ['case_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseType()
    {
        return $this->hasOne(CaseType::className(), ['id' => 'case_type_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicant()
    {
        return $this->hasOne(Applicant::className(), ['id' => 'applicant_id']);
    }

    public function getReceipts()
    {
        return $this->hasMany(Receipt::class, ['case_id' => 'id']);
    }
    
    /**
     * Gets query for [[Organisation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganisation()
    {
        return $this->hasOne(Organisation::class, ['id' => 'organisation_id']);
    }
    public function getCaseWorkOffice()
    {
        return $this->hasOne(Organisation::class, ['id' => 'case_work_office_id']);
    }
    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }
    public function getClientEntity()
    {
        return $this->hasOne(ClientEntity::class, ['id' => 'client_entity']);
    }

    public function getCaseManager()
    {
        return $this->hasOne(User::class, ['id' => 'case_manager_id']);
    }

    public function getCaseWorker()
    {
        return $this->hasOne(User::class, ['id' => 'assigned_to']);
    }

    public function getClientCaseManager()
    {
        return $this->hasOne(User::class, ['id' => 'client_case_manager_id']);
    }

    public function getClientCaseWorker()
    {
        return $this->hasOne(User::class, ['id' => 'client_case_worker_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaseStatus() {
        return $this->hasOne(CaseStatus::className(), ['id' => 'case_status']);
    }
    
    
    /** 
    * Renumber table case_number field
    * @author Nemanja
    * @since 2021-01-08
    * @return true 
    */
    public static function renumber($organisation_id = null)
    {
        if ($organisation_id) 
            $orgs = Organisation::find()->where(['id' => $organisation_id])->all();
        else    
            $orgs = Organisation::find()->all();
        foreach ($orgs as $org) {
            $i = 1;
            $models = Cases::getAll($org->id)->all();
            if (@$models) {
                foreach ($models as $mo) {
                    $dif = explode('-10000', $mo->case_number);
                    $front = $dif[0];
                    
                    $mo->case_number = $front . "-10000" . $i;
                    $mo->mID = $i;

                    $mo->update();
                    $i++;
                }
            }
        }
    }

    /** 
    * get all data
    * @author Nemanja
    * @since 2021-01-11
    * @return all data 
    */
    public static function getAll($organisation_id = null)
    {
        if ($organisation_id) {
            $clients = Client::find()->where(['organisation_id' => $organisation_id])->all();
            $idArr = [];
            foreach ($clients as $item) {
                $idArr[] = $item->id;
            }
            $applicants = Applicant::find()->where(['client_id' => $idArr])->all();
            $idArr = [];
            foreach ($applicants as $item) {
                $idArr[] = $item->id;
            }
            if (count($idArr) > 0)
                return Cases::find()->where(['applicant_id' => $idArr]);
        } 
        return Cases::find();
    }

    public function assignCase($caseWorkerID, $caseID) {
        if (!empty($caseWorkerID) && !empty($caseID)) {
            $caseModel = Cases::findOne(['id' => $caseID]);
            $caseWorker = User::findOne($caseWorkerID);
            if (!empty($caseModel->raised_by_id)) {
                $caseRaiser = User::findOne($caseModel->raised_by_id);
                
                // \Yii::$app->mailer->compose()
                //         ->setFrom(\app\components\GlobalConstant::REPLY_FROM_EMAIL)
                //         ->setTo($caseRaiser->email)
                //         ->setSubject('Case Update from Pangea')
                //         ->setHtmlBody('Dear '.$caseRaiser->username.', <br/><br/> Your case has been assigned to '.$caseWorker->username.'. You can communicate with them at '.$caseWorker->email.' <br/><br/>Thanks')
                //         ->send();

                $fromEmail = $caseRaiser->organisation->user->email;
                $toEmail = $caseRaiser->email;
                $subject = 'Case Update from Northmansterling';
                $htmlBody = 'Dear '.$caseRaiser->username.', <br/><br/> Your case has been assigned to '.$caseWorker->username.'. You can communicate with them at '.$caseWorker->email.' <br/><br/>Thanks';
            
                Helper::sendEmailViaSes($fromEmail, $toEmail, null, $subject, $htmlBody, null, null, null);

            }
            $caseModel->updateAttributes(['assigned_to' => $caseWorkerID]);
        } elseif (empty($caseWorkerID)) {
            $caseModel = Cases::findOne(['id' => $caseID]);
            $caseModel->updateAttributes(['assigned_to' => NULL]);
        }
    }
    // assign client case worker
    public function assignClientCase($clientCaseWorkerID, $caseID) {
        if (!empty($clientCaseWorkerID) && !empty($caseID)) {
            $caseModel = Cases::findOne(['id' => $caseID]);
            $clientCaseWorker = User::findOne($clientCaseWorkerID);
            if (!empty($caseModel->raised_by_id)) {
                $caseRaiser = User::findOne($caseModel->raised_by_id);
                
                

                $fromEmail = $caseRaiser->organisation->user->email;
                $toEmail = $caseRaiser->email;
                $subject = 'Case Update from Northmansterling';
                $htmlBody = 'Dear '.$caseRaiser->username.', <br/><br/> Your case has been assigned to '.$clientCaseWorker->username.'. You can communicate with them at '.$clientCaseWorker->email.' <br/><br/>Thanks';
            
                Helper::sendEmailViaSes($fromEmail, $toEmail, null, $subject, $htmlBody, null, null, null);

            }
            $caseModel->updateAttributes(['client_case_worker_id' => $clientCaseWorkerID]);
        } elseif (empty($clientCaseWorkerID)) {
            $caseModel = Cases::findOne(['id' => $caseID]);
            $caseModel->updateAttributes(['client_case_worker_id' => NULL]);
        }
    }
    public function assignCaseManager($caseManagerID, $caseID) {
        if (!empty($caseManagerID) && !empty($caseID)) {
            $caseModel = Cases::findOne(['id' => $caseID]);
            $caseManager = User::findOne($caseManagerID);
            if (!empty($caseModel->raised_by_id)) {
                $caseRaiser = User::findOne($caseModel->raised_by_id);
                
                // \Yii::$app->mailer->compose()
                //         ->setFrom(\app\components\GlobalConstant::REPLY_FROM_EMAIL)
                //         ->setTo($caseRaiser->email)
                //         ->setSubject('Case Update from Pangea')
                //         ->setHtmlBody('Dear '.$caseRaiser->username.', <br/><br/> Your case has been assigned to '.$cassManager->username.'. You can communicate with them at '.$cassManager->email.' <br/><br/>Thanks')
                //         ->send();

                $fromEmail = $caseRaiser->organisation->user->email;
                $toEmail = $caseRaiser->email;
                $subject = 'Case Update from Northmansterling';
                $htmlBody = 'Dear '.$caseRaiser->username.', <br/><br/> Your case has been assigned to '.$caseManager->username.'. You can communicate with them at '.$caseManager->email.' <br/><br/>Thanks';
            
                Helper::sendEmailViaSes($fromEmail, $toEmail, null, $subject, $htmlBody, null, null, null);

            }
            $caseModel->updateAttributes(['case_manager_id' => $caseManagerID]);
        } elseif (empty($caseManagerID)) {
            $caseModel = Cases::findOne(['id' => $caseID]);
            $caseModel->updateAttributes(['case_manager_id' => NULL]);
        }
    }
    // client case manager
    public function assignClientCaseManager($clientCaseManagerID, $caseID) {
        if (!empty($clientCaseManagerID) && !empty($caseID)) {
            $caseModel = Cases::findOne(['id' => $caseID]);
            $clientCaseManager = User::findOne($clientCaseManagerID);
            if (!empty($caseModel->raised_by_id)) {
                $caseRaiser = User::findOne($caseModel->raised_by_id);
                $fromEmail = $caseRaiser->organisation->user->email;
                $toEmail = $caseRaiser->email;
                $subject = 'Case Update from Northmansterling';
                $htmlBody = 'Dear '.$caseRaiser->username.', <br/><br/> Your case has been assigned to '.$clientCaseManager->username.'. You can communicate with them at '.$clientCaseManager->email.' <br/><br/>Thanks';
            
                Helper::sendEmailViaSes($fromEmail, $toEmail, null, $subject, $htmlBody, null, null, null);

            }
            $caseModel->updateAttributes(['client_case_manager_id' => $clientCaseManagerID]);
        } elseif (empty($clientCaseManagerID)) {
            $caseModel = Cases::findOne(['id' => $caseID]);
            $caseModel->updateAttributes(['client_case_manager_id' => NULL]);
        }
    }

    /** 
    * update datetime
    * @author Nemanja
    * @since 2021-01-08
    * @return true 
    */
    public function behaviors()
    {
        return [
            CustomTimestampBehavior::className(),
        ];
    }

}
