<?php

namespace backend\modules\mii\jsonData;

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
    'type' => 'text',
    'required' => true,
    'label' => 'Client Name',
    'placeholder' => 'type',
    'className' => 'form-control',
    'name' => 'client_name',
    'subtype' => 'text',
  ),
  1 => 
  array (
    'type' => 'text',
    'required' => true,
    'label' => 'Country',
    'placeholder' => 'type',
    'className' => 'form-control',
    'name' => 'country',
    'subtype' => 'text',
  ),
  2 => 
  array (
    'type' => 'text',
    'subtype' => 'email',
    'required' => true,
    'label' => 'Email',
    'placeholder' => 'type',
    'className' => 'form-control',
    'name' => 'email',
  ),
  3 => 
  array (
    'type' => 'text',
    'required' => true,
    'label' => 'Phone',
    'placeholder' => 'type',
    'className' => 'form-control',
    'name' => 'phone',
    'subtype' => 'text',
  ),
  4 => 
  array (
    'type' => 'textarea',
    'required' => true,
    'label' => 'Address',
    'placeholder' => 'type',
    'className' => 'form-control',
    'name' => 'address',
    'subtype' => 'textarea',
  ),
  5 => 
  array (
    'type' => 'text',
    'label' => 'Company Registration Number',
    'className' => 'form-control',
    'name' => 'text-1570532600638',
    'subtype' => 'text',
  ),
  6 => 
  array (
    'type' => 'text',
    'label' => 'Company VAT Registration Number',
    'className' => 'form-control',
    'name' => 'text-1578126561394',
    'subtype' => 'text',
  ),
);
}

}
