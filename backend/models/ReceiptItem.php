<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%receipt_item}}".
 *
 * @property integer $id
 * @property integer $receipt_id
 * @property string $description
 * @property string $price
 * @property integer $section_id
 * @property integer $vat
 * @property integer $quantity
 *
 * @property Receipt $receipt
 * @property ReceiptItemSection $section
 */
class ReceiptItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%receipt_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['receipt_id', 'section_id', 'vat', 'quantity'], 'integer'],
            [['price' , 'vat_rate', 'price_sub_total'], 'number', 'min' => 0],
            [['description'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'receipt_id' => 'Receipt ID',
            'description' => 'Description',
            'price' => 'Price',
            'section_id' => 'Section ID',
            'vat' => 'VAT',
            'quantity' => 'Quantity',
            'price_sub_total' => 'price_sub_total',
            'vat_rate'       => 'vat_rate'

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceipt()
    {
        return $this->hasOne(Receipt::className(), ['id' => 'receipt_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(ReceiptItemSection::className(), ['id' => 'section_id']);
    }

    public function returnSubTotal($vat = null){
        // $tax=self::returnTaxValue($this->vat) ;
        // if($this->price && isset($tax) && is_numeric($tax) ){
        //     return $this->price*((100+$tax)/100) * $this->quantity;
        // }else{
        //     return $this->price * $this->quantity;
        // }
        return $this->price * $this->quantity;
    }
    CONST RECEIPT_ITEM_TAX_NA = 1;
    CONST RECEIPT_ITEM_TAX_NA_VALUE = 'N / A';
    CONST RECEIPT_ITEM_TAX_ZERO_RATED = 2;
    CONST RECEIPT_ITEM_TAX_ZERO_RATED_VALUE = 'Zero Rated';
    CONST RECEIPT_ITEM_TAX_EXEMPTED= 3;
    CONST RECEIPT_ITEM_TAX_EXEMPTED_VALUE= 'Exempted';
    CONST RECEIPT_ITEM_TAX_20 = 4;
    CONST RECEIPT_ITEM_TAX_20_VALUE = 20;

    public static function returnTaxValue($key=null){
        $data=  [self::RECEIPT_ITEM_TAX_NA => self::RECEIPT_ITEM_TAX_NA_VALUE,
            self::RECEIPT_ITEM_TAX_ZERO_RATED => self::RECEIPT_ITEM_TAX_ZERO_RATED_VALUE,
            self::RECEIPT_ITEM_TAX_EXEMPTED =>   self::RECEIPT_ITEM_TAX_EXEMPTED_VALUE ,
            self::RECEIPT_ITEM_TAX_20 =>  self::RECEIPT_ITEM_TAX_20_VALUE
        ];
        if(!empty($key)){
            return $data[$key];
        }
        else return $data;
    }
}
