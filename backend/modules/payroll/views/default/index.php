<?php 
use yii\helpers\Html;
use app\components\GlobalConstant;
use backend\components\Helper;
use yii\bootstrap\ActiveForm;
?>

<div class="payroll-default-index">
    <!-- <h1><?//= "Attendance Importer" ?></h1> -->
    <!-- <h1><? //$this->context->action->uniqueId ?></h1> -->
    <!-- <p>You may upload the attendance sheet and press the upload button to upload the attendance data to our system</p>
    <p>Employees must be created before creating a slip for them. The linking is done using email ID present in the attendance sheet and our system</p>
    <p> -->

    <!-- <pq>Select Start Date and End Date to fetch attendance from Zoho People to our system</p> -->
        <br>
        <!-- <code><?// __FILE__ ?></code> -->

    </p>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
        <!-- <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3">
                <input class="form-control" type="file" id="input" accept=".xls,.xlsx"  >
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" id="button">Upload</button>
                <a href="<?//= "https://accounts.zoho.in/oauth/v2/auth?scope=ZOHOPEOPLE.attendance.READ&client_id=".getenv('ZOHO_CLIENT_ID')."&response_type=code&access_type=offline&redirect_uri=".getenv('BACKEND_URL')."payroll/default/index&prompt=consent"; ?>"><button class="btn btn-primary" id="button">Zoho</button></a>
            </div>
        </div> -->

        <div class="col-md-12">
            <div class="col-md-6">
                <div class="panel panel-default card-view border-panel panel-refresh">
                    <div class="panel-heading active">
                        <div class="pull-left">
                            <div class="panel-title txt-dark">Attendance Importer</div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="report-grid">
                        <span>Select From and To date to fetch attendance records for all employees from Zoho</span><br><br>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php $form = ActiveForm::begin([
                                        'action' => 'zoho',
                                        'class' => 'form-group invisible'
                                    ]); 
                                    ?>
                                    <?php 
                                        echo '<label class="control-label">From Date</label>';
                                        echo \kartik\date\DatePicker::widget([
                                            'name' => 'start_date',
                                            'id' => 'from_date',
                                            'value' => date('d-M-Y', strtotime('-1 month', strtotime(date('d-M-Y')))),
                                            'pluginOptions' => [
                                                'autoclose' => true,
                                                'format' => 'dd-M-yyyy',
                                                'endDate' => "0d",
                                                'orientation' => 'bottom',
                                            ]
                                        ]);
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <?php 
                                        echo '<label class="control-label">To Date</label>';
                                        echo \kartik\date\DatePicker::widget([
                                            'name' => 'end_date',
                                            'id' => 'to_date',
                                            'value' => date('d-M-Y', strtotime('-1 day', strtotime(date('d-M-Y')))),
                                            'pluginOptions' => [
                                                'autoclose' => true,
                                                'format' => 'dd-M-yyyy',
                                                'endDate' => "0d",
                                                'orientation' => 'bottom',
                                            ]
                                        ]);
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div id="invalid-date-range" class="col-md-6 help-block help-block-error" style="font-size: 14px; color: red; visibility: hidden;">
                                    From Date must be lesser than To Date
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4" style="margin-top: 20px;">
                                    <?= Html::submitButton('Fetch from Zoho', ['class' => 'btn btn-primary', 'id' => 'fetch-from-zoho-button']); ?>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>

            <div class="col-md-6">
                <div class="panel panel-default card-view border-panel panel-refresh">
                    <div class="panel-heading active">
                        <div class="pull-left">
                            <div class="panel-title txt-dark">Pay Period Settings</div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="report-grid">
                        <span>Select End day of the month for your pay cycle</span><br><br>
                        <div class="row">
                            <?php 
                                $payPeriodForm = ActiveForm::begin([
                                    'class' => 'form-group invisible',
                                    'action' => 'pay-period-settings'
                                ]);

                                // var_dump($payPeriodModel); die();
                            ?>
                            
                            <div class="col-md-6">
                                <!-- Start Date -->
                                <?php 
                                    $startDateDropdown = [];

                                    $daysDropdown = [];
                                    for ($i = 1; $i <= 27; $i++) {
                                        $daysDropdown[$i] = $i;
                                    }

                                    $startDateDropdown = ["-" => "-"] + $daysDropdown + ["28" => 28];

                                    $daysDropdown["Last Day"] = "Last Day";

                                    echo $payPeriodForm->field($payPeriodModel, 'start_date')->dropdownList($startDateDropdown, ['id' => 'pay-start-date', 'disabled' => true, 'class' => null]);

                                    // echo Html::dropdownList('start_date', $payPeriodModel, $startDateDropdown, ['id' => 'pay-start-date', 'disabled' => true]);
                                ?>
                            </div>

                            <div class="col-md-6">
                                <!-- End Date -->
                                <?php
                                    $endDateDropdown = ["Select End Date" => "Select End Date"] + $daysDropdown;
                                    echo $payPeriodForm->field($payPeriodModel, 'end_date')->dropdownList($endDateDropdown, ['id' => 'pay-end-date', 'class' => null]);
                                    // echo Html::dropdownList('end_date', $payPeriodModel, $endDateDropdown, ['id' => 'pay-end-date']);
                                ?>
                            </div>
                        </div>
                        <div class="row">
                                <div class="col-md-4" style="margin-top: 20px;">
                                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'id' => 'save-pay-period-settings-button']); ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
</body>
<!-- <script>
let selectedFile;
console.log(window.XLSX);
document.getElementById('input').addEventListener("change", (event) => {
    selectedFile = event.target.files[0];
})

let data=[{
    "name":"yashwant",
    "data":"Nova",
    "abc":"sdef"
}]


document.getElementById('button').addEventListener("click", () => {
    XLSX.utils.json_to_sheet(data, 'out.xlsx');
    if(selectedFile){
        let fileReader = new FileReader();
        fileReader.readAsBinaryString(selectedFile);
        fileReader.onload = (event)=>{
         let data = event.target.result;
         let workbook = XLSX.read(data,{type:"binary"});
         console.log(workbook);
         workbook.SheetNames.forEach(sheet => {
              let rowObject = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheet]);
              console.log("Data : ",rowObject);
            //   document.getElementById("jsondata").innerHTML = JSON.stringify(rowObject,undefined,4)
            $.ajax({
                    type: 'POST',
                    url: '// echo \yii\helpers\Url::to(['/payroll/default/index']);',
                    data: { 
                        rowObject: JSON.stringify(rowObject,undefined,4),
                    },
                    success: function() {
                                console.log("Json data Updated");
                            },
                    });
         });
        }
    }
});
</script> -->
<script>
    let selectedFile;
    // console.log(window.XLSX);
    // document.getElementById('input').addEventListener("change", (event) => {
    //     selectedFile = event.target.files[0];
    // })

    // let data=[{
    //     "name":"yashwant",
    //     "data":"Nova",
    //     "abc":"sdef"
    // }]

    $('#from_date, #to_date').on("change", function() {
        let fromDate = Date.parse($('#from_date').val())
        let toDate = Date.parse($('#to_date').val())
        if (fromDate >= toDate) {
            $('#invalid-date-range').prop('style', 'color: red; font-size: 14px; visibility: visible;');
            $('#fetch-from-zoho-button').prop("disabled",true);
        } else {
            $('#invalid-date-range').prop('style', 'visibility: hidden;');
            $('#fetch-from-zoho-button').prop("disabled",false);
        }
    })

    $(document).on('beforeSubmit', 'form', function(event) {
        $(this).find('[type=submit]').attr('disabled', true).addClass('disabled');
        $(this).find(':input[name="PayrollPayPeriodSetting[start_date]"]').prop('disabled', false);
    });

    $('#pay-end-date').on('change', function() {
        let endDateKey = $('#pay-end-date').find(":selected").val();
        let endDate = parseInt(endDateKey)
        if (isNaN(endDate)) {
            if (endDateKey == "Last Day") {
                $('#pay-start-date').val(1)                
            }
        } else {
            $('#pay-start-date').val(endDate + 1)
        }
    })

    document.getElementById('button').addEventListener("click", () => {
        XLSX.utils.json_to_sheet(data, 'out.xlsx');
        if(selectedFile){
            let fileReader = new FileReader();
            fileReader.readAsBinaryString(selectedFile);
            fileReader.onload = (event)=>{
            let data = event.target.result;
            let workbook = XLSX.read(data,{type:"binary"});
            console.log(workbook);
            workbook.SheetNames.forEach(sheet => {
                let objDate = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheet], { range: 1 });
                let objData = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheet], { range: 5 });
                let startDate = Object.keys(objDate[0]).filter(e=>e!='Start Date')?.[0];
                let endDate = objDate[0][startDate];
                console.log("start date : " + startDate);
                console.log("End date : " + endDate);
                console.log(objData);
                $.ajax({
                    type: 'POST',
                    url: '<?php echo \yii\helpers\Url::to(['/payroll/default/index']); ?>',
                    data: { 
                        rowObject: JSON.stringify(objData,undefined,4),
                        startDate: startDate,
                        endDate: endDate,
                    },
                    success: function() {
                                console.log("Json data Updated");
                            },
                    });

                // document.getElementById("jsondata").innerHTML = JSON.stringify(objData,undefined,4)
            });
            }
        }
    });
</script>
</div>
