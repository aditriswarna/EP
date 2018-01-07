<?php
namespace frontend\controllers;

use Yii;
use frontend\models\ProjectParticipation;
use frontend\models\Projects;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use common\models\EmailTemplates;
use common\models\Communique;
use frontend\models\ProjectCoOwners;
use frontend\models\User;
/**
 * ProjectParticipationController implements the CRUD actions for ProjectParticipation model.
 */
class ProjectParticipationController extends Controller
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
        ];
    }
    
    public function beforeAction($action) {
        
        if (\Yii::$app->getUser()->isGuest &&
            \Yii::$app->getRequest()->url !== \yii\helpers\Url::to(\Yii::$app->getUser()->loginUrl)) {
            \Yii::$app->getResponse()->redirect(Yii::$app->request->BaseUrl.'/site/login');
        }
        else {
            return parent::beforeAction($action);
        }
    }

    /**
     * Lists all ProjectParticipation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = '/main2';
        $projectID = Yii::$app->getRequest()->getQueryParam('id');
        $mysqldateformat = Yii::getAlias('@mysqldateformat');
        $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM project_participation WHERE project_ref_id = ' . Yii::$app->getRequest()->getQueryParam('id'))->queryScalar();
        $sql = 'SELECT DISTINCT `projects`.`project_id`, `project_title`, `project_participation`.`user_ref_id`, `project_participation_id`, `participation_type`, `investment_type`, `equity_type`, `amount`, `interest_rate`, DATE_FORMAT(`project_participation`.`created_date`, "'.$mysqldateformat.'") as created_date, CONCAT(`fname`, `lname`) as username '
                . 'FROM `projects` JOIN project_participation ON project_participation.project_ref_id = projects.project_id '
                . 'JOIN user_profile ON project_participation.user_ref_id = user_profile.user_ref_id '
                . 'WHERE project_ref_id = ' . Yii::$app->getRequest()->getQueryParam('id');
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
                'project_title',
                'username',
                'participation_type',
                'investment_type',
                'equity_type',
                'amount',
                'interest_rate',
                'created_date',
            ]
        ]);
        
        

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'projectID' => $projectID,
        ]);
    }

    /**
     * Displays a single ProjectParticipation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$mysqldateformat = Yii::getAlias('@mysqldateformat');
        $query = new \yii\db\Query();
        $query	->select([
                        'projects.project_id', 
                        'project_title', 
                        'project_participation.user_ref_id', 
                        'project_participation_id', 
                        'project_ref_id',
                        'participation_type', 
                        'investment_type', 
                        'REPLACE(equity_type, "_", " ") as equity_type', 
                        'amount', 
                        'interest_rate', 
                        'DATE_FORMAT(project_participation.created_date,"'.$mysqldateformat.'") as created_date', 
                        'CONCAT(fname, " ", lname) as username', ]
                        )  
                ->from('projects')
                ->join('JOIN', 'project_participation', 'project_participation.project_ref_id = projects.project_id')
                ->join('JOIN', 'user_profile', 'user_profile.user_ref_id = project_participation.user_ref_id')
                ->where('project_participation_id = ' . Yii::$app->getRequest()->getQueryParam('id')); 
        
        $command = $query->createCommand();
        $data = $command->queryAll();
        
        //print_r($data); die;
        return $this->renderPartial('view', [
            'model' => $data[0],
        ]);
    }

    /**
     * Creates a new ProjectParticipation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $id = Yii::$app->getRequest()->getQueryParam('id');
        /*$app_name = strpos(strtolower(yii::$app->request->Url), yii::$app->name ) !== false?'/'.yii::$app->name:'';        
        if(strtolower(yii::$app->request->Url) != "$app_name/participate?id=$id"){
            $this->redirect(Yii::$app->urlManager->createAbsoluteUrl("../../participate?id=$id"), 301);
        }*/
        
        $this->layout = '/main2';
        $model = new ProjectParticipation();
        $project = Projects::find()->where(['project_id' => Yii::$app->getRequest()->getQueryParam('id')])->one();
        $projectParticipation = ProjectParticipation::find()->where(['project_ref_id' => Yii::$app->getRequest()->getQueryParam('id'),'user_ref_id' => Yii::$app->user->identity->id])->one();
        
        //$model->project_ref_id = Yii::$app->request->post('project_id');
        //$model->user_ref_id = Yii::$app->request->id;
        $model->created_by = Yii::$app->user->identity->id;
        $model->created_date = date('Y-m-d H:i:s');
        if ($model->load(Yii::$app->request->post())) {
            if($projectParticipation){
            Yii::$app->db->createCommand()->delete('project_participation', 'project_participation_id=' .$projectParticipation->project_participation_id)->query();
        }
            if($model->save()){
            $userdata = \frontend\models\User::getUserDetails(Yii::$app->user->identity->id);
            $projectcreator = Projects::getProjectCreatorDetails($_POST['ProjectParticipation']['project_ref_id']);
            $projectCoowners = ProjectCoOwners::getProjectCoownerDetails($_POST['ProjectParticipation']['project_ref_id'],'');
        //$adminUsers = User::find()->where(['user_type_ref_id' => $projectcreator[0]['user_type_ref_id'], 'user_role_ref_id' => 1])->all();
        $adminUsers =  Yii::$app->db->createCommand("SELECT * FROM `user` AS u 
            LEFT JOIN admin_assigned_user_types AS au ON au.user_ref_id=u.id
            WHERE user_role_ref_id=1 AND u.status=1 AND au.user_type_ref_id=".$projectcreator[0]['user_type_ref_id'])->queryAll();

          //  $psql = 'SELECT (SUM(amount)) AS amount_recieved FROM project_participation WHERE project_ref_id='.$_POST['ProjectParticipation']['project_ref_id'];
           // $amount_recieved = yii::$app->db->createCommand($psql)->queryAll();
           
         //   $project->total_participation_amount = $amount_recieved[0]['amount_recieved'];
          //  $project->save(false);
            $emailtemplate1 = EmailTemplates::getEmailTemplate(26);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $emailtemplate1[2]['descrition']);
                    $body=str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate1[0]['descrition'].$body.$emailtemplate1[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($userdata[0]['email'])
                    ->setSubject($emailtemplate1[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), $userdata[0]['id'], $emailtemplate1[2]['subject'], $body, $userdata[0]['email'], 'Unread', 1);                            
                 
            if($projectcreator[0]['id'] != $userdata[0]['id'])
                {
            $emailtemplate2 = EmailTemplates::getEmailTemplate(27);
                    $body=str_replace("{creatorname}", ucwords($projectcreator[0]['fname']).' '.ucwords($projectcreator[0]['lname']), $emailtemplate2[2]['descrition']);
                    $body=str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $body);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $body);
                    $body=str_replace("{useremail}", $userdata[0]['email'], $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate2[0]['descrition'].$body.$emailtemplate2[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($projectcreator[0]['email'])
                    ->setSubject($emailtemplate2[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), $projectcreator[0]['id'], $emailtemplate2[2]['subject'], $body, $projectcreator[0]['email'], 'Unread', 1);                                    
                }       
            $emailtemplate3 = EmailTemplates::getEmailTemplate(28);
                    $body=str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $emailtemplate3[2]['descrition']);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $body);
                    $body=str_replace("{useremail}", $userdata[0]['email'], $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate3[0]['descrition'].$body.$emailtemplate3[1]['descrition'];
                    $subject=str_replace("{projectname}", $projectcreator[0]['project_title'], $emailtemplate3[2]['subject']);
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo('equippp.noreply@gmail.com')
                    ->setSubject($subject)
                    ->setHtmlBody($body)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), 1, $subject, $body, 'equippp.noreply@gmail.com', 'Unread', 1);                                            
            
            if ($adminUsers) {
                    foreach ($adminUsers as $admins) {
             $emailtemplate4 = EmailTemplates::getEmailTemplate(28);
                    $body=str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $emailtemplate4[2]['descrition']);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $body);
                    $body=str_replace("{useremail}", $userdata[0]['email'], $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate4[0]['descrition'].$body.$emailtemplate4[1]['descrition'];
                    $subject=str_replace("{projectname}", $projectcreator[0]['project_title'], $emailtemplate4[2]['subject']);
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($admins['email'])
                    ->setSubject($subject)
                    ->setHtmlBody($body)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), $admins['id'], $subject, $body, $admins['email'], 'Unread', 1);                                            
                    }
            }
            
            if ($projectCoowners) {
                    foreach ($projectCoowners as $prjCoowners) {
             $emailtemplate5 = EmailTemplates::getEmailTemplate(50);
                    $body = str_replace("{coowner}", ucwords($prjCoowners['fname']) . ' ' . ($prjCoowners['lname']), $emailtemplate5[2]['descrition']);
                    $body=str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $body);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $body);
                    $body=str_replace("{useremail}", $userdata[0]['email'], $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate5[0]['descrition'].$body.$emailtemplate5[1]['descrition'];
                    $subject=str_replace("{projectname}", $projectcreator[0]['project_title'], $emailtemplate5[2]['subject']);
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($prjCoowners['email'])
                    ->setSubject($subject)
                    ->setHtmlBody($body)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), $prjCoowners['id'], $subject, $body, $prjCoowners['email'], 'Unread', 1);                                            
                    }
            }
             
            Yii::$app->session->setFlash('success', "You have participated successfully!");
            return $this->redirect(Yii::$app->request->referrer);
            
            //return $this->redirect(['index', 'id' => Yii::$app->getRequest()->getQueryParam('id')]);            
            
            // return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl("../../project-participation?id=$id"));                
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'project' => $project,
            ]);
        }
    }

    /**
     * Updates an existing ProjectParticipation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->layout = '/main2';
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->project_participation_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProjectParticipation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->layout = '/main2';
        $project = ProjectParticipation::find()->where(['project_participation_id' => Yii::$app->request->get('id')])->all();
        //print_r($project); die();
        $this->findModel($id)->delete();
        
        $project_ref_id = $project[0]['project_ref_id'];
        
        return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl("../../project-participation?id=$project_ref_id")); 
        
        // return $this->redirect(['index', 'id' => $project[0]['project_ref_id']]);
    }

    /**
     * Finds the ProjectParticipation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProjectParticipation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $this->layout = '/main2';
        if (($model = ProjectParticipation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
   public function  actionAjaxcreate(){
         $this->layout = false;
         $msg="";
         $model = new ProjectParticipation();
         $project = Projects::find()->where(['project_id' => Yii::$app->getRequest()->getQueryParam('id')])->one();
        // print_r($project);
        // exit;
         if(isset($_POST['ProjectParticipation']) && $_POST['ProjectParticipation']!="")
        {
         extract($_POST['ProjectParticipation']);
         $model->project_ref_id=$project_ref_id;
         $model->user_ref_id=$user_ref_id;
         $model->participation_type=$participation_type;
         $model->amount=$amount;
         if(isset($equity_type))
            $model->equity_type=$equity_type;
         $model->interest_rate=$interest_rate;
         $model->created_by = Yii::$app->user->identity->id;
         $model->created_date = date('Y-m-d H:i:s');
         if(isset($investment_type))
            $model->investment_type=$investment_type;
      //  print_r($model);
         
       $projectParticipation = ProjectParticipation::find()->where(['project_ref_id' => Yii::$app->getRequest()->getQueryParam('id'),'user_ref_id' => Yii::$app->user->identity->id])->one();
   
            if($projectParticipation){
            // $msg="You Have already participated in this project";
             Yii::$app->db->createCommand()->delete('project_participation', 'project_participation_id=' .$projectParticipation->project_participation_id)->query();
           }
        if($model->save()){ 
            
            $userdata = \frontend\models\User::getUserDetails(Yii::$app->user->identity->id);
            $projectcreator = Projects::getProjectCreatorDetails($_POST['ProjectParticipation']['project_ref_id']);
            $projectCoowners = ProjectCoOwners::getProjectCoownerDetails($_POST['ProjectParticipation']['project_ref_id'],'');
        //$adminUsers = User::find()->where(['user_type_ref_id' => $projectcreator[0]['user_type_ref_id'], 'user_role_ref_id' => 1])->all();
        $adminUsers =  Yii::$app->db->createCommand("SELECT * FROM `user` AS u 
            LEFT JOIN admin_assigned_user_types AS au ON au.user_ref_id=u.id
            WHERE user_role_ref_id=1 AND u.status=1 AND au.user_type_ref_id=".$projectcreator[0]['user_type_ref_id'])->queryAll();

          //  $psql = 'SELECT (SUM(amount)) AS amount_recieved FROM project_participation WHERE project_ref_id='.$_POST['ProjectParticipation']['project_ref_id'];
           // $amount_recieved = yii::$app->db->createCommand($psql)->queryAll();
           
         //   $project->total_participation_amount = $amount_recieved[0]['amount_recieved'];
          //  $project->save(false);
            $emailtemplate1 = EmailTemplates::getEmailTemplate(26);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $emailtemplate1[2]['descrition']);
                    $body=str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate1[0]['descrition'].$body.$emailtemplate1[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($userdata[0]['email'])
                    ->setSubject($emailtemplate1[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), $userdata[0]['id'], $emailtemplate1[2]['subject'], $body, $userdata[0]['email'], 'Unread', 1);                            
                 
            if($projectcreator[0]['id'] != $userdata[0]['id'])
                {
            $emailtemplate2 = EmailTemplates::getEmailTemplate(27);
                    $body=str_replace("{creatorname}", ucwords($projectcreator[0]['fname']).' '.ucwords($projectcreator[0]['lname']), $emailtemplate2[2]['descrition']);
                    $body=str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $body);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $body);
                    $body=str_replace("{useremail}", $userdata[0]['email'], $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate2[0]['descrition'].$body.$emailtemplate2[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($projectcreator[0]['email'])
                    ->setSubject($emailtemplate2[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), $projectcreator[0]['id'], $emailtemplate2[2]['subject'], $body, $projectcreator[0]['email'], 'Unread', 1);                                    
                }       
            $emailtemplate3 = EmailTemplates::getEmailTemplate(28);
                    $body=str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $emailtemplate3[2]['descrition']);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $body);
                    $body=str_replace("{useremail}", $userdata[0]['email'], $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate3[0]['descrition'].$body.$emailtemplate3[1]['descrition'];
                    $subject=str_replace("{projectname}", $projectcreator[0]['project_title'], $emailtemplate3[2]['subject']);
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo('equippp.noreply@gmail.com')
                    ->setSubject($subject)
                    ->setHtmlBody($body)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), 1, $subject, $body, 'equippp.noreply@gmail.com', 'Unread', 1);                                            
            
            if ($adminUsers) {
                    foreach ($adminUsers as $admins) {
             $emailtemplate4 = EmailTemplates::getEmailTemplate(28);
                    $body=str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $emailtemplate4[2]['descrition']);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $body);
                    $body=str_replace("{useremail}", $userdata[0]['email'], $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate4[0]['descrition'].$body.$emailtemplate4[1]['descrition'];
                    $subject=str_replace("{projectname}", $projectcreator[0]['project_title'], $emailtemplate4[2]['subject']);
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($admins['email'])
                    ->setSubject($subject)
                    ->setHtmlBody($body)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), $admins['id'], $subject, $body, $admins['email'], 'Unread', 1);                                            
                    }
            }
            
            if ($projectCoowners) {
                    foreach ($projectCoowners as $prjCoowners) {
             $emailtemplate5 = EmailTemplates::getEmailTemplate(50);
                    $body = str_replace("{coowner}", ucwords($prjCoowners['fname']) . ' ' . ($prjCoowners['lname']), $emailtemplate5[2]['descrition']);
                    $body=str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $body);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $body);
                    $body=str_replace("{useremail}", $userdata[0]['email'], $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate5[0]['descrition'].$body.$emailtemplate5[1]['descrition'];
                    $subject=str_replace("{projectname}", $projectcreator[0]['project_title'], $emailtemplate5[2]['subject']);
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($prjCoowners['email'])
                    ->setSubject($subject)
                    ->setHtmlBody($body)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), $prjCoowners['id'], $subject, $body, $prjCoowners['email'], 'Unread', 1);                                            
                    }
            }
            
            
            
            return 'You have successfully participated in the project';
          //   echo "You have successfully participated in the project";
 }
        
 else
     
 {
     
    print_r($model->getErrors());
 }
 }
else{
        
               
      return $this->renderPartial('create_as_pop', [
                'model' => $model,
                'project' => $project,
            ]);
   
   }
   }

   
 /*public function  actionAjaxsave(){
        $this->layout = false;
        if(isset($_POST['ProjectParticipation']) && $_POST['ProjectParticipation']!="")
        {
         extract($_POST['ProjectParticipation']);
         $model = new ProjectParticipation();
         $model->project_ref_id=$project_ref_id;
         $model->user_ref_id=$user_ref_id;
         $model->participation_type=$participation_type;
         $model->amount=$amount;
         if(isset($equity_type))
         $model->equity_type=$equity_type;
         $model->interest_rate=$interest_rate;
         $model->created_by = Yii::$app->user->identity->id;
         $model->created_date = date('Y-m-d H:i:s');
         if(isset($investment_type))
         $model->investment_type=$investment_type;
      //  print_r($model);
        if($model->save()){ 
            $project = Projects::find()->where(['project_id' => $project_ref_id])->one();
            $userdata = \frontend\models\User::getUserDetails(Yii::$app->user->identity->id);
            $projectcreator = Projects::getProjectCreatorDetails($_POST['ProjectParticipation']['project_ref_id']); 
            $psql = 'SELECT (SUM(amount)) AS amount_recieved FROM project_participation WHERE project_ref_id='.$_POST['ProjectParticipation']['project_ref_id'];
            $amount_recieved = yii::$app->db->createCommand($psql)->queryAll();

            $project->total_participation_amount =$amount_recieved[0]['amount_recieved'];
            $project->save(false);
           
             $emailtemplate1 = EmailTemplates::getEmailTemplate(26);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $emailtemplate1[2]['descrition']);
                    $body=str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate1[0]['descrition'].$body.$emailtemplate1[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($userdata[0]['email'])
                    ->setSubject($emailtemplate1[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), $projectcreator[0]['id'], $emailtemplate1[2]['subject'], $body, $userdata[0]['email'], '', Yii::$app->user->identity->id);                            
                    
            $emailtemplate2 = EmailTemplates::getEmailTemplate(27);
                    $body=str_replace("{creatorname}", ucwords($projectcreator[0]['fname']).' '.ucwords($projectcreator[0]['lname']), $emailtemplate2[2]['descrition']);
                    $body=str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $body);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $body);
                    $body=str_replace("{useremail}", $userdata[0]['email'], $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate2[0]['descrition'].$body.$emailtemplate2[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($projectcreator[0]['email'])
                    ->setSubject($emailtemplate2[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), $projectcreator[0]['id'], $emailtemplate2[2]['subject'], $body, $projectcreator[0]['email'], '', Yii::$app->user->identity->id);                                    
                    
            $emailtemplate3 = EmailTemplates::getEmailTemplate(28);
                    $body=str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $emailtemplate3[2]['descrition']);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $body);
                    $body=str_replace("{useremail}", $userdata[0]['email'], $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate3[0]['descrition'].$body.$emailtemplate3[1]['descrition'];
                    $subject=str_replace("{projectname}", $projectcreator[0]['project_title'], $emailtemplate3[2]['subject']);
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo('equippp.donotreply@gmail.com')
                    ->setSubject($subject)
                    ->setHtmlBody($body)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), $projectcreator[0]['id'], $emailtemplate3[2]['subject'], $body, 'equippp.donotreply@gmail.com', '', Yii::$app->user->identity->id); 
            
             echo "You have successfully participated in the project";
 }
        
 else
     
 {
     
    print_r($model->getErrors());
 }
 }
 }*/
}
