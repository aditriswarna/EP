<?php

namespace backend\controllers;

use Yii;
use backend\models\User;
use backend\models\UserType;
use backend\models\CreateUserForm;
use backend\models\UserStatus;
use common\models\ResetProfilePasswordForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Location;
use yii\helpers\ArrayHelper;
use zyx\phpmailer\Mailer;
use common\models\EmailTemplates;
use common\models\Communique;
use frontend\models\SignupForm;
use frontend\models\UserProfile;
use common\models\MediaAgencies;
use common\models\Subscription;
/**
 * AdminController implements the CRUD actions for User model.
 */
class AdminController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->joinWith(['adminLocations']),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderPartial('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create';
        
        $items = ArrayHelper::map(Location::find()->all(), 'location_id', 'location_name');
        $types = ArrayHelper::map(UserType::find()->where('user_type_id in (3, 5)')->all(),'user_type_id','user_type');
        $randomPwd = $this->random_string(10);
        
        if ($model->load(Yii::$app->request->post()) && $user = $model->signup($randomPwd)) {             
                /* insert record in admin_assigned_user_types table*/ 
                if(isset($_POST['User']['user_type_ref_id']) && !empty($_POST['User']['user_type_ref_id'])){
                    foreach($_POST['User']['user_type_ref_id'] as $val){

                        $assigned_user_types = new \backend\models\AdminAssignedUserTypes();
                        $assigned_user_types->user_ref_id = $user->id;
                        $assigned_user_types->user_type_ref_id = $val;
                        $assigned_user_types->save(false);
                    }
                }
                
                $emailtemplate1 = EmailTemplates::getEmailTemplate(7);
                $body1=str_replace("{username}", ucwords($model->username), $emailtemplate1[2]['descrition']);
                $body1=str_replace("{randompassword}", $randomPwd, $body1);
                $body1=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body1);
                $body1=$emailtemplate1[0]['descrition'].$body1.$emailtemplate1[1]['descrition'];
                
                $message = Yii::$app->mailer->compose();
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo($model->email)
                    ->setSubject($emailtemplate1[2]['subject'])
                    ->setHtmlBody($body1)
                    ->send();
                
                Communique::saveMailData('', $user->id, $emailtemplate1[2]['subject'], $body1, $model->email, 'Unread', Yii::$app->user->identity->id);
                
                $emailtemplate2 = EmailTemplates::getEmailTemplate(29);
                $body2=str_replace("{username}", ucwords($model->username), $emailtemplate2[2]['descrition']);
                $body2=str_replace("{email}", $model->email, $body2);
                $body2=$emailtemplate2[0]['descrition'].$body2.$emailtemplate2[1]['descrition'];
                $message = Yii::$app->mailer->compose();
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo('equippp.noreply@gmail.com')
                    ->setSubject($emailtemplate2[2]['subject'])
                    ->setHtmlBody($body2)
                    ->send();
                Communique::saveMailData('', 1, $emailtemplate2[2]['subject'], $body2, 'equippp.noreply@gmail.com', 'Unread', Yii::$app->user->identity->id);                                            
                /* Yii::$app->mailer->compose('adminUserSignup', 
                    [
                    'user'=> $model,
                    'randomPwd'=>$randomPwd,
                    'title'      => Yii::t('app', 'Password reset'),
                    'htmlLayout' => 'layouts/html'
                    ])
                ->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                ->setTo($model->email)
    //            ->setSubject(Yii::t('app', 'Password reset for ') . Yii::$app->params['supportEmail'])
                ->setSubject('Admin user Activation')
                ->send(); */ 

                return $this->redirect(['admin_list']);
               // return $this->redirect(\Yii::$app->urlManager->createUrl("admin/index"));          
        } else {
            return $this->render('create', [
                'model' => $model,
                'items' => $items,
                'types' => $types,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        
        $items = ArrayHelper::map(Location::find()->all(), 'location_id', 'location_name');
        $types = ArrayHelper::map(UserType::find()->where('user_type_id in (3, 5)')->all(),'user_type_id','user_type');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $user_type_model = \backend\models\AdminAssignedUserTypes::deleteAll('user_ref_id = :id' , [':id' => $id]);
            
            if(isset($_POST['User']['user_type_ref_id']) && !empty($_POST['User']['user_type_ref_id'])){
                    foreach($_POST['User']['user_type_ref_id'] as $val){

                        $assigned_user_types = new \backend\models\AdminAssignedUserTypes();
                        $assigned_user_types->user_ref_id = $id;
                        $assigned_user_types->user_type_ref_id = $val;
                        $assigned_user_types->save(false);
                    }
            }     
           
           /* $locationModelData = (\app\models\AdminLocation::find()->where(['user_ref_id' => $id])->one())?\app\models\AdminLocation::find()->where(['user_ref_id' => $id])->one(): new \app\models\AdminLocation();
         
            $location = $locationModelData;
            $location->location_ref_id = $_POST['User']['location']; 
            $location->user_ref_id = $id;
            $location->save(); */
            
            
            
            return $this->redirect(['admin_list']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'items' => $items,
                'types' => $types,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionCreateUser()
    {
        $model = new CreateUserForm();
        $usertypemodel = new UserType();
        $signup = new SignupForm();
        $profile = new UserProfile();
        $mediatypemodel = new MediaAgencies();
        $countries = \common\models\User::countrieslist();
        if(Yii::$app->request->post()){
        $signup->email = $email = $_POST['CreateUserForm']['email'];
        $signup->password = $password = $_POST['CreateUserForm']['password'];
        $signup->user_type_ref_id = $user_type_ref_id = $_POST['CreateUserForm']['user_type_ref_id'];
        $signup->media_agency_ref_id = $_POST['CreateUserForm']['media_agency_ref_id'];
        $profile->fname = $fname = $_POST['CreateUserForm']['fname'];
        $profile->lname = $lname = $_POST['CreateUserForm']['lname'];
        $profile->mobile = $mobile = $_POST['CreateUserForm']['mobile'];
        $profile->gender = $gender = $_POST['CreateUserForm']['gender'];
         if(isset($_POST['CreateUserForm']['dob'])){
            $date = explode("-",$_POST['CreateUserForm']['dob']);
            $dateofbirth = $date[2].'-'.$date[1].'-'.$date[0];
            }
        $profile->dob = $dateofbirth ;    
        $profile->citizen = $gender = $_POST['CreateUserForm']['citizen'];
        $profile->domicile = $gender = $_POST['CreateUserForm']['domicile'];
        $profile->current_location = $gender = $_POST['CreateUserForm']['current_location'];
        
        
       // print_r($signup); exit;
        
        if ($user = $signup->signup()) {
            //echo Yii::$app->db->getLastInsertID(); exit;
            $userstatus = User::find()->where(['id' => Yii::$app->db->getLastInsertID()])->one();
                $userstatus->status = 1;
                $userstatus->email_confirmed = 1;
                $userstatus->is_profile_set = 1;
               
                $userstatus->save();
                $user_id_email_log = $user->id;
                $profile->user_ref_id = $user->id;
                $profile->save();
                $usermodel = User::find()->where(['email' => $email])->one();
                $usertype = UserType::find()->where(['user_type_id' => $user_type_ref_id])->one();
                $adminUsers =  Yii::$app->db->createCommand("SELECT * FROM `user` AS u 
                    LEFT JOIN admin_assigned_user_types AS au ON au.user_ref_id=u.id
                    WHERE user_role_ref_id=1 AND u.status=1 AND au.user_type_ref_id=".$user_type_ref_id)->queryAll();
                // Mail to admin
                $emailtemplate1 = EmailTemplates::getEmailTemplate(3);
                $body = str_replace("{email}", $_POST['CreateUserForm']['email'], $emailtemplate1[2]['descrition']);
                $body = str_replace("{usertype}", ucwords($usertype->user_type), $body);
                $body = $emailtemplate1[0]['descrition'] . $body . $emailtemplate1[1]['descrition'];
                $message = Yii::$app->mailer->compose();
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo('equippp.noreply@gmail.com')
                        ->setSubject($emailtemplate1[2]['subject'])
                        ->setHtmlBody($body)
                        ->send();
                Communique::saveMailData('', 1, $emailtemplate1[2]['subject'], $body, 'equippp.noreply@gmail.com', 'Unread', 1);
                // Mail to user
                $emailtemplate2 = EmailTemplates::getEmailTemplate(56);
                $body = str_replace("{email}", $_POST['CreateUserForm']['email'], $emailtemplate2[2]['descrition']);
                $body = str_replace("{password}",$_POST['CreateUserForm']['password'], $body);
                $body = $emailtemplate2[0]['descrition'] . $body . $emailtemplate2[1]['descrition'];
                $message = Yii::$app->mailer->compose();
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo($_POST['CreateUserForm']['email'])
                        ->setSubject($emailtemplate2[2]['subject'])
                        ->setHtmlBody($body)
                        ->send();
                Communique::saveMailData('', $userstatus->id, $emailtemplate2[2]['subject'], $body, $_POST['CreateUserForm']['email'], 'Unread', 1);
                if ($adminUsers) {
                foreach ($adminUsers as $admins) {
                $emailtemplate3 = EmailTemplates::getEmailTemplate(43);
                $body = str_replace("{auth_key}", $usermodel->auth_key, $emailtemplate3[2]['descrition']);
                $body = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body);
                $body = str_replace("{email}", $_POST['CreateUserForm']['email'], $body);
                $body = str_replace("{usertype}", ucwords($usertype->user_type), $body);
                $body = $emailtemplate3[0]['descrition'] . $body . $emailtemplate3[1]['descrition'];
                $message = Yii::$app->mailer->compose();
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo($admins['email'])
                        ->setSubject($emailtemplate3[2]['subject'])
                        ->setHtmlBody($body)
                        ->send();
                Communique::saveMailData('', $admins['id'], $emailtemplate3[2]['subject'], $body, $admins['email'], 'Unread', 1);
                }
                
                }
            
        }
        return $this->redirect(['user_list']);
        }
        return $this->render('create-user',[
            'model'=>$model,
            'usertypemodel'=>$usertypemodel,
            'countries'=>$countries,
            'mediatypemodel' => $mediatypemodel
                ]);
        
    }
    
    public  function actionCheckexistinguser(){
        
        $sql = 'SELECT count(*) FROM user WHERE email ='.'"'.$_POST['email'].'"';
        $count = yii::$app->db->createCommand($sql)->queryScalar();
        if($count>0){
            echo "true";
        }else{
            echo "false";
        }
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
   
    public function actionAdmin_list(){
        $model = new User();
        $cond = '';
        
//        $id = Yii::$app->getRequest()->getQueryParam('id');
//        if(isset($id) && $id != '' && $id > 0){
//            $cond = " AND u.user_type_ref_id = ".$id;   
//            $usertype = $this->getUserType($id);
//        }
        
        $email = Yii::$app->getRequest()->getQueryParam('email') ? Yii::$app->getRequest()->getQueryParam('email') : "";
        $fromDate = Yii::$app->getRequest()->getQueryParam('from') ? Yii::$app->getRequest()->getQueryParam('from') : "";
        $toDate = Yii::$app->getRequest()->getQueryParam('to') ? Yii::$app->getRequest()->getQueryParam('to') : "";
        $userType = Yii::$app->getRequest()->getQueryParam('type') ? Yii::$app->getRequest()->getQueryParam('type') : "";
        $userStatus = Yii::$app->getRequest()->getQueryParam('status') ? Yii::$app->getRequest()->getQueryParam('status') : "";
        $mediaAgency = Yii::$app->getRequest()->getQueryParam('mediaAgency') ? Yii::$app->getRequest()->getQueryParam('mediaAgency') : "";
        $where = '';
		
        if(!empty($email) || !empty($fromDate) || !empty($toDate) || !empty($userType) || !empty($userStatus) || !empty($mediaAgency)) {
            $where .= (!empty($email)) ? " AND (u.email LIKE '".trim($email)."%' OR u.username LIKE '".trim($email)."%')" : "";
           $where .= (!empty($fromDate)) ? " AND DATE_FORMAT(FROM_UNIXTIME(u.created_at), '%Y-%m-%d') >= '" . date('Y-m-d', strtotime($fromDate)) . "'" : "";
            $where .= (!empty($toDate)) ? " AND DATE_FORMAT(FROM_UNIXTIME(u.created_at), '%Y-%m-%d') <= '" . date('Y-m-d', strtotime($toDate) ) . "'" : "";
            $where .= (!empty($mediaAgency)) ? " AND u.media_agency_ref_id = ".$mediaAgency : "";
            
//            if(!empty($fromDate)) {
//                $tempArr=explode('-', $fromDate);
//                $fromDate = mktime(0, 0, 0, $tempArr[0], $tempArr[1], $tempArr[2]);
//                $cond .= (!empty($fromDate)) ? " AND u.created_at >= '".$fromDate."'" : "";
//            }
//            if(!empty($toDate)) {
//                $tempArr=explode('-', $toDate);
//                $toDate = mktime(23, 59, 59, $tempArr[0], $tempArr[1], $tempArr[2]);
//                $cond .= (!empty($toDate)) ? " AND u.created_at <= '".$toDate."'" : "";
//            }
            //$cond .= (!empty($fromDate)) ? " AND u.created_at >= '".strtotime($fromDate)."'" : "";
            //$cond .= (!empty($toDate)) ? " AND u.created_at <= '".strtotime($toDate)."'" : "";
//            if(empty(trim($id)))
                $where .= (!empty($userType)) ? " AND aut.user_type_ref_id = ".$userType : "";
            $where .= (!empty($userStatus)) ? " AND u.status = '".$userStatus."'" : "";
        } else {
            $where .= " AND aut.user_type_ref_id IN (3, 5) ";
        }
        
        $query = "SELECT u.id, u.username, u.email, u.status, us.status_name, u.created_at FROM user u "
                //. "LEFT JOIN admin_location a ON a.user_ref_id = u.id "
                //. "LEFT JOIN location l ON l.location_id = a.location_ref_id "
                . "JOIN user_status us ON us.user_status_id = u.status "
                //. "LEFT JOIN media_agencies ON media_agencies.media_agency_id = u.media_agency_ref_id "
                . "JOIN admin_assigned_user_types aut ON aut.user_ref_id = u.id "
                . "WHERE user_role_ref_id = 1 AND superadmin=0 ".$where." ORDER BY u.created_at DESC";
        
        $count = Yii::$app->db->createCommand("SELECT COUNT(id) FROM user u JOIN user_status us ON us.user_status_id = u.status JOIN admin_assigned_user_types aut ON aut.user_ref_id = u.id 
WHERE user_role_ref_id = 1 AND superadmin=0 ".$where)->queryScalar();
        
        $allUserTypes = ArrayHelper::map(UserType::find()->where('user_type_id in (3, 5)')->all(), 'user_type_id', 'user_type');
        $userStatus = ArrayHelper::map(UserStatus::find()->all(), 'user_status_id', 'status_name');
        $mediaAgencies = ArrayHelper::map(MediaAgencies::find()->all(), 'media_agency_id', 'media_agency_name');
    
        return $this->render('_adminGrid',[
            'model'=>$model,
            'query'=>$query,
            'count'=>$count,
            'usertype'=>@$usertype,
            'allUserTypes' => $allUserTypes,
            'userStatus' => $userStatus,
            'mediaAgencies' => $mediaAgencies,
        ]);
        //return $this->render('_adminGrid',['query'=>$query,'count'=>$count]);
    }
    
    public function actionUser_list(){
        $model = new User();
        $cond = $where = '';
        
        $id = Yii::$app->getRequest()->getQueryParam('id')?trim(Yii::$app->getRequest()->getQueryParam('id')):'';
        if(isset($id) && $id != '' && $id > 0){
            $where = " AND u.user_type_ref_id = ".$id;   
            $usertype = $this->getUserType($id);
        }
        
        $email = Yii::$app->getRequest()->getQueryParam('email') ? Yii::$app->getRequest()->getQueryParam('email') : "";
        $fromDate = Yii::$app->getRequest()->getQueryParam('from') ? Yii::$app->getRequest()->getQueryParam('from') : "";
        $toDate = Yii::$app->getRequest()->getQueryParam('to') ? Yii::$app->getRequest()->getQueryParam('to') : "";
        $userType = Yii::$app->getRequest()->getQueryParam('type') ? Yii::$app->getRequest()->getQueryParam('type') : "";
        $userStatus = Yii::$app->getRequest()->getQueryParam('status') ? Yii::$app->getRequest()->getQueryParam('status') : "";
        $mediaAgency = Yii::$app->getRequest()->getQueryParam('mediaAgency') ? Yii::$app->getRequest()->getQueryParam('mediaAgency') : "";
        if(!empty($id) || !empty($email) || !empty($fromDate) || !empty($toDate) || !empty($userType) || !empty($userStatus) || !empty($mediaAgency)) {
            $where .= (!empty($email)) ? " AND (u.email LIKE '".$email."%' OR CONCAT(up.fname, ' ', up.lname) LIKE '".$email."%')" : "";
            $where .= (!empty($fromDate)) ? " AND DATE_FORMAT(FROM_UNIXTIME(u.created_at), '%Y-%m-%d') >= '" . date('Y-m-d', strtotime($fromDate) ) . "'" : "";
            $where .= (!empty($toDate)) ? " AND DATE_FORMAT(FROM_UNIXTIME(u.created_at), '%Y-%m-%d') <= '" . date('Y-m-d', strtotime($toDate) ) . "'" : "";
            if(empty($id))
                $where .= (!empty($userType)) ? " AND u.user_type_ref_id = ".$userType : "";
            $where .= (!empty($userStatus)) ? " AND u.status = '".$userStatus."'" : "";
            $where .= (!empty($mediaAgency)) ? " AND u.media_agency_ref_id = ".$mediaAgency : "";
        }
        
        $query = "SELECT u.id, u.username, u.email, u.status, us.status_name, u.email_confirmed, u.media_agency_ref_id, u.created_at, up.fname, up.lname, up.gender, up.current_location, ut.user_type, ma.media_agency_name  FROM user u "
                . "LEFT JOIN user_profile up ON up.user_ref_id = u.id "
                // . "LEFT JOIN user_profile_by_usertype upt ON upt.user_ref_id = u.id "
                . "JOIN user_type ut ON ut.user_type_id = u.user_type_ref_id "
                . "JOIN status us ON us.status_id = u.status "
                . "LEFT JOIN media_agencies ma ON ma.media_agency_id = u.media_agency_ref_id "
                . "WHERE user_role_ref_id != 1 AND superadmin=0 ".$where;
        
        $sort = Yii::$app->getRequest()->getQueryParam('sort') ? Yii::$app->getRequest()->getQueryParam('sort') : "";
        if (empty($sort))
            $query .= ' ORDER BY u.created_at DESC';
//        echo $query; //return false;
        
        $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM user u 
                    LEFT JOIN user_profile up ON up.user_ref_id = u.id
                    JOIN user_type ut ON ut.user_type_id = u.user_type_ref_id
                    JOIN status us ON us.status_id = u.status
                    LEFT JOIN media_agencies ma ON ma.media_agency_id = u.media_agency_ref_id
                    WHERE user_role_ref_id != 1 AND superadmin=0 '.$where)->queryScalar();
        
        $allUserTypes = ArrayHelper::map(UserType::find()->where('user_type_id in (3, 5)')->all(), 'user_type_id', 'user_type');
        $userStatus = ArrayHelper::map(UserStatus::find()->all(), 'user_status_id', 'status_name');
        $mediaAgencies = ArrayHelper::map(MediaAgencies::find()->all(), 'media_agency_id', 'media_agency_name');
        return $this->render('_userGrid',[
            'model'=>$model,
            'query'=>$query,
            'count'=>$count,
            'usertype'=>@$usertype,
            'allUserTypes' => $allUserTypes,
            'userStatus' => $userStatus,
            'mediaAgencies' => $mediaAgencies
        ]);
    }
    public function actionChangestatus(){
        
        $id= Yii::$app->getRequest()->getQueryParam('id');
        $status = Yii::$app->getRequest()->getQueryParam('status');
        $email = Yii::$app->getRequest()->getQueryParam('email');
        $userdata = \frontend\models\User::getUserDetails($id);
        $query = '';
        //$adminUsers = User::find()->where(['user_type_ref_id' => $userdata[0]['user_type_ref_id'], 'user_role_ref_id' => 1])->all();
        if($userdata[0]['user_type_ref_id'] != ''){
            $adminUsers =  Yii::$app->db->createCommand("SELECT * FROM `user` AS u 
            LEFT JOIN admin_assigned_user_types AS au ON au.user_ref_id=u.id
            WHERE user_role_ref_id=1 AND u.status=1 AND au.user_type_ref_id=".$userdata[0]['user_type_ref_id'])->queryAll(); 
        }
        if($status == 2){
            $query = "update user set status=1 where id=".$id;
        }else if($status == 1){
            $query = "update user set status=2 where id=".$id;
            
            $emailtemplate1 = EmailTemplates::getEmailTemplate(57);
                $body=str_replace("{username}", ucwords($userdata[0]['fname'].' '.$userdata[0]['lname']), $emailtemplate1[2]['descrition']);
              //  $body=str_replace("{randompassword}", $randomPwd, $body);
                $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                $body=$emailtemplate1[0]['descrition'].$body.$emailtemplate1[1]['descrition'];
                $message = Yii::$app->mailer->compose();
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo($email)
                    ->setSubject($emailtemplate1[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
                Communique::saveMailData('', $id, $emailtemplate1[2]['subject'], $body, $email, 'Unread', Yii::$app->user->identity->id);
                
                $emailtemplate2 = EmailTemplates::getEmailTemplate(58);
                
                $body=str_replace("{useremail}", Yii::$app->getRequest()->getQueryParam('email'), $emailtemplate2[2]['descrition']);
                $body=$emailtemplate2[0]['descrition'].$body.$emailtemplate2[1]['descrition'];
                $message = Yii::$app->mailer->compose();
                
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo('equippp.noreply@gmail.com')
                    ->setSubject($emailtemplate2[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
                Communique::saveMailData('', 1, $emailtemplate2[2]['subject'], $body, 'equippp.noreply@gmail.com', 'Unread', 1);
                
                if (isset($adminUsers) && !empty($adminUsers)) {
                    foreach ($adminUsers as $admins) {
                    $emailtemplate3 = EmailTemplates::getEmailTemplate(59);
                    $body=str_replace("{useremail}", $email, $emailtemplate3[2]['descrition']);
                    $body=$emailtemplate3[0]['descrition'].$body.$emailtemplate3[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                            ->setTo($admins['email'])
                        ->setSubject($emailtemplate3[2]['subject'])
                        ->setHtmlBody($body)
                        ->send();
                    Communique::saveMailData('', $admins['id'], $emailtemplate3[2]['subject'], $body, $admins['email'], 'Unread', 1);
                    }
                } 
            
        }
        $result = Yii::$app->db->createCommand($query)->execute();
        if($result && $status == 2){
                
                $emailtemplate1 = EmailTemplates::getEmailTemplate(8);
                $body=str_replace("{username}", ucwords($userdata[0]['fname'].' '.$userdata[0]['lname']), $emailtemplate1[2]['descrition']);
               // $body=str_replace("{randompassword}", $randomPwd, $body);
                $body=str_replace("{site_url}", SITE_URL. yii::getAlias('@web').'/', $body);
                $body=$emailtemplate1[0]['descrition'].$body.$emailtemplate1[1]['descrition'];
                $message = Yii::$app->mailer->compose();
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo($email)
                    ->setSubject($emailtemplate1[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
                Communique::saveMailData('', $id, $emailtemplate1[2]['subject'], $body, $email, 'Unread', Yii::$app->user->identity->id);
                
                $emailtemplate2 = EmailTemplates::getEmailTemplate(44);
                
                $body=str_replace("{useremail}", Yii::$app->getRequest()->getQueryParam('email'), $emailtemplate2[2]['descrition']);
                $body=$emailtemplate2[0]['descrition'].$body.$emailtemplate2[1]['descrition'];
                $message = Yii::$app->mailer->compose();
                
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo('equippp.noreply@gmail.com')
                    ->setSubject($emailtemplate2[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
                Communique::saveMailData('', 1, $emailtemplate2[2]['subject'], $body, 'equippp.noreply@gmail.com', 'Unread', 1);
                
                if (isset($adminUsers) && !empty($adminUsers)) {
                    foreach ($adminUsers as $admins) {
                    $emailtemplate3 = EmailTemplates::getEmailTemplate(45);
                    $body=str_replace("{useremail}", $email, $emailtemplate3[2]['descrition']);
                    $body=$emailtemplate3[0]['descrition'].$body.$emailtemplate3[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                            ->setTo($admins['email'])
                        ->setSubject($emailtemplate3[2]['subject'])
                        ->setHtmlBody($body)
                        ->send();
                    Communique::saveMailData('', $admins['id'], $emailtemplate3[2]['subject'], $body, $admins['email'], 'Unread', 1);
                    }
                } 
        }
        return true;
    }
    
    public function random_string($length) 
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
                $key .= $keys[array_rand($keys)];
        }

        return $key;
    }
        
    public static function getUserDetails($id){
        $sql = "SELECT * FROM user u LEFT JOIN user_profile up ON up.user_ref_id = u.id "
                . "LEFT JOIN user_profile_by_usertype upt ON upt.user_ref_id = u.id "
                . "LEFT JOIN user_type ut ON ut.user_type_id = u.user_type_ref_id "
                . "WHERE u.id = ".$id;
        $data = yii::$app->db->createCommand($sql)->queryOne();  
        
        return $data;
    }
    
    public static function getUserTypes(){
        
        $data = yii::$app->db->createCommand("SELECT * FROM user_type where user_type_id in (3, 5);")->queryAll();  
        
        return $data;
    }
    
    public static function getUserType($id){
        
        $data = yii::$app->db->createCommand("SELECT user_type FROM user_type where user_type_id =".$id)->queryAll();  
        
        return $data;
    }
    
    public function actionSubscribedUsers(){      
        
        $dataProvider = new ActiveDataProvider([
            'query' => Subscription::find(),
            'pagination' => [
                    'pagesize' => 10,
                    ],
            'sort' => [ 
                'defaultOrder' => [
                    'added_on' => SORT_DESC,
                ],
                'attributes' => [                    
                    'email',
                    'ip_address',
                    'added_on',
                ]
            ],
        ]);
        
        return $this->render('_subscribed',[
            'dataProvider' =>$dataProvider,
        ]);
    }

}
