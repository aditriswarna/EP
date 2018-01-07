<?php
namespace frontend\controllers;

use Yii;
use frontend\models\Projects;
use frontend\models\ProjectCategory;
use frontend\models\ProjectType;
use frontend\models\ProjectParticipation;
use frontend\models\ProjectMedia;
use frontend\models\User;
use frontend\models\ProjectStatus;
use frontend\models\ProjectComments;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use yii\web\UploadedFile;
use yii\data\SqlDataProvider;
use common\models\UserRequests;
use yii\helpers\Html;
use common\models\EmailTemplates;
use common\models\Communique;
use frontend\models\Status;
use frontend\models\ProjectCoOwners;
use common\models\Storage;
/**
 * ProjectsController implements the CRUD actions for Projects model.
 */
class ProjectsController extends Controller
{
    public $layout;
    
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
            \Yii::$app->getRequest()->url !== \yii\helpers\Url::to(\Yii::$app->getUser()->loginUrl) &&
            (Yii::$app->controller->action->id != 'private-projects')&&(Yii::$app->controller->action->id != 'get-project-likes')) {
                \Yii::$app->getResponse()->redirect(Yii::$app->request->BaseUrl.'/site/login');
        }
        else {
            return parent::beforeAction($action);
        }
    }

    /**
     * Lists all Projects models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $this->layout = '/main2';
        $model = new Projects();
        $participationModel = new ProjectParticipation();
        $mysqldateformat = Yii::getAlias('@mysqldateformat');
        $projectTitle = (Yii::$app->getRequest()->getQueryParam('title')) ? Yii::$app->getRequest()->getQueryParam('title') : "";
        $projectCategory = (Yii::$app->getRequest()->getQueryParam('cat')) ? Yii::$app->getRequest()->getQueryParam('cat') : "";
        $projectType = (Yii::$app->getRequest()->getQueryParam('type')) ? Yii::$app->getRequest()->getQueryParam('type') : "";
        $projectStatus = (Yii::$app->getRequest()->getQueryParam('status')) ? Yii::$app->getRequest()->getQueryParam('status') : "";
        $fromDate = Yii::$app->getRequest()->getQueryParam('from') ? Yii::$app->getRequest()->getQueryParam('from') : "";
        $toDate = Yii::$app->getRequest()->getQueryParam('to') ? Yii::$app->getRequest()->getQueryParam('to') : "";
        $participation = Yii::$app->getRequest()->getQueryParam('participation') ? Yii::$app->getRequest()->getQueryParam('participation') : "";
        $investmentType = Yii::$app->getRequest()->getQueryParam('iType') ? Yii::$app->getRequest()->getQueryParam('iType') : "";
        $equityType = Yii::$app->getRequest()->getQueryParam('eType') ? Yii::$app->getRequest()->getQueryParam('eType') : "";
        $participate = Yii::$app->getRequest()->getQueryParam('participate') ? Yii::$app->getRequest()->getQueryParam('participate') : "";
        $initiated = Yii::$app->getRequest()->getQueryParam('initiated') ? Yii::$app->getRequest()->getQueryParam('initiated') : "";
//        $btnInitiated = !empty(Yii::$app->getRequest()->getQueryParam('initiated')) ? Yii::$app->getRequest()->getQueryParam('initiated') : "";
//        $btnParticipate = !empty(Yii::$app->getRequest()->getQueryParam('participate')) ? Yii::$app->getRequest()->getQueryParam('participate') : "";
//        echo $projectCategory;
        if(Yii::$app->getRequest()->getQueryParam('initiated'))
            $tabActive = Yii::$app->getRequest()->getQueryParam('initiated');
        elseif(Yii::$app->getRequest()->getQueryParam('participate'))
            $tabActive = Yii::$app->getRequest()->getQueryParam('participate');
        else
            $tabActive = "";
        
        $QRY1 = 'SELECT COUNT(*) FROM projects, user, status WHERE projects.user_ref_id = user.id AND projects.project_status = status.status_id AND projects.project_status = 1 AND user.user_type_ref_id = '.Yii::$app->session->get('userType');
        
        $where = '';
		
        if( empty($participate) && empty($initiated) && ( !empty($projectTitle) || !empty($projectCategory) || !empty($projectType) || !empty($projectStatus) || !empty($fromDate) || !empty($toDate) ) ) {
            $where .= (!empty($projectTitle)) ? " AND project_title LIKE '%".$projectTitle."%'" : "";
            $where .= (!empty($projectCategory)) ? " AND project_category_ref_id = ".$projectCategory : "";
            $where .= (!empty($projectType)) ? " AND project_type_ref_id = ".$projectType : "";
            $where .= ($projectStatus > 0) ? " AND status.status_id = ".$projectStatus : "";
            if(!empty($fromDate) && !empty($toDate)) {
                $where .= (!empty($fromDate)) ? ' AND ("'.date('Y-m-d', strtotime($fromDate) ).'" BETWEEN DATE_FORMAT(projects.`project_start_date`, "%Y-%m-%d") AND DATE_FORMAT(projects.`project_end_date`, "%Y-%m-%d")': "";
                $where .= (!empty($toDate)) ? ' OR "'.date('Y-m-d', strtotime($toDate) ).'" BETWEEN DATE_FORMAT(projects.`project_start_date`, "%Y-%m-%d") AND DATE_FORMAT(projects.`project_end_date`, "%Y-%m-%d"))': "";
            } elseif(!empty($fromDate) || !empty($toDate)) {
                $ChkDate = !empty($fromDate) ? $fromDate : $toDate;
                $where .= ' AND "'.date('Y-m-d', strtotime($ChkDate) ).'" BETWEEN DATE_FORMAT(projects.`project_start_date`, "%Y-%m-%d") AND DATE_FORMAT(projects.`project_end_date`, "%Y-%m-%d")';
            }
        }
        $QRY1 .= $where;
        //echo $QRY1; //exit;
        $COUNT1 = Yii::$app->db->createCommand($QRY1)->queryScalar();
        //print_r($count); die;
        $SQL1 = 'SELECT project_id, user_ref_id, project_category_ref_id, project_title, project_type_ref_id, location, estimated_project_cost, project_start_date, project_end_date, created_date, projectParticipationId, project_status, status_name, project_request_id FROM 
                (
                SELECT DISTINCT `projects`.`project_id`, `projects`.`user_ref_id`, `project_category_ref_id`, `project_title`, `project_type_ref_id`, `location`, `estimated_project_cost`, DATE_FORMAT(`project_start_date`, "'.$mysqldateformat.'") AS project_start_date, DATE_FORMAT(`project_end_date`,"'.$mysqldateformat.'") AS project_end_date, `projects`.`created_date` AS created_date, `project_participation`.`project_ref_id` AS projectParticipationId, project_status, status.status_name,
                IF(projects.project_type_ref_id = 2, (SELECT is_approved FROM user_requests WHERE user_requests.user_ref_id = '.yii::$app->user->id.' AND user_requests.project_ref_id = projects.project_id), "" ) AS project_request_id
                FROM `projects` 
                JOIN `user` ON projects.user_ref_id = user.id 
                LEFT JOIN project_participation ON project_participation.project_ref_id = projects.project_id 
                JOIN status ON status.status_id = projects.project_status 
                WHERE projects.project_status = 1 AND user.user_type_ref_id = '.Yii::$app->session->get("userType");
        $SQL1 .= $where;
        $SQL1 .= ' ) 
                AS a WHERE project_request_id = "" OR user_ref_id = '.yii::$app->user->id;
        
        $sort = Yii::$app->getRequest()->getQueryParam('sort') ? Yii::$app->getRequest()->getQueryParam('sort') : "";
        if (empty($sort))
            $SQL1 .= ' ORDER BY created_date DESC';
        //$SQL1 .= ') result WHERE result.status_name IS NOT NULL';
        $sql_data = count(yii::$app->db->createCommand($SQL1)->queryAll());
        //echo $SQL1; //die;
        $dataProvider1 = new SqlDataProvider([
            'sql' => $SQL1,
            'totalCount' => $sql_data,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
//        $dataProvider1->pagination->pageSize=10;

        $dataProvider1->setSort([
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
            ]
        ]);
        
        
        $QRY2 = 'SELECT COUNT(*) FROM projects, status WHERE projects.project_status = status.status_id AND user_ref_id = '.Yii::$app->user->id;
        
        $where2 = '';
        if(!empty($initiated) && empty($participate) && ( !empty($projectTitle) || !empty($projectCategory) || !empty($projectType) || !empty($projectStatus) || !empty($fromDate) || !empty($toDate) ) ) {
            $where2 .= (!empty($projectTitle)) ? " AND project_title LIKE '%".$projectTitle."%'" : "";
            $where2 .= (!empty($projectCategory)) ? " AND project_category_ref_id = ".$projectCategory : "";
            $where2 .= (!empty($projectType)) ? " AND project_type_ref_id = ".$projectType : "";
            $where2 .= (!empty($projectStatus)) ? " AND status.status_id = ".trim($projectStatus) : "";
            //$where2 .= (!empty($createdDate)) ? " AND DATE_FORMAT(projects.created_date, '%m-%d-%Y') >= '".$createdDate."'" : "";
            if(!empty($fromDate) && !empty($toDate)) {
                $where2 .= (!empty($fromDate)) ? ' AND ("'.date('Y-m-d', strtotime($fromDate) ).'" BETWEEN DATE_FORMAT(projects.`project_start_date`, "%Y-%m-%d") AND DATE_FORMAT(projects.`project_end_date`, "%Y-%m-%d")': "";
                $where2 .= (!empty($toDate)) ? ' OR "'.date('Y-m-d', strtotime($toDate) ).'" BETWEEN DATE_FORMAT(projects.`project_start_date`, "%Y-%m-%d") AND DATE_FORMAT(projects.`project_end_date`, "%Y-%m-%d"))': "";
            } elseif(!empty($fromDate) || !empty($toDate)) {
                 $ChkDate = !empty($fromDate) ? $fromDate : $toDate;
                $where2 .= ' AND "'.date('Y-m-d', strtotime($ChkDate) ).'" BETWEEN DATE_FORMAT(projects.`project_start_date`, "%Y-%m-%d") AND DATE_FORMAT(projects.`project_end_date`, "%Y-%m-%d")';
            }
        }
        $QRY2 .= $where2;
        
        $COUNT2 = Yii::$app->db->createCommand($QRY2)->queryScalar();

        $SQL2 = 'SELECT `project_co_owners`.`project_ref_id`, projects.project_id, `projects`.`user_ref_id`, `project_category_ref_id`, `project_title`, `project_type_ref_id`, `location`, `estimated_project_cost`, DATE_FORMAT(`project_start_date`, "'.$mysqldateformat.'") AS project_start_date, DATE_FORMAT(`project_end_date`,"'.$mysqldateformat.'") AS project_end_date, `projects`.created_date AS created_date, `project_co_owner_id`, status_name '
                . 'FROM `projects` '
                . 'LEFT JOIN project_co_owners ON projects.project_id = project_co_owners.project_ref_id  '
                . 'JOIN status ON projects.project_status = status.status_id  '
                . 'WHERE projects.user_ref_id = '.Yii::$app->user->id;

        $SQL2 .= $where2 . ' GROUP BY projects.project_id';
        
        $sort = Yii::$app->getRequest()->getQueryParam('sort') ? Yii::$app->getRequest()->getQueryParam('sort') : "";
        if (empty($sort))
            $SQL2 .= ' ORDER BY `created_date` DESC';
        //echo $SQL2; //return false;
        
        $dataProvider2 = new SqlDataProvider([
            'sql' => $SQL2,
            'totalCount' => $COUNT2,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        $dataProvider2->pagination->pageSize=10;

        $dataProvider2->setSort([
            'attributes' => [
                'project_category_ref_id',
                'project_title',
                'project_type_ref_id',
                'location',
                'estimated_project_cost',
                'project_start_date',
                'project_end_date',
                'Status',
                'project_co_owner_id',
                'created_date',
            ]
        ]);
               
        $QRY3 = 'SELECT COUNT(*) FROM ( '
                . 'SELECT project_id FROM `projects` LEFT JOIN project_participation pp ON pp.project_ref_id = projects.project_id '
                . 'JOIN status ON status.status_id = projects.project_status '
                . 'LEFT JOIN project_media pm ON pm.project_ref_id = project_id '
                . 'JOIN project_category pc ON pc.project_category_id = project_category_ref_id ';
        $QRY3 .= 'WHERE projects.project_status = 1 AND pp.user_ref_id = '.Yii::$app->user->id;
              //  . ' GROUP BY pp.project_ref_id) as projectParticipationCount;'; 
        
        $where3 = '';
        if(empty($initiated) && !empty($participate) && $participate == 'participate' && ( !empty($participation) || !empty($investmentType) || !empty($equityType) || !empty($fromDate) || !empty($toDate) ) ) {
            $where3 .= (!empty($participation)) ? " AND participation_type = '".$participation."'" : "";
            $where3 .= (!empty($investmentType)) ? " AND investment_type = '" . str_replace(" ", "_", $investmentType) . "'" : "";
            $where3 .= (!empty($equityType)) ? " AND equity_type = '" . str_replace(" ", "_", $equityType) . "'" : "";
            $where3 .= (!empty($fromDate)) ? ' AND DATE_FORMAT(pp.created_date, "%Y-%m-%d") >= "'.date("Y-m-d", strtotime($fromDate)).'"' : "";
            $where3 .= (!empty($toDate)) ? ' AND DATE_FORMAT(pp.created_date, "%Y-%m-%d") <= "'.date("Y-m-d", strtotime($toDate)).'"' : "";
        }
        $QRY3 .= $where3 . ' GROUP BY pp.project_ref_id) as projectParticipationCount';
        
//        echo $QRY3; exit;
        $COUNT3 = Yii::$app->db->createCommand($QRY3)->queryScalar();
        //print_r($COUNT3); die;

        
        $SQL3 = 'SELECT project_id, project_title, pp.user_ref_id, participation_type, investment_type, equity_type, amount, interest_rate, pp.created_date AS created_date, pm.document_name, pc.category_name '
                . 'FROM `projects` '
                . 'LEFT JOIN project_participation pp ON pp.project_ref_id = project_id '
                . 'JOIN status s ON projects.project_status = s.status_id '
                . 'LEFT JOIN project_media pm ON pm.project_ref_id = project_id '
                . 'JOIN project_category pc ON pc.project_category_id = project_category_ref_id '
                . 'WHERE project_status = 1 AND pp.user_ref_id = '.Yii::$app->user->id;
        $SQL3 .= $where3 . ' GROUP BY pp.project_ref_id, pp.user_ref_id';
        
        $sort = Yii::$app->getRequest()->getQueryParam('sort') ? Yii::$app->getRequest()->getQueryParam('sort') : "";
        if (empty($sort))
            $SQL3 .= ' ORDER BY created_date DESC';
        
        $dataProvider3 = new SqlDataProvider([
            'sql' => $SQL3,
            'totalCount' => $COUNT3,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        $dataProvider3->pagination->pageSize=10;

        $dataProvider3->setSort([
            'attributes' => [
                'project_title',
                'participation_type',
                'location',
                'estimated_project_cost',
                'investment_type', 
                'equity_type, amount', 
                'interest_rate', 
                'created_date',
                'category_name',
            ]
        ]);
                
        $dataProviderArray = array($dataProvider1, $dataProvider2, $dataProvider3);
        
        $projectCategories = ArrayHelper::map(ProjectCategory::find()->all(), 'project_category_id', 'category_name');
        //print_r($projectCategories);  die;
        $projectTypes = ArrayHelper::map(ProjectType::find()->all(), 'project_type_id', 'project_type');
        $projectStatus = ArrayHelper::map(Status::find()->where('status_id NOT IN (7,8)')->all(), 'status_id', 'status_name');
        
        return $this->render('index', [
            'model' => $model,
            'participationModel' => $participationModel,
            'dataProvider' => $dataProviderArray,
            'categories' => $projectCategories,
            'projectTypes' => $projectTypes,
            'projectStatus' => $projectStatus,
            'tabActive' => $tabActive,
        ]);
    }

    /**
     * Displays a single Projects model.
     * @param integer $id
     * @return mixed
     */
    
    public function actionView($id) {
        //$project = Projects::find()->asArray()->where(['project_id' => $id])->one();
        
        $actionId = (Yii::$app->getRequest()->getQueryParam('actionId')) ? Yii::$app->getRequest()->getQueryParam('actionId') : "";
        
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

        return Yii::$app->controller->renderPartial('view', [
                    'projectData' => $project, 
                    'rows' => $rows,
                    'actionId' => $actionId,
                ]);
        
    }
    
    public function actionView_bak($id)
    {
        $this->layout = '/main2';
        if (!(is_numeric($id))) {
            $id = base64_decode($id);
        }  
		$mysqldateformat = Yii::getAlias('@mysqldateformat');
      //  $this->layout = '/main2';
        $sql = 'SELECT `projects`.`project_id`, `project_title`, `projects`.`user_ref_id`, CONCAT(`fname`, " ", `lname`) as username, `participation_type`, `investment_type`, `equity_type`, `amount`, `interest_rate`, DATE_FORMAT(`projects`.`created_date`, "'.$mysqldateformat.'") as created_date '
                . 'FROM `projects` LEFT JOIN project_participation ON project_participation.project_ref_id = projects.project_id '
                . 'JOIN user_profile ON user_profile.user_ref_id = project_participation.user_ref_id '
                . 'LEFT JOIN project_comments ON project_comments.project_ref_id = projects.project_id '
                . 'WHERE project_participation.project_ref_id = '.$id;
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
        ]);
        
        $query = new Query();
        $query->select(['project_comment_id', 'project_ref_id', 'comments', 'DATE_FORMAT(project_comments.created_date, "'.$mysqldateformat.'") as created_date', 'project_comments.status', 'status.status_name', 'user_profile.user_ref_id', 'user_profile.fname', 'user_profile.lname', 'user_profile.user_image'])
                ->from('project_comments')
                ->join('JOIN', 'user_profile', 'user_profile.user_ref_id = project_comments.user_ref_id')
                ->join('JOIN', 'status', 'status.status_id = project_comments.status')
                ->where(["project_comments.project_ref_id" => $id])
                ->andWhere("( (project_comments.user_ref_id = ".Yii::$app->user->id." && project_comments.status in (2,7,8)) || (project_comments.user_ref_id != ".Yii::$app->user->id." && project_comments.status in (7)) )")
                ->orderBy(['project_comments.created_date' => SORT_DESC]);
        
        $command = $query->createCommand();
        //print_r($command); exit;
        $comments = $command->queryAll();
        
        $project = Projects::find()->asArray()->where(['project_id' => $id])->one();
        $rows = (new \yii\db\Query())
                ->select(['document_name', 'document_type', 'project_ref_id'])
                ->from('project_media')
                ->where(['project_ref_id' => $id])
                ->limit(10)
                ->all();

       $imageData = (new \yii\db\Query())
                ->select(['document_name', 'document_type', 'project_ref_id'])
                ->from('project_media')
                ->where(['project_ref_id' => $id])
                ->limit(1)
                ->all();


        return Yii::$app->controller->renderPartial('view', [
                    'projectData' => $project, 
                    'rows' => $rows,
                    'imageData'=> $imageData,
                    'model' => $this->findModel($id),
                    //'rows'=>ProjectMedia::find()->where(['project_ref_id'=>$id])->all(),
                    'dataProvider' => $dataProvider,
                    'comments' => $comments,
                ]);
        
        /*
        return $this->renderPartial('view', [
            'model' => $this->findModel($id),
            'rows'=>ProjectMedia::find()->where(['project_ref_id'=>$id])->all(),
            'dataProvider' => $dataProvider,
            'comments' => $comments,
        ]);
        */
    }

    /**
     * Creates a new Projects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout = '/main2';
        $model = new Projects();
        if (isset(Yii::$app->session['userType']) && Yii::$app->session['userType'] == 5) {
            $model->scenario = 'CSR';
        }else if(isset(Yii::$app->session['userType']) && Yii::$app->session['userType'] == 3){
            $model->scenario = 'individual';
        }
        $projectParticipationData = array('participation_type' => '', 'investment_type' => '', 'equity_type' => '', 'amount' => '', 'interest_rate' => '');
        $userData = User::getUserDetails(Yii::$app->user->identity->id);
        //$adminUsers = User::find()->where(['user_type_ref_id' => $userData[0]['user_type_ref_id'], 'user_role_ref_id' => 1])->all();
        $adminUsers =  Yii::$app->db->createCommand("SELECT * FROM `user` AS u 
                    LEFT JOIN admin_assigned_user_types AS au ON au.user_ref_id=u.id
                    WHERE user_role_ref_id=1 AND u.status=1 AND au.user_type_ref_id=".$userData[0]['user_type_ref_id'])->queryAll();
        if($userData[0]['is_profile_set']==1)
        {
            $projectCategories = ArrayHelper::map(ProjectCategory::find()->all(), 'project_category_id', 'category_name');
            $projectTypes = ArrayHelper::map(projectType::find()->all(), 'project_type_id', 'project_type');


            //print_r($_POST); die;

            if ($model->load(Yii::$app->request->post())){

                $projectImages = UploadedFile::getInstances($model, 'project_image');        
                $projectMediaFiles = UploadedFile::getInstances($model, 'document_name');  
                
                $projectTitle = $_POST['Projects']['project_title'] ? $_POST['Projects']['project_title'] : '';
                $projectDesc = $_POST['Projects']['project_desc'] ? $_POST['Projects']['project_desc'] : '';
                $projectObjective = $_POST['Projects']['objective'] ? $_POST['Projects']['objective'] : '';
                $projectConditions = $_POST['Projects']['conditions'] ? $_POST['Projects']['conditions'] : '';
                $project_category_ref_id = $_POST['Projects']['project_category_ref_id'] ? $_POST['Projects']['project_category_ref_id'] : '';
                
                $amount = isset($_POST['Projects']['amount']) ? $_POST['Projects']['amount'] : '';
                $participationType = $_POST['Projects']['participation_type'] ? $_POST['Projects']['participation_type'] : '';
                $investmentType = isset($_POST['Projects']['investment_type']) ? $_POST['Projects']['investment_type'] : '';
                $equityType = isset($_POST['Projects']['equity_type']) ? $_POST['Projects']['equity_type'] : '';
                $interestRate = isset($_POST['Projects']['interest_rate']) ? $_POST['Projects']['interest_rate'] : '';
                $embed_videos = isset($_POST['Projects']['embed_videos']) ? $_POST['Projects']['embed_videos']:'';
                $organisationName = isset($_POST['Projects']['Organization_name']) ? $_POST['Projects']['Organization_name'] : '';
                $primary_email_contact = $_POST['Projects']['primary_email_contact'] ? $_POST['Projects']['primary_email_contact'] : '';
                $secondary_contact = $_POST['Projects']['secondary_contact'] ? $_POST['Projects']['secondary_contact'] : '';
                $targeted_govt_authority = isset($_POST['Projects']['targeted_govt_authority']) ? $_POST['Projects']['targeted_govt_authority'] : '';
                $govt_authority_name = isset($_POST['Projects']['govt_authority_name']) ? $_POST['Projects']['govt_authority_name'] : '';
                
                $model->project_title = addslashes($projectTitle);
                $model->project_desc = addslashes($projectDesc);
                $model->objective = addslashes($projectObjective);
                $model->conditions = addslashes($projectConditions);
                $model->primary_email_contact = $primary_email_contact;
                $model->secondary_contact = $secondary_contact;
                if($_POST['Projects']['project_start_date']){
                    $model->project_start_date =  date('Y-m-d', strtotime($_POST['Projects']['project_start_date']) );
                }
                if(isset($_POST['Projects']['project_end_date'])){
                    $model->project_end_date =  date('Y-m-d', strtotime($_POST['Projects']['project_end_date']) );
                }
                $model->Status = 'Active';
                $model->project_status = 2;
                $model->project_category_ref_id = $project_category_ref_id;
                $model->project_type_ref_id = $_POST['Projects']['project_type_ref_id'];
                $model->Organization_name = addslashes($organisationName);
                $model->longitude = $_POST['Projects']['longitude'];
                $model->latitude = $_POST['Projects']['latitude'];
                $model->targeted_govt_authority = $targeted_govt_authority;
                $model->govt_authority_name = $govt_authority_name;
                $model->user_ref_id = Yii::$app->user->identity->id;
                $model->created_by = Yii::$app->user->identity->id;
                $model->created_date = date('Y-m-d H:i:s');

                if ($model->save()) {

                    $lastInsertId = $model->project_id;

                    $projectParticipation = new ProjectParticipation();
                    $projectParticipation->project_ref_id = trim($lastInsertId);
                    $projectParticipation->user_ref_id = Yii::$app->user->identity->id;
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
                    
                    $project_name = $_POST['Projects']['project_title'];
                    $email =  $userData[0]['email'];
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
                    ->send();*/
                    
                    $emailtemplate1 = EmailTemplates::getEmailTemplate(9);
                    $body=str_replace("{username}", ucwords($userData[0]['fname']).' '.ucwords($userData[0]['lname']), $emailtemplate1[2]['descrition']);
                    $body=str_replace("{projectname}", ucwords($project_name), $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate1[0]['descrition'].$body.$emailtemplate1[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($email)
                    ->setSubject($emailtemplate1[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
                    Communique::saveMailData($lastInsertId, $userData[0]['id'], $emailtemplate1[2]['subject'], $body, $email, 'Unread', 1);                                            

                     /*Yii::$app->mailer->compose('projectCreatedtoAdmin', 
                    [
                    'userdata'=> $userData,
                    'project_name'=> $_POST['Projects']['project_title'] ,
                    'title'      => Yii::t('app', 'User has created project successfully'),
                    'htmlLayout' => 'layouts/html'
                    ])
                    ->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo('equippp.donotreply@gmail.com')
                    ->setSubject('New EquiPPP Project has been created successfully')
                    ->send();*/
                    
                    $emailtemplate2 = EmailTemplates::getEmailTemplate(10);
                    $body=str_replace("{username}", ucwords($userData[0]['fname']).' '.ucwords($userData[0]['lname']), $emailtemplate2[2]['descrition']);
                    $body=str_replace("{projectname}", ucwords($project_name), $body);
                    $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                    $body=$emailtemplate2[0]['descrition'].$body.$emailtemplate2[1]['descrition'];
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
                    
                     Yii::$app->session->setFlash('project_created', "<div class='update-created'> <div>Project has been created successfully!</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'>�</button></div>");
                    // return $this->redirect('index');
                    
                    return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl("../../projects"), 301);
                }
            } else {
                //print_r($projectParticipationAmount); die;
                return $this->render('create', [
                    'model' => $model,
                    'projectParticipationData' => $projectParticipationData,
                    'projectCategories' => $projectCategories,
                    'projectTypes' => $projectTypes,
                    'userData'=>@$userData,
                ]);
            }
        }
        else
        {
            Yii::$app->session->setFlash('pleasefill', "<div class='update-created'> <div>Please fill your profile details to create project!</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'>�</button></div>");
                          
              return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl("../../profile"), 301);
            // return $this->redirect(array('site/user-profile')); 

        }
    }

    /**
     * Updates an existing Projects model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /*$app_name = strpos(strtolower(yii::$app->request->Url), yii::$app->name ) !== false?'/'.yii::$app->name:'';        
        if(strtolower(yii::$app->request->Url) != "$app_name/edit-project?id=$id"){
            $this->redirect(Yii::$app->urlManager->createAbsoluteUrl("../../edit-project?id=$id"), 301);
        }*/
        $this->layout = '/main2';
        $model = $this->findModel($id);
       if (isset(Yii::$app->session['userType']) && Yii::$app->session['userType'] == 5) {
            $model->scenario = 'CSR';
        }else if(isset(Yii::$app->session['userType']) && Yii::$app->session['userType'] == 3){
            $model->scenario = 'individual';
        }
        $projectParticipationData = ProjectParticipation::find()->where(['project_ref_id' => $id, 'user_ref_id' => $model->user_ref_id])->asArray()->one();
        
        
        $projectCategories = ArrayHelper::map(ProjectCategory::find()->all(), 'project_category_id', 'category_name');
        $projectTypes = ArrayHelper::map(projectType::find()->all(), 'project_type_id', 'project_type');
        $userData = User::getUserDetails(Yii::$app->user->identity->id);
        $allProjectImages=$this->getProjectImages($id);
        $allProjectDocuments=$this->getProjectDocuments($id);
        $allProjectVidoes=$this->getProjectVideos($id);

        if ($model->load(Yii::$app->request->post())){

            $projectImages = UploadedFile::getInstances($model, 'project_image');        
            $projectMediaFiles = UploadedFile::getInstances($model, 'document_name'); 
            
            $projectTitle = $_POST['Projects']['project_title'] ? $_POST['Projects']['project_title'] : '';
            $projectDesc = $_POST['Projects']['project_desc'] ? $_POST['Projects']['project_desc'] : '';
            $projectObjective = $_POST['Projects']['objective'] ? $_POST['Projects']['objective'] : '';
            $projectConditions = $_POST['Projects']['conditions'] ? $_POST['Projects']['conditions'] : '';            

            $amount = isset($_POST['Projects']['amount']) ? $_POST['Projects']['amount'] : '';
            $participationType = $_POST['Projects']['participation_type'] ? $_POST['Projects']['participation_type'] : '';
            $investmentType = isset($_POST['Projects']['investment_type']) ? $_POST['Projects']['investment_type'] : '';
            $equityType = isset($_POST['Projects']['equity_type']) ? $_POST['Projects']['equity_type'] : '';
            $interestRate = isset($_POST['Projects']['interest_rate']) ? $_POST['Projects']['interest_rate'] : '';
            $embed_videos = isset($_POST['Projects']['embed_videos']) ? $_POST['Projects']['embed_videos']:'';
            $organisationName = isset($_POST['Projects']['Organization_name']) ? $_POST['Projects']['Organization_name'] : '';
            $primary_email_contact = $_POST['Projects']['primary_email_contact'] ? $_POST['Projects']['primary_email_contact'] : '';
            $secondary_contact = $_POST['Projects']['secondary_contact'] ? $_POST['Projects']['secondary_contact'] : '';
            $targeted_govt_authority = isset($_POST['Projects']['targeted_govt_authority']) ? $_POST['Projects']['targeted_govt_authority'] : '';
            $govt_authority_name = isset($_POST['Projects']['govt_authority_name']) ? $_POST['Projects']['govt_authority_name'] : '';
            $project_category_ref_id = isset($_POST['Projects']['project_category_ref_id']) ? $_POST['Projects']['project_category_ref_id'] : '';
            
            if($_POST['Projects']['project_start_date']){
                $model->project_start_date =  date('Y-m-d', strtotime($_POST['Projects']['project_start_date']) );
            }
            if(isset($_POST['Projects']['project_end_date'])){
                $model->project_end_date =  date('Y-m-d', strtotime($_POST['Projects']['project_end_date']) );
            }
            $model->project_title = addslashes($projectTitle);
            $model->project_desc = addslashes($projectDesc);
            $model->objective = addslashes($projectObjective);
            $model->conditions = addslashes($projectConditions);
            $model->Organization_name = addslashes($organisationName);
            $model->project_type_ref_id = $_POST['Projects']['project_type_ref_id'];
            $model->project_category_ref_id = $project_category_ref_id;
            $model->longitude = $_POST['Projects']['longitude'];
            $model->latitude = $_POST['Projects']['latitude'];
            $model->primary_email_contact = $primary_email_contact;
            $model->secondary_contact = $secondary_contact;  
            $model->targeted_govt_authority = $targeted_govt_authority;
            $model->govt_authority_name = $govt_authority_name;
            $model->user_ref_id = Yii::$app->user->identity->id;            
            $model->modified_by = Yii::$app->user->identity->id;
            $model->modified_date = date('Y-m-d H:i:s');

            if ($model->save()) {

                $lastInsertId = $model->project_id;
                
                $project_participation_model = ProjectParticipation::find()->where(['project_ref_id' => $id, 'user_ref_id'=>Yii::$app->user->identity->id])->one();
                $projectParticipation = array();

                if(empty($project_participation_model)){
                    $projectParticipation = new ProjectParticipation();
                    $projectParticipation->project_ref_id = trim($lastInsertId);
                    $projectParticipation->user_ref_id = Yii::$app->user->identity->id;
                    $projectParticipation->participation_type = $participationType;
                    $projectParticipation->created_by = trim(Yii::$app->user->identity->id);
                    $projectParticipation->investment_type = $investmentType;
                    $projectParticipation->equity_type = $equityType;
                    $projectParticipation->amount = trim($amount);
                    $projectParticipation->interest_rate = trim($interestRate);
                    $projectParticipation->created_date = date('Y-m-d H:i:s');                        
                    $projectParticipation->save(false);
                }else{                       

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
                
                if (isset($projectMediaFiles) && count($projectMediaFiles) > 0) {
                   Storage::mediaUpload($projectMediaFiles, $lastInsertId);
                }   
                 if($embed_videos)
                    {
                    ProjectMedia::saveembedlink($embed_videos,$lastInsertId); 
                    }
                Yii::$app->session->setFlash('project_success', "<div class='update-created'> <div>Project has been updated successfully!</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'>ï¿½</button></div>");
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl("../../projects"), 301);
                
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'projectParticipationData' => $projectParticipationData,
                'projectCategories' => $projectCategories,
                'projectTypes' => $projectTypes,
                'userData'=>@$userData,
                'allProjectImages'=>@$allProjectImages,
                'allProjectDocuments'=>@$allProjectDocuments,
                'allProjectVidoes'=> @$allProjectVidoes,
            ]);
        }
    }

    /**
     * Deletes an existing Projects model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->layout = '/main2';
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
    protected function findModel($id)
    {
        $this->layout = '/main2';
        if (($model = Projects::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionProjects_participation() {
        //echo $x = CHttpRequest::getParam('id'); die;
        //echo Yii::$app->request->get('id'); die;
        $this->layout = '/main2';
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
        
        return $this->render('projectParticipation', ['model'=> $model, 'project' => $project]);
    }
    
    public function actionApprove(){        
        $this->layout = '/main2';
        $this->view->title = 'Private Project Approval Requests';
        $query = "SELECT user_request_id, requested_user_id, project_type_id, project_ref_id, project_created_by_user_id, approved_by, project_title, 
project_type, fname, lname, approved_on, is_approved FROM (
            SELECT ur.user_request_id, ur.user_ref_id AS requested_user_id, pt.project_type_id, ur.project_ref_id, p.user_ref_id AS project_created_by_user_id, ur.approved_by, p.project_title, 
pt.project_type, up.fname, up.lname, ur.approved_on, ur.is_approved FROM user_requests ur LEFT JOIN projects p ON p.project_id = ur.project_ref_id "
                . "JOIN project_type pt ON pt.project_type_id = p.project_type_ref_id "
                . "JOIN user_profile up ON up.user_ref_id = ur.user_ref_id "
                . "WHERE pt.project_type_id=2 AND p.user_ref_id=".yii::$app->user->identity->id."
                    UNION 
                    SELECT ur.user_request_id, ur.user_ref_id AS requested_user_id, pt.project_type_id, ur.project_ref_id, p.user_ref_id AS project_created_by_user_id, ur.approved_by, p.project_title, 
                    pt.project_type, up.fname, up.lname, ur.approved_on, ur.is_approved 
                    FROM user_requests ur 
                    JOIN projects p ON p.project_id = ur.project_ref_id 
                    LEFT JOIN project_co_owners pc ON pc.project_ref_id = ur.project_ref_id
                    JOIN project_type pt ON pt.project_type_id = p.project_type_ref_id 
                    JOIN user_profile up ON up.user_ref_id = ur.user_ref_id 
                    WHERE pt.project_type_id=2 AND pc.user_ref_id=".yii::$app->user->identity->id
                . " ) AS a 
                    ORDER BY approved_on DESC";
        
        $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM user_requests ur LEFT JOIN projects p ON p.project_id = ur.project_ref_id
JOIN project_type pt ON pt.project_type_id = p.project_type_ref_id
JOIN user_profile up ON up.user_ref_id = ur.user_ref_id WHERE pt.project_type_id=2 AND p.user_ref_id='.yii::$app->user->identity->id)->queryScalar();
        
        return $this->render('project_approval',['query'=>$query,'count'=>$count]);
    }
    
    public function actionChangestatus(){    
        $id= Yii::$app->getRequest()->getQueryParam('id');
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
        if($status == 0){
            $query = "UPDATE user_requests SET is_approved = 1, approved_by = " .yii::$app->user->identity->id.", approved_on = '".$date."' WHERE user_request_id=".$id;
            
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
        }else if($status == 1){
            $query = "UPDATE user_requests SET is_approved = 0, approved_by = " .yii::$app->user->identity->id.", approved_on = '".$date."'  WHERE user_request_id=".$id;
            
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
        }
        $result = Yii::$app->db->createCommand($query)->execute();
        
        
        return true;      
    }
    
    public static function getProjectImages($id){
        
        $data = yii::$app->db->createCommand("SELECT * FROM project_media where project_ref_id =".$id." and document_type='projectImage'")->queryAll();  
        
        return $data;
    }
	
    public function actionDeleteProjectImage(){   
        
        if(Yii::$app->getRequest()->getQueryParam('dname')){
            $bucket = Yii::getAlias('@bucket');
            $keyname = 'uploads/project_images/'.Yii::$app->getRequest()->getQueryParam('pid').'/'.Yii::$app->getRequest()->getQueryParam('dname');
            $key = 'uploads/project_images/'.Yii::$app->getRequest()->getQueryParam('pid').'/thumb/'.Yii::$app->getRequest()->getQueryParam('dname');
            $s3 = new Storage();
            $result = $s3->delete($bucket, $keyname);   
            $status = $s3->delete($bucket, $key);
            if($result['@metadata']['statusCode'] == 204 && $status['@metadata']['statusCode'] == 204){
                yii::$app->db->createCommand()->delete('project_media', 'project_media_id = '.Yii::$app->getRequest()->getQueryParam('pmid'))->execute();
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
    
    public static function getProjectDocuments($id){
        
        $data = yii::$app->db->createCommand("SELECT * FROM project_media where project_ref_id =".$id." and document_type='projectDocument'")->queryAll();  
        
        return $data;
    }
    
    public static function getProjectVideos($id)
    {
      $data = yii::$app->db->createCommand("SELECT * FROM project_media where project_ref_id =".$id." and document_type='projectVideos'")->queryAll();
       return $data;
    }
	
    // for private projects 
    public  function actionPrivateProjects(){
        $getPrivateProjects=Projects::getPrivateProjects();
        return $this->render('private-projects',[
            'privateprojimgs' =>  $getPrivateProjects]);
	
        //  $data = yii::$app->db->createCommand("SELECT * FROM project_media where project_ref_id =".$id." and document_type='projectDocument'")->queryAll();  
        
        // return $data;
    }
    
    public  function actionGetParticipantDetails(){
        $sql = 'SELECT count(*) FROM project_participation WHERE project_ref_id ='.$_POST['pid'].' AND user_ref_id = '.$_POST['uid'];
        $count = yii::$app->db->createCommand($sql)->queryScalar();
            return $count;
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
            
            echo '<table class="table-events table-head-fix" width="100%" cellspacing="0" cellpadding="0">';
            echo '<tr class="" style="">';
                echo '<th width="225">Project Title</th>';
                echo '<th width="112">Amount</th>';
                echo '<th width="112">Start Date</th>';
                echo '<th width="112">End Date</th>';
            echo '</tr>';
            echo '<tr>';
                echo '<td class="" width="100%" colspan="4" style=" padding:0px;">';
                    echo '<div class="table-head-fix-body">';
                        echo '<table class="table-events" width="100%" style="overflow: auto;" cellspacing="0" cellpadding="0">';
            $i = 0;
            foreach($projectData as $projectDetails) {
                if((date('Y-m-d', strtotime($projectDetails->project_start_date)) >= $minDate) && (date('Y-m-d', strtotime($projectDetails->project_end_date)) <= $maxDate)) {
                    $bgColor = ($i % 2 == 0) ? "#F4F4F4"  : "#FFFFFF";
                    echo '<tr style="background-color: '.$bgColor.'; height: 20px;">';
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
                echo '</td>';
                echo '</tr>';
            echo '</table>';
        }
        return false;
    }
    
    public  function actionSaveProjectLikes(){
        if(isset($_REQUEST['pid'])){
        $sql = 'SELECT count(*) FROM project_likes WHERE project_ref_id ='.$_REQUEST['pid'].' AND user_ref_id = '.Yii::$app->user->id;
        
        
        $count = yii::$app->db->createCommand($sql)->queryScalar();
        $model = new \common\models\ProjectLikes();
        if($count==0){
        $model->project_ref_id = $_REQUEST['pid'];
        $model->user_ref_id = Yii::$app->user->id;
        $model->created_date = date('Y-m-d H:i:s');
        $model->save(false);
        }else{
            $sql = 'DELETE FROM project_likes WHERE project_ref_id ='.$_REQUEST['pid'].' AND user_ref_id = '.Yii::$app->user->id;
            $result = Yii::$app->db->createCommand($sql)->execute();
        }
        echo $_REQUEST['like'];
        }
    }
    
    public  function actionIsProjectLiked(){
        $pid = (isset($_REQUEST['pid']) && !empty($_REQUEST['pid'])) ? $_REQUEST['pid'] : (Yii::$app->getRequest()->getQueryParam('id') ? Yii::$app->getRequest()->getQueryParam('id') : "");
        if(Yii::$app->getRequest()->getQueryParam('id'))
            $sql = 'SELECT count(*) FROM project_likes WHERE project_ref_id ='.Yii::$app->getRequest()->getQueryParam('id').' AND user_ref_id = '.Yii::$app->user->id;
        else
            $sql = 'SELECT count(*) FROM project_likes WHERE project_ref_id ='.$_REQUEST['pid'].' AND user_ref_id = '.Yii::$app->user->id;
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
        
        $actionId = (Yii::$app->getRequest()->getQueryParam('actionId')) ? Yii::$app->getRequest()->getQueryParam('actionId') : "";
        //echo $projectCommentId .'&&'. $comments .'&&'. $projectId; exit;
        //echo $projectId; exit;
        if($comments && $projectId && $userId && empty($status)) {
            $model = new ProjectComments();

            $model->project_ref_id = $projectId;
            $model->user_ref_id = $userId;
            $model->comments = addslashes($comments);
            $model->status = 2;
            $model->created_by = Yii::$app->user->id;
            $model->created_date = date('Y-m-d H:i:s');
            
            $model->save();
        } elseif($projectCommentId && $status && empty($comments)) {
            //$model = $this->findModel($projectCommentId);
            //print_r($model);
            $query = "UPDATE project_comments SET status = ".$status." WHERE project_comment_id = ".$projectCommentId;
            $result = Yii::$app->db->createCommand($query)->execute();
            //exit;
        } elseif($projectCommentId && $comments && $projectId && (empty($status) && empty($userId)) ) {
            //$model = $this->findModel($projectCommentId);
            //print_r($model);
            $model = ProjectComments::find()->where(['project_comment_id' => $projectCommentId])->one();
            
            $model->comments = addslashes($comments);
            $model->save();
            
            //$query = "UPDATE project_comments SET comments = '".addslashes($comments)."' WHERE project_comment_id = ".$projectCommentId;
            //$result = Yii::$app->db->createCommand($query)->execute();
            //exit;
        } elseif($projectCommentId && (empty($comments) && empty($status) && empty($userId)) ) {
            //$model = ProjectComments::find()->where(['project_comment_id' => $projectCommentId])->delete();
            Yii::$app->db->createCommand()->delete('project_comments', ['project_comment_id' => $projectCommentId])->execute();
            return true;
        }
        
        //$model = new Projects();
        
        //print_r($model); die;
        //$projectId = (Yii::$app->getRequest()->getQueryParam('projectId')) ? Yii::$app->getRequest()->getQueryParam('projectId') : "";
        
        //$comments = ProjectComments::find()->where(['project_ref_id' => $model->project_id, 'status' => '1'])->all();
        
        /*
        if($actionId == 'googleMap')
            $statusCond = ' AND project_comments.status = 7';
        else
            $statusCond = '';
        */
        
        $query = new Query();
        $query->select(['project_comment_id', 'project_ref_id', 'comments', 'DATE_FORMAT(`project_comments`.`created_date`, "'.$mysqldateformat.'") as created_date', 'project_comments.status', 'status.status_name', 'user_profile.user_ref_id', 'user_profile.fname', 'user_profile.lname', 'user_profile.user_image'])
                ->from('project_comments')
                ->join('JOIN', 'projects', 'projects.project_id = project_comments.project_ref_id')
                ->join('JOIN', 'user_profile', 'user_profile.user_ref_id = project_comments.user_ref_id')
                ->join('JOIN', 'status', 'status.status_id = project_comments.status')
                //->where(["project_comments.project_ref_id" => $projectId])
                ->where("project_comments.project_ref_id = ".$projectId)
                ->andWhere("( (project_comments.user_ref_id = ".Yii::$app->user->id." && project_comments.status in (2,7,8) ) || (project_comments.user_ref_id != ".Yii::$app->user->id." && project_comments.status in (7)) )")
                //->andWhere(" '".date('Y-m-d')."' BETWEEN DATE_FORMAT(project_start_date, '%Y-%m-%d') AND DATE_FORMAT(project_end_date, '%Y-%m-%d') ")
                ->orderBy(['project_comments.created_date' => SORT_DESC]); 
        
        $command = $query->createCommand();
        //print_r($command); //echo '-------'.$actionId.'====='; exit;
        $comments = $command->queryAll();
        
        $data = '';
        if(count($comments) > 0)
        {
            foreach($comments as $comment) {
                $data .= '<div class="col-md-12" style="padding-bottom: 10px;" id="divComment_'.$comment['project_comment_id'].'">';
                    $data .= '<div class="divCommentsDesc">
                        <div class="userImg">';
                        if (!empty($comment['user_image']) && file_exists(Yii::getAlias('@upload') . '/frontend/web/uploads/profile_images/' . $comment['user_ref_id'] . '/' . $comment['user_image']))
                            $userImageUrl = Yii::$app->request->baseUrl . '/uploads/profile_images/' . $comment['user_ref_id'] . '/' . $comment['user_image'];
                        else
                            $userImageUrl = Yii::$app->request->baseUrl . '/images/avatar.png';
                    $data .= '<img src="'.$userImageUrl.'" width="50" />
                        </div>
                        <div class="userComment">
                            <textarea class="txtComments" disabled="disabled" id="comment_'.$comment['project_comment_id'].'">'.stripslashes($comment['comments']).'</textarea>
                        </div>
                    </div>
                    <div class="userActions btnCommentAction"><div class="pull-left">';
                       $data .= $comment['fname'].' '.$comment['lname']; 
                                       $data .= '</div>';
                        //$data .= ($comment['status'] == '2') ? '<b>&nbsp;&nbsp;['.$comment['status_name'].']</b>' : '';
                        $commentUrl = Yii::$app->request->BaseUrl.'/projects/displayallcomments';
                        //if($actionId != 'googleMap') {
                            if($comment['status'] == '2') {
                                $data .= '<div class="commentActions pull-right" id="commentStatus_'.$comment['project_comment_id'].'">';
                                    $data .= '<span class="clsPending">Pending</span>';
                                    if($comment['user_ref_id'] == Yii::$app->user->id) {
                                        $data .= '<a id="btnEdit_'.$comment['project_comment_id'].'" class="btn-danger btnCommentAction commentEdit_'.$comment['project_comment_id'].'" onclick="javascript: modifyComment(\''.$comment['project_comment_id'].'\')">Edit</a>';
                                        $data .= '<a onClick="saveComment(\''.$commentUrl.'\', \''.$comment['project_comment_id'].'\', \''.$comment['project_ref_id'].'\')" style="display:none" id="btnSave_'.$comment['project_comment_id'].'" class="btn-primary btnCommentAction commentEdit_'.$comment['project_comment_id'].'"><b>Save</b></a>';
                                        $data .= '<a id="btnDelete_'.$comment['project_comment_id'].'" class="btn-danger btnCommentAction commentEdit_'.$comment['project_comment_id'].'" onclick="javascript: deleteComment(\''.$comment['project_comment_id'].'\')">Delete</a>';
                                        $data .= '<input type="hidden" name="projectId_'.$comment['project_comment_id'].'" id="project_id_'.$comment['project_comment_id'].'" value="'.$comment['project_comment_id'].'" />';
                                    }
        //                            $data .= '<a id="btnAccept" class="btn-primary btnCommentAction onclick="javascript: changeCommentStatus(\''.$comment['project_comment_id'].'\', \''.$comment['project_ref_id'].'\', \''.$comment['user_ref_id'].'\', \'7\')"><b>Accept</b></a>';
        //                            $data .= '<a id="btnReject" class="btn-danger btnCommentAction" onclick="javascript: changeCommentStatus(\''.$comment['project_comment_id'].'\', \''.$comment['project_ref_id'].'\', \''.$comment['user_ref_id'].'\', \'7\')"><b>Reject</b></a>';
        //                            $data .= '<a id="btnReject" class="btn-danger btnCommentAction" onclick="javascript: modifyComment(\''.$comment['project_comment_id'].'\')"><b>EDIT</b></a>';
                                $data .= '</div>';
                            } elseif($comment['status'] == '7') {
                                $data .= '<div class="commentActions pull-right" id="commentStatusAccepted">';
                                    $data .= '<span class="btn-accept btnCommentAction">Accepted</span>';
                                $data .= '</div>';
                            } elseif($comment['status'] == '8') {
                                $data .= '<div class="commentActions pull-right" id="commentStatusRejected">';
                                    $data .= '<span class="btn-reject btnCommentAction">Rejected</span>';
                                $data .= '</div>';
                            }
                        //}
                    $data .= '</div>
                    <div class="clearfix"></div>
                </div>';
            }
        } else {
            $data .= '<div >No Comments Available</div>';
        }
        $data .= '<input type="hidden" id="commentId" name="commentId" value="" />';
        
//        if(empty($actionId)) {
//            $data .= '<div class="col-md-12 submit-comments">
//                        <textarea cols="50" rows="3" placeholder="Comments" id="txtComments" name="txtComments"></textarea>
//                        <input type="button" name="btnAddComments" id="btnAddComments" value="Send" class="btn btn-success" style="float: right;margin: 16px;" />
//                        <input type="hidden" name="projectId" id="projectId" value="'.$projectId.'" />
//                        <input type="hidden" name="userId" id="userId" value="'.Yii::$app->user->id.'" />
//                        <input type="hidden" value="'.Yii::$app->request->BaseUrl.'/projects/displayallcomments" id="commentsUrl">
//                    </div>';
//        }
        //  onclcik="javascript: addComment(\''.Yii::$app->request->BaseUrl.'/projects/displayallcomments\', \''.Yii::$app->user->id.'\')" 
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
}
