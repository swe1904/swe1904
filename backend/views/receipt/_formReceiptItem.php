<div class="form-group" id="add-receipt-item" style="margin-top: 10px">
    <?php

    use backend\models\ReceiptItemSection;
    use kartik\grid\GridView;
    use kartik\builder\TabularForm;
    use yii\data\ArrayDataProvider;
    use yii\helpers\Html;
    use yii\widgets\Pjax;
    use app\components\GlobalConstant;
    ?>

<input type="hidden" id="hiddenVatRate" value="<?= isset($orgVatRate) ? $orgVatRate : 0.00 ?>" />
    <?php

    $receiptItemSections = [];

    //if coming from add-receipt-item, then only add to section on which add button clicked
    if (isset($sectionId)) {
        $sectionModel = ReceiptItemSection::find()->where(['id' => $sectionId])->one();
        if (!empty($sectionModel))
            $receiptItemSections[] = $sectionModel;
    }
    //else first time load all sections
    else
        $receiptItemSections = ReceiptItemSection::find()->all();


    foreach ($receiptItemSections as $section) {

        //Get all models belonging to this section
        $sectionReceiptItems = [];
        foreach ($row as $item) {
            if ($item['section_id'] == $section->id) {
                $sectionReceiptItems[] = $item;
            }
        }

        if ($section->id == GlobalConstant::RECEIPT_SERVICE_FEE_SECTION_ID) {
            $attribute = [
                "id" => ['type' => TabularForm::INPUT_HIDDEN, 'columnOptions' => ['hidden' => true]],
                "section_id" => ['type' => TabularForm::INPUT_HIDDEN, 'columnOptions' => ['hidden' => true]],
                'description' => ['type' => TabularForm::INPUT_TEXT, 'columnOptions' => ['width' => '60%']],
                'quantity' => ['type' => TabularForm::INPUT_TEXT, 'options' => ['class' => 'form-control section-quantity'], 'columnOptions' => ['width' => '10%']],
                'price' => ['type' => TabularForm::INPUT_TEXT, 'label' => 'Unit Price', 'options' => ['class' => 'form-control section-price', 'step' => '0.01'], 'columnOptions' => ['width' => '10%']],
                "vat_rate" => ['type' => TabularForm::INPUT_HIDDEN, 'columnOptions' => ['hidden' => true], 'options' => ['class' => 'vat_rate_input']],

                'vat' => ['type' => TabularForm::INPUT_DROPDOWN_LIST, 'label' => 'VAT Rate', 'options' => ['class' => 'form-control vat-type', 'step' => '0.01'],  'items' => array_merge(['' => 'Select VAT Rate'], GlobalConstant::VAT_TYPE_ARRAY), 'columnOptions' => ['width' => '10%']],

                'price_sub_total' => ['type' => TabularForm::INPUT_TEXT, 'label' => 'Price Sub Total', 'options' => ['class' => 'form-control section-price-sub-total', 'step' => '0.01'], 'columnOptions' => ['width' => '10%']],
                'del' => [
                    'type' => 'raw',
                    'label' => '',
                    'value' => function ($model, $key) {
                        return Html::hiddenInput('Children[' . $key . '][id]', (!empty($model['id'])) ? $model['id'] : "") .
                            Html::a('<i class="fa fa-close text-danger"></i>', '#', ['title' =>  'Delete', 'onClick' => 'delRowReceiptItem(' . $key . ',' . $model['section_id'] . '); return false;', 'id' => 'receipt-item-del-btn', 'class' => '']);
                    },
                ],
            ];
        } elseif ($section->id == GlobalConstant::RECEIPT_GOVT_FEE_SECTION_ID) {
            $attribute = [
                "id" => ['type' => TabularForm::INPUT_HIDDEN, 'columnOptions' => ['hidden' => true]],
                "section_id" => ['type' => TabularForm::INPUT_HIDDEN, 'columnOptions' => ['hidden' => true]],
                'description' => ['type' => TabularForm::INPUT_TEXT, 'columnOptions' => ['width' => '60%']],
                'quantity' => ['type' => TabularForm::INPUT_TEXT, 'options' => ['class' => 'form-control section-quantity'], 'columnOptions' => ['width' => '10%']],
                'price' => ['type' => TabularForm::INPUT_TEXT, 'label' => 'Unit Price', 'options' => ['class' => 'form-control section-price', 'step' => '0.01'], 'columnOptions' => ['width' => '10%']],
                "vat_rate" => ['type' => TabularForm::INPUT_HIDDEN, 'columnOptions' => ['hidden' => true], 'options' => ['class' => 'vat_rate_input']],


                // 'vat' => ['type' => TabularForm::INPUT_DROPDOWN_LIST, 'label' => 'VAT', 'items'=> GlobalConstant::RECEIPT_ITEM_TAX_ARRAY, 'columnOptions'=>['width'=>'10%']],
                // DropZone field for file upload
                //                'additional_attachments' => [
                //                    'type' => 'raw',
                //                    'label' => 'Attachments',
                //                    'value' => function($model, $key) {
                //                        if (empty($model['additional_attachments'])) {
                //                            $model['additional_attachments'] = \Yii::$app->security->generateRandomString(8) . str_replace('.', '', microtime(true));
                //                        }
                //
                //                        return Html::hiddenInput('Children[' . $key . '][additional_attachments]', $model['additional_attachments']) .
                //                            \common\components\DropZone::widget([
                //                                "id" => "drop_zone_" . $key,
                //                                "dropzoneContainer" => "drop_zone_container_" . $key,
                //                                "previewsContainer" => "drop_zone_preview_container_" . $key,
                //                                "options" => [
                //                                    "url" => \yii\helpers\Url::to(["mii/file-upload/upload-temp-file", "session_id" => $model['additional_attachments']]),
                //                                    "paramName" => "attachment",
                //                                    "maxFilesize" => "20", // Max file size in MB
                //                                    "addRemoveLinks" => true,
                //                                ],
                //                                "clientEvents" => [
                //                                    "complete" => "function(file){
                //                                        handleFileUpload();
                //                                    }",
                //                                    "removedfile" => "function(file){
                //                                        removeFile(file);
                //                                    }",
                //                                ],
                //                            ]);
                //                    },
                //                    'columnOptions' => ['width' => '20%'],
                //                ],
                'del' => [
                    'type' => 'raw',
                    'label' => '',
                    'value' => function ($model, $key) {
                        return Html::hiddenInput('Children[' . $key . '][id]', (!empty($model['id'])) ? $model['id'] : "") .
                            Html::a('<i class="fa fa-close text-danger"></i>', '#', ['title' =>  'Delete', 'onClick' => 'delRowReceiptItem(' . $key . ',' . $model['section_id'] . '); return false;', 'id' => 'receipt-item-del-btn', 'class' => '']);
                    },
                ],
            ];
        }


        //****display results start****
        $dataProvider = new ArrayDataProvider([
            'allModels' => $sectionReceiptItems,
            'pagination' => [
                'pageSize' => -1
            ]
        ]);
        echo TabularForm::widget([
            'dataProvider' => $dataProvider,
            'formName' => 'ReceiptItem' . '-' . $section->id,
            'checkboxColumn' => false,
            'actionColumn' => false,
            'attributeDefaults' => [
                'type' => TabularForm::INPUT_TEXT,
            ],
            'attributes' => $attribute,
            'gridSettings' => [
                'id' => 'section-' . $section->id,
                'panel' => [
                    'heading' => false,
                    'type' => GridView::TYPE_DEFAULT,
                    'before' => "<h4>$section->name</h4>",
                    'footer' => false,
                    'after' => '<div style="float: right; margin-right:20%; margin-top: 3px;"> Total: '
                        . Html::input('text', null, '', ['id' => str_replace(' ', '-', $section->name) . '-Price'])
                        . '</div> '
                        . Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                            'type' => 'button',
                            'class' => 'btn btn-rounded btn-success mr-10',
                            'onClick' => 'addRowReceiptItem(' . $section->id . ', getVatRate())'
                        ]),
                ]
            ]
        ]);

        //****display results ends****

    }

    echo  "    </div>\n\n";
    ?>
    <script>
        $(document).ready(function() {
            setTotalSums();
        });

        var originalVatRate = '';

        // On page load, set the selected option based on the hidden VAT rate value
        $('.vat-type').each(function() {
            
            let vatRate = $('#hiddenVatRate').val();
         
            if(!vatRate)
            {   
                vatRate = $('#vat-type-display-input').val();
            }
            vatRate = parseFloat(vatRate).toFixed(2);
          
            updateDropdownDisplay($(this), vatRate);
        });

        // Function to update dropdown text display
        function updateDropdownDisplay(dropdown, vatRate) {
            dropdown.find('option').each(function() {
                let optionText = $(this).text().trim();

                if (optionText === "STANDARD RATE") {
                    // Set display for STANDARD RATE, showing the VAT rate if it's non-zero
                    let displayText = vatRate && vatRate !== "0.00" ? `STANDARD RATE - ${vatRate} %` : "STANDARD RATE - 0.00 %";
                    $(this).text(displayText);
                } else if (optionText === "ZERO-RATED") {
                    // Set display for ZERO-RATED with 0.00%
                    $(this).text("ZERO-RATED - 0.00 %");
                } else if (optionText === "EXEMPTED") {
                    // Set display for EXEMPTED with 0.00%
                    $(this).text("EXEMPTED - 0.00 %");
                }
            });
        }

        // On change, update the selected option display
        $('.vat-type').on('change', function() {
            let selectedVatType = $(this).val();
            let vatRateInput = $(this).closest('tr').find('.vat_rate_input');
            let vatRate = $('#hiddenVatRate').val();
            if(!vatRate)
            {
                  vatRate = $('#vat-type-display-input').val(); 
            }
            if (selectedVatType == 1 || selectedVatType == 2) {
                vatRateInput.val(0);
            } else {
                vatRateInput.val(vatRate);
            }

            setTotalSums();
        });







        var receiptitem1_price = 0;
        var receiptitem2_price = 0;


        function setTotalSums() {
            receiptitem1_price = 0;
            receiptitem2_price = 0;
            var grandtotalprice = 0;

            /*Type-1*/
            /* id starts receiptitem-1 and end with price*/
            $('input[id^="receiptitem-1"][id $="price"]').each(function(el) {

                let quantity = parseInt($('input[id^="receiptitem-1"][id $="quantity"]')[el].value) || 0;

                // let vatRatePercentage = parseFloat($('input[name^="ReceiptItem-1"][name$="[vat_rate]"]')[el].value) || 0;
               
                let vatSelectElement = $('select[id^="receiptitem-1"][id$="vat"]')[el];
                let selectedText = vatSelectElement.options[vatSelectElement.selectedIndex].text;
                let vatRatePercentage = parseFloat(selectedText.match(/(\d+\.\d+)/)?.[0]) || 0;
             
                let price = parseFloat(this.value) || 0;
                let vatRateAmount = (price * vatRatePercentage) / 100;                
                let priceSubTotal = (price + vatRateAmount) * quantity;


                $(this).closest('tr').find('input[id^="receiptitem-1"][id$="price_sub_total"]').val(priceSubTotal.toFixed(2));
                if (!isNaN(priceSubTotal)) {
                    receiptitem1_price += priceSubTotal;
                }

            });
            /* Type-2*/
            /* id starts receiptitem-2 and end with price*/
            $('input[id^="receiptitem-2"][id $="price"]').each(function(el) {
                let quantity = $('input[id^="receiptitem-2"][id $="quantity"]');
                if ((this.value && this.value !== "" && !isNaN(this.value)) && (quantity[el].value && quantity[el].value !== "" && !isNaN(quantity[el].value)))


                    receiptitem2_price += parseInt(quantity[el].value) * (this.value);


            });
            var grandtotalprice = receiptitem1_price + receiptitem2_price;
            receiptitem1_price = receiptitem1_price.toFixed(2);

            receiptitem2_price = receiptitem2_price.toFixed(2);
            grandtotalprice = grandtotalprice.toFixed(2);

           

            $('#Services-Fees-Price').val(receiptitem1_price);
            $('#Government-Fees-Price').val(receiptitem2_price);
            $('#receipt-grand-total').val(grandtotalprice);
        }

        $('input[id^="receiptitem"]').on("change", function(event) {
            setTotalSums();
        });
        /* id starts receiptitem-1 and end with price*/
        $('input[id^="receiptitem-1"][id $="price"]').each(function(el) {
            alert(el.val());
        })


        //        function handleFileUpload() {
        //        var length = $('.dz-hidden-input').length;
        //        if (length === 0) {
        //            $('#submit-btn').attr('disabled', true);
        //        } else {
        //            $('#submit-btn').attr('disabled', false);
        //        }
        //        }
        //        function removeFile(file) {
        //        $.ajax({
        //            'type': 'POST',
        //            'url': '<?php //echo \yii\Helpers\Url::to(['cases/remove-temp-file']); 
                                ?>//',
        //            'data': {
        //            sessionID: '<?php //echo $model->additional_attachments; 
                                    ?>//',
        //            fileName: file.name,
        //            },
        //            'success': function(response) {
        //            var responseData = JSON.parse(response);
        //            if (responseData.code == 1) {
        //                toastr.success(responseData.message);
        //            }
        //            }
        //        })
        //        }
        //
        //        function attachListeners() {
        //        $('.delete-file').on('click', function() {
        //            let fileID = $(this).attr('data-id');
        //            $(this).html('<div class="fa fa-circle-o-notch fa-spin"></div>');
        //            $.ajax({
        //            type: 'POST',
        //            url: '<?php //echo \yii\Helpers\Url::to(['applicant/delete-file']) 
                            ?>//',
        //            data: {
        //                fileID: fileID
        //            },
        //            success: function (response) {
        //                let responseData = JSON.parse(response);
        //                if (responseData.code === 1) {
        //                toastr.success(responseData.message);
        //                $.pjax.reload({container: '#attach-documents-pjax', timeout: 3000, async: false});
        //                } else {
        //                toastr.error(responseData.message);
        //                }
        //                $('.delete-file').html('<i class="fa fa-close" style="color: #d20511;"></i>');
        //            }
        //            })
        //        })
        //        }
        //
        //
        //
        //        $(document).ready(attachListeners)
        //        $(document).ready(removeFile)
        //        handleFileUpload()
        //        $(document).on('pjax:success', attachListeners);
        //        $(document).on('pjax:success', removeFile);

        // get vat rate
        function getVatRate() {
            var vatRateElement = document.getElementById('hiddenVatRate');
            vat = <?= json_encode($orgVatRate) ?>;

            if (!vatRateElement) {
            return vat; // Ensure the PHP value is properly echoed in JavaScript
           }
            return vatRateElement ? vatRateElement.value : null;
        }
        var vatRate = getVatRate();
    </script>