 $client=User::find()->where('id=:id',[':id'=>yii::$app->user->id])->one();
 if(!empty($client->client_id)){
$model->client_id=$client->client_id;
 }