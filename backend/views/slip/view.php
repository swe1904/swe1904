
        <!-- <div class="text-center" style="padding-bottom: 10px">


       <img style="height:80px;width:auto;background-repeat: no-repeat;"

       src="<?php //echo \backend\models\Slip::getImage($receiptModel->organisation_id)?>" /> 



        </div> -->

        <?php //var_dump($receiptModel); die(); ?>
   <div>
   <?php 
        if ($this->context->route == 'slip/slip-pdf') {
            $url = "https://pangeaportal.com/backend/web/images/logo.png";
            echo '<div class="col-md-12">   
    
                <img class="col-md-8" height="100px" width="180px" src="'.$url.'" >
                <span
                    style="
                        font-size: 1.3em;
                    "
                    class="col-md-4"
                    >
                    PAY SLIP - PANGEA WORLDWIDE - KSA
                </span>
                <br> <br>
        </div>';
        }
   ?>
    <table style="width: 50%;" border="3" cellpadding="5">
        <tbody>
        <tr>
            <th>From</th>
            <th><?php echo date("d-M-Y", strtotime($receiptModel->start_date))?></th>
        </tr>
        <tr>
            <th>To</th>
            <th><?php echo date("d-M-Y", strtotime($receiptModel->end_date))?></th>
        </tr>
        <tr>
            <th>Month</th>
            <th><?php echo $receiptModel->payslip_month ?></th>
        </tr>
        <tr>
            <th>Year</th>
            <th><?php echo $receiptModel->payslip_year ?></th>
        </tr>
        <tr>
            <th>Payment Method</th>
            <th><?php 
                if ($receiptModel->payment_mode == 1) {
                    echo 'Cash';
                } elseif ($receiptModel->payment_mode == 2) {
                    echo 'Cheque/Online Payment';
                }
            ?></th>
        </tr>
        <tr>
            <th>Contractual Salary</th>
            <th><?php echo $receiptModel->employee->currency->currency->iso . ' ' . $receiptModel->current_salary ?></th>
        </tr>
        <tr >
            <th>Name</th>
            <th><?php echo $receiptModel->employee->name; ?></th>

        </tr>
        <tr>
            <td>Position</td>
            <td><?php echo $receiptModel->employee->position; ?></td>
        </tr>


        
        <tr>
            <td>Employee Code</td>
            <td><?php echo $receiptModel->employee->employee_id; ?></td>

        </tr>

        <tr>
            <td>Department</td>
            <td>
                <?php echo $receiptModel->employee->department->name; ?>
            </td>
        </tr>

        <tr>
            <td>Joining Date</td>
            <td>
                <?php echo $receiptModel->employee->joining_date; ?>
            </td>
        </tr>

        <tr>
            <td>Leaves Accrued</td>
            <td>
                <?php echo !empty($receiptModel->leaves_accrued) ? $receiptModel->leaves_accrued : "-"; ?>
            </td>
        </tr>
        </tbody>
    </table>

</div>
<br><br>

<div>
    <table style="width: 100%; border : 2px solid black;" border="3" cellpadding="5">
        <tbody>
            <tr>
                <th>Salary</th>
                <th>Amount</th>
                <th>Notes</th>
            </tr>

            <tr>
                <td>Monthly Salary</td>
                <td><?php echo $receiptModel->current_salary ?></td>
                <td>N/A</td>
            </tr>

            <?php 
                $bonuses = backend\models\SlipItem::find()->where(['slip_id' => $receiptModel->id, 'section_id' => 2])->all();
                if (!empty($bonuses)) {
                    echo '<tr>
                        <th>Bonuses</th>
                    </tr>';
                }
            ?>
            <?php 
                foreach($bonuses as $bonus) {
                    $notes = $bonus->notes == '' ? 'N/A' : $bonus->notes;
                    echo '<tr>
                        <td>'.$bonus->description.'</td>
                        <td>'.$bonus->value.'</td>                    
                        <td>'.$notes.'</td>                    
                    </tr>';
                } 
            ?>

            <?php 
                $deductions = backend\models\SlipItem::find()->where(['slip_id' => $receiptModel->id, 'section_id' => 1])->all();
                if (!empty($deductions)) {
                    echo '<tr>
                        <th>Deductions</th>
                    </tr>';
                }
            ?>
            <?php 
                foreach($deductions as $deduction) {
                    $notes = $deduction->notes == '' ? 'N/A' : $deduction->notes;
                    echo '<tr>
                        <td>'.$deduction->description.'</td>
                        <td>'.$deduction->value.'</td>                    
                        <td>'.$notes.'</td>                    
                    </tr>';
                } 
            ?>

            <tr>
                <th>Net Salary</th>
                <th><?php echo $receiptModel->final_salary ?></th>
            </tr>
        </tbody>
    </table>

    <div style="text-align:center;">
        <br> <br><br><br><br><br>
This is an electronically generated slip and does not require signature
<br>
For any queries or concerns, please email hr@pangeaworldwide.sa or speak to your line manager

    </div>
</div>