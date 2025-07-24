<?php

namespace backend\controllers;
use app\components\GlobalConstant;
use backend\models\FileUpload;
use app\models\TempFile;
use backend\components\Helper;
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
use backend\models\CaseTypeServicePrice;
use backend\models\search\CaseTypePricingSearch;
use backend\models\search\CaseTypeServicePriceSearch;
use backend\modules\mii\components\MiiGlobalConstants;
use backend\models\ClientEntity;
use backend\models\ClientOrganisation;

use yii\helpers\ArrayHelper;



/**
 * ReceiptController implements the CRUD actions for Receipt model.
 */
class ReceiptController extends CustomBaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Receipt models.
     * @return mixed
     */
    public function actionIndex()
    {

        /*count free user receipt*/
        /*$receiptCountArray = array();
        $organisationModelCount = Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
        if(!empty($organisationModelCount)){
            $dtObj = new \DateTime($organisationModelCount->created_at);
            $organisationCreatedDate = $dtObj->format('d');
            $receiptCreateStartDate = date('Y-m-'.$organisationCreatedDate.'');
            $receiptCreateEndDate = date("Y-m-$organisationCreatedDate", strtotime("+1 months"));
            $sql = "SELECT * FROM tbl_receipt WHERE created_at BETWEEN '" . $receiptCreateStartDate . "' AND  '" . $receiptCreateEndDate . "' AND user_id = ".Yii::$app->user->identity->id." ORDER by id DESC";
            $receiptCount = Receipt::findBySql($sql)->count();
            array_push($receiptCountArray,$receiptCount);
        }*/

        /*check subscription for year / monthly users*/
        /*only for paid users*/
       /* $recurringModelPaidUsers = PaypalRecurringPaymentsProfile::find()
            ->where(['user_id' => Yii::$app->user->identity->id])
            ->andWhere(['is_cancelled' => Plan::ACTIVE_PLAN])
            ->andWhere(['plan_id' => Plan::MONTHLY_PLAN])
            ->orWhere(['plan_id' => Plan::YEARLY_PLAN])
            ->one();*/
        /*if(isset($recurringModelPaidUsers) && !empty($recurringModelPaidUsers)){
            $currentDate = Date('Y-m-d');
            $subscriptionExpireDate = $recurringModelPaidUsers->billing_end_date;


        }*/



        $searchModel = new ReceiptSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //loading clients for different roles for the filter
        if (isset(Yii::$app->user->identity->organisation_id)) {
            // $clients = Client::find()->select(['id', 'client_name'])->where(['organisation_id' => Yii::$app->user->identity->organisation_id])->all();
            $clients = Client::find()
                        ->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')
                        ->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all();
        } else {
            $clients = Client::find()->select(['id', 'client_name'])->all();
        }

        $clients = ArrayHelper::map($clients, 'id', 'client_name');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'clients' => $clients
            //'receiptCountArray'=>$receiptCountArray
        ]);
    }

    /**
     * Displays a single Receipt model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $receiptServiceModel = \backend\models\ReceiptService::find()->where(['receipt_id'=>$_GET['id']])->all();
        $receiptModel = \backend\models\Receipt::find()->with('organisation')->where('id=:id', array(':id'=>$id))->one();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'receiptModel'=>$receiptModel,
            'template'=>$_GET['template'],
            'receiptServiceModel'=>$receiptServiceModel,
        ]);
    }
    public function actionGovtFeeAttachment($id)
    {
       
        $searchModel = new \backend\models\search\ReceiptItemSearch();
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['receipt_id' => $id])
        ->andWhere(['section_id' => 2]);
     
        return $this->render('govt-fee-attachment', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'receiptId' => $id,
        ]);
    }
    public function actionAttachDocuments($id) {
        $model = ReceiptItem::findOne($id);
        $receipt = Receipt::findOne($model->receipt_id);
     
        return $this->render('attach-documents', [
            'model'     => $model,
            'receipt'   => $receipt
        ]);
    }
    public function actionDownloadAttachment($attachmentID) {
        $fileModel = FileUpload::findOne($attachmentID);
        if (!empty($fileModel && isset($fileModel->s3_file_key))) {
            $file = Helper::getS3Object($fileModel->s3_file_key);
            if (!empty($file)) {
                $headers = Yii::$app->response->headers;
                $headers->set('Content-Description', 'File Transfer');
                $headers->set('Content-Disposition', 'attachment; filename=' . $fileModel->name);
                $headers->set('Content-Type', 'application/octet-stream');
                $headers->set('Expires', '0');
                $headers->set('Cache-Control', 'must-revalidate');
                $headers->set('Pragma', 'public');

                //send file to browser for download. 
                return $file["Body"];
            }
        }
    }

    public function actionSubmitAttachments() {
        if (Yii::$app->request->post() && isset(Yii::$app->request->post()['ReceiptItem']['additional_attachments'])) {
            $sessionID = Yii::$app->request->post()['ReceiptItem']['additional_attachments'];
            $tempFiles = TempFile::find()->where(['session_id' =>  $sessionID ])->all();
        
            if (!empty($tempFiles)) {
               
                foreach ($tempFiles as $tempFile) {
                    
                    $fileUploadModel = new FileUpload();
                    $fileUploadModel->file_id = $sessionID;
                    $fileUploadModel->name = $tempFile->name;
                    $fileUploadModel->extension = $tempFile->extension;
                    $fileUploadModel->file_name = $tempFile->file_name;
                    $fileUploadModel->created_at = $tempFile->created_at;
                    $fileUploadModel->updated_at = $tempFile->updated_at;
                    $fileUploadModel->uploaded_by = $tempFile->uploaded_by;

                    if (getenv('IS_UPLOAD_TO_S3') == 1) {

                        $receiptItemId = Yii::$app->request->post()['ReceiptItem']['id'];
                      
                        $receiptItem = ReceiptItem::findOne($receiptItemId);
                        $receipt = Receipt::findOne($receiptItem->receipt_id);
                      
                        $organisation = Organisation::findOne($receipt->organisation_id);    
                    
                        // $bucket = getenv('AWS_S3_BUCKET');
                        $bucket = 'pangea-live-bucket';
                       
                        $filePath = \Yii::getAlias('@uploadPath'.'/'.MiiGlobalConstants::UPLOAD_IMAGES.'/'.$tempFile->file_name);
                      
                        //Readable folder structure
                        $module = 'Government fees';
                        $S3Key = $organisation->name . '-' . $organisation->id . '/' . $module . '/' .  $receipt->id . '/'. $receiptItem->description . '/' . basename($filePath);
                      
                        $errorMessage = 'Failed to upload files. Please try again.';
                        //Uploading to S3 and getting URL
                        $url = Helper::uploadToS3($bucket, $S3Key, $filePath, $errorMessage);
                        if ($url) {
                            $fileUploadModel->attachment = $url;
                            $fileUploadModel->is_upload_to_s3 = 1;
                            $fileUploadModel->s3_file_key = $S3Key;
                            if($fileUploadModel->save()){
                                //Deleting temp file from DB and Server
                                $tempFile->delete();
                                unlink($filePath); 
                            }
                        } else {
                            //if no url, error message will be displayed and redirect back to index
                            $this->redirect(['receipt/index']);
                        }
                    } else {
                        $fileUploadModel->attachment = $tempFile->attachment;
                        if ($fileUploadModel->save()) {
                            $tempFile->delete();
                        } 
                    }
                }
                $receiptItemModel = ReceiptItem::findOne(Yii::$app->request->post()['ReceiptItem']['id']);
                $receiptItemModel->updateAttributes(['additional_attachments' => $sessionID]);
            }
        }
        $receiptItemID = Yii::$app->request->post()['ReceiptItem']['id'];
        
        return $this->redirect(['attach-documents', 'id' => $receiptItemID]);
    }

    //removes temp file from the server, used with DropZone widget
    public function actionRemoveTempFile() {
        if (Yii::$app->request->post() && isset(Yii::$app->request->post()['sessionID']) && isset(Yii::$app->request->post()['fileName'])) {
            $sessionID = Yii::$app->request->post()['sessionID'];
            $fileName = Yii::$app->request->post()['fileName'];
            $file = TempFile::find()->where(['session_id' => $sessionID, 'uploaded_by' => Yii::$app->user->id, 'name' => $fileName])->orderBy(['id' => SORT_DESC])->one();
            $file->delete();
            return json_encode([
                'code' => 1,
                'message' => 'File Removed!'
            ]);
        }
    }


    /*create pdf download pdf send pdf view pdf*/
    public function actionSamplePdf() {
        $model = \backend\models\Receipt::findOne($_GET['id']);
        $receiptServiceModel = \backend\models\ReceiptService::find()->where(['receipt_id'=>$_GET['id']])->all();
        $receiptModel = \backend\models\Receipt::find()->with('organisation')->where('id=:id', array(':id'=>$_GET['id']))->one();

        $fileUploads = array();
        // Find all receipt items with non-empty 'additional_attachments'
        $receiptItems = ReceiptItem::find()
            ->where(['receipt_id' => $_GET['id']])
            ->andWhere(['section_id' => '2'])
            ->andWhere(['!=', 'additional_attachments', ''])
            ->all();
            $attachmentData = [];
        if(!empty($receiptItems)){
            // Extract all additional_attachments into an array
            $attachmentIds = [];
            foreach ($receiptItems as $item) {
               
                if (!empty($item->additional_attachments)) {
                    $attachmentIds = explode(',', $item->additional_attachments);
        
                 
                    $fileUploads = FileUpload::find()
                        ->where(['file_id' => $attachmentIds])
                        ->all();
        
                    foreach ($fileUploads as $file) {
                      
                        $attachmentData[] = [
                            'file_url' => $file->attachment, 
                            'description' => $item->description,
                            'quantity' => $item->quantity,      
                            'price' => $item->price,             
                        ];
                    }
                }
            }

        }
       
        $template= isset($_GET['template'])?$_GET['template']:1;
        $content = $this->renderPartial('view', [
            'model' => $this->findModel($_GET['id']),
            'receiptModel'=>$receiptModel,
            'template'=>$template,
            'receiptServiceModel'=>$receiptServiceModel,
            'fileUploads'=>$attachmentData,
        ]);
        $case = Cases::findOne($model->case_id);
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => sys_get_temp_dir(), 
            'format' => 'A4',
        ]);
        $footer='{PAGENO} / {nb}';     

        $css = "";
        if($template==2||$template==1) {
            $css = "
                table td {
                    border: 0!important;
                }
                body {margin-top: 0px;margin-left: 0px;}
                // #page_1 {position:relative; overflow: hidden;margin: 51px 0px 26px 0px;padding: 0px;border: none;width: 792px;}
                // #page_1 #p1dimg1 {position:absolute;top:100px;z-index:1;left: 420px;}
                .ft0{font: bold 13px \\'Helvetica\\';line-height: 16px;}
                .ft1{font: 13px \\'Helvetica\\';line-height: 18px;}
                .ft2{font: 13px \\'Helvetica\\';line-height: 16px;}
                .ft3{font: 13px \\'Helvetica\\';line-height: 19px;}
                .ft4{font: 27px \\'Helvetica\\';color: #761e23;line-height: 32px;}
                .ft5{font: 1px \\'Helvetica\\';line-height: 1px;}
                .ft6{font: 1px \\'Helvetica\\';line-height: 7px;}
                .ft7{font: 12px \\'Helvetica\\';color: #761e23;line-height: 15px;}
                .ft8{font: bold 21px \\'Helvetica\\';line-height: 24px;}
                .ft9{font: 1px \\'Helvetica\\';line-height: 11px;}
                .ft10{font: 15px \\'Helvetica\\';line-height: 17px;}
                .ft11{font: 1px \\'Helvetica\\';line-height: 2px;}
                // .p0{text-align: left;padding-left: 35px;margin-top: 0px;margin-bottom: 0px;}
                // .p1{text-align: left;padding-left: 35px;padding-right: 653px;margin-top: 4px;margin-bottom: 0px;}
                // .p2{text-align: left;padding-left: 35px;margin-top: 1px;margin-bottom: 0px;}
                // .p3{text-align: left;padding-left: 35px;padding-right: 550px;margin-top: 2px;margin-bottom: 0px;}
                // .p4{text-align: left;padding-left: 38px;margin-top: 16px;margin-bottom: 0px;}
                // .p5{text-align: left;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p6{text-align: right;padding-right: 86px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p7{text-align: right;padding-right: 64px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p8{text-align: right;padding-right: 92px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p9{text-align: left;padding-left: 42px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p10{text-align: left;padding-left: 47px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p11{text-align: left;padding-left: 46px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p12{text-align: left;padding-left: 52px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p13{text-align: right;padding-right: 14px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p14{text-align: left;padding-left: 21px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p15{text-align: right;padding-right: 13px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p16{text-align: right;padding-right: 42px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p17{text-align: left;padding-left: 29px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p18{text-align: left;padding-left: 38px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p19{text-align: right;padding-right: 124px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p20{text-align: left;padding-left: 22px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p21{text-align: right;padding-right: 43px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                // .p22{text-align: left;padding-left: 20px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                .p23{text-align: left;/*padding-left: 42px;*/margin-top: 10px;margin-bottom: 0px;}
                .p24{text-align: left;/*padding-left: 42px;*/margin-top: 14px;margin-bottom: 0px;}
                .p25{text-align: left;/*padding-left: 42px;*/margin-top: 0px;margin-bottom: 0px;}
                .p26{text-align: left;/*padding-left: 42px;*/margin-top: 2px;margin-bottom: 0px;}
                .td0{padding: 0px;margin: 0px;width: 472px;vertical-align: bottom;}
                .td1{padding: 0px;margin: 0px;width: 220px;vertical-align: bottom;}
                .td2{border-bottom: #761e23 1px solid;padding: 0px;margin: 0px;width: 472px;vertical-align: bottom;}
                .td3{border-bottom: #761e23 1px solid;padding: 0px;margin: 0px;width: 220px;vertical-align: bottom;}
                .td4 {border-bottom: #e4d2d3 1px solid;padding: 0px;margin: 0px;width: 354px;vertical-align: bottom;background: #e4d2d3;}
                .td5{border-bottom: #e4d2d3 1px solid;padding: 0px;margin: 0px;width: 38px;vertical-align: bottom;background: #e4d2d3;padding-top:5px;}
                .td6{border-bottom: #e4d2d3 1px solid;padding: 0px;margin: 0px;width: 139px;vertical-align: bottom;background: #e4d2d3;}
                .td7{border-bottom: #e4d2d3 1px solid;padding: 0px;margin: 0px;width: 91px;vertical-align: bottom;background: #e4d2d3;}
                .td8{border-bottom: #e4d2d3 1px solid;padding: 0px;margin: 0px;width: 146px;vertical-align: bottom;background: #e4d2d3;}
                // .td9{padding: 0px;margin: 0px;width: 354px;vertical-align: bottom;}
                // .td10{padding: 0px;margin: 0px;width: 38px;vertical-align: bottom;}
                // .td11{padding: 0px;margin: 0px;width: 139px;vertical-align: bottom;}
                // .td12{padding: 0px;margin: 0px;width: 91px;vertical-align: bottom;}
                .td13{padding: 0px;margin: 0px;width: 146px;vertical-align: bottom;}
                .tr0{height: 20px;}
                .tr1{height: 20px;}
                .tr2{height: 20px;}
                // .tr3{height: 7px;}
                .tr4{height: 21px;}
                .tr5{height: 22px;}
                // .tr6{height: 16px;}
                // .tr7{height: 19px;}
                // .tr8{height: 51px;}
                // .tr9{height: 29px;}
                // .tr10{height: 11px;}
                // .tr11{height: 34px;}
                // .tr12{height: 2px;}
                .t0{width: 692px;/*margin-left: 38px;*/margin-top: 10px;font: 13px \\'Helvetica\\';}
                .t1{width: 768px;margin-top: 62px;font: 13px \\'Helvetica\\';}
                .padtop{padding-top: 10px;}
                .vatitem{display: none}
                .tr5,.tr6{padding-left: 22px;}
        
                // @page {
                //         margin-top: 4cm;
                //         margin-bottom: 6cm;
                //         }
                ";



        }

        // Set a custom temporary directory that is writable
        $tempDir = Yii::getAlias('@runtime/mpdf_temp');

        // Ensure the temporary directory exists and is writable
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        // Create mPDF instance with custom temporary directory
        $pdf = new \Mpdf\Mpdf([
            'default_font' => 'DejaVuSans',
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'marginBottom' => 60,
            'tempDir' => $tempDir,
            'methods' => [
                'SetTitle' => 'Receipt from Pangea Worldwide',
                'SetHTMLFooter' => [$footer]
            ],
        ]);

        // List of remote PDFs to merge
        $pdfUrls = array();
        foreach ($fileUploads as $uploads) {
            $extension = pathinfo($uploads->attachment, PATHINFO_EXTENSION);
            if (strtolower($extension) === 'pdf') {
                $pdfUrls[] = $uploads->attachment;
            }
        }

        try {
            // Write initial content (HTML or other content)
            $pdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
            $pdf->WriteHTML($content);

            // Add a page to separate the first content from the imported PDFs
            // $pdf->AddPage(); // uncomment this if needed to add exta page gap

            // Process each PDF URL
            foreach ($pdfUrls as $index => $remotePdfUrl) {
                // Save temp file in the writable directory
                $tempPdfPath = $tempDir . "/remote_pdf_{$index}.pdf";

                // Download the remote PDF
                $ch = curl_init($remotePdfUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $remotePdfContent = curl_exec($ch);

                if (curl_errno($ch)) {
                    throw new \Exception('Curl error: ' . curl_error($ch));
                }
                curl_close($ch);

                // Save the downloaded PDF to a temporary file
                file_put_contents($tempPdfPath, $remotePdfContent);

                // Import pages from the remote PDF
                $pageCount = $pdf->SetSourceFile($tempPdfPath);

                // Ensure each page from the imported PDF starts on a new page
                for ($i = 1; $i <= $pageCount; $i++) {
                    $pdf->AddPage(); // Add a new page before importing
                    $importPage = $pdf->ImportPage($i);
                    $pdf->UseTemplate($importPage);
                }

                // Clean up the temporary file
                @unlink($tempPdfPath);
            }

        } catch (\Exception $e) {
            // Log the error
            Yii::error('PDF Merge Error: ' . $e->getMessage());
        }
       
        // Output the final merged PDF
        $pdf->Output($model->receipt_increment_alphabetic_part . '-' . $model->receipt_increment_number_part . '.pdf', 'I');

        /*for send attachments*/
        if(isset($_GET['options']) && $_GET['options']=='send-email'){
            ini_set('max_execution_time', 0); // 5 minutes

            $fileName = $receiptModel->organisation->name . '_' . $model->receipt_number;
            $path = 'pdf/' . $fileName . '.pdf';  // Path where you want to save the file

            // Output the PDF to a file
            $pdf->Output(Yii::getAlias('@backend') . "/web/pdf/$fileName.pdf", 'F'); // 'F' saves to file


            $fromEmail = $receiptModel->organisation->user->email;
            

            // $toEmail = $model->set_email;
            $clientId = $model->client_id;
            $clientUser = Client::getClientUser($clientId);
            $toEmail = $clientUser->email;

            $cc= $receiptModel->organisation->email;
            $subject = 'Receipt from '.$receiptModel->organisation->name.' ';
            $htmlBody = 'Dear '.$model->set_client_name.', <br/><br/><br/> Please find the Receipt attached. <br/><br/>Thanks';
            $filePath = Yii::getAlias("@backend")."/web/pdf/$fileName.pdf";
            
            if (strpos($fromEmail, GlobalConstant::NORTHMAN_EMAIL_DOMAIN) !== false) {
                
            Helper::sendEmailViaSes($fromEmail, $toEmail, $cc, $subject, $htmlBody, null, $filePath, $fileName);
               
                Yii::$app->session->setFlash('success', 'The email has been sent successfully');
            }
            else
                Yii::$app->session->setFlash('error', 'The from(i.e. admin\'s) email is invalid(does not contain @northmansterling.app)');
            if($model->is_receipt == -1)
                    return $this->redirect(['index', 'Receipt[quotes]' => 1]);
                elseif($model->is_receipt == 1)
                    return $this->redirect(['index']);
                else
                    return $this->redirect(['index', 'Receipt[invoices]' => 1]);
            // return $this->redirect('index');
        }
        // return the pdf output as per the destination setting
        return;
    }

    /**
     * Creates a new Receipt model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->combineSectionsIntoSingleReceiptItems();

        /*fetch all bank list*/
        $drawnModel = Drawn::find()->orderBy('order')->all();
        $drawnArray = array();
        if(!empty($drawnModel)){
            foreach($drawnModel as $key=>$value){
                $drawnArray[$value['id']] = $value['name'];
            }
        }
        /*fetch all clients list*/
    // if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_FINANCE || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER){
    //     $clientModel = Client::find()->select(['id','client_name','country'])->where(['organisation_id'=>Yii::$app->user->identity->organisation_id])->all();
    // }else{
    //     $clientModel = Client::find()->select(['id','client_name','country'])->where(['user_id'=>Yii::$app->user->identity->id])->all();
    // }

    //     $clientArray = array();
    //     if(!empty($clientModel)){
    //         foreach($clientModel as $key=>$value){
    //             $clientArray[$value['id']] = $value['client_name'];
    //         }
    //     }

    $clientArray = ArrayHelper::map(Client::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all(),'id','client_name');

        /*fetch all currency*/
        $currencyArray = ArrayHelper::map(Currency::find()->all(),'id',
                                function($model) {
                                    return $model->name.' - '.$model->iso;
                                }
                            );
        // $currencyArray = array();
        // foreach($currencyModel as $key=>$value){
        //     $currencyArray[$value['id']] = $value['name'].' - '.$value['iso'];
        // }

        /*This is for setting the default currency in dropdown*/
        $organisationModel = Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
        if(empty($organisationModel)){
            $organisationModel = Organisation::find()->where(['id'=>Yii::$app->user->identity->organisation_id])->one();
        }
        $model = new \backend\models\Receipt();

        if(isset($_GET['Receipt']['quotes']))
            $model->is_receipt = -1;
        elseif(isset($_GET['Receipt']['invoices']))
            $model->is_receipt = 0;
        else
            $model->is_receipt = 1;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->loadAll($_POST)) {
            $organisationModel = Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
            if(empty($organisationModel)){
                $organisationModel = Organisation::find()->where(['id'=>Yii::$app->user->identity->organisation_id])->one();
            }
            $post = $_POST['Receipt'];

            /*receipt model*/
            $model->user_id =  Yii::$app->user->identity->id;
            $model->organisation_id = $organisationModel->id;
            $model->potential_client_address = $post['potential_client_address'];
            $model->potential_client_currency = $post['potential_client_currency'];
            $model->note = $post['note'];
            $model->receipt_number = $post['receipt_increment_alphabetic_part'].$post['receipt_increment_number_part'];
            $model->service_id = null;
            $model->po_number =$post['po_number'];
            
            /*create duplicates entry*/
//             $clientModelName = Client::find()->where(['id'=>$post['client_id']])->one();
//             if(!empty($clientModelName)){
//                 $model->set_client_name = $clientModelName->client_name;
//                 if(isset($clientModelName->middle_name))
//                     $model->set_client_middle_name = $clientModelName->middle_name;
//                 $model->set_client_country = $clientModelName->country;
//                 //$model->set_client_registration_number = $clientModelName->registration_increment_alpahabetic_part.$clientModelName->registration_increment_number_part;
//                 if(isset($clientModelName->phone))
//                     $model->set_mobile = $clientModelName->phone;
//                 if(isset($clientModelName->email))
//                     $model->set_email = $clientModelName->email;
//                 $model->set_client_address = $clientModelName->address;
// //                $model->set_client_pan = $clientModelName->pan;
// //                $model->set_client_gstin = $clientModelName->gstin;
// //                $model->set_client_is_taxable = $clientModelName->is_taxable;
// //                $model->set_client_tax_percentage = $organisationModel->service_tax_percentage;
//             }
            /*end duplicates entry*/

            $model->setTaxFields();

            if($model->saveAll()){
                // <!--Commented-pangea-->
               /* if($post['service_id']){
                    foreach($post['service_id'] as $key => $value){
                        $receiptServiceModel = new ReceiptService();
                        $receiptServiceModel->service_id = $value;
                        $receiptServiceModel->receipt_id =  $model->id;
                        $receiptServiceModel->save(false);
                    }
                }*/
               /* if(isset($post['Receipt'])){
                    foreach($_POST['Receipt']['service_id'] as $key => $value){
                        $receiptServiceModel = new ReceiptService();
                        $receiptServiceModel->service_id = $value;
                        $receiptServiceModel->receipt_id =  $model->id;
                        $receiptServiceModel->save(false);
                    }
                }*/
                // <!--Commented-pangea end-->
                //return $this->redirect(['update', 'id' => $model->id]);

                $model->saveAmountFromReceiptItems();

                if($model->is_receipt == -1)
                    return $this->redirect(['index', 'Receipt[quotes]' => 1]);
                elseif($model->is_receipt == 1)
                    return $this->redirect(['index']);
                else
                    return $this->redirect(['index', 'Receipt[invoices]' => 1]);
            }
            else {
                echo '<pre>';
                print_r($model->getErrors());
                echo '<pre>';
                die('died');
            }

        } else {
            $model->date = date('Y-m-d');//auto fill today
            $model->due_date = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-d')))); //auto filling due date to 1 month from today
            return $this->render('create', [
                'model' => $model,
               // 'serviceArray'=>$serviceArray,
                'drawnArray'=>$drawnArray,
                'clientArray'=>$clientArray,
                'currencyArray'=>$currencyArray,
                'organisationModel'=>$organisationModel,
            ]);
        }
    }

    /**
     * Updates an existing Receipt model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->combineSectionsIntoSingleReceiptItems();

        $searchModel = new \backend\models\search\ReceiptItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $receiptServiceModel = ReceiptService::find()->where(['receipt_id' => $id])->asArray()->all();
        $organisationModel = Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
        if(empty($organisationModel)){
            $organisationModel = Organisation::find()->where(['id'=>Yii::$app->user->identity->organisation_id])->one();
        }
        /*fetch all bank list*/
        $drawnModel = Drawn::find()->orderBy('order')->all();
        $drawnArray = array();
        if(!empty($drawnModel)){
            foreach($drawnModel as $key=>$value){
                $drawnArray[$value['id']] = $value['name'];
            }
        }
        /*fetch all clients list*/
        // if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_FINANCE || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER){
        //     $clientModel = Client::find()->select(['id','client_name','country'])->where(['organisation_id'=>Yii::$app->user->identity->organisation_id])->all();
        // }else{
        //     $clientModel = Client::find()->select(['id','client_name','country'])->where(['user_id'=>Yii::$app->user->identity->id])->all();
        // }
        $clientArray = ArrayHelper::map(Client::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all(),'id','client_name');
        // $clientArray = array();
        // if(!empty($clientModel)){
        //     foreach($clientModel as $key=>$value){
        //         $clientArray[$value['id']] = $value['client_name'];
        //     }
        // }
        /*fetch all currency*/
        // $currencyModel = Currency::find()->select(['id','iso'])->all();
        // $currencyArray = array();
        // if(!empty($currencyModel)){
        //     foreach($currencyModel as $key=>$value){
        //         $currencyArray[$value['id']] = $value['iso'];
        //     }
        // }
        $currencyArray = ArrayHelper::map(Currency::find()->all(),'id',
                            function($model) {
                                return $model->name.' - '.$model->iso;
                            }
                        );
        $model = $this->findModel($id);
        /*ajax validation */
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->loadAll($_POST)) {
            
            $post = $_POST['Receipt'];
            /*receipt model*/
            $model->user_id =  Yii::$app->user->identity->id;
            $model->service_id = null;
            $model->organisation_id = $organisationModel->id;
            $model->potential_client_address = $post['potential_client_address'];
            $model->potential_client_currency = $post['potential_client_currency'];
            $model->note = $post['note'];
            $model->receipt_number = $post['receipt_increment_alphabetic_part'].$post['receipt_increment_number_part'];
            $model->po_number = $post['po_number'];

            /*create duplicates entry*/
            $clientModelName = Client::find()->where(['id'=>$post['client_id']])->one();
            if(!empty($clientModelName)){
                $model->set_client_name = $clientModelName->client_name;
                if(isset($clientModelName->middle_name))
                    $model->set_client_middle_name = $clientModelName->middle_name;
                $model->set_client_country = $clientModelName->country;
                //$model->set_client_registration_number = $clientModelName->registration_increment_alpahabetic_part.$clientModelName->registration_increment_number_part;
                if(isset($clientModelName->phone))
                    $model->set_mobile = $clientModelName->phone;
                if(isset($clientModelName->email))
                    $model->set_email = $clientModelName->email;
                $model->set_client_address = $clientModelName->address;
//                $model->set_client_pan = $clientModelName->pan;
//                $model->set_client_gstin = $clientModelName->gstin;
//                $model->set_client_is_taxable = $clientModelName->is_taxable;
//                $model->set_client_tax_percentage = $organisationModel->service_tax_percentage;
            }
            /*end duplicates entry*/

            $model->setTaxFields();

            /*delete existing services*/
            $connection = Yii::$app->db;
            $connection	->createCommand()
                ->delete('tbl_receipt_service', ' receipt_id = '.$id.' ')
                ->execute();
            /*end duplicates entry*/

            $model->saveAll();
            // <!--Commented-pangea-->
         /*   if($post['service_id']){
                foreach($post['service_id'] as $key => $value){
                    $receiptServiceModel = new ReceiptService();
                    $receiptServiceModel->service_id = $value;
                    $receiptServiceModel->receipt_id =  $model->id;
                    $receiptServiceModel->save(false);
                }
            }*/
            // <!--Commented-pangea end-->
            //            return $this->redirect(['view', 'id' => $model->id]);

            $model->saveAmountFromReceiptItems();

            if($model->is_receipt == -1)
                return $this->redirect(['index', 'Receipt[quotes]' => true]);
            elseif($model->is_receipt == 1)
                return $this->redirect(['index']);
            else
                return $this->redirect(['index', 'Receipt[invoices]' => true]);

        } else {
            return $this->render('update', [
                'model' => $model,
                'drawnArray'=>$drawnArray,
                'clientArray'=>$clientArray,
                'receiptServiceModel'=>$receiptServiceModel,
                'currencyArray'=>$currencyArray,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'organisationModel'=>$organisationModel
            ]);
        }
    }

    /**
     * Deletes an existing Receipt model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $type = $model->is_receipt;
        $receiptTypeMap = [
            '-1' => 'Quote',
            '0' => 'Invoice',
            '1' => 'Receipt'
        ];
        
        $model->delete();
        Yii::$app->session->setFlash('success', $receiptTypeMap[$type] . ' has been deleted successfully.');
        if ($type == -1) {
            return $this->redirect(['index', 'Receipt[quotes]'=>'true']);
        } elseif ($type == 0) {
            return $this->redirect(['index', 'Receipt[invoices]' => 'true']);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Receipt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Receipt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = \backend\models\Receipt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /*for textbox number part*/
    public function actionGetReceiptNumber(){
        // $receiptModel = \backend\models\Receipt::find()->where(['user_id'=>Yii::$app->user->identity->id])->orderBy('id DESC')->one();
        
        // if (!isset($receiptModel)) {
        //     $receiptModel = \backend\models\Receipt::find()->where(['user_id'=>Yii::$app->user->identity->organisation->user_id])->orderBy('id DESC')->one();
        // }

        // if(!empty($receiptModel)){
        //     $receiptNumber = $receiptModel->receipt_increment_number_part;
        //     $incrementalPart =  $receiptNumber+1;
        //     return $incrementalPart;
        // }else{
        //     $organisationModels =   Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
        //     if(!empty($organisationModels)){
        //         return $organisationModels->receipt_increment_number_part;
        //     }
        // }

        $receiptModel = \backend\models\Receipt::find()->where(['organisation_id'=>Yii::$app->user->identity->organisation_id])->orderBy('id DESC')->one();
        if(!empty($receiptModel)){
            $receiptNumber = $receiptModel->receipt_increment_number_part;
            $incrementalPart =  $receiptNumber+1;
            return $incrementalPart;
        }
        else
            return 1;
    }

    /*for textbox alphabetic part*/
    public function actionGetReceiptAlphabetic()
    {
        $organisationModels = Organisation::find()->where(['user_id' => Yii::$app->user->identity->organisation_id])->one();

        if (!isset($organisationModels)) {
            $organisationModels = Organisation::find()->where(['id' => Yii::$app->user->identity->organisation_id])->one();
        }

        if (!empty($organisationModels)) {
            return $organisationModels->receipt_increment_alpahabetic_part;
        }
    }

    /*price of receipts items create*/

    public function actionCreateReceiptItem($receiptId){
        if(!empty($receiptId)) {
            $model = new ReceiptItem();
            $model->receipt_id = $receiptId;
            $model->description = $_POST['description'];
            $model->price = $_GET['price'];
            $model->save();
        }
    }
    public function actionReceiptItemUpdatePopup($id)
    {
        if(isset($_GET['receipt_id'])){
            $modelId = $_GET['receipt_id'];
        }else{
            $modelId = '0';
        }
        $model = ReceiptItem::findOne($id);
        return $this->renderPartial('_receiptItemUpdate', array(
            'model' => $model,
            'receipt_id'=>$modelId
        ));
    }

    public function actionReceiptItemUpdate()
    {
        if($_POST){
            $connection = Yii::$app->db;
            $sql =  'UPDATE tbl_receipt_item SET price= "'.$_POST['ReceiptItem']['price'].'" ,description= "'.$_POST['ReceiptItem']['description'].'"  WHERE id='.$_POST['ReceiptItem']['id'].'';
            $command = $connection->createCommand($sql);
            $command->execute();
                return $this->redirect(['receipt/update','id'=>$_POST['receipt_id']]);
        }
    }
    /*Delete existing receipt item*/
    public function actionDeleteItemReceipt(){
        $connection = Yii::$app->db;
        $connection	->createCommand('DELETE FROM tbl_receipt_item WHERE id='.$_POST['id'].'')->execute();
    }

    public function actionDownload($month, $year){
        $monthName = Helper::monthWordFromNumber($month);
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $searchModel = new ReceiptSearch();
        $organisationModel = Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();


//        $searchModel->organisation_id = $organisationModel->id;
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams)->getModels();


        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            select tbl_receipt.`date`,tbl_receipt.`description`,tbl_receipt.`cheque_number`,

concat(tbl_receipt.`receipt_increment_alphabetic_part`,tbl_receipt.`receipt_increment_number_part`) as receipt_number,

tbl_currency.`iso` as currency,

tbl_receipt.`amount`,

tbl_receipt.amount,actual_amount_received as 'Actual Amount Received in Rs',

tbl_receipt.date_received as 'Date Received',

concat(tbl_receipt.`set_client_name`,' ',tbl_receipt.`set_client_middle_name`,' ',tbl_receipt.`set_client_country`) as client_name,

tbl_receipt.set_client_gstin,

tbl_receipt.set_client_pan,

IF(tbl_receipt.`drawn_on` = 8, `tbl_receipt`.`other_bank`, tbl_drawn.`name`) AS drawn_bank,

CASE WHEN tbl_receipt.`payment_mode` = 1 then 'Cash' WHEN tbl_receipt.`payment_mode` = 2 then 'Cheque' ELSE NULL END as payment_mode

from tbl_receipt 

left join tbl_drawn on tbl_drawn.id = tbl_receipt.`drawn_on` 

left join `tbl_currency` on `tbl_currency`.`id`= `tbl_receipt`.`currency_id`

where organisation_id = :organisation_id and MONTH(tbl_receipt.date) = :month and YEAR(tbl_receipt.date) = :year order by tbl_receipt.id desc;", [':organisation_id'=> $organisationModel->id,':month' => $month, ':year' => $year]);

        $result = $command->queryAll();

        $downloadFileName = $organisationModel->name."_".$monthName."_receipts.csv";
        Helper::downloadFileSetHeaders($downloadFileName);

//        $receiptsArray = [['header1'=>'val1','header2'=>'val2'], ['header1'=>'val1','header2'=>'val2']];

        echo Helper::array2csv($result);
        die();
    }

    /**
     * Action to load a tabular form grid
     * for ReceiptItem
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddReceiptItem($sectionId)
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('ReceiptItem-'.$sectionId);
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = ['section_id'=>$sectionId];
            return $this->renderPartial('_formReceiptItem', ['row' => $row, 'sectionId' => $sectionId]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function combineSectionsIntoSingleReceiptItems(){
        if(isset($_POST)){
            $_POST['ReceiptItem'] = [];
            $receiptItemSections = ReceiptItemSection::find()->all();
            foreach($receiptItemSections as $section){
                if(isset($_POST["ReceiptItem-$section->id"])){
                    foreach($_POST["ReceiptItem-$section->id"] as $item){
                        $_POST['ReceiptItem'][] = $item;
                    }
                }

            }
            /*       echo "<pre>";
                   var_dump($_POST);
                   echo "</pre>";

                   echo 'ddddd';

                   echo "<pre>";
                   var_dump(Yii::$app->request->post());
                   echo "</pre>";

                   die();*/
        }
    }

    public function actionClientCases() {

        //returns client cases for the cases dropdown in receipt form assigned to CASE MANAGER
        if (isset($_GET["clientID"]) && !empty($_GET["clientID"]) && isset($_GET["caseManagerID"]) && !empty($_GET["caseManagerID"]))
        {
            $clientName = Client::findOne($_GET["clientID"])->client_name;
            $cases = Cases::find()->where(['client_name' => $clientName, 'case_manager_id' => $_GET["caseManagerID"]])->orderBy(['created_at' => SORT_DESC])->select(['id', 'case_number'])->asArray()->all();
        }
        //returns client cases for the cases dropdown in receipt form assigned to CASE WORKER
        elseif (isset($_GET["clientID"]) && !empty($_GET["clientID"]) && isset($_GET["caseWorkerID"]) && !empty($_GET["caseWorkerID"]))
        {
            $clientName = Client::findOne($_GET["clientID"])->client_name;
            $cases = Cases::find()->where(['client_name' => $clientName, 'assigned_to' => $_GET["caseWorkerID"]])->orderBy(['created_at' => SORT_DESC])->select(['id', 'case_number'])->asArray()->all();
        }
        elseif (isset($_GET["clientID"]) && !empty($_GET["clientID"])) {
            $clientName = Client::findOne($_GET["clientID"])->client_name;
            $cases = Cases::find()->where(['client_name' => $clientName])->orderBy(['created_at' => SORT_DESC])->select(['id', 'case_number'])->asArray()->all();
        } 
        return json_encode($cases);
    }
    
    public function actionCaseTypeOfCase($caseID) {
        if (!empty($caseID)) {
            $caseTypeID = Cases::findOne($caseID)->case_type_id;
            $caseType = CaseType::find()->where(['id' => $caseTypeID])->asArray()->one();
            return json_encode($caseType);
        }
    }

    public function actionCaseTypePricing() {

        if(!(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_SUPERADMIN || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_GLOBAL_FINANCE))
        {
            Yii::$app->session->setFlash('error', 'Only organisation admin can access the case type prices page.');
            return $this->redirect(['index']);
        }
        $searchModel = new CaseTypePricingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new CaseTypePricing();
        $clients = ArrayHelper::map(Client::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all(),'id','client_name');
        $caseTypes = ArrayHelper::map(CaseType::find()->all(),'id','name');

        $currency = ArrayHelper::map(Currency::find()->all(),
                                    'id',
                                    function($model) {
                                        return $model->name.' - '.$model->iso;        
                                    }
                                );

        return $this->render('caseTypePricing', [
            'model'=> $model,
            'dataProvider' => $dataProvider,
            'clients' => $clients,
            'currency' => $currency,
            'caseTypes' => $caseTypes
        ]);
    }

    public function actionUpdateCaseTypeServicePrice() {
        if (isset(Yii::$app->request->post()['serviceId']) && isset(Yii::$app->request->post()['serviceName']) && isset(Yii::$app->request->post()['price'])) {
            $service = CaseTypeServicePrice::findOne(Yii::$app->request->post()['serviceId']);
            if (!empty($service)) {
                $service->service_name = Yii::$app->request->post()['serviceName'];
                $service->price = Yii::$app->request->post()['price'];
                if($service->save())
                    return json_encode([
                        'code' => 1,
                        'message' => 'Service successfully updated'
                    ]);
                else
                    return json_encode([
                        'code' => 0,
                        'message' => 'Service could not be updated.\n'.implode(', ', $service->getErrorSummary(true))
                    ]);
            } else {
                return json_encode([
                    'code' => 0,
                    'message' => 'Service could not be updated. Please try again'
                ]);
            }
        }
    }
//    public function actionChangeReceiptStatus()
//    {
//\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//    if(Yii::$app->request->isPost) {
////        $id=Yii::$app->request->queryParams['Receipt']['id'];
////        $model = $this->findModel($id);
////        if (isset(Yii::$app->request->queryParams['Receipt']['quotes'])) {
////            if ($model->is_receipt ==-1) {
////                $model->is_receipt = 0;
////                $model->save();
////                return $this->redirect(['index', 'Receipt[quotes]'=> 1]);
////            }
////        } elseif (isset(Yii::$app->request->queryParams['Receipt']['invoices'])) {
////            if ($model->is_receipt == 0) {
////                $model->is_receipt = 1;
////                $model->save();
////                return $this->redirect(['index', 'Receipt[invoices]' => 1]);
////            }
////        } else return $this->redirect(['index']);;
//
//        $id=Yii::$app->request->post('id');
//        $value=Yii::$app->request->post('value');
//        $model = $this->findModel($id);
//        $model->is_receipt = $value;
//        if($model->save())
//        {
//            return ['status'=>1];
//        }
//
//    }
//
//    }
    public function actionAddCaseTypePricing(){
        $model = new CaseTypePricing();
        if($model->load(Yii::$app->request->post()))
        {
            if ($model->validate()) {
                if($model->save())
                    return json_encode([
                        'code' => 1,
                        'message' => 'Case Type Pricing added successfully',
                        'caseTypePricingId' => $model->id
                    ]);
                else
                    return json_encode([
                        'code' => 0,
                        'message' => 'Error adding Case Type Pricing:\n'.implode(', ', $model->getErrorSummary(true))
                    ]);
            } else {
                return json_encode([
                    'code' => 0,
                    'message' => 'Error adding Case Type Pricing:\n'.implode(', ', $model->getErrorSummary(true))
                ]);
            }
        }
    }
    public function actionAddServiceTemplate(){

        if(isset($_POST['caseTypePricingId']))
        {
            $model = CaseTypePricing::findOne($_POST['caseTypePricingId']);

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            Yii::$app->assetManager->bundles = [

                'yii\bootstrap\BootstrapPluginAsset' => false,

                'yii\bootstrap\BootstrapAsset' => false,

                'yii\web\JqueryAsset' => false

            ];
            $params = Yii::$app->request->queryParams;
            $params['CaseTypeServicePriceSearch']['case_type_pricing_id'] = $_POST['caseTypePricingId']; 
            $searchModel = new CaseTypeServicePriceSearch();
            $dataProvider = $searchModel->search($params);
            return ['status'=>1,'html'=>$this->renderAjax('_add_service_form',['model'=>$model, 'dataProvider' => $dataProvider])];
        }
    }
    public function actionAddService(){
    
        if(isset($_POST['caseTypePricingId']) && isset($_POST['service']) && isset($_POST['price']))
        {
            $caseTypePricingId = $_POST['caseTypePricingId'];
            for($i=0;$i< count($_POST['service']);$i++)
            {
                $model= new CaseTypeServicePrice();
                $model->case_type_pricing_id = $caseTypePricingId;
                $model->service_name = $_POST['service'][$i];
                $model->price = $_POST['price'][$i];
                if(!$model->save())
                return json_encode([
                    'code' => 0,
                    'message' => 'Error adding Services:\n'.implode(', ', $model->getErrorSummary(true))
                ]);
            }
            return json_encode([
                'code' => 1,
                'message' => 'Services added successfully',
            ]);
        }
        else
            return json_encode([
                'code' => 0,
                'message' => 'Insufficient data'
            ]);
    }

    //****This action can be used for accordion in the casetypePricing page's grid expangrow's detailUrl*****
    // public function actionServices()
    // {
    //     if (isset($_POST['expandRowKey'])) {
    //     return $this->renderPartial('_services',['id'=> $_POST['expandRowKey'],'params'=>$_GET['params']]);
    //     }
    //     else
    //     {
    //         return '<div class="alert alert-danger">No data found</div>';
    //     }
    // }

    public function actionServiceDelete($id)
    {
            $item = CaseTypeServicePrice::findOne($id);

            if($item)
            {
                $item->delete();
                Yii::$app->session->setFlash('success', 'Service deleted successfully.');
            }
            else
                Yii::$app->session->setFlash('error', 'Service not found.');

            return $this->redirect(['case-type-pricing']);
    }

    public function actionCasetypepricingDelete($id)
    {
            $item = CaseTypePricing::findOne($id);

            if($item)
            {
                $item->delete();
                Yii::$app->session->setFlash('success', 'Case Type Price deleted successfully.');
            }
            else
                Yii::$app->session->setFlash('errpr', 'Service not found.');

            return $this->redirect(['case-type-pricing']);
    }

    public function actionGetClientData($clientId)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; // Set response type as JSON
    
        // Fetch Client Entities
        $clientEntities = ClientEntity::find()
            ->where(['client_id' => $clientId])
            ->select(['id', 'name'])
            ->asArray()
            ->all();
    
        // Fetch Organization IDs from tbl_client_organisation
        $orgIds = ClientOrganisation::find()
        ->where(['client_id' => $clientId])
        ->select('organisation_id')
        ->column(); // Get array of organisation_id values

            // Fetch Organization details from tbl_organisation using the extracted IDs
            $organizations = Organisation::find()
            ->where(['id' => $orgIds])
            ->select(['id', 'name'])
            ->asArray()
            ->all();

                return [
                    'clientEntities' => $clientEntities,
                    'organizations' => $organizations,
                ];
    }
    
    public function actionGetClientEntities($clientId)
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    // Fetch only Client Entities
    $clientEntities = ClientEntity::find()
        ->where(['client_id' => $clientId])
        ->select(['id', 'name'])
        ->asArray()
        ->all();

    return $clientEntities;
}
public function actionGetClientEntityCases()
{
    if (isset($_GET['clientEntityId'])) {
        $clientEntityId = $_GET['clientEntityId'];
        $userId = Yii::$app->user->identity->id;
        $userRole = Yii::$app->user->identity->getRole();
        $organisationId = Yii::$app->user->identity->organisation_id;

        // Start query
        $cases = Cases::find()->where([
            'client_entity' => $clientEntityId,
            'case_status' => GlobalConstant::CASE_STATUS_SENT_FOR_BILLING,
        ]);

        // Apply role-based conditions
        if ($userRole == GlobalConstant::ROLE_CASE_MANAGER) {
            $cases->andWhere(['case_manager_id' => $userId]);
        } elseif ($userRole == GlobalConstant::ROLE_CASE_WORKER) {
            $cases->andWhere(['assigned_to' => $userId]);
        } else {
            $cases->andWhere(['organisation_id' => $organisationId]);
        }

        // Log query to debug
      
        // Fetch and return data
        $data = ArrayHelper::map($cases->all(), 'id', 'case_number');
        return json_encode($data);
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

}

