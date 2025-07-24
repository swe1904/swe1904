<?php

namespace backend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "employee".
 *
 *
 * @property string $employee_id
 * @property int $id
 * @property string $address
 * @property string $birth_date
 * @property string $joining_date
 * @property string $email
 * @property int $salary
 * @property string $position
 * @property int|null $organisation_id
 * @property string|null $account_number
 * @property string|null $pan
 * @property string|null $account_bank
 * @property string|null $employee_code
 * @property int|null $department_id
 * @property int|null $currency_id
 * @property int|null $nationality_id  // Added nationality field
 *
 * @property DynamicCurrency $currency
 * @property Department $department
 * @property Nationality $nationality  // Added relationship to Nationality model
 * @property Slip[] $slips
 */
class Employee extends \yii\db\ActiveRecord
{
    public $leave_allowance;
    public $passport_file;
    public $passport_copy;
    public $id_card_copy;
    public $educational_document_1;
    public $educational_document_2;
    public $resume;
    public $gross_salary;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'mobile_number', 'position', 'work_email'], 'required'],
            [['date_of_birth','place_of_birth','blood_group', 'date_of_joining','current_residential_address','permanent_address','preferred_first_name','preferred_full_name','emergency_contact_relationship','emergency_mobile_number','emergency_contact_email','department_manager','notice_period', 'probation_period_completion_date','employment_type','contract_type','annual_leave','iban_number','swift_code','bank_country','currency_id','salary_frequency_id','country_manager_id','branch_name','user_id','probation_period'], 'safe'], // Ensure date fields are safe
            [[ 'nationality_id', 'gender_id', 'organisation_id', 'department_id', 'country_of_legal_residence','team','direct_supervisor','country_manager_id'], 'integer'],
            [['salary', 'monthly_salary_basic', 'monthly_salary_housing', 'monthly_salary_transportation', 'total_monthly_salary'], 'number'],
            [['first_name', 'last_name', 'work_email', 'emergency_contact_name','employee_id', 'bank_name'], 'string', 'max' => 255],
            [['passport_copy', 'id_card_copy', 'educational_document_1', 'educational_document_2', 'resume'], 'file', 'extensions' => 'pdf'],
      ];

    
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'employee_id' => 'Employee ID',
            'address' => 'Address',
            'date_of_birth' => 'Date of Birth',
            'place_of_birth' => 'Place of Birth',
            'nationality_id' => 'Nationality',
            'gender_id' => 'Gender',
            'date_of_joining' => 'Date of Joining',
            'email' => 'Personal Email',
            'salary' => 'Salary',
            'passport_file' => 'Passport File',
            'id_card_file' => 'ID Card File',
            'resume_file' => 'Resume File',
            'position' => 'Position',
            'organisation_id' => 'Organisation ID',
            'account_number' => 'Account Number',
            'bank_country' => 'Bank Country',
            'iban_number' => 'IBAN Number',
            'branch_name' => 'Branch Name',
            'swift_code' => 'Swift Code',
            'pan' => 'Pan',
            'account_bank' => 'Account Bank',
            'employee_code' => 'Employee Code',
            'department_id' => 'Department',
            'currency_id' => 'Salary Currency',
            'salary_frequency_id' => 'Salary Frequency',
            'preferred_first_name' => 'Preferred First Name',
            'preferred_full_name' => 'Preferred Full Name',
            'blood_group' => 'Blood Group',
            'country_of_legal_residence' => 'Country',
            'current_residential_address' => 'Current Residential Address',
            'permanent_address' => 'Home country/Permanent Address',
            'mobile_number' => 'Mobile Number',
            'emergency_contact_name' => 'Emergency Contact Name',
            'emergency_contact_relationship' => 'Emergency Contact Relationship',
            'emergency_mobile_number' => 'Emergency Mobile Number',
            'emergency_contact_email' => 'Emergenct contact email address',
            'work_email' => 'Work Email',
            'team' => 'Team',
            'user_id' => 'User name',
            'country' => 'Country',
            'department_manager' => 'Department Manager',
            'direct_supervisor' => 'Direct Supervisor',
            'country_manager_id' => 'Country Manager',
            'employment_type' => 'Employment Type',
            'notice_period' => 'Notice Period (calendar days)',
            'contract_type' => 'Contract Type',
            'notice_period' => 'Notice Period (calendar days)',
            'annual_leave' => 'Annual Leave  (working days)',
            'salary_frequency' => 'Salary Frequency',
            'monthly_salary_basic' => 'Monthly Salary - Basic',
            'monthly_salary_housing' => 'Monthly Salary - Housing',
            'monthly_salary_transportation' => 'Monthly Salary - Transportation',
        ];
    }

    /**
     * Gets query for [[Nationality]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNationality()
    {
        return $this->hasOne(Nationality::class, ['id' => 'nationality_id']);
    }

    public function uploadPassport()
    {
    if ($this->validate()) {
        $filePath = Yii::getAlias('@backend/web/uploads/passports/') . $this->passport_copy->baseName . '.' . $this->passport_copy->extension;
        return $this->passport_copy->saveAs($filePath);
    } else {
        return false;
    }
    }

    public function getEmergencyContactRelationships()
    {
    // Fetch the relationships from the tbl_emergency_contact_relationship table
    $relationships = \Yii::$app->db->createCommand('SELECT id, relationship_name FROM tbl_emergency_contact_relationship')->queryAll();

    // Convert the result into a key-value array for the dropdown
    $relationshipOptions = [];
    foreach ($relationships as $relationship) {
        $relationshipOptions[$relationship['id']] = $relationship['relationship_name'];
    }

    return $relationshipOptions;
    }
    public function getPositionName()
    {
        return $this->hasOne(Position::class, ['id' => 'position']);
    }
    // Other relationships (such as for Currency, Department, etc.)
    
    public function getManager()
{
    return $this->hasOne(Employee::class, ['id' => 'department_manager']);
}

public function getDepartmentManager()
{
    return $this->hasOne(User::class, ['id' => 'department_manager']);
}
// In Employee model
public function getDepartment()
{
    return $this->hasOne(Department::class, ['id' => 'department_id']);
}
public function getCountryOfLegalResidence()
{
    return $this->hasOne(Country::class, ['id' => 'country_of_legal_residence']);
}

public function getOrganisation()
{
    return $this->hasOne(Organisation::class, ['id' => 'organisation_id']);
}

}

