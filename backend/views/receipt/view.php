<?php

use backend\models\ReceiptItemSection;
use backend\models\Cases;
use backend\models\Client;

$template = isset($template) ? $template : 1;
$imageUrl =  getenv('BACKEND_URL').'images/Northman-logo.png';

//   if(isset($_GET['template'])&&$_GET['template']==1){
//       echo 'fdfd';
//$this->render('_template1',['receiptModel'=>$receiptModel,'model'=>$model]);
//}
?>

<?php if (!empty($model->currency->symbol)) {
    $currency = $model->currency->symbol;
} else {
    $currency = $model->currency->iso;
} ?>

<?php
function translateBilingual($sourceMessage, $nonTranslatable = '')
{
    return Yii::t('backend', $sourceMessage, [], 'en-US') . $nonTranslatable . ' ' . Yii::t('backend', $sourceMessage, [], 'ar-AE');
}
?>
<?php if (isset($template)) { ?>
    <div id="page_1">
        <?php
        $receipt_type = ($receiptModel->is_receipt == -1) ? 'QUOTE' : (($receiptModel->is_receipt == 0) ? 'INVOICE' : (($receiptModel->is_receipt == 1) ? 'RECEIPT' : ''));
        ?>
        <table cellpadding="0" cellspacing="0" class="t0">
            <!-- <style>
                table {
                    table-layout: fixed;
                    width: 100%;
                    border-collapse: collapse;
                }

                table td {
                    padding: 5px;
                    border: 1px solid #e4d2d3;
                    vertical-align: top;
                    white-space: normal;
                    word-wrap: break-word;
                    overflow-wrap: break-word;
                }

                .td0,
               

                .td1,
                .td3 {
                    width: 25%;
                    /* Adjust this width for other columns */
                    max-width: 25%;
                }

                .td4,
                .td5,
                .td6,
                .td7,
                .td8 {
                    width: auto;
                    white-space: normal;
                   
                }

              
                .service-name {
                    max-width: 100px;
                    /* Set max width for the column */
                    overflow-wrap: break-word;
                    /* Wrap long words */
                    font-size: 13px;
                }

                ";
            </style> -->
            <tbody>
                <tr>
                        <td style="width: 50%;vertical-align:top;">

                            <img src="<?php echo $imageUrl;?>" style="width:auto; height:70px; object-fit:contain;" id="p1img1" />

                        </td>                   
                    <td align="right" style="vertical-align: top; color: #122034;">
                        <p class="p4 ft4" style="font-size: 15pt; color: #000000;">
                            <?php
                            if ($receipt_type == 'QUOTE') {
                                echo translateBilingual('Fee Estimate');
                            } elseif ($receipt_type == 'INVOICE') {
                                echo translateBilingual('Tax Invoice');
                            } elseif ($receipt_type == 'RECEIPT') {
                                echo translateBilingual('Payment Receipt');
                            }
                            ?>
                        </p>
                        <br>
                        <?php echo translateBilingual($receipt_type, '# ') ?>
                        <?php echo $receiptModel->receipt_number; ?>
                        <br>
                        <?php echo translateBilingual('PO Number', '# ') ?>
                        <?php echo $receiptModel->po_number; ?>
                        <br>
                        <br>
                        <?php $case = Cases::findOne($model->case_id); ?>
                        
                        <p class="p2 ft2 font-weight-bold" style="font-size: 10pt;"><?php echo $case->organisation->address; ?></p>

                        <br>
                        <p class="p3 ft3" style="font-size: 10pt;"><?php echo strtoupper($receiptModel->organisation->email); ?></p>
                        <p class="p3 ft3" style="font-size: 10pt;">
                            <?php 
                            echo isset($receiptModel->organisation->website) ? strtoupper($receiptModel->organisation->website) : ''; 
                            ?>
                       </p>


                       
                    </td>
                    <br>
                   
                </tr>
                <br><br><br><br><br>
                <tr>
                    <td style="width: 50%;vertical-align:top;color: #333333; line-height: 1.5;">
                        <p class="p0 ft0" style="text-transform: uppercase; font-size: 10pt;"><?php $case = Cases::findOne($model->case_id);                                                                                                      echo $case->organisation->name; ?></p>
                        <p class="p1 ft1" style="font-size: 10pt;">Company ID&nbsp;:&nbsp; <?php echo $case->organisation->company_id; ?></p>
                        <p class="p2 ft2" style="font-size: 10pt;"><?php echo $case->organisation->address ?> </p>
                       
                        <p class="p3 ft3" style="font-size: 10pt;"><?php echo Yii::t('backend', 'TRN'); ?> <?php echo $receiptModel->organisation->trn ?></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="p4 ft4">
            <?php
            // if($receipt_type == 'QUOTE') {
            //     echo translateBilingual('Fee Estimate');
            // }
            // elseif($receipt_type == 'INVOICE') {
            //     echo translateBilingual('Tax Invoice');
            // }
            // elseif($receipt_type == 'RECEIPT') {
            //     echo translateBilingual('Payment Receipt');
            // }
            ?>
        </p>
        <table>
            <tr>
                <td height="10"></td>
            </tr>
        </table>
        <!--        style="border-bottom: solid 1px #122034;"-->
        <table cellpadding="0" cellspacing="0" valign="bottom" class="t0">
            <tbody>
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0" style="line-height: 1.8;">
                            <tr>
                                <td class="p5 ft0" style="color: #333333; line-height: 1.5;font-weight: 400; font-size: 10pt;">
                                    <?php
                                    if ($receipt_type == 'INVOICE') {
                                        echo translateBilingual('Bill' . ' To');
                                    } else {
                                        echo translateBilingual(ucfirst(strtolower($receipt_type)) . ' To');
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <!--                                Fragomen Global Immigration Services LLC <br />-->
                                    <!--                                Matawan 90 Matawan Road PO Box 2001 USA <br />-->
                                    <!--                                TRN TRN 123450000090016-->
                                    <span style="color: #333333; line-height: 1.5; font-weight: bold; font-size: 10pt;">
                                        <?php $case = Cases::findOne($model->case_id);
                                        $clientName =  $case->client->client_name;
                                        if (isset($clientName)): ?>
                                            <?php echo $clientName; ?>
                                        <?php endif; ?>
                                    </span>
                                    <br />
                                    <span style="color: #333333; line-height: 1.5;  font-size: 10pt;">
                                        <?php
                                        $case = Cases::findOne($model->case_id);
                                        $applicant = $case->applicant ?? null;
                                        $clientEntity = $case->client_entity ? \backend\models\ClientEntity::findOne($case->client_entity)->name : 'N/A';
                                        echo htmlspecialchars($clientEntity, ENT_QUOTES, 'UTF-8');
                                        ?>
                                    </span>
                                    <br />
                                    <span style="font-size: 10pt; color: #333333;">
                                        <?php
                                        $case = Cases::findOne($model->case_id);
                                        $applicant = $case->applicant ?? null;
                                        $clientAddress = $case->client_entity ? \backend\models\ClientEntity::findOne($case->client_entity)->address : 'N/A';
                                        echo htmlspecialchars($clientAddress, ENT_QUOTES, 'UTF-8');
                                        ?>
                                    </span>
                                    <br />
                                    <!-- <span style="font-size: 10pt; color: #333333;">
                                        <?php
                                        $client = Client::findOne($model->client_id);
                                        '<br>';
                                        $clientAttributeLabels = $client->attributeLabels();
                                        $trnColumnName = (array_flip($clientAttributeLabels))["Company VAT Registration Number"];
                                        echo 'TRN ' . $client->$trnColumnName;
                                        ?>
                                    </span> -->

                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="w40" valign="bottom">
                        <table cellpadding="0" cellspacing="0" style="width: 100%;">
                            <tr>
                                <td class="p5 ft0" align="right">
                                    <!--                                Invoice Issue Date:-->
                                    <span class="ft0" style="font-size: 10pt; color: #333333; font-weight: 300;"><?php echo translateBilingual('Invoice Issue Date') ?> :</span>
                                </td>
                                <td width="20"></td>
                                <td align="right">
                                    <?php echo date('d/m/Y', strtotime($receiptModel->date)); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="h10" height="10"></td>
                            </tr>
                            <tr>
                                <td class="p5 ft0 " align="right">
                                    <span class="ft0 " style="font-size: 10pt; color: #333333; font-weight: 300;"><?php echo translateBilingual('Due Date') ?> :</span>
                                </td>
                                <td width="20"></td>
                                <td align="right">
                                    <?php
                                    if (isset($receiptModel->due_date)) {
                                        echo date('d/m/Y', strtotime($receiptModel->due_date));
                                    } else {
                                        echo date('d/m/Y', strtotime("+1 months", strtotime($receiptModel->date)));
                                    }
                                    ?>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>

                <tr>
                    <td height="20"></td>
                </tr>

                <!-- <tr>
        <td class="tr3 td2"><p class="p5 ft6">&nbsp;</p></td>
        <td class="tr3 td3"><p class="p5 ft6">&nbsp;</p></td>
    </tr> -->
            </tbody>
        </table>
        <table cellpadding="0" cellspacing="0" class="t0">

            <tbody>
                <!--        #e4d2d3-->
                <tr bgcolor="#122034">
                    <td width="20" height="10" bgcolor="#122034"></td>
                    <td height="10" bgcolor="#122034"></td>
                    <td width="10" height="10" bgcolor="#122034"></td>
                    <td height="10" bgcolor="#122034"></td>
                    <td width="10" height="10" bgcolor="#122034"></td>
                    <td height="10" bgcolor="#122034"></td>
                    <td width="10" height="10" bgcolor="#122034"></td>
                    <td height="10" bgcolor="#122034"></td>
                    <td width="10" height="10" bgcolor="#122034"></td>
                </tr>
                <tr  bgcolor="#122034">
                    <td width="10"></td>
                    <td valign="middle" bgcolor="#122034" align="left" class="tr5" style="padding-left: 10px;width:30% !important ">
                        <span class="ft7 " style="color: #ffffff; font-size: 11pt;"><?php echo translateBilingual('Nature of Goods or Services'); ?></span>
                    </td>
                    <td width="10"></td>
                    <td valign="middle" bgcolor="#122034" align="center" style="width:10% !important">
                        <span class="ft7" style="color: #ffffff; font-size: 11pt;"><?php echo translateBilingual('Quantity'); ?></span>
                    </td>
                    <td width="10"></td>
                    <td valign="middle" bgcolor="#122034" align="center" style="width:15% !important">
                        <span class="ft7" style="color: #ffffff; font-size: 11pt;"><?php echo translateBilingual('Unit Price'); ?></span>
                    </td>
                    <td width="10"></td>
                    <td valign="middle" bgcolor="#122034" align="center" style="width:10% !important">
                        <span class="ft7" style="color: #ffffff; font-size: 11pt;" ><?php echo translateBilingual('Vat Rate'); ?></span>
                    </td>

                    <td width="10"></td>
                    <td valign="middle" bgcolor="#122034" align="right" style="padding-right: 10px">
                        <span class="ft7" style="color: #ffffff; font-size: 11pt;"><?php echo translateBilingual('Amount'); ?></span>
                    </td>
                    <td width="10"></td>
                </tr>

                <tr bgcolor="#122034" >
                    <td width="10" height="10" bgcolor="#122034" ></td>
                    <td height="10" bgcolor="#122034"></td>
                    <td width="10" height="10" bgcolor="#122034"></td>
                    <td height="10" bgcolor="#122034"></td>
                    <td width="10" height="10" bgcolor="#122034"></td>
                    <td height="10" bgcolor="#122034"></td>
                    <td width="10" height="10" bgcolor="#122034"></td>
                    <td height="10" bgcolor="#122034"></td>
                    <td width="10" height="10" bgcolor="#122034"></td>
                </tr>
                <?php
                $receiptItemSections = ReceiptItemSection::find()->all();
                foreach ($receiptItemSections as $section) {
                    $totalAmount = 0;
                    $totalPrice = 0;
                ?>
                    <tr>
                        <td></td>
                        <?php
                        foreach ($receiptModel->receiptItems as $item) {
                            if ($item['section_id'] == $section->id) {
                                echo '<td class="tr5 td9" style="line-height: 1.8; padding-left: 10px; padding-top: 10px;"><p class="p9 ft0" style="color: #122034; padding-bottom: 5px;">' . $section->name . '</p></td>';
                                break;
                            }
                        }
                        ?>
                        <td></td>
                        <!-- <td class="tr5 td9"><p class="p9 ft0"><?= htmlspecialchars($section->name) ?></p></td>
                        <!--                <td class="tr5 td10"><p class="p13 ft2"></p></td>-->
                        <td class="tr5 td11">
                            <p class="p14 ft2"></p>
                        </td>
                        <td></td>
                        <td class="tr5 td12">
                            <p class="p15 ft2"></p>
                        </td>
                        <td></td>
                        <td class="tr5 td13">
                            <p class="p16 ft2"></p>
                        </td>
                        <td></td>
                    </tr>

                    <tr>
                        <!--blank row spacer-->
                    </tr>
                    <?php $totalPrice = 0;
                    $serviceFee = 0;
                    $governmentFee = 0; ?>
                    
                    <?php foreach ($receiptModel->receiptItems as $item) {
                         
                        if ($item->section_id == 1) {
                            // For section 1, use price_sub_total
                            $serviceFee += $item->price_sub_total;
                        } elseif ($item->section_id == 2) {

                            $governmentFee = $item->price * $item->quantity;
                        }

                        $totalAmount = $serviceFee + $governmentFee;

                        // Accumulate total price
                        // $totalPrice += $totalAmount;
                        // echo "<pre>";print_r($item);"</pre>";die();
                        // $totalAmount += $item->quantity * $item->price + $item->price_sub_total ;
                        // $totalPrice += ($item->quantity * $item->price);
                        if ($item['section_id'] == $section->id) {
                    ?>
                            <tr>
                                <td></td>
                                <td class="tr6 td9" height="16px" style="line-height: 1.8;padding-left:10px; padding-right: 5px; ">
                                    <p class="p9 ft1"><?php echo $item->description ?></p>
                                </td>
                                <!--                        <td class="tr6 td10"><p class="p13 ft2"></p></td>-->
                                <td></td>

                                <td class="tr6 td12" height="16px" style="line-height: 1.8;padding-left: 5px; padding-right: 5px; text-align: center;">
                                    <p class="p15 ft2"><?php echo $item->quantity ?></p>
                                </td>
                                <td></td>
                                <td class="tr6 td12" height="16px" style="line-height: 1.8;padding-left: 5px; padding-right: 5px; text-align: right;">
                                    <p class="p15 ft2"><?php echo $currency; ?> <?php echo $item->price ?></p>
                                </td>
                                <td></td>

                                <?php if ($section->name == 'Services Fees') { ?>
                                    <td class="tr6 td12" height="16px" style="line-height: 1.8; padding-left: 5px; padding-right: 5px; text-align: right;">
                                        <p class="p15 ft2"><?php echo number_format($item->vat_rate, 2); ?>%</p>
                                    </td>
                                <?php } else { ?>

                                    <td></td>
                                <?php } ?>
                                <!-- <td class="td11" align="center" height="16px"><p class="p14 ft2"><?php //if (is_int($item->returnTaxValue($item->vat))) {
                                                                                                        //     echo Yii::t('backend',$item->returnTaxValue($item->vat)). '%';
                                                                                                        // } else echo Yii::t('backend',$item->returnTaxValue($item->vat));
                                                                                                        ?></p></td> -->

                                <td></td>
                                <td class=" tr6 td13" align="right" height="16px" style="padding-right: 10px; vertical-align: middle; line-height: 1.8;">
                                    <p class="p16 ft2"><?php echo $currency; ?></span>
                                        <?php echo number_format($item->price_sub_total ? $item->price_sub_total : $item->price * $item->quantity, 2); ?></p>

                                <td></td>
                            </tr>


                <?php }
                    }
                } ?>
            </tbody>
        </table>
        <table table cellpadding="0" cellspacing="0" class="t0" style="border-top: dotted 2px grey;">
            <tbody>
                <!--                <tr>-->
                <!--                    <td style="padding: 0px;margin: 0px;width: 300px;" class="tr8 td9 padtop">-->
                <!--                        <p class="p18 ft2"><!--For any questions or concerns, please contact us* --></p>-->
                <!--                    </td>-->
                <!--                    <td class="tr8 td10 padtop">-->
                <!--                        <p class="p5 ft5">&nbsp;</p>-->
                <!--                    </td>-->
                <!--                    <td align="right" class="tr8 td11 padtop">-->
                <!--                        <p class="p5 ft2">--><?php //echo translateBilingual('Sub total'); 
                                                                    ?><!--</p>-->
                <!--                    </td>-->
                <!--                    <td class="tr8 td12 padtop">-->
                <!--                        <p class="p5 ft5">&nbsp;</p>-->
                <!--                    </td>-->
                <!--                    <td class="tr8 td13 padtop" align="right" style="padding-right: 18px;">-->
                <!--                        <p class="p16 ft2"> --><?php //echo $currency; 
                                                                    ?><!--&nbsp;--><?php //echo number_format($totalPrice, 2); 
                                                                                    ?><!--</p>-->
                <!--                    </td>-->
                <!--                </tr>-->
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <?php $taxAmount = 0; ?>
                <?php if ($template == 1): ?>
                    <tr>
                        <td class="tr8 td9">
                            <p class="p18 ft2"></p>
                        </td>
                        <td class="tr8 td10">
                            <p class="p5 ft5">&nbsp;</p>
                        </td>
                        <td align="right" class="tr8 td11">
                            <p class="p5 ft2"><?php echo translateBilingual('Total Taxable Amount (Excluding VAT)'); ?></p>
                        </td>
                        <td class="tr8 td12">
                            <p class="p5 ft5">&nbsp;</p>
                        </td>
                        <td class="tr8 td13" align="right" style="vertical-align: middle; padding-right: 18px;">
                            <p class="p16 ft2"> <?php echo $currency; ?>&nbsp;<?php echo number_format($totalPrice, 2); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="tr8 td9">
                            <p class="p18 ft2"></p>
                        </td>
                        <td class="tr8 td10">
                            <p class="p5 ft5">&nbsp;</p>
                        </td>
                        <td align="right" class="tr8 td11">
                            <p class="p5 ft2"><?php echo Yii::t('backend', 'Standard Rate (' . $receiptModel->vat_rate . '%)'); ?></p>
                        </td>
                        <td class="tr8 td12">
                            <p class="p5 ft5">&nbsp;</p>
                        </td>
                        <?php $taxAmount = ($model->vat_rate / 100) * $totalPrice; ?>
                        <td class="tr8 td13" align="right" style="padding-right: 18px;">
                            <p class="p16 ft2"> <?php echo $currency; ?>&nbsp;<?php echo number_format($taxAmount, 2); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                <?php endif; ?>
                <!-- <tr>
            <td class="tr8 td9"><p class="p18 ft2"></p></td>
            <td class="tr8 td10"><p class="p5 ft5">&nbsp;</p></td>
            <td class="tr8 td11"><p class="p5 ft2"><?php //echo Yii::t('backend','TOTAL');
                                                    ?></p></td>
            <td class="tr8 td12"><p class="p5 ft5">&nbsp;</p></td>
            <td class="tr8 td13" align="right"><p class="p16 ft2"> <?php //echo $currency; 
                                                                    ?>&nbsp;<?php //echo number_format($totalAmount + $taxAmount,2); 
                                                                            ?></p></td>
        </tr> -->

                <tr>
                    <td class="tr2 td9">
                        <p class="p5 ft5">&nbsp;</p>
                    </td>
                    <td class="tr2 td10">
                        <p class="p5 ft5">&nbsp;</p>
                    </td>
                    <td align="right" bgcolor="#122034" class="tr2 td11" style="padding-right: 18px; padding-top: 10px; padding-bottom: 10px;">
                        <p class="p5 ft2" style="color: #ffffff;">
                            <?php
                            // if($receipt_type == 'QUOTE') {
                            //     echo Yii::t('backend','ESTIMATED FEE'); }
                            // elseif($receipt_type == 'INVOICE') {
                            //     echo Yii::t('backend','AMOUNT DUE');}
                            // elseif($receipt_type == 'RECEIPT') {
                            //     echo Yii::t('backend','AMOUNT PAID');}
                            echo translateBilingual('Total Amount');
                            ?>

                        </p>
                    </td>
                    <td class="tr2 td12" bgcolor="#122034">
                        <p class="p5 ft5">&nbsp;</p>
                    </td>
                    <!--            ft8-->
                    <td rowspan="1" bgcolor="#122034" class="tr9 td13" align="right" style="padding-right: 15px; padding-top: 10px; padding-bottom: 10px;">
                        <p class="p16 " style="color: #ffffff;"> <?php echo $currency; ?>&nbsp;<?php echo number_format($serviceFee + $governmentFee, 2); ?></p>
                    </td>
                </tr>
                <tr>
                    <td class="tr10 td9">
                        <p class="p5 ft9">&nbsp;</p>
                    </td>
                    <td class="tr10 td10">
                        <p class="p5 ft9">&nbsp;</p>
                    </td>
                    <td class="tr10 td11">
                        <p class="p5 ft9">&nbsp;</p>
                    </td>
                    <td class="tr10 td11">
                        <p class="p5 ft9">&nbsp;</p>
                    </td>
                    <td class="tr10 td12">
                        <p class="p5 ft9">&nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="tr10 td9">
                        <p class="p5 ft9">&nbsp;</p>
                    </td>
                    <td class="tr10 td10">
                        <p class="p5 ft9">&nbsp;</p>
                    </td>
                    <td align="right" valign="top" class="tr10 td11">
                        <p class="p5 ft2">Total In Words
                    </td>

                    <!--            <td class="tr10 td12"><p class="p5 ft9">&nbsp;</p></td>-->
                    <td colspan="2" class="tr10 td13" align="right" valign="top" style="padding-left: 10px; padding-right: 18px;">

                        <p class="p16 ft2" style="font-style: italic; color: #000000;">
                            <?php
                            // Define an array mapping numbers to their English words


                            // Function to convert a number to English words
                            function numberToWords($number)
                            {

                                $numberWords = [
                                    0 => 'zero',
                                    1 => 'one',
                                    2 => 'two',
                                    3 => 'three',
                                    4 => 'four',
                                    5 => 'five',
                                    6 => 'six',
                                    7 => 'seven',
                                    8 => 'eight',
                                    9 => 'nine',
                                    10 => 'ten',
                                    11 => 'eleven',
                                    12 => 'twelve',
                                    13 => 'thirteen',
                                    14 => 'fourteen',
                                    15 => 'fifteen',
                                    16 => 'sixteen',
                                    17 => 'seventeen',
                                    18 => 'eighteen',
                                    19 => 'nineteen',
                                    20 => 'twenty',
                                    30 => 'thirty',
                                    40 => 'forty',
                                    50 => 'fifty',
                                    60 => 'sixty',
                                    70 => 'seventy',
                                    80 => 'eighty',
                                    90 => 'ninety'
                                ];
                                //   global $numberWords;

                                if ($number < 21) {
                                    return $numberWords[$number];
                                } elseif ($number < 100) {
                                    $tens = $numberWords[10 * floor($number / 10)];
                                    $ones = $number % 10;
                                    return $ones ? $tens . '-' . $numberWords[$ones] : $tens;
                                } elseif ($number < 1000) {
                                    $hundreds = $numberWords[floor($number / 100)] . ' hundred';
                                    $remainder = $number % 100;
                                    return $remainder ? $hundreds . ' ' . numberToWords($remainder) : $hundreds;
                                } else {
                                    return 'number too large';
                                }
                            }

                            // Output the currency name and spelled out total amount
                            echo $model->currency->name . ' ' . ucwords(numberToWords(floor($taxAmount + $totalAmount)));

                            ?>
                        </p>
                    </td>
                </tr>
                <?php if ($template == 1): ?>
                    <tr>
                        <td class="tr11 td9">
                            <p class="p9 ft10" style="color: #122034;"><?php echo translateBilingual('Tax Summary'); ?></p>
                        </td>
                        <td class="tr11 td10">
                            <p class="p5 ft5">&nbsp;</p>
                        </td>
                        <td class="tr11 td11">
                            <p class="p5 ft5">&nbsp;</p>
                        </td>
                        <td class="tr11 td12">
                            <p class="p5 ft5">&nbsp;</p>
                        </td>
                        <td class="tr11 td13">
                            <p class="p5 ft5">&nbsp;</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="tr12 td9">
                            <p class="p5 ft11">&nbsp;</p>
                        </td>
                        <td class="tr12 td10">
                            <p class="p5 ft11">&nbsp;</p>
                        </td>
                        <td class="tr12 td11">
                            <p class="p5 ft11">&nbsp;</p>
                        </td>
                        <td class="tr12 td12">
                            <p class="p5 ft11">&nbsp;</p>
                        </td>
                        <td class="tr12 td13">
                            <p class="p5 ft11">&nbsp;</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        &nbsp;

        <?php if ($template == 1): ?>
            <table cellpadding="0" cellspacing="0" class="t0" style="margin-left: -22px;margin-right: -22px;">
                <tbody>

                    <tr bgcolor="#122034">
                        <td width="10" height="10" bgcolor="#122034"></td>
                        <td height="10" bgcolor="#122034"></td>
                        <td width="10" height="10" bgcolor="#122034"></td>
                        <td height="10" bgcolor="#122034"></td>
                        <td width="10" height="10" bgcolor="#122034"></td>
                        <td height="10" bgcolor="#122034"></td>
                        <td width="10" height="10" bgcolor="#122034"></td>
                        <td height="10" bgcolor="#122034"></td>
                        <td width="10" height="10" bgcolor="#122034"></td>
                    </tr>

                    <tr bgcolor="#122034">
                        <td width="10"></td>
                        <td valign="middle" bgcolor="#122034" align="left" class="tr5" style="padding-left: 10px;">
                            <span class="ft7" style="color: #ffffff; font-size: 9pt;"><?php echo translateBilingual('Tax Details'); ?></span>
                        </td>
                        <td width="10"></td>
                        <td valign="middle" bgcolor="#122034" align="center">
                            <span class="ft7" style="color: #ffffff; font-size: 9pt;"><?php echo translateBilingual('Taxable Amount') . ' (' . $currency . ')'; ?></span>
                        </td>
                        <td width="10"></td>
                        <td valign="middle" bgcolor="#122034" align="center">
                            <span class="ft7" style="color: #ffffff; font-size: 9pt;"><?php echo translateBilingual('Tax Amount') . ' (' . $currency . ')'; ?></span>
                        </td>
                        <td width="10"></td>
                        <td valign="middle" bgcolor="#122034" align="right" style="padding-right: 10px">
                            <span class="ft7" style="color: #ffffff; font-size: 9pt;"><?php echo translateBilingual('Total Amount') . ' (' . $currency . ')'; ?></span>
                        </td>
                        <td width="10"></td>
                    </tr>


                    <tr bgcolor="#122034">
                        <td width="10" height="10" bgcolor="#122034"></td>
                        <td height="10" bgcolor="#122034"></td>
                        <td width="10" height="10" bgcolor="#122034"></td>
                        <td height="10" bgcolor="#122034"></td>
                        <td width="10" height="10" bgcolor="#122034"></td>
                        <td height="10" bgcolor="#122034"></td>
                        <td width="10" height="10" bgcolor="#122034"></td>
                        <td height="10" bgcolor="#122034"></td>
                        <td width="10" height="10" bgcolor="#122034"></td>
                    </tr>



                    <tr>
                        <td></td>
                        <td class="tr5 td9" style="padding-left: 10px; padding-top: 10px;">
                            <p class="p9 ft0" style="font-weight: 300; "><?php echo Yii::t('backend', 'Standard Rate') . ' (' . $model->vat_rate . '%)'; ?></p>
                        </td>
                        <!--                <td class="tr5 td9" align="center"><p class="p5 ft5">&nbsp;</p></td>-->
                        <td></td>
                        <td class="tr5 td12" align="center" style="padding-top: 10px;">
                            <p class="p9 ft0" style="font-weight: 300;"><?php echo number_format($totalAmount, 2) ?></p>
                        </td>
                        <!--                <td class="tr5 td9" align="center"><p class="p5 ft5">&nbsp;</p></td>-->
                        <td></td>
                        <td class="tr5 td12" align="center" style="padding-top: 10px;">
                            <p class="p9 ft0" style="font-weight: 300;"><?php echo number_format($taxAmount, 2) ?></p>
                        </td>
                        <!--                <td class="tr5 td9" align="center"><p class="p5 ft5">&nbsp;</p></td>-->
                        <td></td>
                        <td class="tr5 td12" align="right" style="padding-right: 10px; padding-top: 10px;">
                            <p class="p9 ft0" style="font-weight: 300;"><?php echo number_format($taxAmount + $totalAmount, 2) ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="tr5 td9" style="padding-left: 10px;padding-top: 10px;">
                            <p class="p9 ft0"><?php echo translateBilingual('Total'); ?></p>
                        </td>
                        <!--                <td class="tr5 td9" align="center"><p class="p5 ft5">&nbsp;</p></td>-->
                        <td></td>
                        <td class="tr5 td12" align="center" style="padding-top: 10px;">
                            <p class="p9 ft0"><?php echo number_format($totalAmount, 2) ?></p>
                        </td>
                        <!--                <td class="tr5 td9" align="center"><p class="p5 ft5">&nbsp;</p></td>-->
                        <td></td>
                        <td class="tr5 td12" align="center" style="padding-top: 10px;">
                            <p class="p9 ft0"><?php echo number_format($taxAmount, 2) ?></p>
                        </td>
                        <!--                <td class="tr5 td9" align="center"><p class="p5 ft5">&nbsp;</p></td>-->
                        <td></td>
                        <td class="tr5 td12" align="right" style="padding-right: 10px; padding-top: 10px;">
                            <p class="p9 ft0"><?php echo number_format($taxAmount + $totalAmount, 2) ?></p>
                        </td>
                        <td></td>
                    </tr>

                    <?php
                    // foreach(\backend\models\ReceiptItem::returnTaxValue() as $key=>$taxType){
                    //     $taxTypeAmount=0;$totalAmountWithVat=0;
                    //     foreach ($receiptModel->receiptItems as $item) {
                    //         // Add save tax amount type
                    //         if($item->vat==$key) {
                    //             $taxTypeAmount+=$item->returnSubTotal() - $item->price;
                    //             $totalAmountWithVat+=$item->returnSubTotal();
                    //         }
                    //     }

                    //     $taxTypeAmount = number_format($taxTypeAmount, 2);
                    //     $totalAmountWithVat = number_format($totalAmountWithVat, 2);
                    ?>
                    <!-- <tr>
                <td class="tr4 td9" style="padding-left: 22px"><p class="p19 ft2"><?php //if (is_int($taxType)) {
                                                                                    // echo Yii::t('backend','VAT').' @ '.Yii::t('backend',$taxType) . '%';
                                                                                    // } else echo Yii::t('backend','VAT').' @ '.Yii::t('backend',$taxType);
                                                                                    ?></p></td>
                <td class="tr4 td10" align="center"><p class="p5 ft5">&nbsp;</p></td>
                <td class="tr4 td11" align="center"><p class="p22 ft2"><?php //echo $taxTypeAmount
                                                                        ?></p></td>
                <td class="tr4 td12" align="center"><p class="p5 ft5">&nbsp;</p></td>
                <td class="tr4 td13" align="center"><p class="p21 ft2"><?php //echo $totalAmountWithVat
                                                                        ?></p></td>

            </tr> -->
                    <?php
                    // }
                    ?>
                </tbody>
            </table>
        <?php endif; ?>
        &nbsp;
    </div>
    <!--    <div class="footerfix">-->
    <!--    --><?php //if(!empty($receiptModel->organisation->receipt_note)): 
                ?>
    <!--        --><?php //echo $receiptModel->organisation->receipt_note; 
                    ?>
    <!---->
    <!--    --><?php //endif; 
                ?>
    </div>
<?php } ?>

<!-- new Layout-->
<?php //Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset'] = false;
?>
<?php // Yii::$app->assetManager->bundles['common\assets\AdminLte'] = false;
?>


<!-- Adding additional fields at the bottom: Case number, client entity name, assignee name, client authorizer, po number -->
<div style="position: absolute; bottom: 65px !important;">
    <div style="color: #122034; text-transform: uppercase; font-size: 9pt;">
        <?php
        $case = Cases::findOne($model->case_id);
        $caseNumber = $case->case_number;
        // $clientName = $model->set_client_name;
        ?>
        Case Number: <?php echo $caseNumber; ?>
    </div>
    <div style="color: #122034; text-transform: uppercase; font-size: 9pt;">
        <?php
        $case = Cases::findOne($model->case_id);
        $clientName = $case->client->client_name;
        ?>
        Client Name: <?php echo $clientName; ?>
    </div>
    <div style="color: #122034; text-transform: uppercase; font-size: 9pt;">
        <?php
        $applicant = backend\models\Applicant::findOne($case->applicant_id);
        $applicantLabelsFlipped = array_flip($applicant->attributeLabels());

        if (isset($case->client_entity)) {
            $clientEntity = \backend\models\ClientEntity::findOne($case->client_entity)->name;
        }
        ?>
        <?php if (isset($clientEntity)): ?>
            Client Entity Name: <?php echo $clientEntity; ?>
        <?php endif; ?>
    </div>

    <div style="color: #122034; text-transform: uppercase; font-size: 9pt;">

        <?php
        $case = Cases::findOne($model->case_id);
        if (!empty($case->client_case_worker_id) && $case->clientCaseWorker)
            $clientCaseworker =  $case->clientCaseWorker->username;
        ?>

        <?php if (isset($clientCaseworker)): ?>
            Client Case worker: <?php echo $clientCaseworker; ?>
        <?php else: ?>
            Client Case worker:-Client Case Worker not assigned-
        <?php endif; ?>
    </div>


    

        <div style="color: #122034; text-transform: uppercase; font-size: 9pt;">
        <?php
        $case = Cases::findOne($model->case_id);
        if (!empty($case))
            $clientbillingEntity =  $case->client_billing_entity;
        ?>
        <?php if (isset($clientbillingEntity)): ?>
            Client Billing Entity: <?php echo $clientbillingEntity; ?>

        <?php endif; ?>
        </div>
    



    
    <div style="color: #122034; text-transform: uppercase; font-size: 9pt;">

        <?php
        if (array_key_exists('Assignee Name', $applicantLabelsFlipped)) {
            $assigneeNameColumnName = $applicantLabelsFlipped['Assignee Name'];
        }
        ?>
        <?php if (isset($assigneeNameColumnName) && isset($applicant->$assigneeNameColumnName)): ?>
            Assignee Name: <?php echo $applicant->$assigneeNameColumnName; ?>
        <?php endif; ?>
    </div>
    <div style="color: #122034; text-transform: uppercase; font-size: 9pt;">
        <?php
        if (array_key_exists('PO Number', $applicantLabelsFlipped)) {
            $poNumberColumnName = $applicantLabelsFlipped['PO Number'];
        }
        ?>
        <?php if (isset($poNumberColumnName) && isset($applicant->$poNumberColumnName)): ?>
            PO Number: <?php echo $applicant->$poNumberColumnName; ?>
        <?php endif; ?>
    </div>

</div>
</div>
<style>
   
    .page-break {
        page-break-before: always;
        margin: 0;
        padding: 0;
    }

   
    .page-header {
        margin-bottom: 10px; /* Add space below the header */
        padding: 0;
        font-family: Arial, sans-serif;
    }

    .page-header p {
        margin: 5px 0; 
        font-size: 12pt;
        color: #333333;
    }

    /* Image Container Styling */
    .image-container {
        margin: 0; 
        padding: 0;
        width: 100%; 
        height: auto; 
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .image-container img {
        max-width: 100%; 
        max-height: 90vh; 
        object-fit: contain; 
        display: block;
    }
</style>

<?php foreach ($fileUploads as $uploads) {
    $extension = pathinfo($uploads['file_url'], PATHINFO_EXTENSION);
    if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) { 
        $totalAmount = $uploads['quantity'] * $uploads['price']; ?>
        
        <div class="page-break">
            <!-- Page Header -->
            <div class="page-header">
                <p><strong>Description:</strong> <?= htmlspecialchars($uploads['description']) ?></p>
                <p><strong>Amount:</strong> <?= number_format($totalAmount, 2) ?></p>
            </div>

            <!-- Image Container -->
            <div class="image-container">
                <img src="<?= htmlspecialchars($uploads['file_url']) ?>" alt="Attachment">
            </div>
        </div>

    <?php }
} ?>


<!-- Latest-->


<?php $this->registerCss('

 table td {
    border: 0!important;
}
        body {margin-top: 0px;margin-left: 0px;}

//        #page_1 {position:relative; overflow: hidden;margin: 51px 0px 26px 0px;padding: 0px;border: none;width: 792px;}
//        
//
//        #page_1 #p1dimg1 {position:absolute;top:0px;z-index:1;left: 420px;}




        .ft0{font: bold 13px \\\'Helvetica\\\';line-height: 16px; font-size: 10pt;}
        .ft1{font: 13px \\\'Helvetica\\\';line-height: 18px;}
        .ft2{font: 13px \\\'Helvetica\\\';line-height: 16px;}
        .ft3{font: 13px \\\'Helvetica\\\';line-height: 19px;}
        .ft4{font: 27px \\\'Helvetica\\\';color: #122034;line-height: 32px;}
        .ft5{font: 1px \\\'Helvetica\\\';line-height: 1px;}
        .ft6{font: 1px \\\'Helvetica\\\';line-height: 7px;}
        .ft7{font: 12px \\\'Helvetica\\\';color: #122034;line-height: 15px; display: block; text-indent: 15px;}

        .ft8{font: bold 18px \\\'Helvetica\\\';line-height: 24px;}
        .ft9{font: 1px \\\'Helvetica\\\';line-height: 11px;}
        .ft10{font: 15px \\\'Helvetica\\\';line-height: 17px;}
        .ft11{font: 1px \\\'Helvetica\\\';line-height: 2px;}

//        .p0{text-align: left;padding-left: 35px;margin-top: 0px;margin-bottom: 0px;}
//        .p1{text-align: left;padding-left: 35px;padding-right: 653px;margin-top: 4px;margin-bottom: 0px;}
//        .p2{text-align: left;padding-left: 35px;margin-top: 1px;margin-bottom: 0px;}
//        .p3{text-align: left;padding-left: 35px;padding-right: 550px;margin-top: 2px;margin-bottom: 0px;}
//        .p4{text-align: left;padding-left: 38px;margin-top: 16px;margin-bottom: 0px;}
//        .p5{text-align: left;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}

//        .p6{text-align: right;padding-right: 86px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p7{text-align: right;padding-right: 64px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p8{text-align: right;padding-right: 92px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p9{text-align: left;padding-left: 42px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p10{text-align: left;padding-left: 47px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p11{text-align: left;padding-left: 46px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p12{text-align: left;padding-left: 52px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p13{text-align: right;padding-right: 14px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p14{text-align: left;padding-left: 21px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p15{text-align: right;padding-right: 13px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p16{text-align: right;padding-right: 42px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p17{text-align: left;padding-left: 29px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p18{text-align: left;padding-left: 38px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p19{text-align: right;padding-right: 124px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p20{text-align: left;padding-left: 22px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p21{text-align: right;padding-right: 43px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
//        .p22{text-align: left;padding-left: 20px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
        .p23{text-align: left;/*padding-left: 42px;*/margin-top: 10px;margin-bottom: 0px;}
        .p24{text-align: left;/*padding-left: 42px;*/margin-top: 14px;margin-bottom: 0px;}
        .p25{text-align: left;/*padding-left: 42px;*/margin-top: 0px;margin-bottom: 0px;}
        .p26{text-align: left;/*padding-left: 42px;*/margin-top: 2px;margin-bottom: 0px;}

        .td0{padding: 0px;margin: 0px;width: 472px;vertical-align: bottom;}
        .td1{padding: 0px;margin: 0px;width: 220px;vertical-align: bottom;}
        .td2{border-bottom: #122034 1px solid;padding: 0px;margin: 0px;width: 472px;vertical-align: bottom;}
        .td3{border-bottom: #122034 1px solid;padding: 0px;margin: 0px;width: 220px;vertical-align: bottom;}
        .td4 {border-bottom: #e4d2d3 1px solid;padding: 0px;margin: 0px;width: 354px;vertical-align: middle;background: #e4d2d3;padding-top: 5px; padding-bottom: 5px;}
        .td5{border-bottom: #e4d2d3 1px solid;padding: 0px;margin: 0px;width: 38px;vertical-align: bottom;background: #e4d2d3;}
        .td6{border-bottom: #e4d2d3 1px solid;padding: 0px;margin: 0px;width: 139px;vertical-align: bottom;background: #e4d2d3; }
        .td7{border-bottom: #e4d2d3 1px solid;padding: 0px;margin: 0px;width: 91px;vertical-align: middle;background: #e4d2d3;padding-top: 5px; padding-bottom: 5px;}
        .td8{border-bottom: #e4d2d3 1px solid;padding: 0px;margin: 0px;width: 146px;vertical-align: middle;background: #e4d2d3;padding-top: 5px; padding-bottom: 5px;}
//        .td9{padding: 0px;margin: 0px;width: 354px;vertical-align: bottom;}
//        .td10{padding: 0px;margin: 0px;width: 38px;vertical-align: bottom;}
        .td11{padding: 0px;margin: 0px;width: 139px;vertical-align: bottom;}
//        .td12{padding: 0px;margin: 0px;width: 91px;vertical-align: bottom;}
        .td13{padding: 0px;margin: 0px;width: 146px;vertical-align: bottom;}

        .tr0{height: 20px;}
        .tr1{height: 20px;}
        .tr2{height: 20px;}
//        .tr3{height: 7px;}
        .tr4{height: 21px;}
        .tr5{height: 22px;}
//        .tr6{height: 16px;}
//        .tr7{height: 19px;}
//        .tr8{height: 51px;}
//        .tr9{height: 29px;}
//        .tr10{height: 11px;}
//        .tr11{height: 34px;}
//        .tr12{height: 2px;}

.h10{height: 10px;}

        .t0{width: 692px;/*margin-left: 38px;*/margin-top: 10px;font: 13px \\\'Helvetica\\\';}
        .t1{width: 768px;margin-top: 62px;font: 13px \\\'Helvetica\\\';}
.padtop{padding-top: 10px;}
 .vatitem{display: none}
 .tr5,.tr6{padding-left: 22px;}
 
 .text__right{
    text-align: right;
 }
 .w50{
    width: 50%;
//    width: calc(692px / 2);
 }
 .w40{widthL 40%}
 .pl5{padding-left: 5px;}

'); ?>