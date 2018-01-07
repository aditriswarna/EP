<?php

namespace backend\controllers;

use Yii;
use backend\models\Projects;
use backend\models\ProjectCategory;
use backend\models\ProjectType;
use backend\models\ProjectParticipation;
use backend\models\ProjectMedia;
use backend\models\User;
use backend\models\ProjectStatus;
use backend\models\ProjectComments;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use yii\web\UploadedFile;
use yii\data\SqlDataProvider;
use common\models\UserRequests;
use common\models\EmailTemplates;
use common\models\Communique;
use backend\models\ProjectCoOwners;
use frontend\models\Status;
use common\models\Storage;
date_default_timezone_set('Asia/Kolkata');
/**
 * ProjectsController implements the CRUD actions for Projects model.
 */
class ProjectsController extends Controller {

    public $layout;

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
     * Lists all Projects models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new Projects();
        $mysqldateformat = Yii::getAlias('@mysqldateformat');
        $projectTitle = (Yii::$app->getRequest()->getQueryParam('title')) ? Yii::$app->getRequest()->getQueryParam('title') : "";
        $projectCategory = Yii::$app->getRequest()->getQueryParam('cat') ? Yii::$app->getRequest()->getQueryParam('cat') : "";
        $projectType = Yii::$app->getRequest()->getQueryParam('type') ? Yii::$app->getRequest()->getQueryParam('type') : "";
        $projectStatus = Yii::$app->getRequest()->getQueryParam('status') ? Yii::$app->getRequest()->getQueryParam('status') : "";
        $fromDate = Yii::$app->getRequest()->getQueryParam('from') ? Yii::$app->getRequest()->getQueryParam('from') : "";
        $toDate = Yii::$app->getRequest()->getQueryParam('to') ? Yii::$app->getRequest()->getQueryParam('to') : "";
        $qry = 'SELECT COUNT(*) FROM projects JOIN status ON projects.project_status = status.status_id WHERE 1';
        $where = '';
        if (!empty($projectTitle) || !empty($projectCategory) || !empty($projectType) || !empty($projectStatus) || !empty($fromDate) || !empty($toDate)) {
            $where .= (!empty($projectTitle)) ? " AND project_title LIKE '%".$projectTitle."%'" : "";
            $where .= (!empty($projectCategory)) ? " AND project_category_ref_id = " . $projectCategory : "";
            $where .= (!empty($projectType)) ? " AND project_type_ref_id = " . $projectType : "";
            $where .= (!empty($projectStatus)) ? " AND status.status_id = '" . $projectStatus . "'" : "";
            //$where3 .= (!empty($fromDate)) ? ' AND DATE_FORMAT(pp.created_date, "%Y-%m-%d") >= "'.date("Y-m-d", strtotime(str_replace("-", "/", $fromDate))).'"' : "";
            //$where3 .= (!empty($toDate)) ? ' AND DATE_FORMAT(pp.created_date, "%Y-%m-%d") <= "'.date("Y-m-d", strtotime(str_replace("-", "/", $toDate))).'"' : "";
            if(!empty($fromDate) && !empty($toDate)) {
                $where .= (!empty($fromDate)) ? ' AND ("'.date('Y-m-d', strtotime($fromDate) ).'" BETWEEN DATE_FORMAT(projects.`project_start_date`, "%Y-%m-%d") AND DATE_FORMAT(projects.`project_end_date`, "%Y-%m-%d")': "";
                $where .= (!empty($toDate)) ? ' OR "'.date('Y-m-d', strtotime($toDate) ).'" BETWEEN DATE_FORMAT(projects.`project_start_date`, "%Y-%m-%d") AND DATE_FORMAT(projects.`project_end_date`, "%Y-%m-%d"))': "";
            } elseif(!empty($fromDate) || !empty($toDate)) {
                $ChkDate = !empty($fromDate) ? $fromDate : $toDate;
                $where .= ' AND "'.date('Y-m-d', strtotime($ChkDate)).'" BETWEEN DATE_FORMAT(projects.`project_start_date`, "%Y-%m-%d") AND DATE_FORMAT(projects.`project_end_date`, "%Y-%m-%d")';
            }
        }
        $qry .= $where;
//        echo $qry; //return false;
//        $qry = 'SELECT COUNT(*) FROM projects WHERE project_category_ref_id = 1 AND project_type_ref_id = 1';
        $count = Yii::$app->db->createCommand($qry)->queryScalar();
        //print_r($count); die;
        $sql = 'SELECT DISTINCT `projects`.`project_id`, `projects`.`user_ref_id`, `project_category_ref_id`, `project_title`, `project_type_ref_id`, `location`, `estimated_project_cost`, DATE_FORMAT(`project_start_date`, "'.$mysqldateformat.'") AS project_start_date, DATE_FORMAT(`project_end_date`, "'.$mysqldateformat.'") AS project_end_date, DATE_FORMAT(`projects`.`created_date`, "'.$mysqldateformat.'") as created_date, `project_participation`.`project_ref_id` as projectParticipationId , display_in_home_page, `status_name`,`project_status` '
                . 'FROM `projects` LEFT JOIN project_participation ON project_participation.project_ref_id = projects.project_id '
                . 'LEFT JOIN status ON status.status_id = projects.project_status WHERE 1 ';
        $sql .= $where;
        /* if(!empty($projectCategory) || !empty($projectType) || !empty($projectStatus) || !empty($fromDate) || !empty($toDate)) {
          $sql .= (!empty($projectCategory)) ? " AND project_category_ref_id = ".$projectCategory : "";
          $sql .= (!empty($projectType)) ? " AND project_type_ref_id = ".$projectType : "";
          $sql .= (!empty($projectStatus)) ? " AND projects.Status = '".$projectStatus."'" : "";
          $sql .= (!empty($fromDate)) ? " AND DATE_FORMAT(projects.created_date, '%m-%d-%Y') >= '".$fromDate."'" : "";
          $sql .= (!empty($toDate)) ? " AND DATE_FORMAT(projects.created_date, '%m-%d-%Y') <= '".$toDate."'" : "";
          } */
        $sort = Yii::$app->getRequest()->getQueryParam('sort') ? Yii::$app->getRequest()->getQueryParam('sort') : "";
        if (empty($sort))
            $sql .= ' ORDER BY `projects`.`created_date` DESC';
        //echo $sql; exit;//return false;
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

//        $dataProvider->pagination->pageSize=10;

        $dataProvider->setSort([
            'attributes' => [
                'project_category_ref_id',
                'project_title',
                'project_type_ref_id',
                'location',
                'estimated_project_cost',
                'project_start_date',
                'project_end_date',
                'Status',
                'created_date',
                'display_in_home_page'
            ]
        ]);

        $projectCategories = ArrayHelper::map(ProjectCategory::find()->where(['status' => 'Active'])->all(), 'project_category_id', 'category_name');
        $projectTypes = ArrayHelper::map(ProjectType::find()->all(), 'project_type_id', 'project_type');
        $projectStatus = ArrayHelper::map(Status::find()->where('status_id NOT IN (7,8)')->all(), 'status_id', 'status_name');
//print_r($projectStatus);
        return $this->render('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'categories' => $projectCategories,
                    'projectTypes' => $projectTypes,
                    'projectStatus' => $projectStatus,
        ]);
    }

    /**
     * Displays a single Projects model.
     * @param integer $id
     * @return mixed
     */
    
    public function actionView($id) {
        //$project = Projects::find()->asArray()->where(['project_id' => $id])->one();
        $mysqldateformat = Yii::getAlias('@mysqldateformat');
        $projectsql = "SELECT project_id,user_type.user_type,project_title,conditions, 
                        (SELECT COUNT(*) FROM project_likes WHERE project_likes.project_ref_id=projects.project_id) AS projectlikes,
                         project_category_ref_id, media_agency_name, 
                        projects.user_ref_id, objective, location, project_desc, estimated_project_cost, project_start_date, project_end_date,
                        (SELECT  SUM(amount) FROM project_participation WHERE project_participation.project_ref_id=".$id.") as total_participation_amount, user_profile.fname, user_profile.lname, project_category.category_name
                        FROM projects
                        JOIN project_category ON project_category.project_category_id = projects.project_category_ref_id
                        JOIN user_profile ON user_profile.user_ref_id = projects.user_ref_id
                        JOIN user ON user.id = projects.user_ref_id 
                        JOIN user_type ON user_type.user_type_id=user.user_type_ref_id
                        LEFT JOIN media_agencies ON media_agencies.media_agency_id = user.media_agency_ref_id 
                        WHERE project_id = ".$id."
                        GROUP BY user.id";
        $projects = Yii::$app->db->createCommand($projectsql)->queryAll();
        $project = array_shift($projects);
        $rows = (new Query())
                ->select(['document_name', 'document_type', 'project_ref_id'])
                ->from('project_media')
                ->where(['project_ref_id' => $id])
                ->limit(10)
                ->all();

        $query = new Query();
        $query->select(['project_comment_id', 'project_ref_id', 'comments', 'DATE_FORMAT(project_comments.created_date, "'.$mysqldateformat.'") as created_date', 'project_comments.status', 'status.status_name', 'user_profile.user_ref_id', 'user_profile.fname', 'user_profile.lname', 'user_profile.user_image'])
                ->from('project_comments')
                ->join('JOIN', 'user_profile', 'user_profile.user_ref_id = project_comments.user_ref_id')
                ->join('JOIN', 'status', 'status.status_id = project_comments.status')
                ->where(["project_comments.project_ref_id" => $id])
                ->orderBy(['project_comments.created_date' => SORT_DESC]);

        $command = $query->createCommand();
        //print_r($command); exit;
        $comments = $command->queryAll();

        return Yii::$app->controller->renderPartial('view', [
                    'projectData' => $project, 
                    'rows' => $rows,
                    'comments' => $comments,
                ]);
        
    }

    /**
     * Creates a new Projects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Projects();
        $projectParticipationData = array('participation_type' => '', 'investment_type' => '', 'equity_type' => '', 'amount' => '', 'interest_rate' => '');
        $projectCategories = ArrayHelper::map(ProjectCategory::find()->all(), 'project_category_id', 'category_name');
        $projectTypes = ArrayHelper::map(projectType::find()->all(), 'project_type_id', 'project_type');

        if ($model->load(Yii::$app->request->post())) {
            $projectImages = UploadedFile::getInstances($model, 'project_image');
            $projectMediaFiles = UploadedFile::getInstances($model, 'document_name');
            
            $projectTitle = $_POST['Projects']['project_title'] ? $_POST['Projects']['project_title'] : '';
            $projectDesc = $_POST['Projects']['project_desc'] ? $_POST['Projects']['project_desc'] : '';
            $projectObjective = $_POST['Projects']['objective'] ? $_POST['Projects']['objective'] : '';
            $projectConditions = $_POST['Projects']['conditions'] ? $_POST['Projects']['conditions'] : '';
                
            $amount = $_POST['Projects']['amount'] ? $_POST['Projects']['amount'] : '';
            $participationType = $_POST['Projects']['participation_type'] ? $_POST['Projects']['participation_type'] : '';
            $investmentType = isset($_POST['Projects']['investment_type']) ? $_POST['Projects']['investment_type'] : '';
            $equityType = isset($_POST['Projects']['equity_type']) ? $_POST['Projects']['equity_type'] : '';
            $interestRate = isset($_POST['Projects']['interest_rate']) ? $_POST['Projects']['interest_rate'] : '';
            $embed_videos = isset($_POST['Projects']['embed_videos']) ? $_POST['Projects']['embed_videos']:'';
            
            $model->project_title = addslashes($projectTitle);
            $model->project_desc = addslashes($projectDesc);
            $model->objective = addslashes($projectObjective);
            $model->conditions = addslashes($projectConditions);
            $model->project_start_date = !empty($_POST['Projects']['project_start_date']) ? date('Y-m-d', strtotime($_POST['Projects']['project_start_date']) ) : '';
            $model->project_end_date = !empty($_POST['Projects']['project_end_date']) ? date('Y-m-d', strtotime($_POST['Projects']['project_end_date']) ) : ''; 
            $model->Status = 'Active';
            $model->project_status = 1;

            $user_id = !empty($_POST['Projects']['username']) ? $_POST['Projects']['username'] : Yii::$app->user->identity->id;
            $model->user_ref_id = $user_id;
            $model->created_by = Yii::$app->user->identity->id;
            $model->created_date = date('Y-m-d H:i:s');

            if ($model->save()) {

                $lastInsertId = $model->project_id;

                $projectParticipation = new ProjectParticipation();
                $projectParticipation->project_ref_id = trim($lastInsertId);
                $projectParticipation->user_ref_id = $user_id;
                $projectParticipation->participation_type = $participationType;
                $projectParticipation->created_by = trim(Yii::$app->user->identity->id);
                $projectParticipation->investment_type = $investmentType;
                $projectParticipation->equity_type = $equityType;
                $projectParticipation->amount = trim($amount);
                $projectParticipation->interest_rate = trim($interestRate);
                $projectParticipation->created_date = date('Y-m-d H:i:s');
                $projectParticipation->save(false);

                if (isset($projectImages) && count($projectImages) > 0) {
                    Storage::imageUpload($projectImages, $lastInsertId);
                }

                if (isset($projectMediaFiles) && count($projectMediaFiles) > 0) {
                    Storage::mediaUpload($projectMediaFiles, $lastInsertId);
                }
                if($embed_videos)
                  {
                      ProjectMedia::saveembedlink($embed_videos,$lastInsertId); 
                  }

                $userData = \frontend\models\User::getUserDetails($_POST['Projects']['username']);
                //$adminUsers = User::find()->where(['user_type_ref_id' => $userData[0]['user_type_ref_id'], 'user_role_ref_id' => 1])->all();
                $adminUsers =  Yii::$app->db->createCommand("SELECT * FROM `user` AS u 
                    LEFT JOIN admin_assigned_user_types AS au ON au.user_ref_id=u.id
                    WHERE user_role_ref_id=1 AND u.status = 1 AND au.user_type_ref_id=".$userData[0]['user_type_ref_id'])->queryAll();
                $project_name = $_POST['Projects']['project_title'];
                $email = $userData[0]['email'];
                // go through each uploaded image
                /* Yii::$app->mailer->compose('projectCreated', 
                  [
                  'userdata'=> $userData,
                  'project_name'=> $_POST['Projects']['project_title'] ,
                  'title'      => Yii::t('app', 'Project Created Successfully'),
                  'htmlLayout' => 'layouts/html'
                  ])
                  ->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                  ->setTo($email)
                  ->setSubject('EquiPPP Project has been created successfully')
                  ->send(); */

                $emailtemplate1 = EmailTemplates::getEmailTemplate(11);
                $body = str_replace("{username}", ucwords($userData[0]['fname']) . ' ' . ucwords($userData[0]['lname']), $emailtemplate1[2]['descrition']);
                $body = str_replace("{projectname}", ucwords($project_name), $body);
                $body = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body);
                $body = $emailtemplate1[0]['descrition'] . $body . $emailtemplate1[1]['descrition'];
                $message = Yii::$app->mailer->compose();
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo($email)
                        ->setSubject($emailtemplate1[2]['subject'])
                        ->setHtmlBody($body)
                        ->send();
                Communique::saveMailData($lastInsertId, $userData[0]['id'], $emailtemplate1[2]['subject'], $body, $email, 'Unread', Yii::$app->user->identity->id);

                /* Yii::$app->mailer->compose('projectCreatedtoAdmin', 
                  [
                  'userdata'=> $userData,
                  'project_name'=> $_POST['Projects']['project_title'] ,
                  'title'      => Yii::t('app', 'User has created project successfully'),
                  'htmlLayout' => 'layouts/html'
                  ])
                  ->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                  ->setTo('equippp.donotreply@gmail.com')
                  ->setSubject('New EquiPPP Project has been created successfully')
                  ->send(); */

                $emailtemplate2 = EmailTemplates::getEmailTemplate(10);
                $body = str_replace("{username}", ucwords($userData[0]['fname']) . ' ' . ucwords($userData[0]['lname']), $emailtemplate2[2]['descrition']);
                $body = str_replace("{projectname}", ucwords($project_name), $body);
                $body = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body);
                $body = $emailtemplate2[0]['descrition'] . $body . $emailtemplate2[1]['descrition'];
                $message = Yii::$app->mailer->compose();
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo('equippp.noreply@gmail.com')
                        ->setSubject($emailtemplate2[2]['subject'])
                        ->setHtmlBody($body)
                        ->send();
                Communique::saveMailData($lastInsertId, 1, $emailtemplate2[2]['subject'], $body, 'equippp.noreply@gmail.com', 'Unread', Yii::$app->user->identity->id);
                
                if ($adminUsers) {
                    foreach ($adminUsers as $admins) {
                    $emailtemplate3 = EmailTemplates::getEmailTemplate(46);
                    $body=str_replace("{username}", ucwords($userData[0]['fname']).' '.ucwords($userData[0]['lname']), $emailtemplate3[2]['descrition']);
                    $body=str_replace("{projectname}", ucwords($project_name), $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate3[0]['descrition'].$body.$emailtemplate3[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($admins['email'])
                    ->setSubject($emailtemplate3[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
                    Communique::saveMailData($lastInsertId, $admins['id'], $emailtemplate3[2]['subject'], $body, $admins['email'], 'Unread', Yii::$app->user->identity->id);                                            
                    }
                    }

                return $this->redirect('index');
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'projectParticipationData' => $projectParticipationData,
                'projectCategories' => $projectCategories,
                'projectTypes' => $projectTypes,
            ]);
        }
    }

    /**
     * Updates an existing Projects model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {

        $model = $this->findModel($id);
        //->select('participation_type, investment_type, equity_type, amount, interest_rate')
        $projectParticipationData = ProjectParticipation::find()->where(['project_ref_id' => $id, 'user_ref_id' => $model->user_ref_id])->asArray()->one();

        $projectCategories = ArrayHelper::map(ProjectCategory::find()->all(), 'project_category_id', 'category_name');
        $projectTypes = ArrayHelper::map(projectType::find()->all(), 'project_type_id', 'project_type');

        $uploadedProjectMedia = Yii::$app->db->createCommand('SELECT projects.project_id, project_media_id, document_name, document_type FROM projects JOIN project_media ON projects.project_id = project_media.project_ref_id AND project_ref_id = ' . $id . ' AND (document_type = "projectImage" OR document_type = "projectDocument")')->queryAll();

        $query = 'SELECT u.id, CONCAT(fname, " ", lname, " ", email, " ", user_type) as value, id as id '
                . 'FROM user u LEFT JOIN user_profile uf ON u.id = uf.user_ref_id '
                . 'JOIN user_type ut ON u.user_type_ref_id = ut.user_type_id '
                . 'WHERE u.id = ' . $model->user_ref_id;
        $userDetails = Yii::$app->db->createCommand($query)->queryAll();
        $allProjectImages = Projects::getProjectImages($id);
        $allProjectDocuments = Projects::getProjectDocuments($id);
        $allProjectVidoes= Projects::getProjectVideos($id);
        if ($model->load(Yii::$app->request->post())) {

            $projectImages = UploadedFile::getInstances($model, 'project_image');
            $projectMediaFiles = UploadedFile::getInstances($model, 'document_name');
            $projectTitle = $_POST['Projects']['project_title'] ? $_POST['Projects']['project_title'] : '';
            $projectDesc = $_POST['Projects']['project_desc'] ? $_POST['Projects']['project_desc'] : '';
            $projectObjective = $_POST['Projects']['objective'] ? $_POST['Projects']['objective'] : '';
            $projectConditions = $_POST['Projects']['conditions'] ? $_POST['Projects']['conditions'] : '';
                
            $amount = $_POST['Projects']['amount'] ? $_POST['Projects']['amount'] : '';
            $participationType = $_POST['Projects']['participation_type'] ? $_POST['Projects']['participation_type'] : '';
            $investmentType = isset($_POST['Projects']['investment_type']) && $_POST['Projects']['investment_type'] != '' ? $_POST['Projects']['investment_type'] : '';
            $equityType = isset($_POST['Projects']['equity_type']) && $_POST['Projects']['equity_type'] !='' ? $_POST['Projects']['equity_type'] : '';
            $interestRate = $_POST['Projects']['interest_rate'] ? $_POST['Projects']['interest_rate'] : '';
            $embed_videos = isset($_POST['Projects']['embed_videos']) ? $_POST['Projects']['embed_videos']:'';

            if ($_POST['Projects']['project_start_date']) {
                $model->project_start_date = date('Y-m-d', strtotime($_POST['Projects']['project_start_date']) );
            }
            if (isset($_POST['Projects']['project_end_date'])) {
                $model->project_end_date = date('Y-m-d', strtotime($_POST['Projects']['project_end_date']) );
            }

            $user_id = !empty($_POST['Projects']['username']) ? $_POST['Projects']['username'] : Yii::$app->user->identity->id;
            $model->user_ref_id = $user_id;
            
            $model->project_title = addslashes($projectTitle);
            $model->project_desc = addslashes($projectDesc);
            $model->objective = addslashes($projectObjective);
            $model->conditions = addslashes($projectConditions);
            $model->modified_by = Yii::$app->user->identity->id;
            $model->modified_date = date('Y-m-d H:i:s');

            if ($model->save()) {

                $lastInsertId = $model->project_id;

                $project_participation_model = ProjectParticipation::find()->where(['project_ref_id' => $id, 'user_ref_id' => $user_id])->one();

                if (empty($project_participation_model)) {
                    $projectParticipation = new ProjectParticipation();
                    $projectParticipation->project_ref_id = trim($lastInsertId);
                    $projectParticipation->user_ref_id = $user_id;
                    $projectParticipation->participation_type = $participationType;
                    $projectParticipation->created_by = trim(Yii::$app->user->identity->id);
                    $projectParticipation->investment_type = $investmentType;
                    $projectParticipation->equity_type = $equityType;
                    $projectParticipation->amount = trim($amount);
                    $projectParticipation->interest_rate = trim($interestRate);
                    $projectParticipation->created_date = date('Y-m-d H:i:s');
                    $projectParticipation->save(false);
                } else {
                    $project_participation_model->participation_type = $participationType;
                    $project_participation_model->created_by = trim(Yii::$app->user->identity->id);
                    if($participationType != 'Support') {
                        $project_participation_model->investment_type = $investmentType;
                        $project_participation_model->equity_type = $equityType;
                        $project_participation_model->amount = trim($amount);
                        if($equityType == 'Interest_Earning') {
                            $project_participation_model->interest_rate = trim($interestRate);
                        } else {
                            $project_participation_model->interest_rate = '';
                        }
                    } else {
                        $project_participation_model->investment_type = '';
                        $project_participation_model->equity_type = '';
                        $project_participation_model->amount = '';
                        $project_participation_model->interest_rate = '';
                    }
                    $project_participation_model->save(false);
                }

                if (isset($projectImages) && count($projectImages) > 0) {
                    Storage::imageUpload($projectImages, $lastInsertId);
                }

                  if($embed_videos)
                    {
                    ProjectMedia::saveembedlink($embed_videos,$lastInsertId); 
                    }
                if (isset($projectMediaFiles) && count($projectMediaFiles) > 0) {
                    Storage::mediaUpload($projectMediaFiles, $lastInsertId);
                }
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'projectParticipationData' => $projectParticipationData,
                        'projectCategories' => $projectCategories,
                        'projectTypes' => $projectTypes,
                        'userDetails' => $userDetails,
                        'allProjectImages' => @$allProjectImages,
                        'allProjectDocuments' => @$allProjectDocuments,
                        'allProjectVidoes'=> @$allProjectVidoes
            ]);
        }
    }

    /**
     * Deletes an existing Projects model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Projects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Projects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {

        if (($model = Projects::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function search($params) {
        $query = Projects::find()->where('status = 1');
        if (!($this->load($params) && $this->validate())) {
            return $query;
        }

        $query->andFilterWhere([
            'status' => $this->status,
            'id' => $this->id,
            'reminder' => $this->reminder,
            'order' => $this->order,
            'update_time' => $this->update_time,
            'update_by' => $this->update_by,
            'create_time' => $this->create_time,
            'create_by' => Yii::$app->user->id,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'url', $this->url]);
        return $query->all();
    }

    public function actionProjects_participation() {
        //echo $x = CHttpRequest::getParam('id'); die;
        //echo Yii::$app->request->get('id'); die;

        $model = new ProjectParticipation();

        $project = Projects::find()->where(['project_id' => Yii::$app->request->get('id')])->one();
        //print_r($project['project_id']);
        /*
          $model = $this->findModel($id);

          $projectCategories = ArrayHelper::map(ProjectCategory::find()->all(), 'project_category_id', 'category_name');

          $projectTypes = ArrayHelper::map(projectType::find()->all(), 'project_type_id', 'project_type');

          $model->user_ref_id = Yii::$app->user->identity->id;
          $model->modified_by = Yii::$app->user->identity->id;
          $model->modified_date = date('Y-m-d H:i:s');

          if ($model->load(Yii::$app->request->post()) && $model->save()) {
          return $this->redirect(['view', 'id' => $model->project_id]);
          } else {
          return $this->render('update', [
          'model' => $model,
          'projectCategories' => $projectCategories,
          'projectTypes' => $projectTypes,
          ]);
          }
         */

        return $this->render('projectParticipation', ['model' => $model, 'project' => $project]);
    }

    public function actionApprove() {
        $query = "SELECT ur.user_request_id, ur.user_ref_id AS requested_user_id, pt.project_type_id, ur.project_ref_id, p.user_ref_id AS project_created_by_user_id, ur.approved_by, p.project_id, p.project_title, 
pt.project_type, up.fname, up.lname, ur.approved_on, ur.is_approved FROM user_requests ur LEFT JOIN projects p ON p.project_id = ur.project_ref_id "
                . "LEFT JOIN project_type pt ON pt.project_type_id = p.project_type_ref_id "
                . " LEFT JOIN user_profile up ON up.user_ref_id = ur.user_ref_id WHERE pt.project_type_id=2 ORDER BY ur.approved_on DESC";

        $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM user_requests ur LEFT JOIN projects p ON p.project_id = ur.project_ref_id
LEFT JOIN project_type pt ON pt.project_type_id = p.project_type_ref_id
LEFT JOIN user_profile up ON up.user_ref_id = ur.user_ref_id WHERE pt.project_type_id=2')->queryScalar();

        return $this->render('project_approval', ['query' => $query, 'count' => $count]);
    }

    public function actionChangestatus() {
        $id = Yii::$app->getRequest()->getQueryParam('id');
        $status = Yii::$app->getRequest()->getQueryParam('status');
        $date = date('Y-m-d: g:i:s');
        $userrequest = UserRequests::find()->where(['user_request_id' => $id])->one();
        $userDetails = \frontend\models\User::getUserDetails($userrequest->user_ref_id);
        $projectdata = \frontend\models\Projects::getProjectCreatorDetails($userrequest->project_ref_id);
        $projectCoowners = ProjectCoOwners::getProjectCoownerDetails($userrequest->project_ref_id,$userrequest->user_ref_id);
        //$adminUsers = User::find()->where(['user_type_ref_id' => $userDetails[0]['user_type_ref_id'], 'user_role_ref_id' => 1])->all();   
        $adminUsers =  Yii::$app->db->createCommand("SELECT * FROM `user` AS u 
                    LEFT JOIN admin_assigned_user_types AS au ON au.user_ref_id=u.id
                    WHERE user_role_ref_id=1 AND u.status=1 AND au.user_type_ref_id=".$userDetails[0]['user_type_ref_id'])->queryAll();
        if ($status == 1) {
            $query = "UPDATE user_requests SET is_approved = 0, approved_by = " . yii::$app->user->identity->id . ", approved_on = '" . $date . "'  WHERE user_request_id=" . $id;

            $emailtemplate1 = EmailTemplates::getEmailTemplate(36);

            $body1 = str_replace("{username}", ucwords($userDetails[0]['fname']) . ' ' . ucwords($userDetails[0]['lname']), $emailtemplate1[2]['descrition']);
            $body1 = str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body1);
            $body1 = $emailtemplate1[0]['descrition'] . $body1 . $emailtemplate1[1]['descrition'];
            $message = Yii::$app->mailer->compose();
            $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($userDetails[0]['email'])
                    ->setSubject($emailtemplate1[2]['subject'])
                    ->setHtmlBody($body1)
                    ->send();
            Communique::saveMailData($userrequest->project_ref_id, $userDetails[0]['id'], $emailtemplate1[2]['subject'], $body1, $userDetails[0]['email'], 'Unread', 1);

            $emailtemplate2 = EmailTemplates::getEmailTemplate(37);

            $body2 = str_replace("{projectcreator}", ucwords($projectdata[0]['fname']) . ' ' . ucwords($projectdata[0]['lname']), $emailtemplate2[2]['descrition']);
            $body2 = str_replace("{username}", ucwords($userDetails[0]['fname']) . ' ' . ucwords($userDetails[0]['lname']), $body2);
            $body2 = str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body2);
            $body2 = $emailtemplate2[0]['descrition'] . $body2 . $emailtemplate2[1]['descrition'];
            $message = Yii::$app->mailer->compose();
            $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($projectdata[0]['email'])
                    ->setSubject($emailtemplate2[2]['subject'])
                    ->setHtmlBody($body2)
                    ->send();
            Communique::saveMailData($userrequest->project_ref_id, $projectdata[0]['id'], $emailtemplate2[2]['subject'], $body2, $projectdata[0]['email'], 'Unread', 1);

            $emailtemplate3 = EmailTemplates::getEmailTemplate(38);

            $body3 = str_replace("{username}", ucwords($userDetails[0]['fname']) . ' ' . ucwords($userDetails[0]['lname']), $emailtemplate3[2]['descrition']);
            $body3 = str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body3);
            $body3 = $emailtemplate3[0]['descrition'] . $body3 . $emailtemplate3[1]['descrition'];
            $message = Yii::$app->mailer->compose();
            $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo('equippp.noreply@gmail.com')
                    ->setSubject($emailtemplate3[2]['subject'])
                    ->setHtmlBody($body3)
                    ->send();
            Communique::saveMailData($userrequest->project_ref_id, 1, $emailtemplate3[2]['subject'], $body3, 'equippp.noreply@gmail.com', 'Unread', 1);
            
            if ($adminUsers) {
                    foreach ($adminUsers as $admins) {
            $body3 = str_replace("{username}", ucwords($userDetails[0]['fname']) . ' ' . ucwords($userDetails[0]['lname']), $emailtemplate3[2]['descrition']);
            $body3 = str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body3);
            $body3 = $emailtemplate3[0]['descrition'] . $body3 . $emailtemplate3[1]['descrition'];
            $message = Yii::$app->mailer->compose();
            $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($admins['email'])
                    ->setSubject($emailtemplate3[2]['subject'])
                    ->setHtmlBody($body3)
                    ->send();
            Communique::saveMailData($userrequest->project_ref_id, $admins['id'], $emailtemplate3[2]['subject'], $body3, $admins['email'], 'Unread', 1);
                    }
            }
            
            $emailtemplate4 = EmailTemplates::getEmailTemplate(42);    
            if ($projectCoowners) {
                foreach ($projectCoowners as $coowners) {
                    $body4 = str_replace("{coowner}", ucwords($coowners['fname']) . ' ' . ucwords($coowners['lname']), $emailtemplate4[2]['descrition']);
                    $body4 = str_replace("{username}", ucwords($userDetails[0]['fname']) . ' ' . ucwords($userDetails[0]['lname']), $body4);
                    $body4 = str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body4);
                    $body4 = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body4);
                    $body4 = $emailtemplate4[0]['descrition'] . $body4 . $emailtemplate4[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                            ->setTo($coowners['email'])
                            ->setSubject($emailtemplate4[2]['subject'])
                            ->setHtmlBody($body4)
                            ->send();
                    Communique::saveMailData($userrequest->project_ref_id, $coowners['id'], $emailtemplate4[2]['subject'], $body4, $coowners['email'], 'Unread', 1);
                }
            }
        } else if ($status == 0) {
            $query = "UPDATE user_requests SET is_approved = 1, approved_by = " . yii::$app->user->identity->id . ", approved_on = '" . $date . "' WHERE user_request_id=" . $id;

            $emailtemplate1 = EmailTemplates::getEmailTemplate(33);

            $body1 = str_replace("{username}", ucwords($userDetails[0]['fname']) . ' ' . ucwords($userDetails[0]['lname']), $emailtemplate1[2]['descrition']);
            $body1 = str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body1);
            $body1 = $emailtemplate1[0]['descrition'] . $body1 . $emailtemplate1[1]['descrition'];
            $message = Yii::$app->mailer->compose();
            $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($userDetails[0]['email'])
                    ->setSubject($emailtemplate1[2]['subject'])
                    ->setHtmlBody($body1)
                    ->send();
            Communique::saveMailData($userrequest->project_ref_id, $userDetails[0]['id'], $emailtemplate1[2]['subject'], $body1, $userDetails[0]['email'], 'Unread', 1);

            $emailtemplate2 = EmailTemplates::getEmailTemplate(34);

            $body2 = str_replace("{projectcreator}", ucwords($projectdata[0]['fname']) . ' ' . ucwords($projectdata[0]['lname']), $emailtemplate2[2]['descrition']);
            $body2 = str_replace("{username}", ucwords($userDetails[0]['fname']) . ' ' . ucwords($userDetails[0]['lname']), $body2);
            $body2 = str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body2);
            $body2 = $emailtemplate2[0]['descrition'] . $body2 . $emailtemplate2[1]['descrition'];
            $message = Yii::$app->mailer->compose();
            $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($projectdata[0]['email'])
                    ->setSubject($emailtemplate2[2]['subject'])
                    ->setHtmlBody($body2)
                    ->send();
            Communique::saveMailData($userrequest->project_ref_id, $projectdata[0]['id'], $emailtemplate2[2]['subject'], $body2, $projectdata[0]['email'], 'Unread', 1);

            $emailtemplate3 = EmailTemplates::getEmailTemplate(35);

            $body3 = str_replace("{username}", ucwords($userDetails[0]['fname']) . ' ' . ucwords($userDetails[0]['lname']), $emailtemplate3[2]['descrition']);
            $body3 = str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body3);
            $body3 = $emailtemplate3[0]['descrition'] . $body3 . $emailtemplate3[1]['descrition'];
            $message = Yii::$app->mailer->compose();
            $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo('equippp.noreply@gmail.com')
                    ->setSubject($emailtemplate3[2]['subject'])
                    ->setHtmlBody($body3)
                    ->send();
            Communique::saveMailData($userrequest->project_ref_id, 1, $emailtemplate3[2]['subject'], $body3, 'equippp.noreply@gmail.com', 'Unread', 1);
            
            if ($adminUsers) {
                    foreach ($adminUsers as $admins) {
            $body3 = str_replace("{username}", ucwords($userDetails[0]['fname']) . ' ' . ucwords($userDetails[0]['lname']), $emailtemplate3[2]['descrition']);
            $body3 = str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body3);
            $body3 = $emailtemplate3[0]['descrition'] . $body3 . $emailtemplate3[1]['descrition'];
            $message = Yii::$app->mailer->compose();
            $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($admins['email'])
                    ->setSubject($emailtemplate3[2]['subject'])
                    ->setHtmlBody($body3)
                    ->send();
            Communique::saveMailData($userrequest->project_ref_id, $admins['id'], $emailtemplate3[2]['subject'], $body3, $admins['email'], 'Unread', 1);
                    }
            }
            
            $emailtemplate4 = EmailTemplates::getEmailTemplate(41);    
            if ($projectCoowners) {
                foreach ($projectCoowners as $coowners) {
                    $body4 = str_replace("{coowner}", ucwords($coowners['fname']) . ' ' . ucwords($coowners['lname']), $emailtemplate4[2]['descrition']);
                    $body4 = str_replace("{username}", ucwords($userDetails[0]['fname']) . ' ' . ucwords($userDetails[0]['lname']), $body4);
                    $body4 = str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body4);
                    $body4 = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body4);
                    $body4 = $emailtemplate4[0]['descrition'] . $body4 . $emailtemplate4[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                            ->setTo($coowners['email'])
                            ->setSubject($emailtemplate4[2]['subject'])
                            ->setHtmlBody($body4)
                            ->send();
                    Communique::saveMailData($userrequest->project_ref_id, $coowners['id'], $emailtemplate4[2]['subject'], $body4, $coowners['email'], 'Unread', 1);
                }
            }
        }
        $result = Yii::$app->db->createCommand($query)->execute();

        return true;
    }

    public function actionHomePageDisplay() {

        $id = Yii::$app->getRequest()->getQueryParam('id');
        $status = Yii::$app->getRequest()->getQueryParam('status');

        if ($status == "N") {
            $query = "update projects set display_in_home_page = 'Y' where project_id=" . $id;
        } else if ($status == "Y") {
            $query = "update projects set display_in_home_page = 'N' where project_id=" . $id;
        }
        $result = Yii::$app->db->createCommand($query)->execute();
        // if($result)
        return true;
    }

    public function actionProjectData() {
        $pid = Yii::$app->getRequest()->getQueryParam('id');
        $projectData = Projects::getProjectDetails($pid);
        $allProjectVideos= Projects::getProjectVideos($pid);
        
        //$getamount = 'SELECT amount from project_participation where project_ref_id=' . $pid . ' AND user_ref_id=' . $projectData[0]['user_id'];
        //$amount = Yii::$app->db->createCommand($getamount)->queryAll();
        $allProjectImages = Projects::getProjectImages($pid);
        $allProjectDocuments = Projects::getProjectDocuments($pid);
        return $this->render('project-data', [
                    'projectData' => $projectData,
                    //'amount' => ($amount) ? $amount[0]['amount'] : 0,
                    'allProjectImages' => $allProjectImages,
                    'allProjectDocuments' => $allProjectDocuments,
                    'allProjectVideos'=>$allProjectVideos
        ]);
    }

    public function actionDeleteProjectImage() {        
        if(Yii::$app->getRequest()->getQueryParam('dname')){
            $bucket = 'equippp-docs';
            $keyname = 'uploads/project_images/'.Yii::$app->getRequest()->getQueryParam('pid').'/'.Yii::$app->getRequest()->getQueryParam('dname');
            $key = 'uploads/project_images/'.Yii::$app->getRequest()->getQueryParam('pid').'/thumb/'.Yii::$app->getRequest()->getQueryParam('dname');
            $s3 = new Storage();
            $result = $s3->delete($bucket, $keyname);   
            $status = $s3->delete($bucket, $key);
            if($result['@metadata']['statusCode'] == 204 && $status['@metadata']['statusCode'] == 204){
                yii::$app->db->createCommand()->delete('project_media', 'project_media_id = ' . Yii::$app->getRequest()->getQueryParam('pmid'))->execute();
            }
           return true;
        }       
    }
    public function actionDeleteYoutubeLink(){   
        if(Yii::$app->getRequest()->getQueryParam('dname')){
            yii::$app->db->createCommand()->delete('project_media', 'project_media_id = '.Yii::$app->getRequest()->getQueryParam('pmid'))->execute();
           return true;
        }
    }

    public function actionChangeProjectStatus() {
        if (Yii::$app->getRequest()->getQueryParam('status') == 'approve') {
            $approve = 1;
        } else if (Yii::$app->getRequest()->getQueryParam('status') == 'deactive') {
            $approve = 3;
        } else if (Yii::$app->getRequest()->getQueryParam('status') == 'complete') {
            $approve = 4;
        }
        $projectData = \frontend\models\Projects::getProjectCreatorDetails(Yii::$app->getRequest()->getQueryParam('pid'));
        $projectParticipants = ProjectParticipation::getProjectParticipantsDetails(Yii::$app->getRequest()->getQueryParam('pid'), Yii::$app->getRequest()->getQueryParam('uid'));
        $projectCoowners = ProjectCoOwners::getProjectCoownerDetails(Yii::$app->getRequest()->getQueryParam('pid'),'');
        //$adminUsers = User::find()->where(['user_type_ref_id' => $projectData[0]['user_type_ref_id'], 'user_role_ref_id' => 1])->all();
        $adminUsers =  Yii::$app->db->createCommand("SELECT * FROM `user` AS u 
                    LEFT JOIN admin_assigned_user_types AS au ON au.user_ref_id=u.id
                    WHERE user_role_ref_id=1 AND u.status=1 AND au.user_type_ref_id=".$projectData[0]['user_type_ref_id'])->queryAll();
        // print_r($projectCoowners); exit;
        $query = "UPDATE projects SET project_status = " . $approve . " WHERE project_id=" . Yii::$app->getRequest()->getQueryParam('pid');
        $result = Yii::$app->db->createCommand($query)->execute();
        if ($result) {
            /* Yii::$app->mailer->compose('projectStatusToUser', 
              [
              'projectData'=> $projectData,
              'approve'    => $approve,
              'title'      => Yii::t('app', $title),
              'htmlLayout' => 'layouts/html'
              ])
              ->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
              ->setTo($projectData[0]['email'])
              ->setSubject($subject)
              ->send(); */
            if ($approve == 1) {

                $emailtemplate1 = EmailTemplates::getEmailTemplate(12);
                $emailtemplate2 = EmailTemplates::getEmailTemplate(15);
                $emailtemplate3 = EmailTemplates::getEmailTemplate(20);
                $emailtemplate4 = EmailTemplates::getEmailTemplate(47);
            } else if ($approve == 3) {
                $emailtemplate1 = EmailTemplates::getEmailTemplate(13);
                $emailtemplate2 = EmailTemplates::getEmailTemplate(16);
                $emailtemplate3 = EmailTemplates::getEmailTemplate(18);
                $emailtemplate4 = EmailTemplates::getEmailTemplate(48);
            } else if ($approve == 4) {
                $emailtemplate1 = EmailTemplates::getEmailTemplate(14);
                $emailtemplate2 = EmailTemplates::getEmailTemplate(17);
                $emailtemplate3 = EmailTemplates::getEmailTemplate(19);
                $emailtemplate4 = EmailTemplates::getEmailTemplate(49);
            }
            $body1 = str_replace("{username}", ucwords($projectData[0]['fname']) . ' ' . ucwords($projectData[0]['lname']), $emailtemplate1[2]['descrition']);
            $body1 = str_replace("{projectname}", ucwords($projectData[0]['project_title']), $body1);
            $body1 = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body1);
            $body1 = $emailtemplate1[0]['descrition'] . $body1 . $emailtemplate1[1]['descrition'];
            $message = Yii::$app->mailer->compose();
            $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($projectData[0]['email'])
                    ->setSubject($emailtemplate1[2]['subject'])
                    ->setHtmlBody($body1)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('pid'), $projectData[0]['id'], $emailtemplate1[2]['subject'], $body1, $projectData[0]['email'], 'Unread', 1);

            $body2 = str_replace("{projectname}", ucwords($projectData[0]['project_title']), $emailtemplate2[2]['descrition']);
            $body2 = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body2);
            $body2 = $emailtemplate2[0]['descrition'] . $body2 . $emailtemplate2[1]['descrition'];
            $message = Yii::$app->mailer->compose();
            $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo('equippp.noreply@gmail.com')
                    ->setSubject($emailtemplate2[2]['subject'])
                    ->setHtmlBody($body2)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('pid'), 1, $emailtemplate2[2]['subject'], $body2, 'equippp.noreply@gmail.com', 'Unread', 1);
            
            if ($adminUsers) {
                    foreach ($adminUsers as $admins) {
            $body3 = str_replace("{projectname}", ucwords($projectData[0]['project_title']), $emailtemplate4[2]['descrition']);
            $body3 = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body3);
            $body3 = $emailtemplate4[0]['descrition'] . $body3 . $emailtemplate4[1]['descrition'];
            $message = Yii::$app->mailer->compose();
            $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($admins['email'])
                    ->setSubject($emailtemplate4[2]['subject'])
                    ->setHtmlBody($body3)
                    ->send();
            Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('pid'), $admins['id'], $emailtemplate4[2]['subject'], $body3, $admins['email'], 'Unread', 1);
                    }
            }

            if ($projectParticipants) {
                foreach ($projectParticipants as $participants) {

                    $body3 = str_replace("{username}", ucwords($participants['fname']) . ' ' . ucwords($participants['lname']), $emailtemplate3[2]['descrition']);
                    $body3 = str_replace("{projectname}", ucwords($projectData[0]['project_title']), $body3);
                    $body3 = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body3);
                    $body3 = $emailtemplate3[0]['descrition'] . $body3 . $emailtemplate3[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                            ->setTo($participants['email'])
                            ->setSubject($emailtemplate3[2]['subject'])
                            ->setHtmlBody($body3)
                            ->send();
                    Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('pid'), $participants['id'], $emailtemplate3[2]['subject'], $body3, $participants['email'], 'Unread', 1);
                }
            }

            if ($projectCoowners) {
                foreach ($projectCoowners as $coowners) {
                    $body4 = str_replace("{username}", ucwords($coowners['fname']) . ' ' . ucwords($coowners['lname']), $emailtemplate3[2]['descrition']);
                    $body4 = str_replace("{projectname}", ucwords($projectData[0]['project_title']), $body4);
                    $body4 = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body4);
                    $body4 = $emailtemplate3[0]['descrition'] . $body4 . $emailtemplate3[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                            ->setTo($coowners['email'])
                            ->setSubject($emailtemplate3[2]['subject'])
                            ->setHtmlBody($body4)
                            ->send();
                    Communique::saveMailData(Yii::$app->getRequest()->getQueryParam('pid'), $coowners['id'], $emailtemplate3[2]['subject'], $body4, $coowners['email'], 'Unread', 1);
                }
            }
        }
        return $this->redirect(['index']);
    }
    
    public  function actionIsProjectLiked(){
        $pid = (isset($_REQUEST['pid']) && !empty($_REQUEST['pid'])) ? $_REQUEST['pid'] : (Yii::$app->getRequest()->getQueryParam('id') ? Yii::$app->getRequest()->getQueryParam('id') : "");
        if(Yii::$app->getRequest()->getQueryParam('id'))
            $sql = 'SELECT count(*) FROM project_likes WHERE project_ref_id ='.Yii::$app->getRequest()->getQueryParam('id');
        else
            $sql = 'SELECT count(*) FROM project_likes WHERE project_ref_id ='.$_REQUEST['pid'];
        $count = Yii::$app->db->createCommand($sql)->queryScalar();
        return $count;
    }
    
    public  function actionGetProjectLikes(){
        $sql = 'SELECT count(*) FROM project_likes WHERE project_ref_id ='.$_REQUEST['pid'];
        $count = Yii::$app->db->createCommand($sql)->queryScalar();
        return $count;
    }

    public function actionDisplayallcomments() {
		$mysqldateformat = Yii::getAlias('@mysqldateformat');
        $comments = (Yii::$app->getRequest()->getQueryParam('comments')) ? Yii::$app->getRequest()->getQueryParam('comments') : "";
        $projectId = (Yii::$app->getRequest()->getQueryParam('projectId')) ? Yii::$app->getRequest()->getQueryParam('projectId') : "";
        $userId = (Yii::$app->getRequest()->getQueryParam('userId')) ? Yii::$app->getRequest()->getQueryParam('userId') : "";
        $status = (Yii::$app->getRequest()->getQueryParam('status')) ? Yii::$app->getRequest()->getQueryParam('status') : "";
        $projectCommentId = (Yii::$app->getRequest()->getQueryParam('projectCommentId')) ? Yii::$app->getRequest()->getQueryParam('projectCommentId') : "";

        $data = '';
        if ($comments && $projectId && $userId && empty($status)) {
            $model = new ProjectComments();

            $model->project_ref_id = $projectId;
            $model->user_ref_id = $userId;
            $model->comments = addslashes($comments);
            $model->status = 7;
            $model->created_by = Yii::$app->user->id;
            $model->created_date = date('Y-m-d H:i:s');

            $model->save();
        } elseif ($projectCommentId && $status && empty($comments)) {
            //$model = $this->findModel($projectCommentId);
            //print_r($model);
            $query = "UPDATE project_comments SET status = " . $status . " WHERE project_comment_id = " . $projectCommentId;
            $result = Yii::$app->db->createCommand($query)->execute();
            //exit;
        } elseif ($projectCommentId && $comments && $projectId && (empty($status) && empty($userId))) {
            //$model = $this->findModel($projectCommentId);
            //print_r($model);
            //$data = 'Coming Here'; //die;
            $model = ProjectComments::find()->where(['project_comment_id' => $projectCommentId])->one();

            $model->comments = addslashes($comments);
            $model->save();

            //$query = "UPDATE project_comments SET comments = '".addslashes($comments)."' WHERE project_comment_id = ".$projectCommentId;
            //$result = Yii::$app->db->createCommand($query)->execute();
            //exit;
        } elseif ($projectCommentId && (empty($comments) && empty($status) && empty($projectId) && empty($userId))) {
            //$model = ProjectComments::find()->where(['project_comment_id' => $projectCommentId])->delete();
            Yii::$app->db->createCommand()->delete('project_comments', ['project_comment_id' => $projectCommentId])->execute();
            return true;
        }


        //$model = new Projects();
        //print_r($model); die;
        $projectId = (Yii::$app->getRequest()->getQueryParam('projectId')) ? Yii::$app->getRequest()->getQueryParam('projectId') : "";

        //$comments = ProjectComments::find()->where(['project_ref_id' => $model->project_id, 'status' => '1'])->all();
        $query = new Query();
        $query->select(['project_comment_id', 'project_ref_id', 'comments', 'DATE_FORMAT(project_comments.created_date, "'.$mysqldateformat.'") as created_date', 'project_comments.status', 'status.status_name', 'user_profile.user_ref_id', 'user_profile.fname', 'user_profile.lname', 'user_profile.user_image'])
                ->from('project_comments')
                ->join('JOIN', 'user_profile', 'user_profile.user_ref_id = project_comments.user_ref_id')
                ->join('JOIN', 'status', 'status.status_id = project_comments.status')
                ->where(["project_comments.project_ref_id" => $projectId])
                ->orderBy(['project_comments.created_date' => SORT_DESC]);

        $command = $query->createCommand();
        //print_r($command); exit;
        $comments = $command->queryAll();

        $data .= '';
        if(count($comments) > 0)
        {
            foreach ($comments as $comment) {
                $data .= '<div class="col-md-12" style="padding-bottom: 15px;" id="divComments">';
                $data .= '<div class="divCommentsDesc">
                        <div class="userImg">';
                if (!empty($comment['user_image']) && file_exists(Yii::getAlias('@upload') . '/frontend/web/uploads/profile_images/' . $comment['user_ref_id'] . '/' . $comment['user_image']))
                    $userImageUrl = Yii::$app->urlManagerFrontend->baseUrl . '/profile_images/' . $comment['user_ref_id'] . '/' . $comment['user_image'];
                else
                    $userImageUrl = Yii::$app->urlManagerFrontend->baseUrl . '/../images/avatar.png';
                $data .= '<img src="' . $userImageUrl . '" width="50" />
                        </div>
                        <div class="userComment">' . stripslashes($comment['comments']) . '</div>
                    </div>
                    <div class="userActions btnCommentAction">';
                $data .= $comment['fname'] . ' ' . $comment['lname'];
                $data .= ($comment['status'] == '2') ? '<b class="pd-msg status_'.$comment['project_comment_id'].'">&nbsp;&nbsp;[' . $comment['status_name'] . ']</b>' : '';
                if ($comment['status'] == '2') {
                    $data .= '<div class="commentActions pull-right" id="commentStatus_' . $comment['project_comment_id'] . '">';
                    $data .= '<a id="btnAccept" class="btn-danger btnCommentAction normal-fnt" style="padding: 2px;margin: 5px;" onclick="javascript: changeCommentStatus(\'' . $comment['project_comment_id'] . '\', \'' . $comment['project_ref_id'] . '\', \'' . $comment['user_ref_id'] . '\', \'7\')"><b>Accept</b></a>';
                    $data .= '<a id="btnReject" class="btn-danger btnCommentAction normal-fnt" style="padding: 2px;" onclick="javascript: changeCommentStatus(\'' . $comment['project_comment_id'] . '\', \'' . $comment['project_ref_id'] . '\', \'' . $comment['user_ref_id'] . '\', \'8\')"><b>Reject</b></a>';
                    $data .= '</div>';
                } elseif ($comment['status'] == '7') {
                    $data .= '<div style="float: right; width: 30%; text-align: right;" id="commentStatusAccepted">';
                    $data .= '<span class="btn-primary" style="padding: 2px;margin: 5px;"><b>Accepted</b></span>';
                    $data .= '</div>';
                } elseif ($comment['status'] == '8') {
                    $data .= '<div style="float: right; width: 30%; text-align: right;" id="commentStatusRejected">';
                    $data .= '<span class="btn-danger" style="padding: 2px;"><b>Rejected</b></span>';
                    $data .= '</div>';
                }
                $data .= '</div>
                </div>';
            }
        } else {
            $data .= 'No Comments Available';
        }
        return $data;
    }
    
    public function actionDisplayalldocuments() {
        
        $projectId = (Yii::$app->getRequest()->getQueryParam('projectId')) ? Yii::$app->getRequest()->getQueryParam('projectId') : "0";
        
        $query = new Query();
        $query->select(['project_media_id', 'project_ref_id', 'document_name'])
                ->from('project_media')
                //->where("project_comments.project_ref_id = ".$projectId." AND document_type='projectDocument'")
                ->where(["project_ref_id" => $projectId, "document_type" => "projectDocument"])
                ->orderBy(['project_media_id' => SORT_DESC]);
        
        $command = $query->createCommand();
        //print_r($command); echo '-------'.$actionId.'====='; exit;
        $documents = $command->queryAll();
        
        $data = '';
        if(count($documents) > 0)
        {
            $data .= '<ul>';
            foreach($documents as $document) {
                $data .= "<li><a target='_blank' href='https://s3.ap-south-1.amazonaws.com/".Yii::getAlias('@bucket')."/uploads/project_images/".$document['project_ref_id']."/".$document['document_name']."'>".$document['document_name']."</a></li>";
            }
            $data .= '</ul>';
        } else {
            $data .= '<div>No Documents Available</div>';
        }
        
        return $data;
    }

    public function actionCommentslist() {
        $model = new ProjectComments();
		$mysqldateformat = Yii::getAlias('@mysqldateformat');
        $projectCategory = Yii::$app->getRequest()->getQueryParam('cat') ? Yii::$app->getRequest()->getQueryParam('cat') : "";
        //$projectType = Yii::$app->getRequest()->getQueryParam('type') ? Yii::$app->getRequest()->getQueryParam('type') : "";
        $status = Yii::$app->getRequest()->getQueryParam('status') ? Yii::$app->getRequest()->getQueryParam('status') : "";
        $fromDate = Yii::$app->getRequest()->getQueryParam('from') ? Yii::$app->getRequest()->getQueryParam('from') : "";
        $toDate = Yii::$app->getRequest()->getQueryParam('to') ? Yii::$app->getRequest()->getQueryParam('to') : "";
        $qry = 'SELECT COUNT(*) FROM `project_comments` JOIN `projects` ON project_comments.project_ref_id = projects.project_id '
                . 'JOIN `project_category` ON project_category.project_category_id = projects.project_category_ref_id '
                . 'JOIN `user_profile` ON user_profile.user_ref_id = project_comments.user_ref_id '
                . 'JOIN `status` ON status.status_id = project_comments.status '
                . 'WHERE projects.project_status = 1 ';
        $where = '';
		
        if (!empty($projectCategory) || !empty($status) || !empty($fromDate) || !empty($toDate)) {
            $where .= (!empty($projectCategory)) ? " AND projects.project_category_ref_id = " . $projectCategory : "";
            //$where .= (!empty($projectType)) ? " AND projects.project_type_ref_id = ".$projectType : "";
            $where .= (!empty($status)) ? " AND project_comments.status = '" . $status . "'" : "";
            $where .= (!empty($fromDate)) ? " AND DATE_FORMAT(project_comments.created_date, '%Y-%m-%d') >= '" . date('Y-m-d', strtotime($fromDate) ) . "'" : "";
            $where .= (!empty($toDate)) ? " AND DATE_FORMAT(project_comments.created_date, '%Y-%m-%d') <= '" . date('Y-m-d', strtotime($toDate) ) . "'" : "";
        }
        $qry .= $where;
//        echo $qry; //return false;
//        $qry = 'SELECT COUNT(*) FROM projects WHERE project_category_ref_id = 1 AND project_type_ref_id = 1';
        $count = Yii::$app->db->createCommand($qry)->queryScalar();
        //print_r($count); die;
        $sql = 'SELECT `project_comments`.`project_comment_id`, `project_comments`.`comments`, `project_comments`.`status`, DATE_FORMAT(`project_comments`.`created_date`, "'.$mysqldateformat.'") as created_date, `project_id`, `project_title`, `projects`.`project_category_ref_id`, `projects`.`project_type_ref_id`, CONCAT(`user_profile`.`fname`, " ", `user_profile`.lname) as username, `project_category`.`category_name`, `status`.`status_name` '
                . 'FROM `project_comments` JOIN `projects` ON project_comments.project_ref_id = projects.project_id '
                . 'JOIN `project_category` ON project_category.project_category_id = projects.project_category_ref_id '
                . 'JOIN `user_profile` ON user_profile.user_ref_id = project_comments.user_ref_id '
                . 'JOIN `status` ON status.status_id = project_comments.status '
                . 'WHERE projects.project_status = 1 ';
        $sql .= $where;
        /* if(!empty($projectCategory) || !empty($projectType) || !empty($projectStatus) || !empty($fromDate) || !empty($toDate)) {
          $sql .= (!empty($projectCategory)) ? " AND project_category_ref_id = ".$projectCategory : "";
          $sql .= (!empty($projectType)) ? " AND project_type_ref_id = ".$projectType : "";
          $sql .= (!empty($projectStatus)) ? " AND projects.Status = '".$projectStatus."'" : "";
          $sql .= (!empty($fromDate)) ? " AND DATE_FORMAT(projects.created_date, '%m-%d-%Y') >= '".$fromDate."'" : "";
          $sql .= (!empty($toDate)) ? " AND DATE_FORMAT(projects.created_date, '%m-%d-%Y') <= '".$toDate."'" : "";
          } */
        $sort = Yii::$app->getRequest()->getQueryParam('sort') ? Yii::$app->getRequest()->getQueryParam('sort') : "";
        if (empty($sort))
            $sql .= ' ORDER BY `project_comments`.`created_date` DESC';
//        echo $sql; //return false;
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

//        $dataProvider->pagination->pageSize=10;

        $dataProvider->setSort([
            'attributes' => [
                'project_category_ref_id',
                'project_title',
                'project_type_ref_id',
                'status',
                'created_date',
                'username',
                'Status',
                'created_date',
            ]
        ]);

        $projectCategories = ArrayHelper::map(ProjectCategory::find()->where(['status' => 'Active'])->all(), 'project_category_id', 'category_name');
        $projectTypes = ArrayHelper::map(ProjectType::find()->all(), 'project_type_id', 'project_type');
        //$projectStatus = ArrayHelper::map(ProjectStatus::find()->all(), 'status_name', 'status_name');
        $status = array('2' => 'Pending', '7' => 'Accepted', '8' => 'Rejected');

        return $this->render('comments_list', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'categories' => $projectCategories,
                    'projectTypes' => $projectTypes,
                    'status' => $status,
        ]);
    }

    public function actionUpdatecommentstatus() {

        $id = Yii::$app->getRequest()->getQueryParam('id');
        $status = Yii::$app->getRequest()->getQueryParam('status');

        $query = "update project_comments set status = " . $status . " where project_comment_id=" . $id;

        $result = Yii::$app->db->createCommand($query)->execute();
        // if($result)
        return true;
    }
    
    public function actionListofprojects() {
        $phpdateformat = Yii::getAlias('@phpdateformat');
        //print_r($_REQUEST); die;
        
        //$projectDetails = Projects::find()->where(['user_ref_id' => Yii::$app->user->id, 'project_status' => '1'])->all();
        
        //$currentDate = date('Y-m-d');
        $currentDate = Yii::$app->getRequest()->getQueryParam('selDate') ? Yii::$app->getRequest()->getQueryParam('selDate') : "";
        $projectData = Projects::find()->where('("'.$currentDate.'" >= DATE_FORMAT(project_start_date, "%Y-%m-%d") AND "'.$currentDate.'" <= DATE_FORMAT(project_end_date, "%Y-%m-%d")) AND project_status = 1')->all();

        //echo $sql = 'SELECT * FROM projects WHERE "'.$currentDate.'" BETWEEN DATE_FORMAT(project_start_date, "%Y-%m-%d") AND DATE_FORMAT(project_end_date, "%Y-%m-%d") AND project_status = 1';
        //$projectDetails = yii::$app->db->createCommand($sql)->queryScalar();
        
        //print_r($projectDetails); die;
        //echo '<pre>'; print_r($projectData); echo '</pre>';
        $minDate = $maxDate = '0000-00-00';
        $i = 0;
        //echo '<pre>'; print_r($projectData[0]['project_start_date']); echo '</pre>';   //;
        if(count($projectData) > 0) {
            foreach($projectData as $projectDetails) {
                //echo date('Y-m-d', strtotime($projectDetails->project_start_date)).'____'.date('Y-m-d', strtotime($projectDetails->project_end_date)).'<br>';
                if($i == 0) {
                    $minDate = date('Y-m-d', strtotime($projectDetails->project_start_date));
                    $maxDate = date('Y-m-d', strtotime($projectDetails->project_end_date));
                    $i++;
                }
                if(date('Y-m-d', strtotime($minDate)) > date('Y-m-d', strtotime($projectDetails->project_start_date)))
                    $minDate = date('Y-m-d', strtotime($projectDetails->project_start_date));
                if(date('Y-m-d', strtotime($maxDate)) < date('Y-m-d', strtotime($projectDetails->project_end_date)))
                    $maxDate = date('Y-m-d', strtotime($projectDetails->project_end_date));
            }
            //echo '<pre>'; print_r($projectDetails); echo '</pre>';
            //echo $minDate.'____'.$maxDate.'<br>'.$minDate .'>'. $projectDetails->project_start_date;
            
            echo '<table class="table-events" width="100%" cellspacing="0" cellpadding="0">';
            echo '<tr class="tr-events" style=" ">';
                echo '<th width="235">Project Title</th>';
                echo '<th width="112">Amount</th>';
                echo '<th width="112">Start Date</th>';
                echo '<th width="120">End Date</th>';
            echo '</tr>';
            echo '<tr>';
                //echo '<td class="td-eventsrsp" width="100%" colspan="4" style="display: list-item; margin-top: 20px !important;">';
                echo '<td class="" width="100%" colspan="4" style=" padding:10px 0px;">';
                    echo '<div class="table-head-fix-body" style="margin-top: 30px; width: 100%; overflow-y: auto; height: 350px;">';
                        echo '<table class="table-events" width="100%" style="overflow: auto;" cellspacing="0" cellpadding="0">';
            $i = 0;
            foreach($projectData as $projectDetails) {
                if((date('Y-m-d', strtotime($projectDetails->project_start_date)) >= $minDate) && (date('Y-m-d', strtotime($projectDetails->project_end_date)) <= $maxDate)) {
                    $bgColor = ($i % 2 == 0) ? "#F4F4F4"  : "#FFFFFF";
                    echo '<tr style="background-color: '.$bgColor.';">';
                        echo '<td width="40%">'.stripslashes($projectDetails->project_title).'</td>';
                        echo '<td width="20%">'.$projectDetails->estimated_project_cost.'</td>';
                        echo '<td width="20%">'.date($phpdateformat, strtotime($projectDetails->project_start_date)).'</td>';
                        echo '<td width="20%">'.date($phpdateformat, strtotime($projectDetails->project_end_date)).'</td>'; 
                    echo '</tr>';
                    $i++;
                }
            }
                    echo '</table>';
                echo '</div>';
            echo '</table>';
        }
        return false;
    }

    /*
      public function actionUpdatecommentstatus($projectCommentId, $status) {

      $query = "UPDATE project_comments SET status = ".$status." WHERE project_comment_id = ".$projectCommentId;
      $result = Yii::$app->db->createCommand($query)->execute();

      }
     */
}
