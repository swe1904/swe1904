<?php

namespace backend\models;

use Yii;
use backend\models\clientFixed\ApplicantFixed;
use backend\models\FileUpload;
use app\models\ClientMultiSelect;
use backend\models\Client;
/**
 * This is the model class for table "applicant".
 *
 * @property integer $id
 * @property integer $client_id
 * @property integer $parent_id
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $nationality
 * @property string $sending_country
 * @property string $date_of_birth
 * @property string $passport_number
 * @property string $mobile_number
 * @property string $office_address
 * @property string $file_1609222030883
 * @property string $date_1674644208007
 * @property string $textarea_1716885445830
 * @property string $select_1716885518762
 * @property string $date_1716885690490
 * @property string $date_1716885716345
 * @property string $select_1716885772442
 * @property string $file_1716885886753
 * @property string $file_1716885947331
 * @property string $file_1716886041312
 * @property string $file_1716886071776
 * @property string $select_1717755396737
 *
 * @property Applicant $parent
 * @property Applicant[] $applicants
 * @property Cases[] $cases



* @property string $attachment_ids_file_1609222030883
* @property string $attachment_ids_file_1716885886753
* @property string $attachment_ids_file_1716885947331
* @property string $attachment_ids_file_1716886041312
* @property string $attachment_ids_file_1716886071776
* @property FileUpload[] $file_1609222030883s
* @property FileUpload[] $file_1716885886753s
* @property FileUpload[] $file_1716885947331s
* @property FileUpload[] $file_1716886041312s
* @property FileUpload[] $file_1716886071776s
* @property string file_1609222030883_upload
* @property string file_1716885886753_upload
* @property string file_1716885947331_upload
* @property string file_1716886041312_upload
* @property string file_1716886071776_upload
 */
class Applicant extends ApplicantFixed
{
public $attachment_ids_file_1609222030883,$file_1609222030883_upload,$attachment_ids_file_1716885886753,$file_1716885886753_upload,$attachment_ids_file_1716885947331,$file_1716885947331_upload,$attachment_ids_file_1716886041312,$file_1716886041312_upload,$attachment_ids_file_1716886071776,$file_1716886071776_upload;

   
    public static function select_1717755396737()
    {
    return array (
  'Husband' => 'Husband',
  'Wife' => 'Wife',
  'Mother' => 'Mother',
  'Father' => 'Father',
);
    }

    
    public static function select_1716885518762()
    {
    return array (
  'Male' => 'Male',
  'Female' => 'Female',
  'Prefer not to say' => 'Prefer not to say',
);
    }

    
    public static function select_1716885772442()
    {
    return array (
  'Single' => 'Single',
  'Married' => 'Married',
  'Widowed' => 'Widowed',
  'Divorced' => 'Divorced',
  'Seperated' => 'Seperated',
);
    }

        public static function returnAttachmentAttr()
    {
        return ['attachment_ids_file_1609222030883','attachment_ids_file_1716885886753','attachment_ids_file_1716885947331','attachment_ids_file_1716886041312','attachment_ids_file_1716886071776'];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'applicant';
    }
    
    /**
    * @inheritdoc
    */
    public $customRules = [];
    public function rules()
    {
        return array_merge([
            [['client_id', 'parent_id'], 'integer'],
            [['date_of_birth', 'date_1674644208007', 'date_1716885690490', 'date_1716885716345'], 'safe'],
            [['textarea_1716885445830'], 'string'],
            [['email', 'nationality', 'file_1609222030883', 'select_1716885518762', 'select_1716885772442', 'file_1716885886753', 'file_1716885947331', 'file_1716886041312', 'file_1716886071776', 'select_1717755396737'], 'string', 'max' => 255],
            [['attachment_ids_file_1609222030883'], 'string'],
            [['attachment_ids_file_1716885886753'], 'string'],
            [['attachment_ids_file_1716885947331'], 'string'],
            [['attachment_ids_file_1716886041312'], 'string'],
            [['attachment_ids_file_1716886071776'], 'string'],
            ['first_name','string'],
            ['last_name','string'],
            ['mobile_number','string'],
            ['email','email'],
            ['textarea_1716885445830','string'],
            ['office_address','string'],
            ['passport_number','string'],
            ['nationality','string'],
            ['sending_country','string']
        ], $this->customRules);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'client_id' => '',
            'parent_id' => '',
            'email' => 'Email',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'nationality' => 'Nationality',
            'sending_country' => 'Sending Country',
            'date_of_birth' => 'Date of Birth',
            'passport_number' => 'Passport Number',
            'mobile_number' => 'Mobile Number',
            'office_address' => 'Office Address',
            'file_1609222030883' => 'Passport Upload',
            'date_1674644208007' => 'Request Date',
            'textarea_1716885445830' => 'Home Address',
            'select_1716885518762' => 'Select Gender',
            'date_1716885690490' => 'Passport Issue Date',
            'date_1716885716345' => 'Passport Expiry Date',
            'select_1716885772442' => 'Marital Status',
            'file_1716885886753' => 'Birth Certificate',
            'file_1716885947331' => 'Driving License',
            'file_1716886041312' => 'Educational Certificates',
            'file_1716886071776' => 'Other Docs',
            'select_1717755396737' => 'Relationship',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Applicant::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicants()
    {
        return $this->hasMany(Applicant::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCases()
    {
        return $this->hasMany(Cases::className(), ['applicant_id' => 'id']);
    }












    /**
    * @return \yii\db\ActiveQuery
    */
    public function getFile_1609222030883s()
    {
    return $this->hasMany(FileUpload::className(), ['file_id' => 'file_1609222030883']);
    }







    /**
    * @return \yii\db\ActiveQuery
    */
    public function getFile_1716885886753s()
    {
    return $this->hasMany(FileUpload::className(), ['file_id' => 'file_1716885886753']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getFile_1716885947331s()
    {
    return $this->hasMany(FileUpload::className(), ['file_id' => 'file_1716885947331']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getFile_1716886041312s()
    {
    return $this->hasMany(FileUpload::className(), ['file_id' => 'file_1716886041312']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getFile_1716886071776s()
    {
    return $this->hasMany(FileUpload::className(), ['file_id' => 'file_1716886071776']);
    }



public function getClient()
{
    return $this->hasOne(Client::className(), ['id' => 'client_id']);
}
}
