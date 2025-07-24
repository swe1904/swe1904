<?php

namespace frontend\modules\mii\jsonData;

use Yii;

class Client
{
/**
* @inheritdoc
*/
public static function returnData()
{
   return array (
  0 => 
  array (
    'type' => 'date',
    'required' => true,
    'label' => 'choose date',
    'className' => 'form-control',
    'name' => 'date-1528809715690',
  ),
  1 => 
  array (
    'type' => 'text',
    'required' => true,
    'label' => 'erer',
    'className' => 'form-control',
    'name' => 'text-1528808645886',
    'subtype' => 'text',
  ),
  2 => 
  array (
    'type' => 'text',
    'required' => true,
    'label' => 'First Name',
    'placeholder' => 'type',
    'className' => 'form-control',
    'name' => 'first_name_fixed',
    'subtype' => 'text',
  ),
  3 => 
  array (
    'type' => 'select',
    'label' => 'Select',
    'className' => 'form-control',
    'name' => 'select-1528809495736',
    'values' => 
    array (
      0 => 
      array (
        'label' => 'Option 1',
        'value' => 'option-1',
        'selected' => true,
      ),
      1 => 
      array (
        'label' => 'Option 2',
        'value' => 'option-2',
      ),
      2 => 
      array (
        'label' => 'Option 3',
        'value' => 'option-3',
      ),
    ),
  ),
  4 => 
  array (
    'type' => 'text',
    'required' => true,
    'label' => 'Phone',
    'placeholder' => 'type',
    'className' => 'form-control',
    'name' => 'phone_fixed',
    'subtype' => 'text',
  ),
  5 => 
  array (
    'type' => 'textarea',
    'required' => true,
    'label' => 'Address',
    'placeholder' => 'type',
    'className' => 'form-control',
    'name' => 'address_fixed',
    'subtype' => 'textarea',
  ),
  6 => 
  array (
    'type' => 'text',
    'required' => true,
    'label' => 'Last Name',
    'placeholder' => 'type',
    'className' => 'form-control',
    'name' => 'last_name_fixed',
    'subtype' => 'text',
  ),
  7 => 
  array (
    'type' => 'date',
    'label' => 'Date Field',
    'className' => 'form-control',
    'name' => 'date-1528810280939',
  ),
  8 => 
  array (
    'type' => 'file',
    'label' => 'File Upload',
    'className' => 'form-control',
    'name' => 'file-1528956850557',
    'subtype' => 'file',
  ),
  9 => 
  array (
    'type' => 'file',
    'label' => 'File Upload',
    'className' => 'form-control',
    'name' => 'file-1528956854120',
    'subtype' => 'file',
  ),
);
}

}
