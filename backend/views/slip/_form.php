<?php

use app\components\GlobalConstant;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\Helpers\ArrayHelper;
use backend\models\Employee;
use backend\models\SlipItem;
use common\models\Organisation;

/* @var $this yii\web\View */
/* @var $model backend\models\Slip */
/* @var $form yii\widgets\ActiveForm */
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END,
    'viewParams' => [
        'value' => \yii\helpers\Json::encode($model->slipItems),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);

?>


    <div class="slip-form">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'options' => [
                'options' => ['class' => 'form-group invisible']
            ],
        ],]); ?>

        <div class="col-md-12">
            <div class="col-md-4">
                <div class="form-group">

                <?php $organizationId = Organisation::find()->where(['user_id'=>Yii::$app->user->identity->id])->one()->id;?>
                    <?=$form->field($model,'employee_id')->dropDownList(
                        ArrayHelper::map(Employee::find()->where(['organisation_id'=>$organizationId])->all(),'id','name'),
                        [
                            'prompt' => 'select Employee',
                            'readonly' => $model->isNewRecord ? false : true,
                        ]
                    )->label('Employee');
                    ?>



                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <?= $form->field($model, 'payslip_month')->dropDownList(GlobalConstant::MONTHS_DROPDOWN,['options'=>[date("M")=>['Selected'=>true]]]); ?>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <?= $form->field($model, 'payslip_year')->dropDownList([date("Y")-1=>date("Y")-1,date("Y")=>date("Y"),date("Y")+1=>date("Y")+1,date("Y")+2=>date("Y")+2,date("Y")+3=>date("Y")+3,date("Y")+4=>date("Y")+4,date("Y")+5=>date("Y")+5]) ?>
                </div>
            </div>

            <!-- <div class="col-md-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php //if ($model->isNewRecord) $model->By_Month = 1; ?>
                            <?php //echo $form->field($model, 'By_Month')->checkbox(['checked' => true]); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <?php //echo $form->field($model, 'By_Date')->checkbox(); ?>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>


        <div class="col-md-12">
            <!-- <div class="col-md-4">
                <div class="form-group">

                    <?//= $form->field($model, 'leaves_left')->textInput() ?>

                </div>
            </div> -->

            <div class="col-md-4">
                <div class="form-group" style="z-index: 100;">
                    <?php 
                        echo $form->field($model, 'start_date')->widget(\kartik\date\DatePicker::classname(), [
                            'id' => 'start_date',
                            'pickerButton' => false,
                            'removeButton' => false,
                            'type' => 1,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy',
                            ],
                            'pluginEvents' => [
                                'changeDate' => "function(e) { changeDate(e) }",
                            ],
                        ]);
                    ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group" style="z-index: 100;">
                    <!-- <?//= $form->field($model, 'end_date')->textInput() ?> -->
                    <?php 
                        echo $form->field($model, 'end_date')->widget(\kartik\date\DatePicker::classname(), [
                            'id' => 'end_date',
                            'pickerButton' => false,
                            'removeButton' => false,
                            'type' => 1,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy',
                            ], 
                            'pluginEvents' => [
                                'changeDate' => "function(e) { changeDate(e) }",
                            ],
                        ]);
                    ?>
                </div>
            </div>
        </div>
       
        <div class="col-md-12">
            <div class="col-md-6">
                <div class="form-group">

                    <?= $form->field($model, 'leaves_taken')->textInput() ?>
                </div>
            </div>

            <!-- <div class="col-md-6">
                <div class="form-group">

                    <?//= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
                </div>
            </div> -->

            <div class="col-md-6">
                <div class="form-group">
                    <?= $form->field($model, 'leaves_accrued')->textInput() ?>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            


            <!-- <div class="col-md-6">
                <div class="form-group">
                    <?//= $form->field($model, 'deduction')->textInput() ?>
                </div>
            </div> -->
        </div>


        <!-- <div class="col-md-12">
            <div class="col-md-6">
                <div class="form-group">

                    <?//= $form->field($model, 'bonus')->textInput() ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">

                    <?//= $form->field($model, 'final_salary')->textInput() ?>
                </div>
            </div>

        </div> -->

        <div class="col-md-12">
            <div class="col-md-6">

                <div class="form-group">
                    <?php
                    $role = array('1' => 'Cash', '2' => 'Cheque/ Online Payment');
                    echo $form->field($model, 'payment_mode')->dropDownList($role, ['prompt' => 'Select Payment Mode']);
                    ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div id="slip-cheque_number", style="...">
                        <?= $form->field($model, 'cheque_number')->textInput() ?>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12"> -->
        <div class="col-md-12">
        <?php
        echo $this->render('_formSlipItem', [
            'row' => \yii\helpers\ArrayHelper::toArray($model->slipItems),
            // 'row' => []
        ]);
        ?>
        </div>

        <div class="col-md-12">
            <div class="col-md-6">
                    <div class="form-group">
                        <?= $form->field($model, 'current_salary')->textInput() ?>
                    </div>
            </div>
            <div class="col-md-6">
                    <div class="form-group">

                        <?= $form->field($model, 'final_salary')->textInput(['value' => '0']) ?>
                    </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-6">  
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>               
            </div>
        </div>


<?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<script>
</script>

<?php
$url = Yii::$app->urlManager->createUrl('slip/get-salary');
$script = <<< JS
   $(function(){        
/*var date = new Date(), y = date.getFullYear(), m = date.getMonth();
var firstDay = new Date(y, m, 1);
var lastDay = new Date(y, m + 1, 0);

$("#slip-start_date").datepicker("setDate",firstDay);
$("#slip-end_date").datepicker("setDate",lastDay);*/
 
//  $( "#slip-leaves_taken,#slip-leaves_left" ).bind("change",function()
 $( "#slip-leaves_taken" ).bind("change",function()
        {
            var leaves = parseFloat($("#slip-leaves_taken").val());
            var bonus = parseFloat($("#slip-bonus").val());
            var salary = parseFloat($("#slip-current_salary").val());
            if(leaves)
            {
                var deduction = (salary/30)*leaves;
                $('#slip-deduction').val(deduction.toFixed(2));
                var finalSalary = salary - deduction;
                if(bonus)
                    finalSalary = finalSalary + bonus;
                // $('#slip-final_salary').val(finalSalary.toFixed(2));

            }
            else{
                $('#slip-deduction').val('');
                var finalSalary = salary;
                if(bonus)
                    finalSalary = finalSalary + bonus;
                // $('#slip-final_salary').val(finalSalary.toFixed(2));
            }
            // var c=parseFloat($("#slip-current_salary").val());
            // var d=parseFloat($("#slip-leaves_taken").val());
            // var e=parseFloat($("#slip-leaves_left").val());

            // if (d>e){
            //     var f = (d-e)/22*c;
            //     $('#slip-deduction').val(f.toFixed(2));
            // }
            // else{
            //     var f=0;
            //     $('#slip-deduction').val(f.toFixed(2));
            // }
            //     var total = c-f;
            //     $('#slip-final_salary').val(total.toFixed(2));
    });
$( "#slip-bonus" ).bind("change",function()
        {
            var bonus = parseFloat($("#slip-bonus").val());
            var finSalary = parseFloat($('#slip-final_salary').val());
            console.log("Bonus", bonus);
            if(bonus)
            {
                var revisedSalary = finSalary + bonus;
                // $('#slip-final_salary').val(revisedSalary.toFixed(2));
            }
            else
            {
                var leaves= parseFloat($("#slip-leaves_taken").val());

                if(leaves!='')
                {
                    var salary = parseFloat($("#slip-current_salary").val());
                    var deduction = (salary/30)*leaves;
                    // $('#slip-deduction').val(deduction.toFixed(2));
                    var finalSalary = salary - deduction;
                    console.log("Final Salary : ", finalSalary);
                    // $('#slip-final_salary').val(finalSalary.toFixed(2));
                }
                else{
                    $('#slip-deduction').val('');
                    
                    $('#slip-final_salary').val('');
                }
            }
        });
 $( "#slip-deduction" ).bind("change",function()
        {
            var deduction = $('#slip-deduction').val();
            var salary = parseFloat($("#slip-current_salary").val());
            var bonus = parseFloat($("#slip-bonus").val());
            if(deduction)
            {                
                var finalSalary = salary - deduction;
                if(bonus)
                    finalSalary = finalSalary + bonus;
                // $('#slip-final_salary').val(finalSalary.toFixed(2));
            }
            else{                
                var finalSalary = salary;
                if(bonus)
                    finalSalary = finalSalary + bonus;
                // $('#slip-final_salary').val(finalSalary.toFixed(2));
            }

            // var c=parseFloat($("#slip-current_salary").val());
            // var d = parseFloat(e);
            // var e = $("#slip-leaves_left").val();
            // var g =$("#slip-leaves_taken").val();
            // var h=parseFloat(g);
            // if(e!=''){
            // e = parseFloat(e);
            // }
            // if(g!='')
            //     {
            //     g=parseFloat(g);
            //     }

            //  if(g=='' && e=='')
            // {

            // var f=parseFloat($("#slip-deduction").val());
            // var total=c-f;
            //  $('#slip-final_salary').val(total.toFixed(2));
            // }
            // else {

            // var f=parseFloat($("#slip-deduction").val());
            // var total=c-f;
            //  $('#slip-final_salary').val(total.toFixed(2));
            // }


});

$('#slip-employee_id').change(function(){
    var id = this.value;
  $.ajax({
            url:  '$url',
            type: 'POST',
             data: { empId: id },
             success: function(data) {
                     $('#slip-current_salary').val(data   );

             }
         });

})

$(function() {
    setTotalSums();
})

});




JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
<?php

$script = <<< JS
//  $("#slip-start_date").datepicker({
//             dateFormat: 'dd-mm-yy',
//             changeMonth: true,
//             changeYear: true,
//             yearRange: '1950:2025',
//         },
//         $.datepicker.regional["fr"],
//     );

  
//  $("#slip-end_date").datepicker({
//             dateFormat: 'dd-mm-yy',
//             changeMonth: true,
//             changeYear: true,
//             yearRange: '1950:2025',
//         },
//         $.datepicker.regional["fr"]
//     );

JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>





<?php
$urlsalary = Yii::$app->urlManager->createUrl('slip/get-salary');
$monthsDatesUrl = Url::to(['slip/months-dates']);
$slipAlreadyExistsUrl = Url::to(['slip/slip-already-exists']);
$payrollRecord = Url::to(['slip/get-payroll-record']);
$getLeavesByDate = Url::to(['slip/get-leaves-by-date']);
$slipID = $model->id;

$onPageLoadScript = "";
if($model->isNewRecord){
    $onPageLoadScript = "onMonthOrYearChange()";
}

$script = <<< JS
    //execute on load, but, only for create form
    $onPageLoadScript;

    function changeDate(e) {
        let startDate = (e.target.id === 'slip-start_date') ? e.target.value : $('#slip-start_date').val();
        let endDate = (e.target.id === 'slip-end_date') ? e.target.value : $('#slip-end_date').val();
        let month = $('#slip-payslip_month').val();
        let year = $('#slip-payslip_year').val();
        let slipID = '$slipID';

        $.ajax({
            url: '$getLeavesByDate',
            data: {'startDate': startDate, 'endDate': endDate, 'employeeID': $('#slip-employee_id').val(), 'month': month, 'year': year, 'slipID': slipID},
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
            },
            success: function(data, textStatus) {
                if (data.status === true && data.type === 'overlap') {
                    $('.alert-warning').html('Salary Dates for '+ data.employee_name + ' overlap with an existing slip made for them.');
                } 

                if (data.status === true && data.type === 'exists') {
                    $('.alert-warning').html("Salary slip for " + data.employee_name + " and month " + data.month + ', ' + data.year + ' already exists.');
                }

                if (data.status === true) {
                    $('.alert-warning').show();
                    $('#slip-deduction').val('');
                    $('#slip-final_salary').val('');
                    $("#slip-leaves_taken").val('')
                    $("#slip-bonus").val('')
                    return;
                }
                var absDays = data.absentDays;
                var salary;
                $('.alert-warning').hide();
                var id = $('#slip-employee_id').val();
                $.ajax({
                    url:  '$urlsalary',
                    type: 'POST',
                    data: { empId: id },
                    success: function(data) 
                    {                                            
                        salary = parseFloat(data);
                        var deduction = (salary/30)*absDays;
                        $("#slip-leaves_taken").val(absDays)
                        $('#slip-deduction').val(deduction.toFixed(2));
                        // var finalSalary = salary - deduction;
                        // $('#slip-final_salary').val(finalSalary.toFixed(2));                                
                        addRowSlipItem(1, 'Leave Deduction', deduction);
                        setTotalSums();
                    }
                });
            },
            complete: function(xhr, textStatus){

            },
            error: function(xhr, textStatus, errorThrown) {
            }
        });
    }

  $(function () {
  $("#slip-cheque_number").hide();
        $("#slip-payment_mode").change(function () {
            if ($(this).val() == "2") {
                $("#slip-cheque_number").show();
            } else {
                $("#slip-cheque_number").hide();
            }
        });
         var slip_payment_mode = $("#slip-payment_mode").val();
        if(slip_payment_mode==2){
       $("#slip-cheque_number").show();
        }
    });

    $('#slip-payslip_month,  #slip-payslip_year').on("change",function(){
       onMonthOrYearChange($(this).val());
    })
    

    function onMonthOrYearChange() {
       $.ajax({
                url: '$monthsDatesUrl',
                data: { 'monthName': $(' #slip-payslip_month').val(), 'monthYear': $(' #slip-payslip_year').val()},
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {

                },
                success: function(data, textStatus) {
                    // $('#slip-start_date').kvDatepicker({ 
                    //     format: 'dd/mm/yyyy', 
                    //     orientation: 'bottom',
                    //     endDate: '0d',
                    // });
                    // $('#slip-end_date').kvDatepicker({ format: 'dd/mm/yyyy', orientation: 'bottom' });

                    $("#slip-start_date").kvDatepicker({
                        format: 'dd-mm-yyyy',
                        autoclose: true,
                    }).kvDatepicker("update", data.firstDate);
                    
                    $("#slip-end_date").kvDatepicker({
                        format: 'dd-mm-yyyy',
                        autoclose: true,
                    }).kvDatepicker("update", data.lastDate);

                    // $('#slip-start_date').val(data.firstDate).trigger('change');
                    // $('#slip-end_date').val(data.lastDate).trigger('change');
                },
                complete: function(xhr, textStatus){

                },
                error: function(xhr, textStatus, errorThrown) {
                    alert("error");
                }
        });
    }
    
    $('#slip-employee_id, #slip-payslip_month, #slip-payslip_year').on("change",function(){
        $.ajax({
                url: '$slipAlreadyExistsUrl',
                // $('#myForm').serialize() + "&moredata=" + morevalue
                data: $('#w0').serialize() + "&slipID=" + '$slipID',
                type: 'POST',
                dataType: 'json',
                beforeSend: function() {
                },
                success: function(data, textStatus) {
                    // console.log("Data : ", data);
                    // console.log('$slipAlreadyExistsUrl');
                    if((data.status != 'undefined' && data.status == true)){
                        if(data.type == "exists")
                            $('.alert-warning').html("Salary slip for " + data.employee_name + " and month " + data.month + ', ' + data.year + ' already exists.');
                        if(data.type == "notfound")
                            $('.alert-warning').html("Attendance data for " + data.employee_name + " and month " + data.month + ', ' + data.year + ' not found. Please fetch from zoho from the Settings tab');
                        if (data.type == "overlap") 
                            $('.alert-warning').html('Salary Dates for '+ data.employee_name + ' overlap with an existing slip made for them.');
                        $('.alert-warning').show();
                        $('#slip-deduction').val('');
                        $('#slip-final_salary').val('');
                        $("#slip-leaves_taken").val('')
                        $("#slip-bonus").val('')
                        $("#slipitem-1-0-value").val(0)
                        setTotalSums()
                    }
                    else 
                    {
                        var absDays = data.absentDays;
                        var salary;
                        $('.alert-warning').hide();
                            var id = $('#slip-employee_id').val();
                            $.ajax({
                                        url:  '$urlsalary',
                                        type: 'POST',
                                        data: { empId: id },
                                        success: function(data) 
                                        {                                                
                                            salary = parseFloat(data);
                                            var deduction = (salary/30)*absDays;
                                            $("#slip-leaves_taken").val(absDays)
                                            $('#slip-deduction').val(deduction.toFixed(2));
                                            // var finalSalary = salary - deduction;
                                            // $('#slip-final_salary').val(finalSalary.toFixed(2));                                
                                            addRowSlipItem(1, 'Leave Deduction', deduction);
                                            setTotalSums();
                                        }
                                    });
                    }

                },
                complete: function(xhr, textStatus){

                },
                error: function(xhr, textStatus, errorThrown) {
                    // alert("Select fields correctly");
                    console.log(errorThrown);
                }
        });   
    });

JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>




