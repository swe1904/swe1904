
$organisation=Organisation::find()->where('user_id=:user_id',[':user_id'=>yii::$app->user->id])->one();
$organisation_id='';
if(!empty($organisation)){
$organisation_id=$organisation->id;
}else{Yii::$app->getResponse()->redirect(array('organisation/create'));}
$query = Client::find()->where('organisation_id=:organisation_id and user_id=:user_id',[':organisation_id'=>$organisation_id,':user_id'=>yii::$app->user->id]);
