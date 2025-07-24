<?php

namespace backend\modules\mii\jsonData;

use backend\modules\mii\models\ApplicantFieldsData;
use Yii;

class Applicant
{
/**
* @inheritdoc
*/
public static function returnData()
{
    //earlier this file was being used to store the fields as array
    //but now we store that JSON in the database
    //this function has been changed to return JSON fields in array format 
    //so as to not break code everywhere else, since this function is being used in a lot of places.
    $fieldsData = ApplicantFieldsData::find()->one();
    if (!empty($fieldsData)) {
        $jsonData = json_decode($fieldsData['fields_json'], true);
        return $jsonData;
    }

    return array();
//   return array (
//  0 =>
//  array (
//    'type' => 'text',
//    'required' => true,
//    'label' => 'First Name',
//    'placeholder' => 'type',
//    'className' => 'form-control',
//    'name' => 'first_name',
//    'subtype' => 'text',
//  ),
//  1 =>
//  array (
//    'type' => 'text',
//    'required' => true,
//    'label' => 'Last Name',
//    'placeholder' => 'type',
//    'className' => 'form-control',
//    'name' => 'last_name',
//    'subtype' => 'text',
//  ),
//  2 =>
//  array (
//    'type' => 'text',
//    'subtype' => 'email',
//    'required' => true,
//    'label' => 'Email',
//    'placeholder' => 'type',
//    'className' => 'form-control',
//    'name' => 'email',
//    'value' => 'sachin123@mailinator.com',
//  ),
//  3 =>
//  array (
//    'type' => 'text',
//    'required' => true,
//    'label' => 'Nationality',
//    'placeholder' => 'type',
//    'className' => 'form-control',
//    'name' => 'nationality',
//    'subtype' => 'text',
//  ),
//  4 =>
//  array (
//    'type' => 'text',
//    'required' => true,
//    'label' => 'Sending Country',
//    'placeholder' => 'type',
//    'className' => 'form-control',
//    'name' => 'sending_country',
//    'subtype' => 'text',
//  ),
//  5 =>
//  array (
//    'type' => 'date',
//    'required' => true,
//    'label' => 'Date of Birth',
//    'className' => 'form-control',
//    'name' => 'date_of_birth',
//  ),
//  6 =>
//  array (
//    'type' => 'text',
//    'required' => true,
//    'label' => 'Passport Number',
//    'placeholder' => 'type',
//    'className' => 'form-control',
//    'name' => 'passport_number',
//    'subtype' => 'text',
//  ),
//  7 =>
//  array (
//    'type' => 'text',
//    'required' => true,
//    'label' => 'Mobile Number',
//    'placeholder' => 'type',
//    'className' => 'form-control',
//    'name' => 'mobile_number',
//    'subtype' => 'text',
//  ),
//  8 =>
//  array (
//    'type' => 'textarea',
//    'required' => true,
//    'label' => 'Office Address',
//    'placeholder' => 'type',
//    'className' => 'form-control',
//    'name' => 'office_address',
//    'subtype' => 'textarea',
//  ),
//  9 =>
//  array (
//    'type' => 'file',
//    'label' => 'Passport Upload',
//    'placeholder' => 'Passport Upload',
//    'className' => 'form-control',
//    'name' => 'file-1609222030883',
//    'subtype' => 'file',
//  ),
//);
}

}
