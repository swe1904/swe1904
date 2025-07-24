<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "earning".
 *
 * @property integer $id
 * @property string $name
 * @property string $percentage
 */
class Earning extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'earning';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'percentage'], 'required'],
            [['percentage'], 'number'],
            [['name'], 'string', 'max' => 50],
            ['percentage', 'compare', 'compareValue' => 0, 'operator' => '>'],
      ['percentage', 'compare', 'compareValue' => 100, 'operator' => '<='],
      ['percentage','validatePercentage']
        ];
    }

    /**
     *
     */
    public function validatePercentage()
    {

$sum = $this->find()->sum('percentage');
        $rem=100-$sum;
        if($this->percentage + $sum>100.00){
if($this->percentage>$rem)
            $this->addError('percentage','Incorrect percentage, use less than equal to'.$rem.'(Even equal too is allowed)');
        }


   }
    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'percentage' => 'Percentage',
        ];
    }
}
