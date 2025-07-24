<?php

namespace common\models;

use Yii;
use backend\models\Cases;
use backend\models\Client;

/**
 * This is the model class for table "{{%receipt}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $date
 * @property string $description
 * @property string $cheque_number
 * @property string $receipt_increment_alphabetic_part
 * @property integer $receipt_increment_number_part
 * @property string $amount
 * @property integer $payment_mode
 * @property integer $drawn_on
 * @property integer $organisation_id
 * @property integer $service_id
 * @property integer $client_id
 * @property string $receipt_number
 * @property string $draft_number
 * @property string $other_bank
 * @property string $set_client_name
 * @property string $set_client_country
 * @property string $set_client_middle_name
 * @property string $set_client_registration_number
 * @property string $set_mobile
 * @property string $set_email
 * @property string $created_at
 * @property string $updated_at
 * @property integer $currency_id
 * @property string $set_client_address
 * @property string $set_client_gstin
 * @property string $set_client_pan
 * @property integer $set_client_is_taxable
 * @property integer $set_client_subtotal
 * @property integer $set_client_tax
 * @property integer $set_client_tax_percentage
 * @property number $actual_amount_received
 * @property integer $is_receipt
 * @property integer $case_id 
 *
 * @property Currency $currency
 * @property Drawn $drawnOn
 * @property Organisation $organisation
 * @property User $user
 * @property ReceiptService[] $receiptServices
 *
 */
class Receipt extends \yii\db\ActiveRecord
{
    public $client_entity;
    public $case_type;
    public $currency_id_display;
    public $vat_rate_display;

    use \mootensai\relation\RelationTrait;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames()
    {
        return [
            'receiptItems',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%receipt}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'receipt_increment_number_part', 'payment_mode', 'drawn_on', 'organisation_id', 'client_id', 'set_mobile', 'currency_id', 'set_client_is_taxable', 'is_receipt', 'case_id','vat_type'], 'integer'],
            [['date', 'due_date'], 'required'],
            [['amount', 'set_client_subtotal', 'set_client_tax', 'set_client_tax_percentage', 'actual_amount_received','vat_rate'], 'number'],
            [['date', 'due_date', 'receipt_increment_alphabetic_part', 'created_at', 'updated_at'], 'string', 'max' => 50],
            [['description', 'cheque_number', 'receipt_number', 'draft_number', 'other_bank', 'set_client_name', 'set_client_country', 'set_client_middle_name', 'set_client_registration_number', 'set_email', 'set_client_address'], 'string', 'max' => 512],
            [['receipt_increment_number_part'], 'unique'],
            [['set_client_gstin', 'set_client_pan'], 'string', 'max' => 255],
            ['date_received','safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'date' => 'Date',
            'description' => 'Description',
            'cheque_number' => 'Cheque Number',
            'receipt_increment_alphabetic_part' => 'Receipt Alphabetic ',
            'receipt_increment_number_part' => 'Receipt Number ',
            'amount' => 'Amount',
            'payment_mode' => 'Payment Mode',
            'drawn_on' => 'Drawn On',
            'organisation_id' => 'Organisation ID',
            'service_id' => 'Service',
            'client_id' => 'Client',
            'receipt_number' => 'Receipt Number',
            'draft_number' => 'Draft Number',
            'other_bank' => 'Other Bank',
            'set_client_name' => 'Client Name',
            'set_client_country' => 'Country',
            'set_client_middle_name' => 'Client Middle Name',
            'set_client_registration_number' => 'Client Registration Number',
            'set_mobile' => 'Mobile',
            'set_email' => 'Email',
            'currency_id' => 'Currency',
            'currency_id_display' => 'Currency',
            'set_client_address' => 'Set Client Address',
            'set_client_gstin' => 'Set Client Gstin',
            'set_client_pan' => 'Set Client Pan',
            'set_client_is_taxable' => 'Set Client Is Taxable',
            'set_client_subtotal' => 'Set Client Subtotal',
            'set_client_tax' => 'Set Client Tax',
            'set_client_tax_percentage' => 'Set Client Tax Percentage',
            'actual_amount_received' => 'Actual amount received in Rs',
            'is_receipt' => 'Is Receipt',
            'case_id' => 'Case ID',
            'due_date' => 'Due Date',
            'vat_rate' => 'Vat Rate',
            'vat_type' => 'Vat Type',
            'po_number' => 'PO Number',
            'potential_client_name' => 'Potential Client Name',
            'potential_client_email' => 'Potential Client Email',
            'potential_client_address' => 'Potential Client Address',
            'note' => 'note,'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDrawnOn()
    {
        return $this->hasOne(Drawn::className(), ['id' => 'drawn_on']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganisation()
    {
        return $this->hasOne(Organisation::className(), ['id' => 'organisation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    public function getCase()
    {
        return $this->hasOne(Cases::className(), ['id' => 'case_id']);
    }
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiptServices()
    {
        return $this->hasMany(ReceiptService::className(), ['receipt_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiptItems()
    {
        return $this->hasMany(\backend\models\ReceiptItem::className(), ['receipt_id' => 'id']);
    }
}
