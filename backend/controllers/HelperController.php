<?php

namespace backend\controllers;

use app\components\GlobalConstant;
use backend\components\Helper;
use backend\models\CaseTypeServicePrice;
use backend\models\Client;
use backend\models\Drawn;
use backend\models\Organisation;
use backend\models\ReceiptItem;
use backend\models\ReceiptItemSection;
use backend\models\ReceiptService;
use backend\models\Service;
use frontend\models\Plan;
use Yii;
use common\models\Receipt;
use backend\models\search\ReceiptSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\web\Response;
use yii\widgets\ActiveForm;
use backend\models\Currency;
use backend\models\Cases;
use backend\models\CaseType;
use backend\models\CaseTypePricing;

use backend\models\ClientEntity;
use yii\helpers\ArrayHelper;



/**
 * ReceiptController implements the CRUD actions for Receipt model.
 */
class HelperController extends CustomBaseController
{
    public function actionGetClientEntities()
    {
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; // Set response to JSON
    
        if (Yii::$app->request->isAjax && isset($_GET['clientId'])) {
            $data = ArrayHelper::map(
                ClientEntity::find()->where(['client_id' => $_GET['clientId']])->all(), 'id', 'name'
            );
            return $data; // Yii2 automatically converts it to JSON
        }
    
        return ['error' => 'Invalid request'];
    }
    
    public function actionGetClientOrgs()
    {
        if(isset($_GET['clientId']))
        {
            $data = ArrayHelper::map(Organisation::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.organisation_id = tbl_organisation.id')->andWhere(['tbl_client_organisation.client_id' => $_GET['clientId']])->all(),'id','name');
            return json_encode($data);
        }
    }
    public function actionGetClientEntityCases()
    {
        if(isset($_GET['clientEntityId']))
        {
            //Case Manager and worker can create bills for the cases to which they are assigned to, irrespective of organisation 
            $cases = Cases::find()->where([
                'client_entity' => $_GET['clientEntityId'],
                'case_status' => GlobalConstant::CASE_STATUS_SENT_FOR_BILLING,
            ]);
            if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER) 
                $cases->andWhere(['case_manager_id' => Yii::$app->user->identity->id]);
            else if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER)
                $cases->andWhere(['assigned_to' => Yii::$app->user->identity->id]);
            else //roles other than Case Manager and worker can create bills for their organisation's cases only
                $cases->andWhere(['organisation_id' => Yii::$app->user->identity->organisation_id]);
            $data = ArrayHelper::map($cases->all(),'id','case_number');
          
            return json_encode($data);
        }
    }
    public function actionGetCaseTypeOfCase() {
        if (isset($_GET['caseId']))
         {
            $caseTypeID = Cases::findOne($_GET['caseId'])->case_type_id;
            $caseType = CaseType::find()->where(['id' => $caseTypeID])->asArray()->one();
            return json_encode($caseType);
        }
    }
    public function actionGetServices() {
        if (isset($_GET['clientId']) && isset($_GET['clientEntityId']) && isset($_GET['caseTypeId']) && isset($_GET['caseId']))
        {
            $organisation = Cases::findOne($_GET['caseId'])->organisation;

            $caseTypePricing = CaseTypePricing::findOne([
                'client_id'=> $_GET['clientId'],
                'client_entity_id'=> $_GET['clientEntityId'],
                'case_type_id'=> $_GET['caseTypeId'],
                'organisation_id' => $organisation->id

            ]);

            if($caseTypePricing)
            {
                $services = CaseTypeServicePrice::find()->where(['case_type_pricing_id'=>$caseTypePricing->id])->asArray()->all();
                $currency = Currency::findOne($caseTypePricing->currency_id)->toArray();
                if($services)
                    return json_encode([
                        'code' => 1,
                        'services' => $services,
                        'currency' => $currency,
                ]);
                else
                return json_encode([
                    'code' => 1,
                    'services' => [],
                    'currency' => $currency,
                    'message' => 'No services found. Kindly set services <a target="_blank" style="color: blue; text-decoration: underline;" href="'. getenv('BACKEND_URL').'receipt/case-type-pricing">here</a> or add services in the below section'
                ]);
            }
            else
            {
                return json_encode([
                    'code' => 0,
                    'message' => 'Case type price not configured. Kindly configure <a target="_blank" style="color: blue; text-decoration: underline;" href="'. getenv('BACKEND_URL').'receipt/case-type-pricing">here</a> or add services in the below section'
                ]);
            }
        }
    }
    public function actionGetVatTypeAndVatRateOfOrgOfCase() {
        if (isset($_GET['caseId']))
         {
            $organisation = Cases::findOne($_GET['caseId'])->organisation;
            if($organisation)//will be null for client user
            {
                if($organisation->vat_type && $organisation->vat_type)
                {
                    $vatType = $organisation->vat_type;
                    $vatTypeName = GlobalConstant::VAT_TYPE_ARRAY[$organisation->vat_type];
                    $vatRate = $organisation->vat_rate;
                    return json_encode([
                        'code' => 1,
                        'vatData' =>['vatType' => $vatType, 'vatTypeName' => $vatTypeName, 'vatRate' =>$vatRate],
                        
                        ]);
                }
                else
                    return json_encode([
                        'code' => 0,
                        'message' =>'VAT type or rate not configured. Kindly '.(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN?'configure vat type and rate in organisation setup.':'contact admin of organisation "'.$organisation->name.'"')
                        
                    ]);
            }
        }
    }
}
?>