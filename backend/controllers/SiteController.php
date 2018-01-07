<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use backend\models\Projects;
use backend\models\User;
use backend\models\ProjectParticipation;
use yii\db\Query;
use backend\models\ProjectComments;
date_default_timezone_set('Asia/Kolkata');
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'ghost-access' => [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
               /* 'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],*/
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function actionIndex() {
        //$this->layout = '/main2';

        $totalProjects = Projects::find()->count();
        $totalProjectInitiated = Projects::find()->where('project_status in (1, 4)')->count();
        $totalProjectParticipated = ProjectParticipation::find()->groupBy(['project_ref_id'])->count();
        $sql = 'SELECT COUNT(*) FROM user u LEFT JOIN user_profile up ON up.user_ref_id = u.id JOIN user_type ut ON ut.user_type_id = u.user_type_ref_id
WHERE u.status = 1 AND u.user_role_ref_id != 1 AND u.superadmin=0;';
        $totalUsers = Yii::$app->db->createCommand($sql)->queryScalar();
        $totalParticipants = ProjectParticipation::find()->groupBy(['user_ref_id','project_ref_id'])->count();
        $mpMlaProjects = Projects::getMlaMpProjects();
        $csrProjects = Projects::getCsrProjects();
        $bankProjects = Projects::getBankProjects();
        $monthlyParticipation = Projects::getMonthlyParticipationAmount();
        $monthlyParticipants = Projects::getMonthlyParticipats();
        $allprojects = Projects::getAllProjectsForGraph();
        $allprojectsbymembers = Projects::getAllProjectsByMembersForGraph();
        //print_r($monthlyParticipation); exit;
        //echo count($monthlyParticipation); exit;
        
        $members = json_encode($monthlyParticipants);

        $status = 2;
        $query = new Query();
        $query->select(['project_comment_id', 'project_comments.project_ref_id', 'comments', 'DATE_FORMAT(project_comments.created_date, "%d %b %Y %I:%i %p") as created_date', 'project_comments.status', 'projects.project_title', 'status.status_name', 'user_profile.user_ref_id', 'user_profile.fname', 'user_profile.lname', 'user_profile.user_image'])
                ->from('project_comments')
                ->join('JOIN', 'user_profile', 'user_profile.user_ref_id = project_comments.user_ref_id')
                ->join('JOIN', 'status', 'status.status_id = project_comments.status')
                ->join('JOIN', 'user', 'user.id = user_profile.user_ref_id')
                ->join('JOIN', 'projects', 'projects.project_id = project_comments.project_ref_id')
                ->join('LEFT JOIN', 'project_participation', 'project_participation.project_ref_id = project_comments.project_ref_id')
                //->where(["projects.project_id" => $id])
                //->where("( (project_comments.project_ref_id = projects.project_id && project_comments.status in (2,7,8)) || (project_comments.user_ref_id != projects.project_id && project_comments.status in (7)) )")
                ->where("( project_comments.status = " . $status . ")")
                ->groupBy('project_comment_id')
                ->orderBy(['project_comments.created_date' => SORT_DESC]);

        $command = $query->createCommand();
        //print_r($command); exit;
        $comments = $command->queryAll();
        /**/
        $email_notif = Yii::$app->db->createCommand('SELECT COUNT(*) as count FROM communique c JOIN user_profile u ON(c.created_by=u.user_ref_id) WHERE c.view_status=0 AND c.user_ref_id=' . Yii::$app->user->id)->queryAll();
        //for  comment and likes
        $project_comment_notif = Yii::$app->db->createCommand("SELECT count(*) as count FROM project_comments pc 
	 LEFT JOIN projects p ON(p.project_id=pc.project_ref_id) 
	 JOIN `status`s ON(status_id=pc.status)
         WHERE pc.read_status=0 AND  s.status_name='Accept'")->queryAll();
        $project_likes_notif = Yii::$app->db->createCommand('SELECT count(*) as count FROM project_likes pl LEFT JOIN projects p ON(p.project_id=pl.project_ref_id) WHERE pl.read_status=0')->queryAll();
        $email_notif_count = $email_notif[0]['count'];
        
        
        $project_recent_activities = Yii::$app->db->createCommand('SELECT status, id, created_date, comments, project_title, user_ref_id  FROM (
            SELECT pc.read_status AS status,pc.project_comment_id AS id, pc.created_date AS created_date,pc.comments,p.project_title,pc.user_ref_id 
            FROM project_comments pc
                    LEFT JOIN projects p ON(p.project_id=pc.project_ref_id) 
                    JOIN `status`s ON(status_id=pc.status)
            WHERE s.status_name="Accept"
            UNION
            SELECT pl.read_status AS status,pl.project_likes_id AS id, pl.created_date AS created_date, "" AS comments,p.project_title,pl.user_ref_id
            FROM project_likes pl 
                    LEFT JOIN projects p ON(p.project_id=pl.project_ref_id) 
            UNION
            SELECT c.status, c.communique_id AS id, c.created_date AS created_date, "" AS comments,p.project_title AS project_title, c.user_ref_id 
            FROM communique c
                LEFT JOIN projects p ON(p.project_id=c.project_ref_id) 
            WHERE c.user_ref_id='.Yii::$app->user->id.') AS a
            ORDER BY created_date DESC')->queryAll();
        
        //$likes_communique_count = array_sum(array_map(function($b) {return (empty($b['comments']) && ((is_numeric($b['status']) && $b['status'] == 0) || $b['status'] == 'Unread')) ? 1 : 0;}, $project_recent_activities));
        //$email_notif_count = array_sum(array_map(function($b) {return (!empty($b['comments']) && (is_numeric($b['status']) && $b['status'] == 0)) ? 1 : 0;}, $project_recent_activities));
		
		
		$curmonth = date('M', strtotime('-5 month'));
		 $curyear = date('Y', strtotime('-5 month'));
         $fmonth = $curyear.'-'.$curmonth.'-01'; 
		 
        $tmonth = date('d-M-Y');
		
		$start = strtotime($fmonth);
		$end = strtotime($tmonth);
		while($start < $end)
		{
			$months[] = date('M-Y', $start); 
			$start = strtotime("+1 month", $start);
		}
		$variables= array();
		if(count($monthlyParticipation)>0)
		{
		for ($j=0; $j<count($monthlyParticipation); $j++ ) {
		$project_ref_id = $monthlyParticipation[$j]['project_ref_id'];
		$project_title = $monthlyParticipation[$j]['project_title'];
		$estimated_project_cost = $monthlyParticipation[$j]['estimated_project_cost'];
		$amount = $monthlyParticipation[$j]['amount'];
		$getmonths = $monthlyParticipation[$j]['months'];
		
		for($i=0; $i<count($months); $i++)
		{
		if($months[$i] == $getmonths){
		$variables[$j][$i]['project_ref_id'] = $project_ref_id;
		$variables[$j][$i]['project_title'] = $project_title;
		$variables[$j][$i]['estimated_project_cost'] = $estimated_project_cost;
		$variables[$j][$i]['amount'] = $amount;
		$variables[$j][$i]['months'] = $months[$i];
		}else{
		$variables[$j][$i]['project_ref_id'] = $project_ref_id;
		$variables[$j][$i]['project_title'] = $project_title;
		$variables[$j][$i]['estimated_project_cost'] = "0";
		$variables[$j][$i]['amount'] = "0";
		$variables[$j][$i]['months'] = $months[$i];
		}
		}
		
		}
		$amount = call_user_func_array('array_merge', $variables);
		}else{
		$variables= array();
		$amount = $variables;
		}
		$amount = json_encode($amount);
        return $this->render('dashboard', [
                    'totalProjectInitiated' => $totalProjectInitiated,
                    'totalProjectParticipated' => $totalProjectParticipated,
                    'totalProjects' => $totalProjects,
                    'totalUsers' => $totalUsers,
                    'totalParticipants' => $totalParticipants,
                    'mpMlaProjects' => $mpMlaProjects,
                    'csrProjects' => $csrProjects,
                    'bankProjects' => $bankProjects,
                    'monthlyParticipation' => $amount,
                    'monthlyParticipants' => $members,
                    'allprojects' => $allprojects,
                    'allprojectsbymembers' => $allprojectsbymembers,
                    'comments' => $comments,
                    'email_notif_count' => $email_notif_count,
                    'project_likes_notif' => $project_likes_notif[0]['count'],
                    'project_comment_notif' => $project_comment_notif[0]['count'],
                    'project_recent_activities' => $project_recent_activities
                ]);
    }
    
    public function actionGetMonthsData(){
        $frommonth = isset($_REQUEST['frommonth'])?$_REQUEST['frommonth']:'';
        $tomonth = isset($_REQUEST['tomonth'])?$_REQUEST['tomonth']:'';
		$fromyear = isset($_REQUEST['fromyear'])?$_REQUEST['fromyear']:'';
        $toyear = isset($_REQUEST['toyear'])?$_REQUEST['toyear']:'';
        $selectedprj = isset($_REQUEST['prjid'])?$_REQUEST['prjid']:'';
		$checkValues = isset($_REQUEST['checkValues'])?$_REQUEST['checkValues']:'';
        $monthsParticipants = Projects::getParticipatsBetweenMonths($frommonth,$tomonth,$fromyear,$toyear,$selectedprj,$checkValues);
		$count = count($monthsParticipants);
		if($frommonth){
		$fmonth = $fromyear.'-'.$frommonth.'-01';
        }else{
         $curmonth = date('M', strtotime('-5 month'));
		 $curyear = date('Y', strtotime('-5 month'));
         $fmonth = $curyear.'-'.$curmonth.'-01'; 
        }
		 
		 if($tomonth){
		$lastdate = date('t', strtotime($tomonth.','.$toyear));
        $tmonth = $lastdate.'-'.$tomonth.'-'.$toyear;
        }else{
            $tmonth = date("d-M-Y");
        }
		 
		$start = strtotime($fmonth);
		$end = strtotime($tmonth);
		while($start < $end)
		{
			$months[] = date('M-Y', $start); 
			$start = strtotime("+1 month", $start);
		}
		$variables = array();
		
		
		for ($j=0; $j<count($monthsParticipants); $j++ ) {
		$project_ref_id = $monthsParticipants[$j]['project_ref_id'];
		$project_title = $monthsParticipants[$j]['project_title'];
		$estimated_project_cost = $monthsParticipants[$j]['estimated_project_cost'];
		$amount = $monthsParticipants[$j]['amount'];
		$getmonths = $monthsParticipants[$j]['months'];
		
		for($i=0; $i<count($months); $i++)
		{
		if($months[$i] == $getmonths){
		$variables[$j][$i]['project_ref_id'] = $project_ref_id;
		$variables[$j][$i]['project_title'] = $project_title;
		$variables[$j][$i]['estimated_project_cost'] = $estimated_project_cost;
		$variables[$j][$i]['amount'] = $amount;
		$variables[$j][$i]['months'] = $months[$i];
		}else{
		$variables[$j][$i]['project_ref_id'] = $project_ref_id;
		$variables[$j][$i]['project_title'] = $project_title;
		$variables[$j][$i]['estimated_project_cost'] = "0";
		$variables[$j][$i]['amount'] = "0";
		$variables[$j][$i]['months'] = $months[$i];
		}
		}
		
		}
		//print_r($monthsParticipants); exit;
		if($variables){
		$variables = call_user_func_array('array_merge', $variables);
        print_r(json_encode($variables)); //exit;
		}else{
		print_r(json_encode($variables));
		}
		
    }
    
    public function actionGetMonthsDataForParticipants(){
       
        $frommonth = isset($_REQUEST['frommonth'])?$_REQUEST['frommonth']:'';
        $tomonth = isset($_REQUEST['tomonth'])?$_REQUEST['tomonth']:'';
		$fromyear = isset($_REQUEST['fromyear'])?$_REQUEST['fromyear']:'';
        $toyear = isset($_REQUEST['toyear'])?$_REQUEST['toyear']:'';
        $selectedprj = isset($_REQUEST['prjid'])?$_REQUEST['prjid']:'';
		$checkValues = isset($_REQUEST['checkValues'])?$_REQUEST['checkValues']:'';
        $monthsParticipants = Projects::getMonthlyParticipatsBetweenMonths($frommonth,$tomonth,$fromyear,$toyear,$selectedprj,$checkValues);
        print_r(json_encode($monthsParticipants)); 
    }
    
    public function actionDisplaycomments() {

        $comments = (Yii::$app->getRequest()->getQueryParam('comments')) ? Yii::$app->getRequest()->getQueryParam('comments') : "";
        $projectId = (Yii::$app->getRequest()->getQueryParam('projectId')) ? Yii::$app->getRequest()->getQueryParam('projectId') : "";
        $userId = (Yii::$app->getRequest()->getQueryParam('userId')) ? Yii::$app->getRequest()->getQueryParam('userId') : "";
        $status = (Yii::$app->getRequest()->getQueryParam('status')) ? Yii::$app->getRequest()->getQueryParam('status') : "0";
        $projectCommentId = (Yii::$app->getRequest()->getQueryParam('projectCommentId')) ? Yii::$app->getRequest()->getQueryParam('projectCommentId') : "";

        if ($projectCommentId && $comments && $projectId && (empty($status) && empty($userId))) {
            $model = ProjectComments::find()->where(['project_comment_id' => $projectCommentId])->one();

            $model->comments = addslashes($comments);
            $model->save();
        } elseif ($projectCommentId && (empty($comments) && empty($status) && empty($projectId))) {
            //$model = ProjectComments::find()->where(['project_comment_id' => $projectCommentId])->delete();
            Yii::$app->db->createCommand()->delete('project_comments', ['project_comment_id' => $projectCommentId])->execute();
            return true;
        }

//        if ($status == '2')
//            $andWhere = " AND project_comments.user_ref_id = " . Yii::$app->user->id;
//        else
//            $andWhere = '';

        $query = new Query();
        $query->select(['project_comment_id', 'project_comments.project_ref_id', 'comments', 'DATE_FORMAT(`project_comments`.`created_date`, "%d %b %Y %I:%i %p") as created_date', 'project_comments.status', 'projects.project_title', 'status.status_name', 'user_profile.user_ref_id', 'user_profile.fname', 'user_profile.lname', 'user_profile.user_image'])
                ->from('project_comments')
                ->join('JOIN', 'user_profile', 'user_profile.user_ref_id = project_comments.user_ref_id')
                ->join('JOIN', 'status', 'status.status_id = project_comments.status')
                ->join('JOIN', 'user', 'user.id = user_profile.user_ref_id')
                ->join('JOIN', 'projects', 'projects.project_id = project_comments.project_ref_id')
                ->where("project_comments.status = " . $status)
             //   ->andWhere(' ("' . date('Y-m-d') . '" BETWEEN DATE_FORMAT(project_start_date, "%Y-%m-%d") AND DATE_FORMAT(project_end_date, "%Y-%m-%d"))')
                ->groupBy('project_comment_id')
                ->orderBy(['project_comments.created_date' => SORT_DESC]);

        $command = $query->createCommand();
        //print_r($command); //exit;
        $comments = $command->queryAll();
        $data = '';
        if(count($comments) > 0)
        {
            foreach ($comments as $comment) {

                $data .= '<div class="mt-comment" id="divComment_' . $comment['project_comment_id'] . '">
                    <div class="mt-comment-img">';
                if (!empty($comment['user_image']))
                    $userImageUrl = 'https://s3.ap-south-1.amazonaws.com/'. Yii::getAlias('@bucket') . '/uploads/profile_images/' . $comment['user_ref_id'] . '/' . $comment['user_image'];
                else
                    $userImageUrl = Yii::$app->urlManagerFrontend->baseUrl . '/images/avatar.png';
                $data .= '<img src="' . $userImageUrl . '" width="50"> </div>';
                $data .='<div class="mt-comment-body">
                        <div class="mt-comment-info">
                            <span class="mt-comment-author">' . $comment['project_title'] . '</span>
                            <span class="mt-comment-date">' . $comment['created_date'] . '</span>
                        </div>
                        <div class="mt-comment-text">
                            <div id="comment_display_'.$comment['project_comment_id'].'">'.stripslashes($comment['comments']).'</div>
                            <textarea class="txtComments" id="comment_'.$comment['project_comment_id'].'" style="display: none">'.stripslashes($comment['comments']).'</textarea>                            
                        </div>
                        <div class="mt-comment-details">
                            <span class="mt-comment-status mt-comment-status-pending">';
                $data .= ($status == '2') ? "PENDING" : (($status == '7') ? "APPROVED" : "REJECTED");
                $data .= '</span>';
                if ($status == '2') {
                    $data .= '<ul class="mt-comment-actions">
                                    <li>
                                        <a id="btnEdit_' . $comment['project_comment_id'] . '" class="btnCommentAction commentEdit_' . $comment['project_comment_id'] . '" onclick="javascript: modifyComment(\'' . $comment['project_comment_id'] . '\')">Edit</a>
                                        <a style="display:none" id="btnSave_' . $comment['project_comment_id'] . '" class="btnCommentAction editComment commentEdit_' . $comment['project_comment_id'] . '" onClick="editComment()">Save</a>
                                        <input type="hidden" name="projectId_' . $comment['project_comment_id'] . '" id="projectId_' . $comment['project_comment_id'] . '" value="' . $comment['project_comment_id'] . '" />
                                    </li>
                                    <li>
                                        <a href="#" id="btnView_' . $comment['project_comment_id'] . '" data-target="#viewComment" onclick="javascript: viewComment1(\'' . $userImageUrl . '\', \'' . $comment['project_title'] . '\', \'' . $comment['created_date'] . '\', \'' . $comment['comments'] . '\', \'' . $comment['status_name'] . '\')" data-toggle="modal">View</a>
                                    </li>
                                    <li>
                                        <a id="btnDelete_' . $comment['project_comment_id'] . '" class="btnCommentAction commentEdit_' . $comment['project_comment_id'] . '" onclick="javascript: deleteComment(\'' . $comment['project_comment_id'] . '\')">Delete</a>
                                    </li>
                                </ul>';
                }
                $data .= '</div>
                    </div>
                </div>';
            }
        } else {
            echo "<div align='center'>There are no comments to display</div>";
        }

        echo $data;
    }

    /*public function actionNotificationDashboard()
    {
//        return $this->render('dashboard');
        $asview = array();
        $dash_notifications = Yii::$app->db->createCommand('select * from communique c JOIN user_profile u ON(c.created_by=u.user_ref_id) where c.view_status=0')->queryAll();
        if (count($dash_notifications) > 0) {
            foreach ($dash_notifications as $asviewed) {
                $asview[] = $asviewed['communique_id'];
            }
            $asviewupdate = implode(',', $asview);
            //  @Yii::$app->db->createCommand("UPDATE Communique SET view_status=1 where communique_id IN($asviewupdate)")->execute();
            return $this->renderPartial('dashNotification', [
                        'dash_notifications' => $dash_notifications,
                    ]);
        }
    }
*/
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionInvesterList() {
		$mysqldateformat = Yii::getAlias('@mysqldateformat');
        $id = Yii::$app->request->post('pid');

        //  $this->layout = '/main2';
        $sql = 'SELECT `projects`.`project_id`, `project_title`, CONCAT(`fname`, " ", `lname`) as username, IF(participation_type="Support", "Kind", "Cash") AS participation_type, `investment_type`, `equity_type`, `amount`, `interest_rate`, DATE_FORMAT(`project_participation`.`created_date`, "'.$mysqldateformat.'") as created_date '
                . 'FROM `projects` LEFT JOIN project_participation ON project_participation.project_ref_id = projects.project_id '
                . 'JOIN user_profile ON user_profile.user_ref_id = project_participation.user_ref_id '
                . 'WHERE project_participation.project_ref_id = ' . $id;
        //echo $sql;
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
        ]);
        
        $projectUserDetails = Projects::getProjectCreatorDetails($id);

        //echo count($dataProvider);
        //echo $dataProvider->getCount();

        return $this->renderPartial('invester_list', [
            'dataProvider' => $dataProvider,
            'projectUserDetails' => $projectUserDetails,
        ]);
    }
    //for notifications
    
     public function actionNotificationDashboard() {
        $asview = array();
        $mysqldateformat = Yii::getAlias('@mysqldateformat');
        $dash_notifications = Yii::$app->db->createCommand("select c.*, u.fname, u.lname, u.user_image, DATE_FORMAT(c.created_date, '".$mysqldateformat." %h:%i %p') as created_date from communique c JOIN user_profile u ON(c.created_by=u.user_ref_id) where c.view_status=0 ORDER BY c.created_date DESC")->queryAll();
        if (count($dash_notifications) > 0) {
            foreach ($dash_notifications as $asviewed) {
                $asview[] = $asviewed['communique_id'];
            }
            $asviewupdate = implode(',', $asview);
            @Yii::$app->db->createCommand("UPDATE communique SET view_status=1 where communique_id IN($asviewupdate)")->execute();
            return $this->renderPartial('dashNotification', [
                        'dash_notifications' => $dash_notifications,
                    ]);
        }
    }

    public function actionLcNotification() {
	$mysqldateformat = Yii::getAlias('@mysqldateformat');
	
		/*
        $dash_notifications_comments = Yii::$app->db->createCommand("SELECT pc.read_status,pc.project_comment_id,u.user_image,u.fname,u.lname,DATE_FORMAT(pc.created_date, '".$mysqldateformat." %h:%i %p') as created_date,pc.comments,p.project_title,pc.user_ref_id FROM project_comments pc 
	  LEFT JOIN user_profile u USING(user_ref_id) 
	 LEFT JOIN projects p ON(p.project_id=pc.project_ref_id) 
	 JOIN `status`s ON(status_id=pc.status)
         WHERE s.status_name='Accept' ORDER BY pc.created_date DESC")->queryAll();
        $dash_notif_likes = Yii::$app->db->createCommand("SELECT pl.read_status,pl.project_likes_id,u.user_image,u.fname,u.lname,DATE_FORMAT(pl.created_date, '".$mysqldateformat." %h:%i %p') as created_date,p.project_title,pl.user_ref_id FROM project_likes pl LEFT JOIN user_profile u USING(user_ref_id) LEFT JOIN projects p ON(p.project_id=pl.project_ref_id) ORDER BY pl.created_date DESC")->queryAll();
		
		if (count($dash_notifications_comments) > 0) {
            $comment_array_list = array();
            foreach ($dash_notifications_comments as $notifications_comments) {
                if ($notifications_comments['read_status'] == 0) {
                    $comment_array_list[]= $notifications_comments['project_comment_id'];
                }
            }
            if (count($comment_array_list) > 0) {
                $comment_array_query = implode(',',$comment_array_list);
                @Yii::$app->db->createCommand("UPDATE project_comments SET read_status=1 where project_comment_id IN($comment_array_query)")->execute();
            }
         }
        if (count($dash_notif_likes) > 0) {
            $likes_array_list = array();
            foreach ($dash_notif_likes as $notif_likes) {
                if ($notif_likes['read_status'] == 0) {
                    $likes_array_list[] = $notif_likes['project_likes_id'];
                }
            }
            if (count($likes_array_list) > 0) {
                $likes_array_query = implode(',', $likes_array_list);

                @Yii::$app->db->createCommand("UPDATE project_likes SET read_status=1 where project_likes_id IN($likes_array_query)")->execute();
            }
        }
        $notification_lc_result = array_merge($dash_notifications_comments, $dash_notif_likes);
        usort($notification_lc_result, function($a, $b) {
                    return $a['created_date'] < $b['created_date'];
                });
		*/
		
		$dash_notifications = Yii::$app->db->createCommand("SELECT read_status, project_comment_id, project_likes_id, user_image, fname, lname, created_date, comments, project_title, user_ref_id FROM (
			SELECT pc.read_status, pc.project_comment_id, '' AS project_likes_id, u.user_image,u.fname,u.lname, pc.created_date,pc.comments,p.project_title,pc.user_ref_id 
			FROM project_comments pc 
			LEFT JOIN user_profile u USING(user_ref_id) 
			LEFT JOIN projects p ON(p.project_id=pc.project_ref_id) 
			JOIN `status`s ON (status_id=pc.status)
			WHERE s.status_name='Accept'
		UNION
			SELECT pl.read_status, '' AS project_comment_id, pl.project_likes_id, u.user_image,u.fname,u.lname, pl.created_date, '' AS comments,p.project_title,pl.user_ref_id 
			FROM project_likes pl LEFT JOIN user_profile u USING(user_ref_id) 
			LEFT JOIN projects p ON(p.project_id=pl.project_ref_id)
		) AS a ORDER BY created_date DESC;")->queryAll();
		
		if (count($dash_notifications) > 0) {
            $comment_array_list = $likes_array_list = array();            
            foreach ($dash_notifications as $notification) {
                if ($notification['read_status'] == 0 && !empty($notification['project_comment_id'])) {
                    $comment_array_list[] = $notification['project_comment_id'];
                } else if ($notification['read_status'] == 0 && !empty($notification['project_likes_id'])) {
                    $likes_array_list[] = $notification['project_likes_id'];
                }
            }
            if (count($comment_array_list) > 0) {
                $comment_array_query = implode(',', $comment_array_list);
                @Yii::$app->db->createCommand("UPDATE project_comments SET read_status=1 where project_comment_id IN($comment_array_query)")->execute();
            }
            if (count($likes_array_list) > 0) {
                $likes_array_query = implode(',', $likes_array_list);
                @Yii::$app->db->createCommand("UPDATE project_comments SET read_status=1 where project_comment_id IN($likes_array_query)")->execute();
            }
        }

        return $this->renderPartial('LcNotification', [
                    'result_array_notifications_lc' => $dash_notifications,
                ]);
    }
}
