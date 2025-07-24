<?php
/**
 * @author Eugene Terentev <eugene@terentev.net>
 * @var $model common\models\TimelineEvent
 */
?>

<div class="timeline-item">
    <span class="time">
        <i class="fa fa-usd"></i>
     <?php //echo Yii::$app->formatter->asRelativeTime($model->created_at) ?>
        <b>USD: <?php echo $model->usd; ?> | Article Word: <?php echo $model->no_of_line; ?> </b>
    </span>

    <h3 class="timeline-header">
        <?php echo Yii::t('backend', 'You have new article request!') ?>
    </h3>

    <div class="timeline-body">
        <?php echo Yii::t('backend', 'New article request({identity}) was registered at {created_at}.', [
            'identity' => $model->title,
            'created_at' => Yii::$app->formatter->asDate($model->created_at)
        ]) ?>
        <br>
        <?php echo substr($model->description,0,300); ?>
    </div>

    <div class="timeline-footer">
        <?php echo \yii\helpers\Html::a(
            Yii::t('backend', 'Article Reply'),
            ['/article/create', 'id' => $model->id],
            ['class' => 'btn btn-success btn-sm']
        ) ?>
    </div>
</div>