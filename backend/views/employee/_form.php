<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\DynamicCurrency;
use backend\models\{Position, Department, Team, Gender, Nationality, EmergencyContactRelationship, EmploymentType, ContractType, SalaryCurrency, SalaryFrequency, Country, Document, Employee};
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\User;
use yii\helpers\Url;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Employee */
/* @var $form yii\widgets\ActiveForm */
$currentOrganisationId = Yii::$app->user->identity->organisation_id;
$managerUrl = Url::to(['employee/get-department-manager']); 
// Get user IDs already in the employee table
$currentUserId = $model->user_id ?? null;

$assignedUserIds = Employee::find()
    ->select('user_id')
    ->column();

// Exclude all assigned IDs **except the one being edited**
if ($currentUserId !== null) {
    $assignedUserIds = array_diff($assignedUserIds, [$currentUserId]);
}

$userList = User::find()
    ->where(['not in', 'id', $assignedUserIds])
    ->orderBy('fullname')
    ->all();



?>
<?= Html::csrfMetaTags() ?>

<div class="employee-form">

    <?php $form = ActiveForm::begin([
    'id' => 'employee-form',
    'options' => ['enctype' => 'multipart/form-data'], // This is essential for file uploads
]);
?>

<div class="row">
        <div class="col-md-4">
           
        <?= $form->field($model, 'user_id')->widget(Select2::class, [
    'data' => ArrayHelper::map($userList, 'id', 'fullname'),
    'options' => ['placeholder' => 'Select User'],
    'pluginOptions' => [
        'allowClear' => true,
        'width' => '100%',
    ],
]); ?>
</div>
</div>
    <!-- Employee Information Section -->
    <h3 class="ribbon">Employee Information</h3>
    <div class="row">
        <div class="col-md-4">
        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?> 
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'preferred_first_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'preferred_full_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
           <?= $form->field($model, 'date_of_birth')->widget(DatePicker::class, [
    'dateFormat' => 'yyyy-MM-dd',
    'options' => ['class' => 'form-control'],
    'clientOptions' => [
        'changeMonth' => true,
        'changeYear' => true,
        'yearRange' => '1950:2050',
    ]
]) ?>

        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'place_of_birth')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'nationality_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Nationality::find()->all(), 'id', 'name'),
                    'options' => ['placeholder' => 'Select Nationality'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'width' => '100%',
                    ],
            ]) ?>
        </div>
        <div class="col-md-4">
        <?= $form->field($model, 'blood_group')->widget(Select2::class, [
                'data' => \backend\models\BloodGroup::getBloodGroupList(), // Fetch dynamic list from the database
                'options' => ['placeholder' => 'Select Blood Group'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'width' => '100%',
                ],
            ]) ?>
        </div>
        <div class="col-md-4">
        <?= $form->field($model, 'gender_id')->widget(Select2::class, [
                        'data' => [1 => 'MALE', 2 => 'FEMALE', 3 => 'UNDISCLOSED'],
                        'options' => ['placeholder' => 'Select Gender'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'width' => '100%',
                        ],
                        ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
        <?= $form->field($model, 'country_of_legal_residence')->label('Country of Legal Residence')->widget(Select2::class, [
                                    'data' => ArrayHelper::map(Country::find()->all(), 'id','country_name'),
                                    'language' => 'en',
                                    'options' => [
                                            'placeholder' => 'Select country',
                                                 'class'=>'multiple',
                                                'style'=>"height:250px",
                                            ],
                                    'pluginOptions' => [
                                            'allowClear' => true,
                                            'label' => false,
                                        ],

                                        ])
                                        ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'mobile_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'current_residential_address')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'permanent_address')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
        <?= $form->field($model, 'emergency_contact_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
    <div class="col-md-4">
    
<?= $form->field($model, 'emergency_contact_relationship')->widget(Select2::class, [
    'data' => $model->getEmergencyContactRelationships(),
    'options' => ['placeholder' => 'Select Relationship'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]) ?>
    </div>
        <div class="col-md-4">
            <?= $form->field($model, 'emergency_mobile_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
        <?= $form->field($model, 'emergency_contact_email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <!-- Employment Information Section -->
    <h3 class="ribbon">Employment Information</h3>
    <div class="row">
        <div class="col-md-4">
           
            <?= $form->field($model, 'employee_id')->textInput(['readonly' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'work_email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
        <?= $form->field($model, 'position')->widget(Select2::class, [
        'data' => ArrayHelper::map(Position::find()->all(), 'id', 'name'), // Adjust the attributes as per your DB
        'options' => ['placeholder' => 'Select a position...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
       
        </div>
    </div>

    <div class="row">
   
      <div class="col-md-4">
            <?= $form->field($model, 'team')->widget(Select2::class, [
                'data' => ArrayHelper::map(Team::find()->all(), 'id', 'name'), // Adjust the attributes as per your DB
                'options' => ['placeholder' => 'Select a position...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
                </div>
                 <div class="col-md-4">
            <?= $form->field($model, 'department_id')->widget(Select2::class, [
    'data' => ArrayHelper::map(Department::find()->all(), 'id', 'name'),
    'options' => [
        'placeholder' => 'Select a department...',
        'id' => 'department-id',
    ],
    'pluginOptions' => ['allowClear' => true],
]); ?>

                </div>

                   <div class="col-md-4">
                    <!-- <?php // $form->field($model, 'date_of_joining')->textInput(['type' => 'date']) ?> -->

                    <?= $form->field($model, 'date_of_joining')->widget(DatePicker::class, [
    'dateFormat' => 'yyyy-MM-dd',
    'options' => ['class' => 'form-control', 'id' => 'date-of-joining'],
    'clientOptions' => [
        'changeMonth' => true,
        'changeYear' => true,
        'yearRange' => '1950:2050',
    ]
]) ?>

                </div>
           
               
                
   
       
       
        
    </div>

    <div class="row">

    <div class="col-md-4">
    <?= $form->field($model, 'probation_period')->dropDownList(
    ['3' => '3 Months', '6' => '6 Months'],
    ['prompt' => 'Select Probation Period', 'id' => 'probation-period']
) ?>

</div>

   
          <div class="col-md-4">
                  <?= $form->field($model, 'probation_period_completion_date')->textInput([
    'type' => 'date',
    'readonly' => true,
    'id' => 'completion-date'
]) ?>
                </div>
                
                <!-- <div class="col-md-4">
                <?= $form->field($model, 'department_manager')->widget(Select2::class, [
    'data' => [], // Will be loaded dynamically
    'options' => [
        'placeholder' => 'Select a department manager...',
        'id' => 'department-manager',
    ],
    'pluginOptions' => ['allowClear' => true],
]); ?>

                </div> -->
                 <!-- <div class="col-md-4">
                <?= $form->field($model, 'direct_supervisor')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Department::find()->all(), 'supervisor_id', 'supervisor_name'), // Adjust accordingly
                    'options' => ['placeholder' => 'Select a direct supervisor...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
                </div> -->
       



</div>

    <div class="row">
   
 <div class="col-md-4">
                <?= $form->field($model, 'country_manager_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Employee::find()->all(), 'id', 'first_name'), // Adjust the attributes as per your DB
                    'options' => ['placeholder' => 'Select a Country Manager...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
               <div class="col-md-4">
            <?= $form->field($model, 'employment_type')->widget(Select2::class, [
            'data' => [
                '1' => 'FULL TIME - PERMANENT',
                '2' => 'PART TIME - PERMANENT',
                '3' => 'FULL TIME - TEMPORARY',
                '4' => 'PART TIME - TEMPORARY',
            ],
            'options' => ['placeholder' => 'Select Employment Type...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
            ]) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'notice_period')->widget(Select2::class, [
            'data' => [
                '30' => '30 Days',
                '60' => '60 Days',
                '90' => '90 Days',
                '180' => '180 Days',
            ],
            'options' => ['placeholder' => 'Select Notice Period...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
            ]) ?>
            </div>
               
               
          

    </div>

    <div class="row">
           

        <div class="col-md-4">
            
            <?= $form->field($model, 'contract_type')->widget(Select2::class, [
            'data' => [
                'unlimited' => 'UNLIMITED',
                '3_months' => 'FIXED TERM - 3 MONTHS',
                '6_months' => 'FIXED TERM - 6 MONTHS',
                '9_months' => 'FIXED TERM - 9 MONTHS',
                '1_year' => 'FIXED TERM - 1 YEAR',
                '2_years' => 'FIXED TERM - 2 YEARS',
            ],
            'options' => ['placeholder' => 'Select Notice Period...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
            ]) ?>
        </div>
        <div class="col-md-4">
    <?= $form->field($model, 'annual_leave')->widget(Select2::class, [
        'data' => [
            '21' => '21 Days',
            '22' => '22 Days',
            '30' => '30 Days',
        ],
        'options' => ['placeholder' => 'Select Annual Leave...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>
</div>

<div class="col-md-4">
    <?= $form->field($model, 'initial_annual_leave')->textInput([
        'readonly' => true,
        'id' => 'annual_leave_calculated',
        'placeholder' => 'Annual leave will be calculated based on joining date'
    ])->label('Total Leave for Current Year') ?>
</div>


      </div>

       
     
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'iban_number')->textInput(['maxlength' => true]) ?>
        </div>
         <div class="col-md-4">
            <?= $form->field($model, 'branch_name')->textInput(['maxlength' => true]) ?>
        </div>
       
    </div>

  


    <div class="row">
 
        <div class="col-md-4">
            <?= $form->field($model, 'swift_code')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-4">
        <?= $form->field($model, 'bank_country')->label('Bank of Country')->widget(Select2::class, [
                                    'data' => ArrayHelper::map(Country::find()->all(), 'id','country_name'),
                                    'language' => 'en',
                                    'options' => [
                                            'placeholder' => 'Select country',
                                                 'class'=>'multiple',
                                                'style'=>"height:250px",
                                            ],
                                    'pluginOptions' => [
                                            'allowClear' => true,
                                            'label' => false,
                                        ],

                                        ])
                                        ?>
        </div>
         <div class="col-md-4">
           
            <?= $form->field($model, 'currency_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(DynamicCurrency::find()->all(), 'id', 'code'), // Adjust accordingly
                    'options' => ['placeholder' => 'Select Currency...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
        </div>
        
    </div>

   
    <div class="row">
           <div class="col-md-4">
            <?= $form->field($model, 'account_number')->textInput(['maxlength' => true]) ?>
        </div>
   
        <div class="col-md-4">
            <?= $form->field($model, 'salary_frequency_id')->dropDownList(
                [1 => 'Monthly', 2 => 'Bi-Monthly', 3 => 'Weekly', 4 => 'Annually'],
                ['prompt' => 'Select Salary Frequency']
            ) ?>
        </div>
        <div class="col-md-4">
        <?= $form->field($model, 'monthly_salary_basic')->textInput(['type' => 'number', 'id' => 'monthly_salary_basic', 'class' => 'form-control salary-input']) ?>
    </div>
       
    </div>

    <div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'monthly_salary_housing')->textInput(['type' => 'number', 'id' => 'monthly_salary_housing', 'class' => 'form-control salary-input']) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'monthly_salary_transportation')->textInput(['type' => 'number', 'id' => 'monthly_salary_transportation', 'class' => 'form-control salary-input']) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'total_monthly_salary')->textInput(['type' => 'number', 'id' => 'total_monthly_salary', 'class' => 'form-control', 'readonly' => true, 'style' => 'background-color: #e9ecef;']) ?>
    </div>
    </div>

    <!-- Employee Documents Section -->
    <h3 class="ribbon">Employee Documents</h3>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'passport_copy')->fileInput(['accept' => '.pdf']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'id_card_copy')->fileInput(['accept' => '.pdf']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'educational_document_1')->fileInput(['accept' => '.pdf']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'educational_document_2')->fileInput(['accept' => '.pdf']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'resume')->fileInput(['accept' => '.pdf']) ?>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<style>

        .select2-container--krajee .select2-selection--single {
        height: 40px !important;
        display: flex;
        align-items: center;
        border-radius: 6px !important;
        }
        .select2-selection__rendered {
        padding-top: 6px !important;
        padding-left: 10px;
        }

        .ribbon {
        position: relative;
        padding: 5px 10px; /* Decrease padding to reduce the height and width */
        background-color: rgb(10, 10, 20);
        color: white;
        font-size: 1em; /* Reduce font size */
        text-align: center;
        margin-top: 15px; /* Adjust margin if needed */
        margin-bottom: 5px; /* Adjust margin if needed */
        display: inline-block; /* Make the ribbon fit the content */
        }

        .row {
        margin-bottom: 10px;
        }

</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<?php
        $this->registerJs(<<<JS
        function calculateTotalSalary() {
            let basic = parseFloat($('#monthly_salary_basic').val()) || 0;
            let housing = parseFloat($('#monthly_salary_housing').val()) || 0;
            let transportation = parseFloat($('#monthly_salary_transportation').val()) || 0;
            let total = basic + housing + transportation;
            $('#total_monthly_salary').val(total);
        }

        $('.salary-input').on('input', calculateTotalSalary);
            // Replace with actual route

        JS);
?>
<?php
    $departmentUrl = \yii\helpers\Url::to(['employee/get-teams-by-department']);
 
?>


<script>

        $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
        });

        $('#department-id').on('change', function () {
        var deptId = $(this).val();

        if (deptId) {
            $.ajax({
                // url: '<?php echo $departmentUrl; ?>', // adjust route if using modules
                url: '<?php echo \yii\helpers\Url::to(['get-teams-by-department']) ?>',

                type: 'POST',
                data: { dept_id: deptId },
                success: function (data) {
        var managerDropdown = $('#department-manager');
        managerDropdown.empty();

        managerDropdown.append($('<option>', {
            value: '',
            text: 'Select Manager'
        }));

        managerDropdown.append($('<option>', {
            value: data.manager_id,
            text: data.manager_name
        }));
        },
                error: function (xhr, status, error) {
        console.error('AJAX Error:', status, error);
        console.error('Response Text:', xhr.responseText);
        alert('Error: ' + xhr.responseText); // For visibility
        }
            });
        }
        });

</script>
<?php
$this->registerJs(<<<JS
function calculateInitialLeave() {
    let joiningDate = $('#date-of-joining').val();
    let totalAnnualLeave = $('#employee-annual_leave').val();

    if (!joiningDate || !totalAnnualLeave) {
        $('#annual_leave_calculated').val('');
        return;
    }

    let joinDate = new Date(joiningDate);
    let joinMonth = joinDate.getMonth(); // January is 0
    let monthsLeft = 12 - joinMonth;

    let proratedLeave = Math.round((totalAnnualLeave / 12) * monthsLeft);
    $('#annual_leave_calculated').val(proratedLeave);
}

$('#date-of-joining, #employee-annual_leave').on('change', calculateInitialLeave);
calculateInitialLeave();
JS);
?>

<?php
$script = <<<JS
function calculateCompletionDate() {
    let joiningDate = document.getElementById('date-of-joining').value;
    let period = document.getElementById('probation-period').value;

    if (joiningDate && period) {
        let date = new Date(joiningDate);
        date.setMonth(date.getMonth() + parseInt(period));

        // Format date as yyyy-mm-dd
        let year = date.getFullYear();
        let month = String(date.getMonth() + 1).padStart(2, '0');
        let day = String(date.getDate()).padStart(2, '0');
        let formatted = year + '-' + month + '-' + day;

        document.getElementById('completion-date').value = formatted;
    } else {
        document.getElementById('completion-date').value = '';
    }
}

document.getElementById('probation-period').addEventListener('change', calculateCompletionDate);
document.getElementById('date-of-joining').addEventListener('change', calculateCompletionDate);
JS;

$this->registerJs($script);
?>

