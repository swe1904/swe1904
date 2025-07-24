<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tbl_receipt_item_section".
 *
 * @property integer $id
 * @property string $name
 *
 * @property ReceiptItem[] $receiptItems
 */
class ReceiptItemSection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_receipt_item_section';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiptItems()
    {
        return $this->hasMany(ReceiptItem::className(), ['section_id' => 'id']);
    }
}
