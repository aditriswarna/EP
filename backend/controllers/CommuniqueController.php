<?php

namespace backend\controllers;
use yii\data\SqlDataProvider;
use common\models\Communique;
use common\models\EmailTemplates;
use yii\web\Controller;
use yii\filters\VerbFilter;
use Yii;

class CommuniqueController extends Controller
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
        $this->enableCsrfValidation = false;

        if (\Yii::$app->getUser()->isGuest){
            \Yii::$app->getResponse()->redirect(Yii::$app->request->BaseUrl . '/site/login');
        } else {
            return parent::beforeAction($action);
        }
    }
    
    public function actionIndex()
    {
        $this->layout = '/main';
        $model = new Communique();
        
        $QRY = "SELECT COUNT(*) fROM communique AS c "
                . "LEFT JOIN `user_profile` AS up ON up.user_ref_id = c.user_ref_id "
                . "WHERE c.user_ref_id=" . Yii::$app->user->identity->id;
        $COUNT = Yii::$app->db->createCommand($QRY)->queryScalar();
        
        $SQL = "SELECT c.*, if(c.project_ref_id IS NOT NULL, (select project_title from projects where project_id = c.project_ref_id), '') as projecttitle,  CONCAT(up.fname,' ',up.lname) as fullname from communique AS c "
                . "LEFT JOIN `user_profile` AS up ON up.user_ref_id = c.user_ref_id "
                . "WHERE c.user_ref_id=" . Yii::$app->user->identity->id;
        
        $dataProvider = new SqlDataProvider([
            'sql' => $SQL,
            'totalCount' => $COUNT,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
        return $this->render('index', [
                'dataProvider' => $dataProvider,
                'model' => $model
            ]);
    }
    
     protected function findModel($id) {
        if (($model = Communique::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    public function actionInboxMails()
    {  
	$mysqldateformat = Yii::getAlias('@mysqldateformat');
	$subject = (Yii::$app->getRequest()->getQueryParam('subject')) ? Yii::$app->getRequest()->getQueryParam('subject') : "";
	$user_ref = (Yii::$app->getRequest()->getQueryParam('user_ref')) ? Yii::$app->getRequest()->getQueryParam('user_ref') : "";
	$prj_ref = (Yii::$app->getRequest()->getQueryParam('prj_ref')) ? Yii::$app->getRequest()->getQueryParam('prj_ref') : "";
	$fromDate = (Yii::$app->getRequest()->getQueryParam('from_date')) ? Yii::$app->getRequest()->getQueryParam('from_date') : "";
	$toDate = (Yii::$app->getRequest()->getQueryParam('to_date')) ? Yii::$app->getRequest()->getQueryParam('to_date') : "";
	$mailstatus = (Yii::$app->getRequest()->getQueryParam('mailstatus')) ? Yii::$app->getRequest()->getQueryParam('mailstatus') : "";
	
	$where = '';
        if(  !empty($subject) || !empty($user_ref) || !empty($prj_ref) || !empty($fromDate) || !empty($toDate) || !empty($mailstatus) ) {
            $where .= (!empty($subject)) ? " AND c.subject LIKE '%".$subject."%'" : "";
            $where .= (!empty($user_ref)) ? " AND CONCAT(up.fname, ' ', up.lname) LIKE '%".$user_ref."%'" : "";
            $where .= (!empty($prj_ref)) ? " AND p.project_title LIKE '%".$prj_ref."%'" : "";
            $where .= (!empty($fromDate)) ? ' AND DATE_FORMAT(c.created_date, "%Y-%m-%d") >= "'.date('Y-m-d', strtotime($fromDate) ).'"' : "";
			$where .= (!empty($toDate)) ? ' AND DATE_FORMAT(c.created_date, "%Y-%m-%d") <= "'.date('Y-m-d', strtotime($toDate) ).'"' : "";
			if($mailstatus == 'Read' || $mailstatus == 'Unread'){
				$where .= (!empty($mailstatus)) ? ' AND c.status="'.$mailstatus.'"' : '';
			}
        }
        $model = new Communique();
        $this->layout = '/main';
        $SQL = "SELECT c.*, if(c.project_ref_id IS NOT NULL, (select project_title from projects where project_id = c.project_ref_id), '') as projecttitle,  CONCAT(up.fname,' ',up.lname) as fullname from communique AS c ";
 $QRY = "SELECT COUNT(*) fROM communique AS c "
                . "LEFT JOIN `user_profile` AS up ON up.user_ref_id = c.created_by "
				. "LEFT JOIN `projects` AS p ON p.project_id = c.project_ref_id "
                . "WHERE c.user_ref_id=" . Yii::$app->user->identity->id.$where;
        $COUNT = Yii::$app->db->createCommand($QRY)->queryScalar();
        
        $SQL = "SELECT c.*, DATE_FORMAT(c.created_date, '".$mysqldateformat." %h:%i %p') as createddate, if(c.project_ref_id IS NOT NULL, p.project_title, '') as projecttitle,  CONCAT(up.fname,' ',up.lname) as fullname FROM communique AS c "
                . "LEFT JOIN `user_profile` AS up ON up.user_ref_id = c.created_by "
				. "LEFT JOIN `projects` AS p ON p.project_id = c.project_ref_id "
                . "WHERE c.user_ref_id=" . Yii::$app->user->identity->id.$where." ORDER BY c.communique_id DESC, DATE_FORMAT(c.created_date, '%Y-%m-%d') DESC";
        $dataProvider = new SqlDataProvider([
            'sql' => $SQL,
            'totalCount' => $COUNT,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('inbox-mails', [
                    'dataProvider' => $dataProvider,
                    'count' => $COUNT,
					'model' => $model,
                ]);

    }
    
    public function actionMailView()
    {
         $this->layout = false;
        $mailid = isset($_POST['mailid']) ? $_POST['mailid'] : '';
		$mailtype = isset($_POST['mailtype']) ? $_POST['mailtype'] : '';
        $communique = Communique::find()->where(['communique_id' => $mailid])->one();
        if($mailtype == 'inbox'){
        $communique->status = 'Read';
		$communique->save();
		}
        
        $SQL = "SELECT c.*, CONCAT(up.fname,' ',up.lname) as fullname from communique AS c "
                . "LEFT JOIN `user_profile` AS up ON up.user_ref_id = c.user_ref_id "
                . "WHERE c.communique_id=" . $mailid;
        $mail = Yii::$app->db->createCommand($SQL)->queryAll();
        $maildata = array_shift($mail);
        return $this->renderPartial('mail-view', [
                'maildata' => $maildata
            ]);
    }
    
    public function actionSentMails()
    {
	$mysqldateformat = Yii::getAlias('@mysqldateformat');
        $model = new Communique();$this->layout = '/main';
        $subject = (Yii::$app->getRequest()->getQueryParam('subject')) ? Yii::$app->getRequest()->getQueryParam('subject') : "";
	$user_ref = (Yii::$app->getRequest()->getQueryParam('user_ref')) ? Yii::$app->getRequest()->getQueryParam('user_ref') : "";
	$prj_ref = (Yii::$app->getRequest()->getQueryParam('prj_ref')) ? Yii::$app->getRequest()->getQueryParam('prj_ref') : "";
	$fromDate = (Yii::$app->getRequest()->getQueryParam('from_date')) ? Yii::$app->getRequest()->getQueryParam('from_date') : "";
	$toDate = (Yii::$app->getRequest()->getQueryParam('to_date')) ? Yii::$app->getRequest()->getQueryParam('to_date') : "";
	$where = '';
	
	if(  !empty($subject) || !empty($user_ref) || !empty($prj_ref) || !empty($fromDate) || !empty($toDate) ) {
            $where .= (!empty($subject)) ? " AND c.subject LIKE '%".$subject."%'" : "";
            $where .= (!empty($user_ref)) ? " AND CONCAT(up.fname, ' ', up.lname) LIKE '%".$user_ref."%'" : "";
            $where .= (!empty($prj_ref)) ? " AND p.project_title LIKE '%".$prj_ref."%'" : "";
            $where .= (!empty($fromDate)) ? ' AND DATE_FORMAT(c.created_date, "%Y-%m-%d") >= "'.date('Y-m-d', strtotime($fromDate) ).'"' : "";
			$where .= (!empty($toDate)) ? ' AND DATE_FORMAT(c.created_date, "%Y-%m-%d") <= "'.date('Y-m-d', strtotime($toDate) ).'"' : "";
        }
        $QRY = "SELECT COUNT(*) fROM communique AS c "
                . "LEFT JOIN `user_profile` AS up ON up.user_ref_id = c.user_ref_id "
				. "LEFT JOIN `projects` AS p ON p.project_id = c.project_ref_id "
                . "WHERE c.created_by=" . Yii::$app->user->identity->id.$where;
        $COUNT = Yii::$app->db->createCommand($QRY)->queryScalar();
        
$SQL = "SELECT c.*, DATE_FORMAT(c.created_date, '".$mysqldateformat." %h:%i %p') as created_date, if(c.project_ref_id IS NOT NULL, p.project_title, '') as projecttitle,  CONCAT(up.fname,' ',up.lname) as fullname from communique AS c "
                . "LEFT JOIN `user_profile` AS up ON up.user_ref_id = c.user_ref_id "
				. "LEFT JOIN `projects` AS p ON p.project_id = c.project_ref_id "
                . "WHERE c.created_by=" . Yii::$app->user->identity->id.$where." ORDER BY c.communique_id DESC, DATE_FORMAT(c.created_date, '%Y-%m-%d') DESC";
        
        $dataProvider = new SqlDataProvider([
            'sql' => $SQL,
            'totalCount' => $COUNT,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
      // print_r($dataProvider); exit;
        return $this->render('sent-mails', [
                    'dataProvider' => $dataProvider,
                'model' => $model,
                'count' => $COUNT
                ]);

    }
    
    public function actionNewMessage()
    {
        $this->layout = '/main';
        $this->view->title = 'New message';
        $communique = new Communique();
        $sql = "SELECT CONCAT(fname, ' ', lname, ' , ', user_type, ' , ', email) as value, id as id  FROM `user`  u 
                LEFT JOIN project_co_owners pc ON pc.user_ref_id = u.id
                JOIN user_profile up ON u.id = up.user_ref_id
                JOIN user_type ut ON u.user_type_ref_id = ut.user_type_id 
                GROUP BY u.id ORDER BY email";
        
        $users = Yii::$app->db->createCommand($sql)->queryAll();
        
        $logged_user_data = \frontend\models\UserProfile::find()->where('user_ref_id = :id', [':id'=>Yii::$app->user->identity->id])->one();
       
        $logged_user_name = (isset($logged_user_data->fname) && $logged_user_data->fname != '')?$logged_user_data->fname.' '.$logged_user_data->lname :'';
        
        
        if ($communique->load(Yii::$app->request->post())) {            
            $exmail = $_POST['Communique']['existing_email']; 
            $newmail = $_POST['Communique']['new_email']; 
            // $projects = $_POST['Communique']['projects']; 
            
            $nmails = explode(",",$newmail);
            
            if(isset($_POST['Communique']['to_email'])) $communique->to_email = $_POST['Communique']['to_email'];
            if(isset($_POST['Communique']['subject'])) $communique->subject = $_POST['Communique']['subject'];
            if(isset($_POST['Communique']['message'])) $communique->message = $_POST['Communique']['message'];

            if(isset($exmail[0]) && $exmail[0] != ''){                
            foreach ($exmail as $uid) {
               // foreach ($projects as $project) {

                  //  $projectdata = \frontend\models\Projects::getProjectCreatorDetails($project);
                    $userdata = \frontend\models\User::getUserDetails($uid);

                    $emailTemplate = EmailTemplates::getEmailTemplate(60);
                    $header = $emailTemplate[0]['descrition'];
                    $msg = $emailTemplate[2]['descrition'];
                    $footer = $emailTemplate[1]['descrition'];  
                    $username = $userdata[0]['fname'].' '.$userdata[0]['lname'];

                    $msg = str_replace(array('[content]','[username]','[name]'), array($_POST['Communique']['message'],$username,$logged_user_name), $msg);
                    $body = $header. $msg. $footer;
                    /* $msg = '<tr>
                            <td bgcolor="#FFFFFF">
                            <br />
                            <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" >
                          <tr>
                            <td style="font-family:Arial, Geneva, sans-serif; font-size:14px; color:#45482f; line-height:20px">'.$_POST['Communique']['message'].'</td>
                          </tr>
                        </table>
                            <br /></td>
                          </tr> '; 
                     $body=$emailtemplate[0]['descrition'].$msg.$emailtemplate[1]['descrition']; */
                    
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($userdata[0]['email'])
                    ->setSubject($_POST['Communique']['subject'])
                    ->setHtmlBody($body)
                    ->send();
                    Communique::saveMailData('', $userdata[0]['id'], $_POST['Communique']['subject'], $body, $userdata[0]['email'], 'Unread', Yii::$app->user->identity->id);                                       
               // }
            }
            }            
           
            if(isset($nmails[0]) && $nmails[0] != ''){                 
            foreach ($nmails as $usermail) {                
                // foreach ($projects as $project) {
                    $userData = \common\models\User::find()->select('id')->where(['email'=>$usermail])->one();
                    $user_ref_id = !empty($userData->id)?$userData->id:'';
                    // $projectdata = \frontend\models\Projects::getProjectCreatorDetails($project);

                    $emailTemplate = EmailTemplates::getEmailTemplate(60);
                    $header = $emailTemplate[0]['descrition'];
                    $msg = $emailTemplate[2]['descrition'];
                    $footer = $emailTemplate[1]['descrition']; 
                    
                    $username = substr($usermail, 0, strpos($usermail, "@"));                    

                    $msg = str_replace(array('[content]','[username]','[name]'), array($_POST['Communique']['message'],$username,$logged_user_name), $msg);                    
                    $body = $header. $msg. $footer;
                    
                    /*$msg = '<tr>
                            <td bgcolor="#FFFFFF">
                            <br />
                            <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" >
                          <tr>
                            <td style="font-family:Arial, Geneva, sans-serif; font-size:14px; color:#45482f; line-height:20px">'.$_POST['Communique']['message'].'</td>
                          </tr>
                        </table>
                            <br /></td>
                          </tr> ';
                     $body=$emailtemplate[0]['descrition'].$msg.$emailtemplate[1]['descrition']; */
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($usermail)
                    ->setSubject($_POST['Communique']['subject'])
                    ->setHtmlBody($body)
                    ->send();
                    Communique::saveMailData('', $user_ref_id, $_POST['Communique']['subject'], $body, $usermail, 'Unread', Yii::$app->user->identity->id);                                       
               // }
            }
            }
            return $this->redirect(['sent-mails']);
        }
        return $this->render('new-message',[
                'model' => $communique,
                'users' => $users]);
    }
    
    

}
