<?php

namespace backend\controllers;

use app\components\GlobalConstant;
use backend\models\Client;
use backend\models\Organisation;
use Yii;
use common\models\User;
use backend\models\UserForm;
use backend\models\search\UserSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\ClientOrganisation;
use backend\models\ClientEntity;
use backend\components\Helper;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends CustomBaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        /*    'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['unimpersonate'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ],*/
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
       
        $model = new UserForm();
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(Yii::$app->user->can('administrator')){
            $model->setScenario('create');
            $getChildRoles=GlobalConstant::ORGANISATION_ADMIN_ARRAY;
        }else{
            $model->scenario = UserForm::SCENARIO_CREATE_CLIENTS;
            $getChildRoles=$this->getchildroles(Yii::$app->user->id);
            // remove client-hr role for org-admin to create
            if(Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_ADMIN) || Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_MANAGER)){
                ArrayHelper::remove($getChildRoles, GLobalConstant::ROLE_CLIENT_HR);
            }
        }
        if ($model->load(Yii::$app->request->post())) {

            // Set the fullname attribute based on posted data
           $model->fullname = Yii::$app->request->post('UserForm')['fullname'];
           if($model->save())
           {
               if(Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN))
                   Yii::$app->session->setFlash('success', "Organisation-admin Created.");
               else
                   Yii::$app->session->setFlash('success', "User Created.");
               return $this->redirect(['index']);
           }
       }

        //--select clients and assign to user client id if applicant
        $connectClients=[];
        $organisation=Organisation::find()->where('user_id=:user_id',[':user_id'=>yii::$app->user->id])->one();
     
        if (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_ADMIN) {
            $clientId = ArrayHelper::getColumn(User::find()->where(['not in','client_id',''])->all(),'client_id');
            $clientModel = Client::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->andWhere(['not in', 'client.id', $clientId])->all();

            
        }
        else{
            // for client to add client-hr
                $clientModel = Client::find()->where('id=:id ',[':id'=>Yii::$app->user->identity->client_id])->all();
        }
        $connectClients=ArrayHelper::map($clientModel,'id','client_name');
        $allClients = ArrayHelper::map(Client::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all(),'id','client_name');
       // Extract client IDs from $allClients
     
        $clientEntityArr = [];
        if (!empty($allClients)) {
            $clientIds = array_keys($allClients); // Get all client IDs
            $clientEntityArr = ArrayHelper::map(
                ClientEntity::find()->where(['client_id' => $clientIds])->all(),
                'id',
                'name'
            );
        }
        // echo "<pre>";
        // print_r(User::find()->asArray()->all());
        
        //  echo "<pre>";print_r($getChildRoles);"</pre>";
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'roles' => $getChildRoles,
            'connectClients'=>$connectClients,
            'allClients'=>$allClients,
            'clientEntityArr' =>$clientEntityArr 
        ]);
    }


 public function actionIndexsuperadminusers()
{
    if (!Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN)) {
        throw new \yii\web\ForbiddenHttpException('Access denied');
    }

    $searchModel = new UserSearch();
    $dataProvider = $searchModel->searchSuperadmin(Yii::$app->request->queryParams);

    // Set up the model for the user form
    $model = new \backend\models\UserForm();
    $model->scenario = 'create';

    // You can use this constant or define roles manually
    $roles = GlobalConstant::ORGANISATION_ADMIN_ARRAY;

    $connectClients = [];
    $allClients = [];
    $clientEntityArr = [];

    return $this->render('index-superadmin', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'model' => $model,
        'roles' => $roles,
        'connectClients' => $connectClients,
        'allClients' => $allClients,
        'clientEntityArr' => $clientEntityArr
    ]);
}


    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
   

    public function actionClearBalance($id)
    {
        $this->layout = false;
        return $this->render('clear_balance', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionClearBalanceSave($id)
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $user_data = User::findOne($id);
        $user_data->balance='0';

        Yii::$app->session->setFlash('alert', [
            'options' => ['class' => 'alert-success'],
            'body' => Yii::t('backend', 'Writer balance has been successfully cleared !', [], 'user/index')
        ]);
        if ($user_data->save(false)) {
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    /* ---Get Child roles of User */
        protected function getchildroles($user_id){
            $roles=Yii::$app->authManager->getRolesByUser($user_id);
            /*get current role of user */
            $role=array_shift( $roles)->name;
            /*get child roles and remove current role of user from array*/
            
            if (Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_ADMIN)||Yii::$app->user->can(GlobalConstant::ROLE_HR_MANAGER)||Yii::$app->user->can(GlobalConstant::ROLE_COUNTRY_MANAGER)||Yii::$app->user->can(GlobalConstant::ROLE_DEPARTMENT_MANAGER)||Yii::$app->user->can(GlobalConstant::ROLE_TEAM_MANAGER)) {
                $childRoles = [
                    "HR Manager" => "HR Manager",
                    "Country Manager" => "Country Manager",
                    "Payroll Manager" => "Payroll Manager",
                    "Employee" => "Employee",
                    "Department Manager" => "Department Manager",
                    "Team Manager" => "Team Manager",
                  
                ];
            } else {
                // Else, get child roles dynamically
                $childRoles = ArrayHelper::map(Yii::$app->authManager->getChildRoles($role), 'name', 'name');

                //Remove the user's own role from the list
                ArrayHelper::remove($childRoles, $role);
            }
            return $childRoles;
        }
    public function actionCreate()
    {
        

        // print_r($_POST);DIE;
        $model = new UserForm();
        if(Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN) || Yii::$app->user->can(GlobalConstant::ROLE_HR_MANAGER)){
            $model->setScenario('create');
            $getChildRoles=GlobalConstant::ORGANISATION_ADMIN_ARRAY;
        }else{
            $model->setScenario(UserForm::SCENARIO_CREATE_CLIENTS);
            $getChildRoles=$this->getchildroles(Yii::$app->user->id);
            // remove client-hr role for org-admin to create
            if(Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_ADMIN || Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_MANAGER))|| Yii::$app->user->can(GlobalConstant::ROLE_HR_MANAGER)){
                ArrayHelper::remove($getChildRoles, GlobalConstant::ROLE_CLIENT_HR);
            }
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) { 
            if(Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN))
                Yii::$app->session->setFlash('success', "Organisation-admin Created.");  
            else
                Yii::$app->session->setFlash('success', "User Created.");
            return $this->redirect(['index']);
        }


        //--select clients and assign to user client id if applicant
        $clients=[];
        $organisation=Organisation::find()->where('user_id=:user_id',[':user_id'=>yii::$app->user->id])->one();
        if(!empty($organisation)&&!Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN)|| Yii::$app->user->can(GlobalConstant::ROLE_HR_MANAGER)){
            $clientId = ArrayHelper::getColumn(User::find()->where(['not in','client_id',''])->all(),'client_id');
            $clientModel = Client::find()->where('organisation_id=:organisation_id and user_id=:user_id',[':organisation_id'=>$organisation->id,':user_id'=>yii::$app->user->id])->andWhere(['not in', 'id', $clientId])->all();
        }else{
            // for client to add client-hr
                $clientModel = Client::find()->where('id=:id ',[':id'=>Yii::$app->user->identity->client_id])->all();
        }
            $clients=ArrayHelper::map($clientModel,'id','client_name');


        return $this->render('create', [
            'model' => $model,
            'roles' => $getChildRoles,
            'clients'=>$clients
        ]);
    }

    /**
     * Updates an existing User model.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new UserForm();
        $model->setModel($this->findModel($id));
        if(Yii::$app->user->can('administrator')){
            $getChildRoles=GlobalConstant::ORGANISATION_ADMIN_ARRAY;
        }else{
            $model->scenario = UserForm::SCENARIO_UPDATE_CLIENTS;
            $getChildRoles=$this->getchildroles(Yii::$app->user->id);
            // remove client-hr role for org-admin to create
            if(Yii::$app->user->can(GlobalConstant::ROLE_ORGANISATION_ADMIN)){
                ArrayHelper::remove($getChildRoles, GlobalConstant::ROLE_CLIENT_HR);
            }
        }
        if ($model->load(Yii::$app->request->post()) ) {
            // check if role of user selected is client or not and if not then set the client_id as null
            $userRole = Yii::$app->request->post()['UserForm']['roles'];
            if(!($userRole != GlobalConstant::ROLE_CLIENT || $userRole != GlobalConstant::ROLE_CLIENT_CASE_WORKER || $userRole != GlobalConstant::ROLE_CLIENT_CASE_MANAGER))
            {
                $model->client_id = null;
            }

            if($model->validate()){
                $model->save();
            }
            if (Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN) && isset($_GET['role']) && $_GET['role'] == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER){
                Yii::$app->session->setFlash('success', "Client-Group-Manager Updated.");
                return $this->redirect(['index', 'role' => 'Client Group Manager']);
            }
            elseif(Yii::$app->user->can(GlobalConstant::ROLE_SUPERADMIN))
                Yii::$app->session->setFlash('success', "Organisation-admin Updated.");
            else
                Yii::$app->session->setFlash('success', "User Updated.");
            return $this->redirect(['index']);
        }

        //--select clients and assign to user client id if applicant
        $connectClients=[];
        $organisation=Organisation::find()->where('user_id=:user_id',[':user_id'=>yii::$app->user->id])->one();
        if(!empty($organisation)){//case or user being organisation-admin
            $clientId = ArrayHelper::getColumn(User::find()->where(['not in','client_id',''])->all(),'client_id');
            // $clientModel = Client::find()->where('organisation_id=:organisation_id and user_id=:user_id',[':organisation_id'=>$organisation->id,':user_id'=>yii::$app->user->id])->andWhere(['not in', 'id', $clientId])->all();;
            $clientModel = Client::find()->where('organisation_id=:organisation_id',[':organisation_id'=>$organisation->id])->andWhere(['not in', 'id', $clientId])->all();
        }
        elseif (Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER) {
            $organisation = Organisation::findOne(Yii::$app->user->identity->organisation_id);
            $clientId = ArrayHelper::getColumn(User::find()->where(['not in','client_id',''])->all(),'client_id');
            $clientModel = Client::find()->where('organisation_id=:organisation_id and user_id=:user_id',[':organisation_id'=>$organisation->id,':user_id'=>yii::$app->user->id])->andWhere(['not in', 'id', $clientId])->all();;
        }
        else{
            // for client to add client-hr
                $clientModel = Client::find()->where('id=:id ',[':id'=>Yii::$app->user->identity->client_id])->all();
        }

        // $clients=ArrayHelper::map($clientModel,'id','client_name');
        $connectClients=ArrayHelper::map($clientModel,'id','client_name');
        $allClients = ArrayHelper::map(Client::find()->leftJoin('tbl_client_organisation', 'tbl_client_organisation.client_id = client.id')->andWhere(['tbl_client_organisation.organisation_id' => Yii::$app->user->identity->organisation_id])->all(),'id','client_name');
        // Assuming $model is already defined and contains the user model
        $roles = ArrayHelper::getColumn(
            Yii::$app->authManager->getRolesByUser($model->getModel()->id),
            'name'
        );
        
        // getting first user role considering that the user has only one role in the system
        $userRole = reset($roles);
        
        if($userRole && $userRole == 'Client' && $model->getModel() && $model->getModel()->client_id)
        {
            $assignedClient = Client::findOne($model->getModel()->client_id);
            if($assignedClient)
            {
                $connectClients[$assignedClient->id] = $assignedClient->client_name;
            }
        }
            
        $clientEntityArr = [];
        if (!empty($allClients)) {
            $clientIds = array_keys($allClients); // Get all client IDs
            $clientEntityArr = ArrayHelper::map(
                ClientEntity::find()->where(['client_id' => $clientIds])->all(),
                'id',
                'name'
            );
        }

            


        return $this->render('update', [
            'model' => $model,
            'roles' => $getChildRoles,
            // 'clients'=>$clients
            'connectClients'=>$connectClients,
            'allClients'=>$allClients,
            'clientEntityArr' => $clientEntityArr

        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->authManager->revokeAll($id);
        $this->findModel($id)->delete();
        if (isset($_GET['role']) && $_GET['role'] == GlobalConstant::ROLE_CLIENT_GROUP_MANAGER){
       
        return $this->redirect(['index', 'role' => 'Client Group Manager']);
      }
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
        /* impersonation Function*/
    /**
     * @param $id
     * @return \yii\web\Response
     */

    protected function allowOrganisation($id)
    {
    $allowOrganisationadmin = false;

    if (Yii::$app->user->can('organisation-admin')) {
        $adminOrganisation = Organisation::find()->where(['user_id' => Yii::$app->user->id])->one();
        $impersonateUser = User::findOne($id);

        if ($impersonateUser) {
            if ($impersonateUser->client_id) {
                $clientOrganisations = ClientOrganisation::find()->where(['client_id' => $impersonateUser->client_id])->all();
                foreach ($clientOrganisations as $clientOrg) {
                    if ($adminOrganisation && $adminOrganisation->id == $clientOrg->organisation_id) {
                        $allowOrganisationadmin = true;
                        break;
                    }
                }
            } else {
                $impersonateOrganisationId = $impersonateUser->organisation_id;

                if ($adminOrganisation && $adminOrganisation->id == $impersonateOrganisationId) {
                    $allowOrganisationadmin = true;
                }
            }
        }
    }

    // Client-to-client fallback
    $impersonateUser = User::findOne($id);
    if (!empty(Yii::$app->user->identity->client_id) && $impersonateUser && $impersonateUser->client_id == Yii::$app->user->identity->client_id) {
        $allowOrganisationadmin = true;
    }

    return $allowOrganisationadmin;
    }

    public function actionImpersonate($id)
    {
        $allowOrganisationadmin = $this->allowOrganisation($id);
        $initialId = Yii::$app->user->getId();
        if ($id == $initialId) {

            Yii::$app->session->setFlash('alert', [
                'options' => ['type' => 'error', 'class'=>'alert-error'],
                'body' => Yii::t('backend', 'Cannot impersonate self')
            ]);

            return $this->redirect(Yii::$app->request->referrer);

        }
       else if((Yii::$app->user->can('administrator'))||$allowOrganisationadmin || Yii::$app->user->identity->getRole() == GlobalConstant::ROLE_ORGANISATION_MANAGER){

            $user = User::findOne($id);
              //Change the current user.
           $duration = Yii::$app->user->authTimeout ?? 3600; // ✅ Use proper duration
            Yii::$app->user->switchIdentity($user, $duration);
            Yii::$app->session->set('user.idbeforeswitch', $initialId);
            Yii::$app->session->set('isImpersonating', true);

          
            
           /* SK-Todo: based on the role we can impersonate user multiple level */
           if(empty(Yii::$app->session['user.oldId'])){
               /* SK-Todo: save value in session when impersonate 1 level user */
               Yii::$app->session->set('user.oldId', $initialId);
               Yii::$app->session->set('user.oldRole', 1);
           }
           else if(empty(Yii::$app->session['user.oldTwoId'])){
               /* SK-Todo: save value in session when impersonate 2 level User */
               Yii::$app->session->set('user.oldTwoId', $initialId);
               Yii::$app->session->set('user.oldTwoRole', 2);
           }
            return $this->redirectUserBasedOnRole();

        }
        else{

            Yii::$app->session->setFlash('alert', [
                'options' => ['type' => 'error', 'class'=>'alert-error'],
                'body' => Yii::t('backend', 'Cannot impersonate This User')
            ]);
            // echo '<pre>';
            // print_r(Yii::$app->user->id);
            // echo '<pre>';
            //die('die here');
            return $this->redirect(Yii::$app->request->referrer);

        }
    }

    // private function redirectUserBasedOnRole()
    // {
    // $user = Yii::$app->user->identity;

    // if ($user->role == 'HR Manager') {
    //     return Yii::$app->response->redirect(['/employee/index']);
    // } elseif ($user->role == 'Employee') {
    //     return Yii::$app->response->redirect(['/leave-request/index']);
    // } else {
    //     return Yii::$app->response->redirect(['/user/index']);
    // }
    // }
    private function redirectUserBasedOnRole()
{
    $user = Yii::$app->user->identity;

    if ($user->role == 'HR Manager') {
        return $this->redirect(['/employee/index']);
    } elseif ($user->role == 'Employee') {
        return $this->redirect(['/leave-request/index']);
    }elseif ($user->role == 'Team Manager') {
        return $this->redirect(['/leave-request/approve']);
    } else {
        return $this->redirect(['/user/index']);
    }
}


    public function actionUnimpersonate()
    {
        $initialId = Yii::$app->session->get('user.oldid');
        $originalId = Yii::$app->session->get('user.idbeforeswitch');

        if ($originalId) {
            $user = User::findOne($originalId);
            $duration = 3600;
            Yii::$app->user->switchIdentity($user, $duration);
            //            \Yii::$app->session->set('user.email',$user->email);
            //            \Yii::$app->session->set('user.username',$user->username);
            //            \Yii::$app->session->set('user.uid',$user->id);
            //            \Yii::$app->session->set('user.avatar',Yii::$app->user->identity->userProfile->getAvatar(Yii::$app->urlManager->getBaseUrl('').'/img/anonymous.jpg'));
            //            //setSessionValues($user);
            //            Yii::$app->session->set('user.idbeforeswitch',Yii::$app->session->get('user.oldid'));
            //
            //            if ($initialId==Yii::$app->session->get('user.idbeforeswitch')) {
            //                Yii::$app->session->remove('user.oldid');
            //            }
            //
            //            if ($initialId==$originalId) {
            //                Yii::$app->session->remove('user.idbeforeswitch');
            //            }
            //            echo '<pre>';
            //            print_r(Yii::$app->user->id);
            //            echo '<pre>';
            //die('die here');
            if(!empty(Yii::$app->session['user.oldTwoRole'])){
                /* SK-Todo: unimpersonate user 2nd level if exist */
                Yii::$app->session->remove('isImpersonating');
                Yii::$app->session->remove('user.oldTwoId');
                Yii::$app->session->remove('user.oldTwoRole');

                /* SK-Todo: remove 2nd level and add value in 1st level */
                Yii::$app->session->set('user.idbeforeswitch', Yii::$app->session['user.oldId']);
            }
            else if(!empty(Yii::$app->session['user.oldRole'])){
                /* SK-Todo: unimpersonate user 1st level if exist */
                Yii::$app->session->remove('user.oldId');
                Yii::$app->session->remove('user.oldRole');
                /* SK-Todo: remove 1st level user */
                Yii::$app->session->remove('user.idbeforeswitch');

            }
            Yii::$app->session->setFlash('alert', [
                'options' => ['type' => 'sucess', 'class'=>'alert-success'],
                'body' => Yii::t('backend', 'Successfully Unimpersonated')
            ]);
        } else {
            Yii::$app->session->setFlash('alert', [
                'options' => ['type' => 'error', 'class'=>'alert-error'],
                'body' => Yii::t('backend', 'Failed unimpersonating')
            ]);
            // echo '<pre>';
            // print_r(Yii::$app->user->id);
            // echo '<pre>';
            //die('die here');
        }

        return $this->redirect(['user/index']);
    }
    public function actionGetClientEntities()
    {
        if(isset($_GET))
        {
            $data = ArrayHelper::map(
                ClientEntity::find()->where(['client_id' => $_GET['clientId']])->all(),'id','name'
            );
            return json_encode($data);
            
        }
    }

   
    }

   

