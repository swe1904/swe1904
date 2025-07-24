<?php
/**
 * Created by PhpStorm.
 * User: ome
 * Date: 12-06-2018
 * Time: 17:02
 */

namespace frontend\modules\mii\components;


class MiiGlobalConstants
{
  public static function returnClientFixedFields(){
      return [
          'first_name',
          'last_name',
          'phone',
          'address'
      ];
  }
    public static function returnApplicantFixedFields(){
        return [
            'email',
            'name',
        ];
    }
}