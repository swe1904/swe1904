<?php
/**
 * Created by PhpStorm.
 * User: ome
 * Date: 12-06-2018
 * Time: 17:02
 */

namespace backend\modules\mii\components;


class MiiGlobalConstants
{
    const UPLOAD_IMAGES = "images";
    public static function returnClientPrimaryFields(){
        return ['id','user_id','organisation_id'];
    }
    public static function returnClientFixedFields(){
        return [
            'client_name',
            'country',
            'phone',
            'address',
            'email'
        ];
    }
    public static function returnApplicantPrimaryFields(){
        return ['id','client_id','parent_id'];
    }
    public static function returnApplicantFixedFields(){
        return [
            'email',
            'first_name',
            'last_name',
            'nationality',
            'sending_country',
            'date_of_birth',
            'passport_number',
            'mobile_number',
            'office_address'
        ];
    }
}