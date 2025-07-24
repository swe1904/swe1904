$organisation=Organisation::find()->where('user_id=:user_id',[':user_id'=>yii::$app->user->id])->one();
$model->organisation_id=$organisation->id;
$model->user_id=yii::$app->user->id;