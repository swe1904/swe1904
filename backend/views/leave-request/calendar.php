<?php
use yii\helpers\Html;
?>

<h3>Leave Calendar for <?= $startDate->format('F Y') ?></h3>

<div class="btn-group mb-3">
    <?= Html::a('← Previous', ['leave-request/calendar', 'month' => $prevMonth->month, 'year' => $prevMonth->year], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Next →', ['leave-request/calendar', 'month' => $nextMonth->month, 'year' => $nextMonth->year], ['class' => 'btn btn-primary']) ?>
</div>

<div class="mb-3">
    <span class="badge bg-success">A - Approved</span>
    <span class="badge bg-warning text-dark">P - Pending</span>
    <span class="badge bg-danger">X - Rejected</span>
</div>

<!-- ✅ DESKTOP TABLE (unchanged) -->
<div class="leave-calendar-desktop">
    <table class="table table-bordered table-sm table-striped">
        <thead>
            <tr>
                <th>Employee</th>
                <?php foreach ($period as $date): ?>
                    <th><?= $date->format('d') ?><br><small><?= $date->format('D') ?></small></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $emp): ?>
                <tr>
                    <td><?= Html::encode($emp->first_name . ' ' . $emp->last_name) ?></td>
                    <?php foreach ($period as $date): ?>
                        <?php
                            $dayOfWeek = $date->format('N');
                            $d = $date->toDateString();
                            $leaveInfo = $leaveMap[$emp->user_id][$d] ?? null;

                            if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                                $class = 'weekend-day';
                                $label = '';
                            } else {
                                if ($leaveInfo) {
                                    $status = $leaveInfo['status'];
                                    $type = strtoupper(substr($leaveInfo['type'], 0, 1));
                                    if ($status === 'approve') {
                                        $class = 'leave-approved';
                                        $label = $type ?: 'A';
                                    } elseif (in_array($status, ['request', 'pending'])) {
                                        $class = 'leave-requested';
                                        $label = $type ?: 'P';
                                    } elseif (in_array($status, ['reject', 'rejected'])) {
                                        $class = 'leave-rejected';
                                        $label = 'X';
                                    } else {
                                        $class = '';
                                        $label = '';
                                    }
                                } else {
                                    $class = '';
                                    $label = '';
                                }
                            }
                        ?>
                        <td class="<?= $class ?>" title="<?= $leaveInfo['type'] ?? '' ?>"><?= $label ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- ✅ MOBILE VIEW (Cards) -->
<div class="leave-calendar-mobile">
    <?php foreach ($employees as $emp): ?>
        <div class="mobile-card">
            <div class="mobile-card-header">
                <?= Html::encode($emp->first_name . ' ' . $emp->last_name) ?>
            </div>
            <div class="mobile-card-body">
                <?php foreach ($period as $date): ?>
                    <?php
                        $d = $date->toDateString();
                        $dow = $date->format('N');
                        $leaveInfo = $leaveMap[$emp->user_id][$d] ?? null;

                        $statusLabel = '';
                        $statusClass = 'badge-default';

                        if ($dow == 6 || $dow == 7) {
                            $statusClass = 'weekend-badge';
                            $statusLabel = 'Weekend';
                        } elseif ($leaveInfo) {
                            $status = $leaveInfo['status'];
                            $type = ucfirst($leaveInfo['type'] ?? '');
                            if ($status === 'approve') {
                                $statusClass = 'bg-success text-white';
                                $statusLabel = "Approved ($type)";
                            } elseif (in_array($status, ['request', 'pending'])) {
                                $statusClass = 'bg-warning text-dark';
                                $statusLabel = "Pending ($type)";
                            } elseif (in_array($status, ['reject', 'rejected'])) {
                                $statusClass = 'bg-danger text-white';
                                $statusLabel = "Rejected ($type)";
                            }
                        }
                    ?>
                    <div class="mobile-row">
                        <div><strong><?= $date->format('D, d M') ?></strong></div>
                        <div class="badge <?= $statusClass ?>"><?= $statusLabel ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- ✅ STYLES -->
<style>
/* DESKTOP DEFAULT STYLES */
.leave-calendar-desktop {
    display: block;
}

.leave-calendar-mobile {
    display: none;
}

/* RESPONSIVE OVERRIDE FOR MOBILE */
@media (max-width: 768px) {
    .leave-calendar-desktop {
        display: none;
    }

    .leave-calendar-mobile {
        display: block;
    }

    .mobile-card {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .mobile-card-header {
        padding: 0.75rem 1rem;
        background: #f8f9fa;
        font-weight: bold;
        font-size: 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    .mobile-card-body {
        padding: 0.75rem 1rem;
    }

    .mobile-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #eee;
        font-size: 0.9rem;
    }

    .badge {
        padding: 0.35rem 0.6rem;
        font-size: 0.75rem;
        border-radius: 0.25rem;
    }

    .weekend-badge {
        background-color: #e9ecef;
        color: #6c757d;
    }
}

/* Existing Desktop Styles */
.table th, .table td {
    text-align: center;
    vertical-align: middle;
    font-size: 0.9rem;
    padding: 0.35rem;
}
.leave-approved { background-color: #d4edda !important; color: #155724; font-weight: bold; }
.leave-requested { background-color: #fff3cd !important; color: #856404; font-weight: bold; }
.leave-rejected { background-color: #f8d7da !important; color: #721c24; font-weight: bold; }
.weekend-day { background-color: #e9ecef; color: #6c757d; }
</style>
