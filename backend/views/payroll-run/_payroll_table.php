<script>
$(document).ready(function() {
    $('.inline-edit').on('blur', function() {
        let field = $(this).data('field');
        let value = $(this).text().trim();
        let employeeId = $(this).closest('tr').data('employee-id');

        if (!employeeId || !field) {
            alert('Invalid data');
            return;
        }

        $.ajax({
            url: '<?= \yii\helpers\Url::to(["payroll-run/update-payroll"]) ?>',
            type: 'POST',
            data: { employee_id: employeeId, field: field, value: value },
            success: function(response) {
                if (response.success) {
                    alert('Updated successfully');
                } else {
                    alert('Update failed');
                }
            },
            error: function() {
                alert('Error updating data');
            }
        });
    });
});
</script>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Employee Name</th>
            <th>Position</th>
            <th>Basic Salary</th>
            <th>Housing</th>
            <th>Transportation</th>
            <th>Gross Monthly Salary</th>
            <th>Sales Commission</th>
            <th>Bonus</th>
            <th>Damages</th>
            <th>Social Insurance</th>
            <th>Income Tax</th>
            <th>Absence</th>
            <th>Employee Loan</th>
            <th>Net Salary</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($employees as $emp): ?>
            <tr data-employee-id="<?= $emp['id'] ?>">
                <td><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></td>
                <td><?= $emp['position'] ?></td>
                <td class="monthly_salary_basic"><?= $emp['monthly_salary_basic'] ?></td>
                <td class="monthly_salary_housing"><?= $emp['monthly_salary_housing'] ?></td>
                <td class="monthly_salary_transportation"><?= $emp['monthly_salary_transportation'] ?></td>
                <td class="gross_salary"><?= ($emp['monthly_salary_basic'] + $emp['monthly_salary_housing'] + $emp['monthly_salary_transportation']) ?></td>
                <td contenteditable="true" class="inline-edit sales_commission" data-field="sales_commission"><?= $emp['sales_commission'] ?? '0.00' ?></td>
                <td contenteditable="true" class="inline-edit bonus" data-field="bonus"><?= $emp['bonus'] ?? '0.00' ?></td>
                <td contenteditable="true" class="inline-edit damages" data-field="damages"><?= $emp['damages'] ?? '0.00' ?></td>
                <td class="social_insurance">0.00</td>
                <td class="income_tax">0.00</td>
                <td contenteditable="true" class="inline-edit absence" data-field="absence">0.00</td>
                <td contenteditable="true" class="inline-edit employee_loan" data-field="employee_loan">0.00</td>
                <td class="net_salary">0.00</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
