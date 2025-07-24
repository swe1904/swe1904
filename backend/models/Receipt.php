<?php

namespace backend\models;
use app\components\GlobalConstant;
use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Receipt extends \common\models\Receipt
{
    public function rules()
{
    $parentRules = parent::rules();
    return ArrayHelper::merge(
        $parentRules,
        [
            ['cheque_number', 'required','when' => function() {
                return $this->payment_mode == 2;
            }, 'enableClientValidation' => false],
            ['draft_number', 'required','when' => function() {
                return $this->payment_mode == 3;
            }, 'enableClientValidation' => false],
            ['drawn_on', 'required','when' => function() {
                return $this->payment_mode == 2;
            }, 'enableClientValidation' => false],
            ['other_bank', 'required','when' => function() {
                return $this->drawn_on == 8;
            }, 'enableClientValidation' => false],
            ['client_id', 'required','when' => function() {
                return $this->is_receipt == 1;
            }, 'enableClientValidation' => false],
            ['client_id', 'required','when' => function() {
                return $this->is_receipt == 0;
            }, 'enableClientValidation' => false],
            ['currency_id', 'required','when' => function() {
                return $this->is_receipt == 1;
            }, 'enableClientValidation' => false],
            ['currency_id', 'required','when' => function() {
                return $this->is_receipt == 0;
            }, 'enableClientValidation' => false],
            ['case_id', 'required','when' => function() {
                return $this->is_receipt == 0;
            }, 'enableClientValidation' => false],
            ['case_id', 'required','when' => function() {
                return $this->is_receipt == 1;
            }, 'enableClientValidation' => false],
            
            ['potential_client_name', 'required','when' => function() {
                return $this->is_receipt == -1;
            }, 'enableClientValidation' => false],
            ['potential_client_email', 'required','when' => function() {
                return $this->is_receipt == -1;
            }, 'enableClientValidation' => false],
            ['potential_client_email', 'email','when' => function() {
                return $this->is_receipt == -1;
            }, 'enableClientValidation' => false],
             ['potential_client_currency', 'required','when' => function() {
                return $this->is_receipt == -1;
            }, 'enableClientValidation' => false],
            [['vat_type', 'vat_rate'], 'safe'],


        ]
    );
}

    function number_to_word( $num = '' )
    {
        $num    = ( string ) ( ( int ) $num );

        if( ( int ) ( $num ) && ctype_digit( $num ) )
        {
            $words  = array( );

            $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );

            $list1  = array('','one','two','three','four','five','six','seven',
                'eight','nine','ten','eleven','twelve','thirteen','fourteen',
                'fifteen','sixteen','seventeen','eighteen','nineteen');

            $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
                'seventy','eighty','ninety','hundred');

            $list3  = array('','thousand','million','billion','trillion',
                'quadrillion','quintillion','sextillion','septillion',
                'octillion','nonillion','decillion','undecillion',
                'duodecillion','tredecillion','quattuordecillion',
                'quindecillion','sexdecillion','septendecillion',
                'octodecillion','novemdecillion','vigintillion');

            $num_length = strlen( $num );
            $levels = ( int ) ( ( $num_length + 2 ) / 3 );
            $max_length = $levels * 3;
            $num    = substr( '00'.$num , -$max_length );
            $num_levels = str_split( $num , 3 );

            foreach( $num_levels as $num_part )
            {
                $levels--;
                $hundreds   = ( int ) ( $num_part / 100 );
                $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
                $tens       = ( int ) ( $num_part % 100 );
                $singles    = '';

                if( $tens < 20 )
                {
                    $tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
                }
                else
                {
                    $tens   = ( int ) ( $tens / 10 );
                    $tens   = ' ' . $list2[$tens] . ' ';
                    $singles    = ( int ) ( $num_part % 10 );
                    $singles    = ' ' . $list1[$singles] . ' ';
                }
                $words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
            }

            $commas = count( $words );

            if( $commas > 1 )
            {
                $commas = $commas - 1;
            }

            $words  = implode( ', ' , $words );

            //Some Finishing Touch
            //Replacing multiples of spaces with one space
            $words  = trim( str_replace( ' ,' , ',' , $this->trim_all( ucwords( $words ) ) ) , ', ' );
            if( $commas )
            {
                $words  = $this->str_replace_last( ',' , ' and' , $words );
            }

            return $words;
        }
        else if( ! ( ( int ) $num ) )
        {
            return 'Zero';
        }
        return '';
    }

    function str_replace_last( $search , $replace , $str ) {
        if( ( $pos = strrpos( $str , $search ) ) !== false ) {
            $search_length  = strlen( $search );
            $str    = substr_replace( $str , $replace , $pos , $search_length );
        }
        return $str;
    }

    function trim_all( $str , $what = NULL , $with = ' ' )
    {
        if( $what === NULL )
        {
            $what   = "\\x00-\\x20";    //all white-spaces and control chars
        }

        return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
    }

    public static function getClientName($id){
        $model = Client::find()->where(['id'=>$id])->one();
       if(!empty($model))
        return  $model->client_name;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function setTaxFields(){
        if($this->set_client_is_taxable && isset($this->set_client_tax_percentage) && !empty($this->amount)){
            $this->set_client_subtotal =  $this->amount/((100+$this->set_client_tax_percentage)/100);
            $this->set_client_tax = ($this->set_client_tax_percentage/100)*$this->set_client_subtotal;
        }
    }

    public function saveAmountFromReceiptItems()
    {
        $this->amount = 0;
        $serviceFee = 0;
        $governmentFee = 0;

        foreach ($this->receiptItems as $receiptItem) {
            if($receiptItem->section_id == 1)
            {
                $serviceFee += $receiptItem->price_sub_total; 
            }
            else{
                $governmentFee += $receiptItem->price * $receiptItem->quantity;  
            }
            
            $this->amount = $serviceFee + $governmentFee;
            // if ($receiptItem->vat == ReceiptItem::RECEIPT_ITEM_TAX_20_VALUE) {
            //     $this->amount += $receiptItem->price_sub * $receiptItem->quantity * 1.2;
            // } else
            //     $this->amount += $receiptItem->price * $receiptItem->quantity;
        }

        $this->save(false, ['amount']);
    }
    public static function getReceiptType()
    {
        if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER)
            return ['-1'=>'Quote']; // TODO: Change the autogenerated stub
        else
            return ['-1'=>'Quote','0'=>'Invoice','1'=>'Receipt']; // TODO: Change the autogenerated stub
    }
}
