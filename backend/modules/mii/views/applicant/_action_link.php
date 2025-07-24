'link'=>function($url, $model){
$url=Yii::$app->urlManager->createUrl(['/cases/index','CasesSearch[applicant_id]'=> $model->id]);
return'<a class="btn btn-default edit" href="'.$url.'" title="Cases"><i class="fa fa-suitcase"></i></a>';
}