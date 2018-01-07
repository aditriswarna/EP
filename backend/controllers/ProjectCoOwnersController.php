<?php

namespace backend\controllers;

use Yii;
use backend\models\ProjectCoOwners;
use backend\models\Projects;
use backend\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use common\models\EmailTemplates;
use common\models\Communique;
/**
 * ProjectCoOwnersController implements the CRUD actions for ProjectCoOwners model.
 */
class ProjectCoOwnersController extends Controller
{
    public function behaviors()
    {
        return [
            'ghost-access' => [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
            /* 'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ], */
        ];
    }

    /**
     * Lists all ProjectCoOwners models.
     * @return mixed
     */
    public function actionIndex()
    {
//        $dataProvider = new ActiveDataProvider([
//            'query' => ProjectCoOwners::find(),
//        ]);
        $mysqldateformat = Yii::getAlias('@mysqldateformat');
        $project = Projects::find()->where(['project_id' => Yii::$app->getRequest()->getQueryParam('id')])->one();


        $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM project_co_owners WHERE project_ref_id = '.Yii::$app->getRequest()->getQueryParam('id'))->queryScalar();
        
        $sql = 'SELECT `projects`.`project_id`, `project_title`, fname, lname, `email`, DATE_FORMAT(`project_co_owners`.`created_date`, "'.$mysqldateformat.'") as created_date, `project_co_owner_id`'
                . 'FROM `projects` JOIN `project_co_owners` ON projects.project_id = project_co_owners.project_ref_id '
                . 'JOIN `user_profile` ON user_profile.user_ref_id = project_co_owners.user_ref_id '
                . 'JOIN user ON user.id = project_co_owners.user_ref_id AND project_ref_id = '.Yii::$app->getRequest()->getQueryParam('id')
                . ' GROUP BY project_co_owners.project_ref_id, project_co_owners.user_ref_id';
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        $dataProvider->pagination->pageSize=10;

        $dataProvider->setSort([
            'attributes' => [
                'fname',
                'lname',
                'email',
                'created_date',
            ]
        ]);
        
        
        /*
        $dataProvider2 = new ActiveDataProvider([
            'query' => User::find()->where(['user_type_ref_id' => Yii::$app->session->get('userType')])->orderBy('email'),
        ]);
        */
        
        //echo 'In Index Page'; die;
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'project' => $project,
        ]);
    }

    /**
     * Displays a single ProjectCoOwners model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$project = Projects::find()->where(['project_id' => Yii::$app->getRequest()->getQueryParam('id')])->one();
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'project' => $project,
        ]);
    }

    /**
     * Creates a new ProjectCoOwners model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProjectCoOwners();
        
        $project = Projects::find()->where(['project_id' => Yii::$app->getRequest()->getQueryParam('id')])->one();
        $userTypeId = Yii::$app->db->createCommand('SELECT u.user_type_ref_id FROM `projects` p, `user` u WHERE p.user_ref_id = u.id AND project_id = '.Yii::$app->getRequest()->getQueryParam('id'))->queryScalar();;
        
        $sql = "SELECT CONCAT(fname, ' ', lname, ' , ', user_type, ' , ', email) as value, id as id  FROM `user`  u 
                LEFT JOIN project_co_owners pc ON pc.user_ref_id = u.id
                JOIN user_profile up ON u.id = up.user_ref_id
                JOIN user_type ut ON u.user_type_ref_id = ut.user_type_id 
                WHERE u.id !=".$project->user_ref_id." AND u.user_type_ref_id = ". $userTypeId." AND u.status = 1
                AND u.id NOT IN(SELECT user_ref_id FROM project_co_owners WHERE project_ref_id = ".Yii::$app->getRequest()->getQueryParam('id').") 
                GROUP BY u.id ORDER BY email";
        
        $users = Yii::$app->db->createCommand($sql)->queryAll();
        //$users = User::find()->where('id != '.Yii::$app->user->identity->id .' AND user_type_ref_id = '. Yii::$app->session->get('userType').' AND status = 1')->orderBy('email')->all();
        
        //print_r($users);
        
        $model->created_by = Yii::$app->user->identity->id;
        $model->created_date = date('Y-m-d H:i:s');
        
        $result = ProjectCoOwners::find()->where(['project_ref_id' => Yii::$app->request->post('ProjectCoOwners')['project_ref_id'], 'user_ref_id' => Yii::$app->request->post('ProjectCoOwners')['user_ref_id']])->one();
        //print_r($_POST);
        //die;
        
        if(count($result) == 0)
        {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $last_inserted_id = $model->project_co_owner_id;
                $coowner = \frontend\models\User::getUserDetails($_POST['ProjectCoOwners']['user_ref_id']);
                $projectdata = \frontend\models\Projects::getProjectCreatorDetails($_POST['ProjectCoOwners']['project_ref_id']);
                $userdata = \frontend\models\User::getUserDetails($projectdata[0]['id']);
                $projectCoowners = ProjectCoOwners::getProjectCoownerDetails($_POST['ProjectCoOwners']['project_ref_id'],$_POST['ProjectCoOwners']['user_ref_id']);
                //$adminUsers = User::find()->where(['user_type_ref_id' => $userdata[0]['user_type_ref_id'], 'user_role_ref_id' => 1])->all();
                $adminUsers =  Yii::$app->db->createCommand("SELECT * FROM `user` AS u 
                    LEFT JOIN admin_assigned_user_types AS au ON au.user_ref_id=u.id
                    WHERE user_role_ref_id=1 AND u.status=1 AND au.user_type_ref_id=".$userdata[0]['user_type_ref_id'])->queryAll();
                $coowner_details = \frontend\models\UserProfileByUsertype::find()->where(['user_ref_id' => $_POST['ProjectCoOwners']['user_ref_id']])->one();
                $coowner_name = isset($coowner[0]['fname'])? (ucwords($coowner[0]['fname']).' '.ucwords($coowner[0]['lname'])): ucwords($coowner_details->company_name);
                /*Yii::$app->mailer->compose('coownerToCreator', 
                [
                'userdata'=> $userdata,
                'coowner'=> $coowner,
                'projectdata'=> $projectdata,    
                'title'      => Yii::t('app', 'Project Participation'),
                'htmlLayout' => 'layouts/html'
                ])
                ->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                ->setTo($userdata[0]['email'])
                ->setSubject('Co-owner Created Successfully')
                ->send();
                
                Yii::$app->mailer->compose('coownerUser', 
                [
                'coowner'=> $userdata,
                'userdata'=> $coowner,
                'projectdata'=> $projectdata,
                'coownerpk' => Yii::$app->db->getLastInsertID(),
                'title'      => Yii::t('app', 'Project Participation'),
                'htmlLayout' => 'layouts/html'
                ])
                ->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                ->setTo($coowner[0]['email'])
                ->setSubject('You are added as co-owner')
                ->send();
                
                Yii::$app->mailer->compose('coownerToAdmin', 
                [
                'userdata'=> $userdata,
                'coowner'=> $coowner,
                'projectdata'=> $projectdata,    
                'title'      => Yii::t('app', 'Project Participation'),
                'htmlLayout' => 'layouts/html'
                ])
                ->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                ->setTo('equippp.donotreply@gmail.com')
                ->setSubject('A New Co-owner is added')
                ->send();*/
                
                $emailtemplate1 = EmailTemplates::getEmailTemplate(21);
                    $body=str_replace("{username}", $coowner_name, $emailtemplate1[2]['descrition']);
                    $body=str_replace("{coownername}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $body);
                    $body=str_replace("{site_url}", SITE_URL.'/', $body);
                    $body=str_replace("{cownerpk}", base64_encode($last_inserted_id), $body);
                    $body=str_replace("{iscowner}", base64_encode(1), $body);
                    $body=str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body);
                    $body=str_replace("{prjname}", base64_encode($projectdata[0]['project_title']), $body);
                    $body=str_replace("{isnotcowner}", base64_encode(0), $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate1[0]['descrition'].$body.$emailtemplate1[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($coowner[0]['email'])
                    ->setSubject($emailtemplate1[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
                Communique::saveMailData($_POST['ProjectCoOwners']['project_ref_id'], $coowner[0]['id'], $emailtemplate1[2]['subject'], $body, $coowner[0]['email'], 'Unread', Yii::$app->user->identity->id);
                
                $emailtemplate2 = EmailTemplates::getEmailTemplate(22);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $emailtemplate2[2]['descrition']);
                    $body=str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body);
                    $body=str_replace("{coownername}", $coowner_name, $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate2[0]['descrition'].$body.$emailtemplate2[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($userdata[0]['email'])
                    ->setSubject($emailtemplate2[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
                Communique::saveMailData($_POST['ProjectCoOwners']['project_ref_id'], $userdata[0]['id'], $emailtemplate2[2]['subject'], $body, $coowner[0]['email'], 'Unread', Yii::$app->user->identity->id);
                    
                    
                    $emailtemplate3 = EmailTemplates::getEmailTemplate(23);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $emailtemplate3[2]['descrition']);
                    $body=str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body);
                    $body=str_replace("{cownername}", $coowner_name, $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate3[0]['descrition'].$body.$emailtemplate3[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo('equippp.noreply@gmail.com')
                    ->setSubject($emailtemplate3[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();    
                Communique::saveMailData($_POST['ProjectCoOwners']['project_ref_id'], 1, $emailtemplate3[2]['subject'], $body, 'equippp.noreply@gmail.com', 'Unread', Yii::$app->user->identity->id);
                
                
                if ($adminUsers) {
                    foreach ($adminUsers as $admins) {
                    $emailtemplate4 = EmailTemplates::getEmailTemplate(23);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $emailtemplate4[2]['descrition']);
                    $body=str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body);
                    $body=str_replace("{cownername}", $coowner_name, $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate4[0]['descrition'].$body.$emailtemplate4[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($admins['email'])
                    ->setSubject($emailtemplate4[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();    
                Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), $admins['id'], $emailtemplate4[2]['subject'], $body, $admins['email'], 'Unread', 1);    
                    }
            }
            
            if ($projectCoowners) {
                    foreach ($projectCoowners as $prjCoowners) {
                    $emailtemplate5 = EmailTemplates::getEmailTemplate(51);
                    $body = str_replace("{coowner}", ucwords($prjCoowners['fname']) . ' ' . ($prjCoowners['lname']), $emailtemplate5[2]['descrition']);
                    $body=str_replace("{cownername}", $coowner_name, $body);
                    $body=str_replace("{projectname}", ucwords($project->project_title), $body);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate5[0]['descrition'].$body.$emailtemplate5[1]['descrition'];
                    $subject=str_replace("{projectname}", $project->project_title, $emailtemplate5[2]['subject']);
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($prjCoowners['email'])
                    ->setSubject($subject)
                    ->setHtmlBody($body)
                    ->send();
                    Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), $prjCoowners['id'], $subject, $body, $prjCoowners['email'], 'Unread', 1);                                            
                    }
            }
                
                return $this->redirect(['index', 'id' => $project->project_id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'project' => $project, 
                    'users' => $users,
                ]);
            }
        } else {
            return $this->redirect(['index', 'id' => $project->project_id]);
        }
    }

    /**
     * Updates an existing ProjectCoOwners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->project_co_owner_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProjectCoOwners model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $project = ProjectCoOwners::find()->where(['project_co_owner_id' => Yii::$app->request->get('id')])->all();
        $this->findModel($id)->delete();

        return $this->redirect(['index?id='.$project[0]['project_ref_id']]);
    }

    /**
     * Finds the ProjectCoOwners model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProjectCoOwners the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProjectCoOwners::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionCoownerToUser()
    {
        $is_coowner = base64_decode(Yii::$app->request->get('is_coowner'));
        $cpk = base64_decode(Yii::$app->request->get('cpk'));
        $pname = base64_decode(Yii::$app->request->get('pname'));
        $project = ProjectCoOwners::find()->where(['project_co_owner_id' => $cpk])->one(); 
        $userDetails = \frontend\models\User::getUserDetails($project->user_ref_id);
        $projectdata = \frontend\models\Projects::getProjectCreatorDetails($project->project_ref_id);
        $projectCoowners = ProjectCoOwners::getProjectCoownerDetails($project->project_ref_id,$project->user_ref_id);
        //$adminUsers = User::find()->where(['user_type_ref_id' => $userDetails[0]['user_type_ref_id'], 'user_role_ref_id' => 1])->all();   
        $adminUsers =  Yii::$app->db->createCommand("SELECT * FROM `user` AS u 
						LEFT JOIN admin_assigned_user_types AS au ON au.user_ref_id=u.id
						WHERE user_role_ref_id=1 AND u.status=1 AND au.user_type_ref_id=".$userDetails[0]['user_type_ref_id'])->queryAll();
        $project->is_coowner=$is_coowner;
        if($project->save(false)){
             if($is_coowner == 1){
                $emailtemplate1 = EmailTemplates::getEmailTemplate(24); 
                $emailtemplate2 = EmailTemplates::getEmailTemplate(52); 
                $emailtemplate3 = EmailTemplates::getEmailTemplate(54); 
            }else{
                $emailtemplate1 = EmailTemplates::getEmailTemplate(25);     
                $emailtemplate2 = EmailTemplates::getEmailTemplate(53); 
                $emailtemplate3 = EmailTemplates::getEmailTemplate(55); 
            }
                    $body=str_replace("{projectcreatorname}", ucwords($projectdata[0]['fname']).' '.ucwords($projectdata[0]['lname']), $emailtemplate[2]['descrition']);
                    $body=str_replace("{username}", ucwords($userDetails[0]['fname']).' '.ucwords($userDetails[0]['lname']), $body);
                    $body=str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body);
                    $body=$emailtemplate[0]['descrition'].$body.$emailtemplate[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($projectdata[0]['email'])
                    ->setSubject($emailtemplate[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
            Communique::saveMailData($project->project_ref_id, $projectdata[0]['id'], $emailtemplate[2]['subject'], $body, $projectdata[0]['email'], 'Unread', 1);         
            
            if ($adminUsers) {
                    foreach ($adminUsers as $admins) {
                    $body3=str_replace("{username}", ucwords($userDetails[0]['fname']).' '.ucwords($userDetails[0]['lname']),  $emailtemplate2[2]['descrition']);
                    $body3=str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body3);
                    $body3=$emailtemplate2[0]['descrition'].$body3.$emailtemplate2[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($admins['email'])
                    ->setSubject($emailtemplate2[2]['subject'])
                    ->setHtmlBody($body3)
                    ->send();
            Communique::saveMailData($project->project_ref_id, $admins['id'], $emailtemplate2[2]['subject'], $body3, $admins['email'], 'Unread', 1); 
                    }
            }
            
            if ($projectCoowners) {
                    foreach ($projectCoowners as $coowners) {
                    $body4=str_replace("{coowner}", ucwords($coowners['fname']).' '.ucwords($coowners['lname']), $emailtemplate3[2]['descrition']);
                    $body4=str_replace("{username}", ucwords($userDetails[0]['fname']).' '.ucwords($userDetails[0]['lname']), $body4);
                    $body4=str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body4);
                    $body4=$emailtemplate3[0]['descrition'].$body4.$emailtemplate3[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($coowners['email'])
                    ->setSubject($emailtemplate3[2]['subject'])
                    ->setHtmlBody($body4)
                    ->send();
            Communique::saveMailData($project->project_ref_id, $coowners['id'], $emailtemplate3[2]['subject'], $body4, $coowners['email'], 'Unread', 1); 
                    }
            }
        }    

       if($is_coowner == 1){
		echo '<p>You are the co-owner of the project <strong>'.$pname.'</strong></br>You have access to view and access the project details.</p></br><a href="'.SITE_URL.'equippp/frontend/web/index.php">Go to EquiPPP</a>';
			}else{
			echo '<p>You have declined to be a co-owner of the project <strong>'.$pname.'</strong></p></br><a href="'.SITE_URL.'equippp/frontend/web/index.php">Go to EquiPPP</a>';
			}
    }
}
