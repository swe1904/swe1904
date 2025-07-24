<?php

use backend\models\Employee;
use common\models\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Approve Leave Requests';
$this->params['breadcrumbs'][] = $this->title;
$userId = Yii::$app->user->id;
?>

<div class="leave-request-approve container mt-4">
    <h4><b><?= Html::encode($this->title) ?></b></h4><br>

    <!-- Leave Request Table Section -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Leave Type</th>
                    <th>Employee</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Applied Days</th>
                    <th>Leave Balance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
           
            <?php foreach ($dataProvider->models as $index => $leaveRequest): ?>
    <?php 
    // Access the employee via the user_id in LeaveRequest
    $employee = Employee::findOne(['user_id' => $leaveRequest->employee_id]);

    // $leaveDays = (strtotime($leaveRequest->end_date) - strtotime($leaveRequest->start_date)) / (60 * 60 * 24) + 1;
    ?>

    <tr>
        <td><?= $index + 1 ?></td>
        <td><?= Html::encode($leaveRequest->leave_type) ?></td>
        <td> <?= $employee ? Html::encode($employee->preferred_full_name) : 'No Employee Found' ?></td>
        <td><?= Html::encode($leaveRequest->start_date) ?></td>
        <td><?= Html::encode($leaveRequest->end_date) ?></td>
        <td><?= Html::encode($leaveRequest->no_of_days) ?> Days</td>
        <td><?= $employee ? Html::encode($employee->annual_leave) . ' Days' : 'No Employee Found' ?></td>
        <td>
            <!-- Open Modal Button -->
            <?= Html::button('<i class="fa fa-eye"></i> View', [
                'class' => 'btn btn-primary btn-sm',
                'data-toggle' => 'modal',
                'data-target' => '#detailsModal',
                'data-id' => $leaveRequest->id,
                'data-leave-type' => $leaveRequest->leave_type,
                'data-employee' => $employee ? $employee->preferred_full_name : 'No Employee Found',
                'data-start-date' => $leaveRequest->start_date,
                'data-end-date' => $leaveRequest->end_date,
                'data-days' => $leaveRequest->no_of_days
            ]) ?>
        </td>
    </tr>
<?php endforeach; ?>


            </tbody>
        </table>
    </div>

    <!-- Leave Request Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Leave Request Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="border p-3 mb-3">
                        <p><strong>Leave Type:</strong> <span id="leaveType"></span></p>
                        <p><strong>Employee:</strong> <span id="employee"></span></p>
                        <p><strong>Start Date:</strong> <span id="startDate"></span></p>
                        <p><strong>End Date:</strong> <span id="endDate"></span></p>
                        <p><strong>Number of Days Requested:</strong> <span id="daysRequested"></span></p>
                    </div>

                    <!-- Form for Approving Leave -->
                    <form id="approveLeaveForm" method="post" action="<?= Url::to(['leave-request/approve-leave']) ?>">
                    <input type="hidden" name="id" id="leaveRequestDisplay" class="form-control" readonly>
                   <input type="hidden" name="approved_by" value="<?= Yii::$app->user->id ?>">

                        <div class="form-group">
                            <label for="statusDropdown" class="form-label">Decision</label>
                            <select name="status" id="statusDropdown" class="form-control">
                                <option value="">Select Decision</option>
                                <option value="approve">Approved</option>
                                <option value="reject">Rejected</option>
                                <option value="postpone">Postponed</option>
                            </select>
                        </div>
                            <!-- Pay Type Dropdown -->
                            <div class="form-group" id="payTypeContainer" style="display: none;">
                            <label for="payType" class="form-label">Pay Type</label>
                            <select name="pay_type" id="payType" class="form-control">
                                <option value="">Select Pay Type</option>
                                <option value="With Pay">With Pay</option>
                                <option value="Without Pay">Without Pay</option>
                            </select>
                            </div>

                        <!-- User Dropdown (Initially hidden) -->
                        <div class="form-group" id="userDropdownContainer" style="display: none;">
                            <label for="userDropdown" class="form-label">Leave Coverage</label>
                            <?= Select2::widget([
                                'name' => 'leave_coverage',
                                'data' => ArrayHelper::map(Employee::find()->all(), 'user_id', 'preferred_full_name'),
                                'options' => ['placeholder' => 'Select a User...'],
                                'pluginOptions' => ['allowClear' => true],
                            ]); ?>
                        </div>

                        <!-- Remarks Field -->
                        <div class="form-group">
                            <label for="remarks" class="form-label">Approval Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="4" placeholder="Enter remarks..."></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="approveButton">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination Section -->
    <div class="mt-3">
        <?= LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            'prevPageLabel' => '« Previous',
            'nextPageLabel' => 'Next »',
        ]) ?>
    </div>
</div>

<script>
$(document).ready(function() {
    // Populate modal on button click
    $('#detailsModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var leaveType = button.data('leave-type');
        var employee = button.data('employee');
        var startDate = button.data('start-date');
        var endDate = button.data('end-date');
        var days = button.data('days');

        // Set modal content
        $('#leaveType').text(leaveType);
        $('#employee').text(employee);
        $('#startDate').text(startDate);
        $('#endDate').text(endDate);
        $('#daysRequested').text(days);
        $('#leaveRequestDisplay').val(id);

        // Update field visibility based on leave type
        updateModalFields();
    });

    // Toggle coverage dropdown when status changes
    $('#statusDropdown').on('change', function() {
        updateModalFields();
    });

    // Function to show/hide Pay Type & Leave Coverage
    function updateModalFields() {
        const leaveType = $('#leaveType').text().trim();
        const decision = $('#statusDropdown').val();

        const typesNeedingPayType = [
            'Sick Leave', 
            'Study Leave', 
            'Compassionate Leave', 
            'Maternity Leave', 
            'Paternity Leave'
        ];

        // Show Pay Type only if the leave type requires it
        if (typesNeedingPayType.includes(leaveType)) {
            $('#payTypeContainer').show();
        } else {
            $('#payTypeContainer').hide();
        }

        // Show coverage field only if approved
        $('#userDropdownContainer').toggle(decision === 'approve');
    }

    // AJAX Form Submission
    $("#approveLeaveForm").on("submit", function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: '<?= Yii::$app->urlManager->createUrl('leave-request/approve-leave') ?>',
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                alert(response.message);
                if (response.success) {
                    location.reload();
                }
            },
            error: function(xhr) {
                console.log("AJAX Error:", xhr.responseText);
                alert("An error occurred! Please try again.");
            }
        });
    });
});
</script>


<style>
.modal-content { border-radius: 8px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); }
.btn-primary { background-color: #007bff; border: none; }
.btn-primary:hover { background-color: #0056b3; }
.form-group label { font-weight: bold; }
.form-control { border-radius: 5px; }
</style>
