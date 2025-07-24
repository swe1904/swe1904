<?php

use backend\models\CaseStepsNotes;
use yii\grid\GridView;


?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'columns' => [
        //            ['class' => 'yii\grid\SerialColumn'],

        //            'id',
        //            'case_steps_id',
        'description:ntext',
        'user.userProfile.firstname',
        'created_at',

        //            ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>

<p>
    <?= $this->render('_form', [
        'model' => new CaseStepsNotes(),
    ]) ?>
</p>