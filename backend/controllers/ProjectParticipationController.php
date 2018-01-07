<?php

namespace backend\controllers;

use Yii;
use backend\models\ProjectParticipation;
use backend\models\Projects;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use common\models\EmailTemplates;
use common\models\Communique;
use backend\models\ProjectCoOwners;
use backend\models\User;

/**
 * ProjectParticipationController implements the CRUD actions for ProjectParticipation model.
 */
class ProjectParticipationController extends Controller {

    public function behaviors() {
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
     * Lists all ProjectParticipation models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new ProjectParticipation();
		$mysqldateformat = Yii::getAlias('@mysqldateformat');
//        $dataProvider = new ActiveDataProvider([
//            'query' => ProjectParticipation::find()->where('project_ref_id = '.Yii::$app->getRequest()->getQueryParam('id')),
//        ]);
        //  $projectID = Yii::$app->getRequest()->getQueryParam('id');

        $participationType = Yii::$app->getRequest()->getQueryParam('pType') ? Yii::$app->getRequest()->getQueryParam('pType') : "";
        $investmentType = Yii::$app->getRequest()->getQueryParam('iType') ? Yii::$app->getRequest()->getQueryParam('iType') : "";
        $equityType = Yii::$app->getRequest()->getQueryParam('eType') ? Yii::$app->getRequest()->getQueryParam('eType') : "";
        $fromDate = Yii::$app->getRequest()->getQueryParam('from') ? Yii::$app->getRequest()->getQueryParam('from') : "";
        $toDate = Yii::$app->getRequest()->getQueryParam('to') ? Yii::$app->getRequest()->getQueryParam('to') : "";
        $qry = 'SELECT COUNT(*) FROM `projects` JOIN project_participation ON project_participation.project_ref_id = projects.project_id '
                . 'JOIN user_profile ON project_participation.user_ref_id = user_profile.user_ref_id WHERE 1';
        $where = '';
		
        if (!empty($participationType) || !empty($investmentType) || !empty($equityType) || !empty($fromDate) || !empty($toDate)) {
            //echo 'Coming Here';
            $where .= (!empty($participationType)) ? " AND participation_type = '" . $participationType . "'" : "";
            $where .= (!empty($investmentType)) ? " AND investment_type = '" . str_replace(" ", "_", $investmentType) . "'" : "";
            $where .= (!empty($equityType)) ? " AND equity_type = '" . str_replace(" ", "_", $equityType) . "'" : "";
            $where .= (!empty($fromDate)) ? " AND DATE_FORMAT(project_participation.created_date, '%Y-%m-%d') >= '" . date('Y-m-d', strtotime($fromDate) ) . "'" : "";
            $where .= (!empty($toDate)) ? " AND DATE_FORMAT(project_participation.created_date, '%Y-%m-%d') <= '" . date('Y-m-d', strtotime($toDate) ) . "'" : "";
        }
        $qry .= $where;
//echo $qry; exit;
        $count = Yii::$app->db->createCommand($qry)->queryScalar();
        //print_r($count); die;
        $sql = 'SELECT `projects`.`project_id`, `project_title`, `project_participation`.`user_ref_id`, `project_participation_id`, `participation_type`, `investment_type`, `equity_type`, `amount`, `interest_rate`, DATE_FORMAT(`project_participation`.`created_date`, "'.$mysqldateformat.'") as created_date, CONCAT(`fname`, " ", `lname`) as username '
                . 'FROM `projects` JOIN project_participation ON project_participation.project_ref_id = projects.project_id '
                . 'JOIN user_profile ON project_participation.user_ref_id = user_profile.user_ref_id';
        $sql .= $where;

        $sort = Yii::$app->getRequest()->getQueryParam('sort') ? Yii::$app->getRequest()->getQueryParam('sort') : "";
        if (empty($sort))
            $sql .= ' ORDER BY `project_participation`.`created_date` DESC';

        $dataProvider = new SqlDataProvider([
                    'sql' => $sql,
                    'totalCount' => $count,
                    'pagination' => [
                        'pageSize' => 10,
                        ],
                ]);

        $dataProvider->pagination->pageSize = 10;

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
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'projectID' => 2,
                ]);
    }

    /**
     * Displays a single ProjectParticipation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        /*  return $this->render('view', [
          'model' => $this->findModel($id),
          ]);
          } */
		$mysqldateformat = Yii::getAlias('@mysqldateformat');
        $query = new \yii\db\Query();
        $query->select([
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
                    'CONCAT(fname, " ", lname) as username',]
                )
                ->from('projects')
                ->join('JOIN', 'project_participation', 'project_participation.project_ref_id = projects.project_id')
                ->join('JOIN', 'user_profile', 'user_profile.user_ref_id = project_participation.user_ref_id')
                ->where('project_participation_id = ' . Yii::$app->getRequest()->getQueryParam('id'));

        $command = $query->createCommand();
        $data = $command->queryAll();


        $partitype = ($data[0]['participation_type']=='Support') ? 'Kind' : 'Cash';
        return $this->renderPartial('view', [
                    'model' => $data[0],
                    'partitype' => $partitype,
                ]);
    }

    /**
     * Creates a new ProjectParticipation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
//print_r(Yii::$app->request->post('ProjectParticipation')['user_ref_id']); die;
        
        $user_ref_id = (Yii::$app->request->post('ProjectParticipation')['user_ref_id']) ? Yii::$app->request->post('ProjectParticipation')['user_ref_id'] : "";
        
        $sql = "SELECT CONCAT(fname,' ', lname, ' , ', user_type, ' , ', email) as value, id as id  FROM `user`  u 
                LEFT JOIN user_profile up ON u.id = up.user_ref_id
                JOIN user_type ut ON u.user_type_ref_id = ut.user_type_id 
                WHERE u.status = 1 AND u.username IS NOT NULL AND up.fname IS NOT NULL
                GROUP BY u.id ORDER BY email";

        $users = Yii::$app->db->createCommand($sql)->queryAll();
        $model = new ProjectParticipation();
        $project = Projects::find()->where(['project_id' => Yii::$app->getRequest()->getQueryParam('id')])->one();
        $projectParticipation = ProjectParticipation::find()->where(['project_ref_id' => Yii::$app->getRequest()->getQueryParam('id'), 'user_ref_id' => $user_ref_id])->one();

        //$model->project_ref_id = Yii::$app->request->post('project_id');
        //$model->user_ref_id = Yii::$app->request->id;
        $model->created_by = Yii::$app->user->identity->id;
        $model->created_date = date('Y-m-d H:i:s');
        if ($model->load(Yii::$app->request->post())) {
            if ($projectParticipation) {
                Yii::$app->db->createCommand()->delete('project_participation', 'project_participation_id=' . $projectParticipation->project_participation_id)->query();
            }
            
            if ($model->save()) {
                $userdata = \frontend\models\User::getUserDetails($user_ref_id);
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
                $body = str_replace("{username}", ucwords($userdata[0]['fname']) . ' ' . ucwords($userdata[0]['lname']), $emailtemplate1[2]['descrition']);
                $body = str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $body);
                $body = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body);
                $body = $emailtemplate1[0]['descrition'] . $body . $emailtemplate1[1]['descrition'];
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
                $body = str_replace("{creatorname}", ucwords($projectcreator[0]['fname']) . ' ' . ucwords($projectcreator[0]['lname']), $emailtemplate2[2]['descrition']);
                $body = str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $body);
                $body = str_replace("{username}", ucwords($userdata[0]['fname']) . ' ' . ucwords($userdata[0]['lname']), $body);
                $body = str_replace("{useremail}", $userdata[0]['email'], $body);
                $body = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body);
                $body = $emailtemplate2[0]['descrition'] . $body . $emailtemplate2[1]['descrition'];
                $message = Yii::$app->mailer->compose();
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo($projectcreator[0]['email'])
                        ->setSubject($emailtemplate2[2]['subject'])
                        ->setHtmlBody($body)
                        ->send();
                Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), $projectcreator[0]['id'], $emailtemplate1[2]['subject'], $body, $projectcreator[0]['email'], 'Unread', 1);
                }
                
                $emailtemplate3 = EmailTemplates::getEmailTemplate(28);
                $body = str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $emailtemplate3[2]['descrition']);
                $body = str_replace("{username}", ucwords($userdata[0]['fname']) . ' ' . ucwords($userdata[0]['lname']), $body);
                $body = str_replace("{useremail}", $userdata[0]['email'], $body);
                $body = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body);
                $body = $emailtemplate3[0]['descrition'] . $body . $emailtemplate3[1]['descrition'];
                $subject = str_replace("{projectname}", $projectcreator[0]['project_title'], $emailtemplate3[2]['subject']);
                $message = Yii::$app->mailer->compose();
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo('equippp.noreply@gmail.com')
                        ->setSubject($subject)
                        ->setHtmlBody($body)
                        ->send();
                Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('id'), 1, $subject, $body, 'equippp.noreply@gmail.com', 'Unread', 1);
                
                if ($adminUsers) {
                    foreach ($adminUsers as $admins) {
             $emailtemplate3 = EmailTemplates::getEmailTemplate(28);
                    $body=str_replace("{projectname}", ucwords($projectcreator[0]['project_title']), $emailtemplate3[2]['descrition']);
                    $body=str_replace("{username}", ucwords($userdata[0]['fname']).' '.ucwords($userdata[0]['lname']), $body);
                    $body=str_replace("{useremail}", $userdata[0]['email'], $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate3[0]['descrition'].$body.$emailtemplate3[1]['descrition'];
                    $subject=str_replace("{projectname}", $projectcreator[0]['project_title'], $emailtemplate3[2]['subject']);
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

                return $this->redirect(['index', 'id' => Yii::$app->getRequest()->getQueryParam('id')]);
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'project' => $project,
                        'users' => $users
                    ]);
        }
    }

    /**
     * Updates an existing ProjectParticipation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            $project = Projects::find()->where(['project_id' => $model->project_ref_id])->one();
            return $this->render('update', [
                        'model' => $model,
                        'project' => $project
                    ]);
        }
    }

    /**
     * Deletes an existing ProjectParticipation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return true;
    }

    /**
     * Finds the ProjectParticipation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProjectParticipation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ProjectParticipation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
