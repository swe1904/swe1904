
if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_SUPERADMIN){
$query = Applicant::find();
}
//else if(Yii::$app->user->can('organisation-admin')){
//$clients=Client::find()->where('user_id=:user_id',[':user_id'=>yii::$app->user->id])->all();
//if(empty($clients)){
//return null;
//}
//$client_ids=[];
//foreach ($clients as $client){
//array_push($client_ids,$client->id);
//}
//$query = Applicant::find()->where(['in', 'client_id', $client_ids]);
//if(!empty($params['client_id'])) {
//$this->client_id = $params['client_id'];
//}
//}
else if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN){
$clients=Client::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all();
if(empty($clients)){
//return null;
}
$client_ids=[];
foreach ($clients as $client){
array_push($client_ids,$client->id);
}
$query = Applicant::find()->where(['in', 'client_id', $client_ids]);
if(!empty($params['client_id'])) {
$this->client_id = $params['client_id'];
}
}else if(Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT){
$client=User::find()->where('id=:id',[':id'=>yii::$app->user->id])->one();
if(empty($client->client_id)){
//return null;
}
$query = Applicant::find()->where('client_id=:client_id',[':client_id'=>$client->client_id]);
}