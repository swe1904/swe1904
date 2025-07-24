<?php
use yii\helpers\Url;

$this->title = 'Attendance Dashboard';
?>

<h3>My Attendance</h3>

<?php if ($shift): ?>
    <p><strong>Shift Time:</strong> <?= $shift->shift_start ?> - <?= $shift->shift_end ?></p>
<?php endif; ?>

<?php if ($log && $log->clock_in_time): ?>
    <p><strong>Clock-In:</strong> <?= $log->clock_in_time ?></p>
<?php endif; ?>

<?php if ($log && $log->clock_out_time): ?>
    <p><strong>Clock-Out:</strong> <?= $log->clock_out_time ?></p>
    <p><strong>Worked:</strong> <?= $log->worked_minutes ?> mins</p>
<?php endif; ?>

<?php if (!$log || !$log->clock_in_time): ?>
    <button id="clockInBtn" class="btn btn-success">Clock In</button>
<?php elseif (!$log->clock_out_time): ?>
    <button id="clockOutBtn" class="btn btn-danger">Clock Out</button>
<?php endif; ?>

<script>
document.getElementById("clockInBtn")?.addEventListener("click", () => {
    fetch("<?= Url::to(['employee-attendance/clock-in']) ?>", {method: "POST"})
        .then(res => res.json()).then(() => location.reload());
});
document.getElementById("clockOutBtn")?.addEventListener("click", () => {
    fetch("<?= Url::to(['employee-attendance/clock-out']) ?>", {method: "POST"})
        .then(res => res.json()).then(() => location.reload());
});
</script>
