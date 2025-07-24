<div class="form-group" id="add-slip-item" style="margin-top: 10px">
    <?php
    use backend\models\SlipItemSection;
    use kartik\grid\GridView;
    use kartik\builder\TabularForm;
    use yii\data\ArrayDataProvider;
    use yii\helpers\Html;
    use yii\widgets\Pjax;


    $slipItemSections = [];

    //if coming from add-slip-item, then only add to section on which add button clicked
    if(isset($sectionId)){
        $sectionModel = SlipItemSection::find()->where(['id' => $sectionId])->one();
        if(!empty($sectionModel))
            $slipItemSections[] = $sectionModel;
    }
    //else first time load all sections
    else
        $slipItemSections = SlipItemSection::find()->all();


    foreach ($slipItemSections as $section){

        //Get all models belonging to this section
        $sectionSlipItems = [];
        foreach($row as $item){
            if($item['section_id'] == $section->id){
                $sectionSlipItems[] = $item;
            }
        }

        //****display results start****

        $dataProvider = new ArrayDataProvider([
            'allModels' => $sectionSlipItems,
            'pagination' => [
                'pageSize' => -1
            ]
        ]);

        echo TabularForm::widget([
            'dataProvider' => $dataProvider,
            'formName' => 'SlipItem'.'-'.$section->id,
            'checkboxColumn' => false,
            'actionColumn' => false,
            'attributeDefaults' => [
                'type' => TabularForm::INPUT_TEXT,
            ],
            'attributes' => [
                "id" => ['type' => TabularForm::INPUT_HIDDEN, 'columnOptions' => ['hidden'=>true]],
                "section_id" => ['type' => TabularForm::INPUT_HIDDEN, 'columnOptions' => ['hidden'=>true]],
                'description' => ['type' => TabularForm::INPUT_TEXT, 'columnOptions'=>['width'=>'40%']],
                'value' => ['type' => TabularForm::INPUT_TEXT, 'columnOptions'=>['width'=>'10%']],
                'notes' => ['type' => TabularForm::INPUT_TEXT, 'columnOptions'=>['width'=>'30%']],
                // 'vat' => ['type' => TabularForm::INPUT_DROPDOWN_LIST, 'label' => 'VAT', 'items'=>\app\components\GlobalConstant::RECEIPT_ITEM_TAX_ARRAY, 'columnOptions'=>['width'=>'10%']],
                'del' => [
                    'type' => 'raw',
                    'label' => '',
                    'value' => function($model, $key) {
                        return
                            Html::hiddenInput('Children[' . $key . '][id]', (!empty($model['id'])) ? $model['id'] : "") .
                            Html::a('<i class="fa fa-close text-danger"></i>', '#', ['title' =>  'Delete', 'onClick' => 'delRowSlipItem(' . $key . ',' . $model['section_id'] . '); return false;', 'id' => 'slip-item-del-btn','class'=>'']);
                    },
                ],
            ],
            'gridSettings' => [
                'id' => 'section-'.$section->id,
                'panel' => [
                    'heading' => false,
                    'type' => GridView::TYPE_DEFAULT,
                    'before' => "<h4>$section->name</h4>",
                    'footer' => false,
                    'after' => '<div style="float: right; margin-right:20%; margin-top: 3px;"> Total: '.Html::input('text',null,'',['id'=>str_replace(' ','-',$section->name).'-Value']) . '</div> ' .Html::button('<i class="glyphicon glyphicon-plus"></i>' . '', ['type' => 'button', 'class' => 'btn btn-rounded btn-success mr-10', 'onClick' => 'addRowSlipItem('.$section->id.')']),
                ]
            ]
        ]);

        //****display results ends****

    }

    echo  "    </div>\n\n";
    ?>
    <script>
        var slipitem1_value=0;
        var slipitem2_value=0;


        function setTotalSums(){
            slipitem1_value = 0;
            slipitem2_value = 0;
            /*Type-1*/
            /* id starts slipitem-1 and end with value*/
            $('input[id^="slipitem-1"][id $="value"]').each(function (el) {
                if(this.value && this.value !== "" && !isNaN(this.value))
                    slipitem1_value +=parseInt(this.value);
            });
            /* Type-2*/
            /* id starts slipitem-2 and end with value*/
            $('input[id^="slipitem-2"][id $="value"]').each(function (el) {
                if(this.value && this.value !== "" && !isNaN(this.value))
                    slipitem2_value +=parseInt(this.value);
            });

            $('#Deduction-Value').val(slipitem1_value);
            $('#Bonus-Value').val(slipitem2_value);
            const currentSalary = $('#slip-current_salary').val()
            $('#slip-final_salary').val(currentSalary - slipitem1_value + slipitem2_value);
        }

        $('input[id^="slipitem"]').on("change", function(event) {
            setTotalSums();
        });
        /* id starts slipitem-1 and end with value*/
        $('input[id^="slipitem-1"][id $="value"]').each(function (el) {
            // alert(el.val());
        })

    </script>
