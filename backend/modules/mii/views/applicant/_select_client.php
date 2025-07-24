<?php
echo  "
<?php 
if(\$model->parent_id){
    \$parentApplicant = Applicant::findOne(\$model->parent_id);
    \$model->client_id = \$parentApplicant->client_id;
    echo \$form->field(\$model, 'client_id')->hiddenInput()->label(false);
}
else if(Yii::\$app->user->identity->getRole() == GlobalConstant::ROLE_CLIENT){
        \$model->client_id = Yii::\$app->user->identity->client_id;
    echo \$form->field(\$model, 'client_id')->hiddenInput()->label(false);
} 
else if(Yii::\$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_WORKER || Yii::\$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER || Yii::\$app->user->identity->getRole() == GlobalConstant::ROLE_CASE_MANAGER || Yii::\$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN){
 \$clientArray = ArrayHelper::map(Client::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')->andWhere(['tbl_client_organisation.organisation_id' => Yii::\$app->user->identity->organisation_id])->all(), 'id', 'client_name');
 ?>
<label class='control-label custom-label' for='template_id'>
    Select Client
</label>
<?php echo \$form->field(\$model, 'client_id')->dropDownList(\$clientArray, array('prompt' => '- Select -','class'=>'myselect','required'=>true))->label(false);
}
    ?>";
    ?>
