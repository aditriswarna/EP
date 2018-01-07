<?php

namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\UserForm;
use frontend\models\UserProfile;
use frontend\models\UserProfileByUsertype;
use common\models\ProfileImage;
use common\models\UserType;
use common\models\ResetProfilePasswordForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\ProjectComments;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\User;
use common\models\Communique;
use zyx\phpmailer;
use yii\web\UploadedFile;
use frontend\models\Projects;
use frontend\models\ProjectParticipation;
use common\models\UserRequests;
use frontend\models\ProjectMedia;
use yii\data\SqlDataProvider;
use common\models\EmailTemplates;
use frontend\models\ProjectCategory;
use frontend\models\ProjectCoOwners;
use yii\db\Query;
use common\models\ProjectLikes;
use common\models\MediaAgencies;
use frontend\models\Status;
use common\models\Subscription;
use frontend\models\ForgotPasswordForm;
date_default_timezone_set('Asia/Kolkata');
//error_reporting(E_ALL ^ E_NOTICE);
/**
 * Site controller
 */
class SiteController extends Controller {

    public $layout;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                ],
            ],
                ],
                /* 'verbs' => [
                  'class' => VerbFilter::className(),
                  'actions' => [
                  'logout' => ['post'],
                  ],
                  ], */
        ];
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;

        if (\Yii::$app->getUser()->isGuest &&
                \Yii::$app->getRequest()->url !== \yii\helpers\Url::to(\Yii::$app->getUser()->loginUrl) &&
                (Yii::$app->controller->action->id != 'login' && Yii::$app->controller->action->id != 'signup' &&
                Yii::$app->controller->action->id != 'request-password-reset' && Yii::$app->controller->action->id != 'reset-password' &&
                Yii::$app->controller->action->id != 'resend-email-verification' &&
                Yii::$app->controller->action->id != 'email-verification' && Yii::$app->controller->action->id != 'forgot-password-modal' &&
                Yii::$app->controller->action->id != 'index' && Yii::$app->controller->action->id != 'dynamic-map' &&
                Yii::$app->controller->action->id != 'mapdata' && Yii::$app->controller->action->id != 'get-data' &&
                Yii::$app->controller->action->id != 'is-private' && Yii::$app->controller->action->id != 'view' && Yii::$app->controller->action->id != 'contact-us' && 
                Yii::$app->controller->action->id != 'validateuser' && Yii::$app->controller->action->id != 'get-months-data' && Yii::$app->controller->action->id != 'get-months-data-for-participants' && 
                Yii::$app->controller->action->id != 'validatesignupuser' && Yii::$app->controller->action->id != 'validate-forgot-password' ) && Yii::$app->controller->action->id != 'coming-soon' && 
                Yii::$app->controller->action->id != 'is-login' && Yii::$app->controller->action->id != 'dynamic-new' && Yii::$app->controller->action->id != 'index1' && Yii::$app->controller->action->id != 'invester-list' && 
                yii::$app->controller->action->id != 'about' && yii::$app->controller->action->id != 'blog' && yii::$app->controller->action->id != 'terms-of-use' && yii::$app->controller->action->id != 'careers' &&
                yii::$app->controller->action->id != 'privacy-policy' && yii::$app->controller->action->id != 'csr' && yii::$app->controller->action->id != 'yuva' && yii::$app->controller->action->id != 'how-it-works' && yii::$app->controller->action->id != 'subscribe'
                && yii::$app->controller->action->id != 'banks' && yii::$app->controller->action->id != 'contact' && yii::$app->controller->action->id != 'captcha'  && yii::$app->controller->action->id != 'maintenance') {
            \Yii::$app->getResponse()->redirect(Yii::$app->request->BaseUrl . '/../../login');
        } else {
            return parent::beforeAction($action);
        }
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex1() {

        $projimgs1 = Projects::getProjectImages('all');
        $projimgs2 = Projects::getProjectImages('recent');
        $projimgs3 = Projects::getProjectImages('popular');

        $projimgs = array($projimgs1, $projimgs2, $projimgs3);
        //echo "<pre>"; print_r($projimgs);exit;
        return $this->render('index_bak', [
                    'projimgs' => $projimgs]);
    }

    /* testing home page */

    public function actionIndex() {
        $this->view->title ='EquiPPP - Home';
     /*   $projimgs = Projects::getProjectDetailForHomePage();

        $totalProjects = Projects::find()->where('project_status in (1, 4)')->count();
        
        $sql = 'SELECT COUNT(*) FROM user u LEFT JOIN user_profile up ON up.user_ref_id = u.id JOIN user_type ut ON ut.user_type_id = u.user_type_ref_id
WHERE u.status = 1 AND u.user_role_ref_id != 1 AND u.superadmin=0;';
        $totalUsers = Yii::$app->db->createCommand($sql)->queryScalar();
        
        //echo "<pre>"; print_r($projimgs);exit;
        return $this->render('index', [
                    'projimgs' => $projimgs,
                    'totalProjects' => $totalProjects,
                    'totalUsers' => $totalUsers]); */
        
      //  $this->layout= '/main-construction';
        return $this->render('index');
    }

    /* end here */

    /**
     * Logs in a user.
     *
     * @return mixed
     */
//    public function beforeAction($action) {
//    $this->enableCsrfValidation = false;
//   return parent::beforeAction($action);
//   }
    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl() . '/../../');
        }

        $model = new LoginForm();
        $model->scenario = 'loginpage';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $userData = User::find()->where(['email' => $_POST['LoginForm']['username']])->one();
            if ($userData->email_confirmed != 1) {

                // Yii::$app->session->setFlash('mailnotconfirmed', "<div class='update-created'> <div>Your email is not yet activated. Please check your mail for activation link.<a href='" . Yii::$app->getUrlManager()->getBaseUrl() . "/site/resend-email-verification?id=" . base64_encode($userData->id) . "'>Resend email</a></div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'></button></div>");

                Yii::$app->session->setFlash('mailnotconfirmed', "Your email is not yet activated. Please check your mail for activation link.<a href='" . Yii::$app->getUrlManager()->getBaseUrl() . "/site/resend-email-verification?id=" . base64_encode($userData->id) . "'>Resend email</a>");

                return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl() . '/../../login');
                /*  return $this->render('login', [
                  'model' => $model,
                  ]); */
            } else if ($userData->status != 1) {
                // Yii::$app->session->setFlash('statusnotenabled', "<div class='update-created'> <div>Admin has not yet activated your account.</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'></button></div>");
                Yii::$app->session->setFlash('statusnotenabled', 'Admin has not yet activated your account.');
                /* return $this->render('login', [
                  'model' => $model,
                  ]); */
                return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl() . '/../../login');
            } else if ($model->login()) {
                Yii::$app->session['userType'] = $userData->user_type_ref_id;
                Yii::$app->session['email'] = $userData->email;
                Yii::$app->session['userRole'] = $userData->user_role_ref_id;
                /* if($userData->is_profile_set == 1){
                  return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/site/user-profile');
                  }else{
                  return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/profile/profile');
                  } */
                return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl() . '/../../');
            }
        } else {
            return $this->render('login', [
                        'model' => $model,
                    ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();
        if (Yii::$app->getRequest()->getQueryParam('status') == 'logout') {            
            Yii::$app->session->setFlash('password_changed', "<div class='change-pwd update-created'><div> Password changed successfully!</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'></button></div>");
            return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl() . '/../../login');
        } else {
            return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl() . '/../../');
        }
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContactUs() {      
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
		//print_r($_POST['ContactForm']); exit;
		
		$emailtemplate1 = EmailTemplates::getEmailTemplate(62);
                $body1 = $emailtemplate1[2]['descrition'];
                $body1 = $emailtemplate1[0]['descrition'] . $body1 . $emailtemplate1[1]['descrition'];
                $message = Yii::$app->mailer->compose();
				
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo($_POST['ContactForm']['email'])
                        ->setSubject($emailtemplate1[2]['subject'])
                        ->setHtmlBody($body1)
                        ->send();
                Communique::saveMailData('', 1, $emailtemplate1[2]['subject'], $body1, $_POST['ContactForm']['email'], 'Unread', 1);
				
		$emailtemplate2 = EmailTemplates::getEmailTemplate(63);
                $body2 = str_replace("{name}", $_POST['ContactForm']['name'], $emailtemplate2[2]['descrition']);
                $body2 = str_replace("{email}", $_POST['ContactForm']['email'], $body2);
				$body2 = str_replace("{message}", $_POST['ContactForm']['body'], $body2);
                $body2 = $emailtemplate2[0]['descrition'] . $body2 . $emailtemplate2[1]['descrition'];
                $message = Yii::$app->mailer->compose();
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo('equippp.noreply@gmail.com')
                        ->setSubject($emailtemplate2[2]['subject'])
                        ->setHtmlBody($body2)
                        ->send();
                Communique::saveMailData('', '', $emailtemplate2[2]['subject'], $body2, 'equippp.noreply@gmail.com', 'Unread', 1);		
				Yii::$app->session->setFlash('contact_success', "Your message has been submitted successfully");
            return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl() . '/../../contact-us');
        } else {
            return $this->render('contact', [
                        'model' => $model,
                    ]);
        }
    }

    public function actionValidateuser() {
        $model = new LoginForm();
        $model->scenario = 'loginpopup';
        $model->username = $_POST['uname'];
        $model->password = $_POST['password'];
        $reference_url = $_POST['reference_url'];
        if ($model->validate()) {
            $userData = User::find()->where(['email' => $_POST['uname']])->one();
            if ($userData->email_confirmed != 1) {
                echo json_encode(array("msg"=>"Your email is not yet activated. Please check your mail for activation link.<a href='" . Yii::$app->getUrlManager()->getBaseUrl() . "/site/resend-email-verification?id=" . base64_encode($userData->id) . "'>Resend email</a>"));                            
            } else if ($userData->status != 1) {
                //Yii::$app->session->setFlash('statusnotenabled', 'Admin has not yet activated your account.');
                echo json_encode(array('msg'=>'Admin has not yet activated your account.'));                
            } else if ($model->login()) {
                Yii::$app->session['userType'] = $userData->user_type_ref_id;
                Yii::$app->session['email'] = $userData->email;
                Yii::$app->session['userRole'] = $userData->user_role_ref_id;
                if ($reference_url == 'referrer') {
                    echo json_encode(array('redirect'=>Yii::$app->request->referrer));
                   // return $this->redirect(Yii::$app->request->referrer);
                } else {
                    echo json_encode(array('redirect'=>$reference_url));
                    // return $this->redirect($reference_url);
                }
            }
        } else {
            echo json_encode(array('msg'=>'Incorrect username or password.'));
           // echo 'Incorrect username or password.';
        }
    }

    public function actionValidatesignupuser() {
        $model = new SignupForm();
        $usertypemodel = new UserType();
        $mediatypemodel = new MediaAgencies();
        $model->email = Yii::$app->request->post('uemail'); // = 'rajithatestmail3@gmail.com';
        $model->password = Yii::$app->request->post('password');
        ; // = 'rajitha';
        $model->confirmpassword = Yii::$app->request->post('password'); // = 'rajitha';
        $model->user_type_ref_id = Yii::$app->request->post('usertype'); // = 7;
        if (Yii::$app->request->post('mediatype'))
            $model->media_agency_ref_id = Yii::$app->request->post('mediatype');
        $reference_url = (isset($_POST['reference_url']) ? $_POST['reference_url'] : '');

        $userData = User::find()->where(['email' => Yii::$app->request->post('uemail')])->one();
        if (count($userData) > 0) {
            //Yii::$app->session->setFlash('mailnotconfirmed', "Your email is not yet activated. Please check your mail for activation link.<a href='" . Yii::$app->getUrlManager()->getBaseUrl() . "/site/resend-email-verification?id=" . base64_encode($userData->id) . "'>Resend email</a>");
            echo json_encode(array('msg'=>'The email Address has already been taken'));           
        } else {
            if ($user = $model->signup()) {
                //$adminUsers = User::find()->where(['user_type_ref_id' => $model->user_type_ref_id, 'user_role_ref_id' => 1])->all();
                $userstatus = User::find()->where(['id' => Yii::$app->db->getLastInsertID()])->one();
                $userstatus->status = 2;
                $userstatus->media_agency_ref_id = (isset($_POST['mediatype']) ? $_POST['mediatype'] : '');
                $userstatus->save();
                $user_id_email_log = $user->id;
                $usermodel = User::find()->where(['email' => $model->email])->one();
                $usertype = UserType::find()->where(['user_type_id' => $user->user_type_ref_id])->one();

                $adminUsers = Yii::$app->db->createCommand("SELECT * FROM `user` AS u 
                    LEFT JOIN admin_assigned_user_types AS au ON au.user_ref_id=u.id
                    WHERE user_role_ref_id=1 AND u.status=1 AND au.user_type_ref_id=" . $user->user_type_ref_id)->queryAll();

                //Mail to superadmin
                $emailtemplate1 = EmailTemplates::getEmailTemplate(3);
                $body = str_replace("{email}", $usermodel->email, $emailtemplate1[2]['descrition']);
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
                $emailtemplate2 = EmailTemplates::getEmailTemplate(4);
                $body = str_replace("{auth_key}", $usermodel->auth_key, $emailtemplate2[2]['descrition']);
                $body = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body);
                $body = $emailtemplate2[0]['descrition'] . $body . $emailtemplate2[1]['descrition'];
                $message = Yii::$app->mailer->compose();
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo($_POST['uemail'])
                        ->setSubject($emailtemplate2[2]['subject'])
                        ->setHtmlBody($body)
                        ->send();
                Communique::saveMailData('', $userstatus->id, $emailtemplate2[2]['subject'], $body, $_POST['uemail'], 'Unread', 1);

                if ($adminUsers) {
                    foreach ($adminUsers as $admins) {
                        $emailtemplate3 = EmailTemplates::getEmailTemplate(43);
                        $body = str_replace("{auth_key}", $usermodel->auth_key, $emailtemplate3[2]['descrition']);
                        $body = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body);
                        $body = str_replace("{email}", $_POST['uemail'], $body);
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
                Yii::$app->session->setFlash('signupsuccess', "<div class='update-created'> <div>Please check your email and click the link for email verification.</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'></button></div>");
            }
            if ($reference_url == 'referrer') {
               // return $this->redirect(Yii::$app->request->referrer);
                echo json_encode(array('redirect'=>Yii::$app->request->referrer));
            } else {
                // return $this->redirect($reference_url);
                echo json_encode(array('redirect'=>$reference_url));
            }
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout() {
        $this->view->title = 'About us';
        return $this->render('about');
    }
    
    public function actionBlog() {
        $this->view->title = 'EquiPPP - blog';
        return $this->render('coming-soon');
    }
    
    public function actionTermsOfUse() {
        $this->view->title = 'Terms of use';
        return $this->render('terms');
    }
    
    public function actionCareers() {
        $this->view->title = 'Careers';
        return $this->render('coming-soon');
    }
    
    public function actionPrivacyPolicy() {
        $this->view->title = 'Privacy policy';
        return $this->render('privacy');
    }
    
    public function actionCsr() {
        $this->view->title = 'CSR';
        return $this->render('coming-soon');
    }
    
    public function actionYuva() {
        $this->view->title = 'Yuva';
        return $this->render('coming-soon');
    }
    
    public function actionHowItWorks() {
        $this->view->title = 'How it works';
        return $this->render('how_it_works');
    }
    
    public function actionBanks() {
        $this->view->title = 'Banks';
        return $this->render('coming-soon');
    }
    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        $model = new SignupForm();
        $usertypemodel = new UserType();
        $mediatypemodel = new MediaAgencies();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $email = $_POST['SignupForm']['email'];
            $usertypeid = $_POST['SignupForm']['user_type_ref_id'];
            //$adminUsers = User::find()->where(['user_type_ref_id' => $usertypeid, 'user_role_ref_id' => 1])->all();

            if ($user = $model->signup()) {
                $userstatus = User::find()->where(['id' => Yii::$app->db->getLastInsertID()])->one();
                $userstatus->status = 2;
                $userstatus->media_agency_ref_id = (isset($_POST['SignupForm']['media_agency_ref_id']) ? $_POST['SignupForm']['media_agency_ref_id'] : '');
                $userstatus->save();
                $user_id_email_log = $user->id;
                $usermodel = User::find()->where(['email' => $email])->one();
                $usertype = UserType::find()->where(['user_type_id' => $usertypeid])->one();
                $adminUsers = Yii::$app->db->createCommand("SELECT * FROM `user` AS u 
                    LEFT JOIN admin_assigned_user_types AS au ON au.user_ref_id=u.id
                    WHERE user_role_ref_id=1 AND u.status=1 AND au.user_type_ref_id=" . $usertypeid)->queryAll();
                // Mail to admin
                /* $emailtemplate1 = EmailTemplates::getEmailTemplate(3);
                  $body = str_replace("{email}", $usermodel->email, $emailtemplate1[2]['descrition']);
                  $body = str_replace("{usertype}", ucwords($usertype->user_type), $body);
                  $body = $emailtemplate1[0]['descrition'] . $body . $emailtemplate1[1]['descrition'];
                  $message = Yii::$app->mailer->compose();
                  $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                  ->setTo('equippp.noreply@gmail.com')
                  ->setSubject($emailtemplate1[2]['subject'])
                  ->setHtmlBody($body)
                  ->send();
                  Communique::saveMailData('', 1, $emailtemplate1[2]['subject'], $body, 'equippp.noreply@gmail.com', 'Unread', $usermodel->id);
                  // Mail to user
                  $emailtemplate2 = EmailTemplates::getEmailTemplate(4);
                  $body = str_replace("{auth_key}", $usermodel->auth_key, $emailtemplate2[2]['descrition']);
                  $body = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body);
                  $body = $emailtemplate2[0]['descrition'] . $body . $emailtemplate2[1]['descrition'];
                  $message = Yii::$app->mailer->compose();
                  $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                  ->setTo($email)
                  ->setSubject($emailtemplate2[2]['subject'])
                  ->setHtmlBody($body)
                  ->send();
                  Communique::saveMailData('', $userstatus->id, $emailtemplate2[2]['subject'], $body, $model->email, 'Unread', 'equippp.noreply@gmail.com');
                  if ($adminUsers) {
                  foreach ($adminUsers as $admins) {
                  $emailtemplate3 = EmailTemplates::getEmailTemplate(43);
                  $body = str_replace("{auth_key}", $usermodel->auth_key, $emailtemplate3[2]['descrition']);
                  $body = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body);
                  $body = $emailtemplate3[0]['descrition'] . $body . $emailtemplate3[1]['descrition'];
                  $message = Yii::$app->mailer->compose();
                  $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                  ->setTo($admins['email'])
                  ->setSubject($emailtemplate3[2]['subject'])
                  ->setHtmlBody($body)
                  ->send();
                  Communique::saveMailData('', $admins['id'], $emailtemplate3[2]['subject'], $body, $admins['email'], 'Unread', 'equippp.noreply@gmail.com');
                  }

                  } */

                Yii::$app->session->setFlash('signupsuccess', "<div class='update-created'> <div>Please check your email and click the link for email verification.</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'></button></div>");

                // Yii::$app->session->setFlash('signupsuccess', 'Please check your email and click the link for email verification');
                //$this->comminique('', $user_id_email_log, 'Test Subject', 'Test Body', 'equippp.donotreply@gmail.com');
                return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl() . '/../../');
            }
        }

        return $this->render('signup', [
                    'model' => $model, 'usertypemodel' => $usertypemodel, 'mediatypemodel' => $mediatypemodel
                ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();

        $randomPwd = $this->random_string(10);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $email = $_POST['PasswordResetRequestForm']['email'];
            $query = "SELECT u.id, u.email, up.fname, up.lname from user as u LEFT JOIN user_profile as up ON u.id=up.user_ref_id WHERE u.email ='$email'";
            $userprofile = Yii::$app->db->createCommand($query)->queryAll();
            $user = new User();

            if ($user->forgotpassword($randomPwd, $userprofile[0]['id'])) {

                $emailtemplate = EmailTemplates::getEmailTemplate(5);
                $body = str_replace("{username}", ucwords($userprofile[0]['fname']) . ' ' . ucwords($userprofile[0]['lname']), $emailtemplate[2]['descrition']);
                $body = str_replace("{randompassword}", $randomPwd, $body);
                $body = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body);
                $body = $emailtemplate[0]['descrition'] . $body . $emailtemplate[1]['descrition'];
                $message = Yii::$app->mailer->compose();
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo($userprofile[0]['email'])
                        ->setSubject($emailtemplate[2]['subject'])
                        ->setHtmlBody($body)
                        ->send();
                Communique::saveMailData('', $userprofile[0]['id'], $emailtemplate[2]['subject'], $body, $userprofile[0]['email'], 'Unread', $userprofile[0]['id']);
                
                Yii::$app->session->setFlash('forgotpassword', "<div class='update-created'> <div>Password has been sent to your email.</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'></button></div>");                
                return $this->redirect(Yii::$app->urlManager->createUrl('/../../login'));
            }
            // return $this->redirect(\Yii::$app->urlManager->createUrl("admin/index")); 
        }
        return $this->render('RequestPasswordResetToken', [
                    'model' => $model,
                ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('resetpassword', "<div class='update-created'> <div>New password was saved.</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'></button></div>");
            return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl() . '/../../');
        }

        return $this->render('resetPassword', [
                    'model' => $model,
                ]);
    }

    public function actionEmailVerification() {
        $authkey = $_GET['authkey'];
        $model = new User();
        $userData = User::find()->where(['auth_key' => $authkey])->one();
        if ($userData) {
            if ($userData->email_confirmed == 0 && $userData->status == 1) {
                $userData->email_confirmed = 1;
                $userData->save();
                Yii::$app->session->setFlash('emailverified', "<div class='update-created'> <div>Your Email is verified!</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'></button></div>");
                // Yii::$app->session->setFlash('emailverified', 'Your Email is verified');
            } else if ($userData->email_confirmed == 0) {
                $userData->email_confirmed = 1;
                $userData->save();
                Yii::$app->session->setFlash('emailverified', "<div class='update-created'> <div>Your Email is verified! Admin approval is pending.</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'></button></div>");
                // Yii::$app->session->setFlash('emailverified', 'Email is verified. Admin approval is pending.');
            } else if ($userData->email_confirmed == 1 && $userData->status == 2) {
                Yii::$app->session->setFlash('emailverified', "<div class='update-created'> <div>Your email is already verified. Admin Approval is pending.</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'></button></div>");
                // Yii::$app->session->setFlash('emailverified', 'Your email is already verified. Admin Approval is pending');
            } else if ($userData->email_confirmed == 1 && $userData->status == 1) {
                Yii::$app->session->setFlash('emailverified', "<div class='update-created'> <div>Your email is already verified.</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'></button></div>");
                // Yii::$app->session->setFlash('emailverified', 'Your email is already verified.');
            }
            return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl() . '/../../');
        } else {
            
        }
    }

    public function actionResendEmailVerification() {
        // $this->layout = '/main2';

        $usermodel = User::getUserDetails(base64_decode($_GET['id']));

        $emailtemplate = EmailTemplates::getEmailTemplate(6);
        $body = str_replace("{username}", ucwords($usermodel[0]['fname']) . ' ' . ucwords($usermodel[0]['lname']), $emailtemplate[2]['descrition']);
        $body = str_replace("{auth_key}", $usermodel[0]['auth_key'], $body);
        $body = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body);
        $body = $emailtemplate[0]['descrition'] . $body . $emailtemplate[1]['descrition'];
        $message = Yii::$app->mailer->compose();
        $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                ->setTo($usermodel[0]['email'])
                ->setSubject($emailtemplate[2]['subject'])
                ->setHtmlBody($body)
                ->send();
        Communique::saveMailData('', $usermodel[0]['id'], $emailtemplate[2]['subject'], $body, $usermodel[0]['email'], 'Unread', $usermodel[0]['id']);

       // Yii::$app->session->setFlash('resendemailsuccess', "<div class='update-created'> <div>Verification link has been sent to your email!</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'></button></div>");
        Yii::$app->session->setFlash('resendemailsuccess', 'Verification link has been sent to your email');
        return $this->redirect(Yii::$app->urlManager->createUrl('/../../login'));
    }

    public function actionDashboard() {
        $this->layout = '/main2';

        $totalProjects = Projects::find()->count();
        $totalProjectInitiated = Projects::find()->where(['user_ref_id' => Yii::$app->user->id])->count();
        $totalProjectParticipated = ProjectParticipation::find()->where(['user_ref_id' => Yii::$app->user->id])->groupBy(['project_ref_id'])->count();
        $mpMlaProjects = Projects::getMlaMpProjects();
        $csrProjects = Projects::getCsrProjects();
        $bankProjects = Projects::getBankProjects();
        $monthlyParticipation = Projects::getMonthlyParticipationAmount();
        $monthlyParticipants = Projects::getMonthlyParticipats();
        $allprojects = Projects::getAllProjectsForGraph(Yii::$app->user->id);
        $allprojectsbymembers = Projects::getAllProjectsByMembersForGraph(Yii::$app->user->id);
        //echo count($monthlyParticipation); exit;
        $amount = json_encode($monthlyParticipation);
        $members = json_encode($monthlyParticipants);

        $status = 2;
        $query = new Query();
        $query->select(['project_comment_id', 'project_comments.project_ref_id', 'comments', 'DATE_FORMAT(`project_comments`.`created_date`, "%d %b %Y %I:%i %p") as created_date', 'project_comments.status', 'projects.project_title', 'status.status_name', 'user_profile.user_ref_id', 'user_profile.fname', 'user_profile.lname', 'user_profile.user_image'])
                ->from('project_comments')
                ->join('JOIN', 'user_profile', 'user_profile.user_ref_id = project_comments.user_ref_id')
                ->join('JOIN', 'status', 'status.status_id = project_comments.status')
                ->join('JOIN', 'user', 'user.id = user_profile.user_ref_id')
                ->join('JOIN', 'projects', 'projects.project_id = project_comments.project_ref_id')
                ->join('LEFT JOIN', 'project_participation', 'project_participation.project_ref_id = project_comments.project_ref_id')
                //->where(["projects.project_id" => $id])
                //->where("( (project_comments.project_ref_id = projects.project_id && project_comments.status in (2,7,8)) || (project_comments.user_ref_id != projects.project_id && project_comments.status in (7)) )")
                ->where("( user.user_type_ref_id = " . Yii::$app->session->get('userType') . " && project_comments.status = " . $status . ") AND project_comments.user_ref_id = " . Yii::$app->user->id)
               // ->andWhere(' ("' . date('Y-m-d') . '" BETWEEN DATE_FORMAT(project_start_date, "%Y-%m-%d") AND DATE_FORMAT(project_end_date, "%Y-%m-%d"))')
                ->groupBy('project_comment_id')
                ->orderBy(['project_comments.created_date' => SORT_DESC]);

        $command = $query->createCommand();
        //print_r($command); exit;
        $comments = $command->queryAll();
        /**/
        $email_notif = Yii::$app->db->createCommand('select count(*) as count from communique where view_status=0 and user_ref_id=' . Yii::$app->user->id)->queryAll();
        //for  comment and likes
        $project_comment_notif = Yii::$app->db->createCommand("SELECT count(*) as count FROM project_comments pc 
	 LEFT JOIN projects p ON(p.project_id=pc.project_ref_id) 
	 JOIN `status`s ON(status_id=pc.status)
         WHERE pc.read_status=0 AND  s.status_name='Accept' AND p.user_ref_id=" . Yii::$app->user->id)->queryAll();
        $project_likes_notif = Yii::$app->db->createCommand('SELECT count(*) as count FROM project_likes pl LEFT JOIN projects p ON(p.project_id=pl.project_ref_id) WHERE pl.read_status=0 AND p.user_ref_id=' . Yii::$app->user->id)->queryAll();
        $email_notif_count = $email_notif[0]['count'];

        $project_recent_activities = Yii::$app->db->createCommand('SELECT status, id, user_image, username, created_date, comments, project_title, user_ref_id  FROM (
            SELECT pc.read_status AS status,pc.project_comment_id AS id,u.user_image,CONCAT(u.fname, " ",u.lname) AS username, pc.created_date AS created_date,pc.comments,p.project_title,pc.user_ref_id 
            FROM project_comments pc 
                    LEFT JOIN user_profile u USING(user_ref_id) 
                    LEFT JOIN projects p ON(p.project_id=pc.project_ref_id) 
                    JOIN `status`s ON(status_id=pc.status)
            WHERE s.status_name="Accept" AND p.user_ref_id=' . Yii::$app->user->id .
                        ' UNION
            SELECT pl.read_status AS status,pl.project_likes_id AS id,u.user_image,CONCAT(u.fname, " ",u.lname) AS username, pl.created_date AS created_date, "" AS comments,p.project_title,pl.user_ref_id
            FROM project_likes pl 
                    LEFT JOIN user_profile u USING(user_ref_id) 
                    LEFT JOIN projects p ON(p.project_id=pl.project_ref_id) 
            WHERE p.user_ref_id=' . Yii::$app->user->id .
                        ' UNION
            SELECT c.status, c.communique_id AS id, u.user_image, CONCAT(u.fname, " ",u.lname) AS username, c.created_date AS created_date, "" AS comments,p.project_title AS project_title, c.user_ref_id 
            FROM communique c 
                JOIN user_profile u ON(c.created_by=u.user_ref_id) 
                LEFT JOIN projects p ON(p.project_id=c.project_ref_id) 
            WHERE c.user_ref_id=' . Yii::$app->user->id . ') AS a
            ORDER BY created_date DESC')->queryAll();
			
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

    public function actionGetMonthsData() {
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
		
		if($variables){
		$variables = call_user_func_array('array_merge', $variables);
        print_r(json_encode($variables)); //exit;
		}else{
		print_r(json_encode($variables));
		}
    }

   public function actionGetMonthsDataForParticipants() {
        $frommonth = isset($_POST['frommonth']) ? $_POST['frommonth'] : '';
        $tomonth = isset($_POST['tomonth']) ? $_POST['tomonth'] : '';
		$fromyear = isset($_REQUEST['fromyear'])?$_REQUEST['fromyear']:'';
        $toyear = isset($_REQUEST['toyear'])?$_REQUEST['toyear']:'';
        $selectedprj = isset($_POST['prjid']) ? $_POST['prjid'] : ''; 
		$checkValues = isset($_REQUEST['checkValues'])?$_REQUEST['checkValues']:'';
        $monthsParticipants = Projects::getMonthlyParticipatsBetweenMonths($frommonth,$tomonth,$fromyear,$toyear,$selectedprj,$checkValues);
        print_r(json_encode($monthsParticipants));
    }

    public function actionUserProfile() {
        $this->layout = '/main2';
        $this->view->title = 'Profile';
        $imagemodel = new ProfileImage();
        $userformmodel = new UserForm();
        $model = new UserProfile();
        $usermodel = new User();
        $user_ref = new UserProfileByUsertype();
        $countries = \common\models\User::countrieslist();
        $resetpasswordmodel = new ResetProfilePasswordForm();
        $userdata = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
        $userdata_reference = UserProfileByUsertype::find()->where(['user_ref_id' => Yii::$app->user->identity->id])->one();
        $userdatamodel = UserProfile::find()->where(['user_ref_id' => Yii::$app->user->identity->id])->one();
        $user_ref->user_ref_id = $model->user_ref_id = Yii::$app->user->identity->id;

        return $this->render('user-profile', [
                    'userdata' => $userdata, 'userdata_ref' => $userdata_reference, 'userdatamodel' => $userdatamodel, 'userformmodel' => $userformmodel, 'resetpasswordmodel' => $resetpasswordmodel, 'usermodel' => $usermodel, 'imagemodel' => $imagemodel, 'countries' => $countries
                ]);
    }

    //for saving emails
    public function actionProfile() {        
        $this->layout = '/main2';
        $userformmodel = new UserForm();
        $model = new UserProfile();
        $usermodel = new User();
        $user_ref = new UserProfileByUsertype();

        $userdata = User::find()->where(['id' => Yii::$app->user->identity->id])->one();

        //echo Yii::$app->session['userType'];
        //echo Yii::$app->user->identity->id;
        //print_r($userdata);
        $userdata_reference = UserProfileByUsertype::find()->where(['user_ref_id' => Yii::$app->user->identity->id])->one();
        $userdatamodel = UserProfile::find()->where(['user_ref_id' => Yii::$app->user->identity->id])->one();
        $user_ref->user_ref_id = $model->user_ref_id = Yii::$app->user->identity->id;



        return $this->render('user-profile', [
                    'userdata' => $userdata, 'userdata_ref' => $userdata_reference, 'userdatamodel' => $userdatamodel
                ]);
    }

    function comminique($project_ref_id = '', $user_ref_id, $subject, $message, $to_email, $status = '') {
        $model = new Communique();
        $model->project_ref_id = $project_ref_id;
        $model->user_ref_id = $user_ref_id;
        $model->subject = $subject;
        $model->message = $message;
        $model->to_email = $to_email;
        $model->status = $status;
        $model->created_by = 1;
        $model->created_date = date('Y-m-d h:i:sa');
        $model->save(false);
    }

    public function actionForgotPasswordModal() {

        $model = new PasswordResetRequestForm();

        $randomPwd = $this->random_string(10);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $email = $_POST['PasswordResetRequestForm']['email'];
            $query = "SELECT u.id, u.email, up.fname, up.lname from user as u LEFT JOIN user_profile as up ON u.id=up.user_ref_id WHERE u.email ='$email'";
            $userprofile = Yii::$app->db->createCommand($query)->queryAll();
            $user = new User();

            if ($user->forgotpassword($randomPwd, $userprofile[0]['id'])) {

                $emailtemplate = EmailTemplates::getEmailTemplate(5);
                $body = str_replace("{username}", ucwords($userprofile[0]['fname']) . ' ' . ucwords($userprofile[0]['lname']), $emailtemplate[2]['descrition']);
                $body = str_replace("{randompassword}", $randomPwd, $body);
                $body = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body);
                $body = $emailtemplate[0]['descrition'] . $body . $emailtemplate[1]['descrition'];
                $message = Yii::$app->mailer->compose();
                $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo($userprofile[0]['email'])
                        ->setSubject($emailtemplate[2]['subject'])
                        ->setHtmlBody($body)
                        ->send();
                Communique::saveMailData('', $userprofile[0]['id'], $emailtemplate[2]['subject'], $body, $userprofile[0]['email'], 'Unread', $userprofile[0]['id']);
                Yii::$app->session->setFlash('forgotpassword', "<div class='update-created'> <div>Password has been sent to your email.</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'></button></div>");
                // Yii::$app->session->setFlash('forgotpassword', 'Password has been sent to your email');
                return $this->redirect(Yii::$app->request->referrer);
            }
            // return $this->redirect(\Yii::$app->urlManager->createUrl("admin/index")); 
        }


        return $this->renderPartial('forgot_password_popup', [
                    'model' => $model,
                ]);
    }

    public function actionValidateForgotPassword() {
        $model = new ForgotPasswordForm();

        $randomPwd = $this->random_string(10);

        // if ($model->validate()) {
        $email = $_POST['email'];
        $query = "SELECT u.id, u.email, up.fname, up.lname from user as u LEFT JOIN user_profile as up ON u.id=up.user_ref_id WHERE u.email ='$email'";
        $userprofile = Yii::$app->db->createCommand($query)->queryAll();
        $user = new User();
        if ($model->validate() && $userprofile && $user->forgotpassword($randomPwd, $userprofile[0]['id'])) {

            $emailtemplate = EmailTemplates::getEmailTemplate(5);
            $body = str_replace("{username}", ucwords($userprofile[0]['fname']) . ' ' . ucwords($userprofile[0]['lname']), $emailtemplate[2]['descrition']);
            $body = str_replace("{randompassword}", $randomPwd, $body);
            $body = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body);
            $body = $emailtemplate[0]['descrition'] . $body . $emailtemplate[1]['descrition'];
            $message = Yii::$app->mailer->compose();
            $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($userprofile[0]['email'])
                    ->setSubject($emailtemplate[2]['subject'])
                    ->setHtmlBody($body)
                    ->send();
            Communique::saveMailData('', $userprofile[0]['id'], $emailtemplate[2]['subject'], $body, $userprofile[0]['email'], 'Unread', $userprofile[0]['id']);
            Yii::$app->session->setFlash('forgotpassword', "<div class='update-created'> <div>Password has been sent to your email.</div><button type='button' class='close update-close' data-dismiss='alert' aria-hidden='true'></button></div>");
            
            echo json_encode(array('redirect'=>Yii::$app->request->referrer));            
        }        
        else {
            echo json_encode(array('msg'=>'There is no user with such email.'));            
        }
    }

    public function actionStaff() {
        echo $name = Yii::$app->getRequest()->getQueryParam('staff');

        exit;
        $projimgs = Projects::getProjectImages($name);
        print_r($projimgs);
        exit;
        return $this->renderPartial('project_images', [
                    'projimgs' => $projimgs]);
    }

    //for map

    public function actionDynamicMap() {

        //return $this->render('map');
        return $this->render('mapLocator');
        //  return $this->render('mapLocatorDynamic');
        //return $this->render('mapLocatorPanel');
    }

    // newly added for new map//
    public function actionDynamicNew() {
        $this->layout = '/main';
        $model = new ProjectCategory();
        $category = new UserType();
        $media_agency = new MediaAgencies();
        $project_status = new Status();
        $project = new \common\models\Projects();
        //return $this->render('map');
        return $this->render('mapLocatornew', ['model' => $model, 'category' => $category, 'media_agency' => $media_agency, 'project_status' => $project_status, 'project' => $project]);

        //  return $this->render('mapLocatorDynamic');
        //return $this->render('mapLocatorPanel');
    }

    public function actionMapdata() {
        $connection = Yii::$app->getDb();
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $id_forimages = trim($_GET['id']);
            $projects = $connection->
                    createCommand('SELECT p.project_id,p.user_ref_id,p.project_category_ref_id,p.project_type_ref_id,p.project_title,p.location ,p.longitude as lng,p.status,p.latitude as lat,ut.user_type,pc.category_name,pm.document_name,pm.document_type  FROM projects p JOIN project_category pc ON pc.project_category_id=p.project_category_ref_id  JOIN `user` u ON u.id= p.user_ref_id  JOIN user_type ut ON ut.user_type_id=u.user_type_ref_id LEFT outer JOIN project_media pm ON (pm.project_ref_id=p.project_id) WHERE project_status=1 AND (pm.document_type="projectImage" OR pm.document_type IS NULL) and p.project_id=' . $id_forimages . '
 GROUP BY  p.project_id, pm.document_type')
                    ->queryAll();
            // $projects = Projects::find()->asArray()->where(['project_id' => trim($_GET['id'], '#')])->all();
        } else {

            /*   $projects = $connection-> createCommand('SELECT (SELECT COUNT(*) FROM  project_likes WHERE project_ref_id=p.project_id) AS likes,p.project_id,p.user_ref_id,p.project_category_ref_id category ,p.project_type_ref_id,p.project_title,p.location, DATEDIFF(p.project_end_date,NOW()) AS left_days,p.total_participation_amount,p.estimated_project_cost,p.longitude AS lng,p.status,p.latitude AS lat,ut.user_type,ut.user_type_id,pc.category_name,pm.document_name,pm.document_type  FROM projects p JOIN project_category pc ON pc.project_category_id=p.project_category_ref_id  JOIN `user` u ON u.id= p.user_ref_id  JOIN user_type ut ON ut.user_type_id=u.user_type_ref_id LEFT OUTER JOIN project_media pm ON (pm.project_ref_id=p.project_id) WHERE project_status=1 AND (pm.document_type="projectImage" OR pm.document_type IS NULL) AND p.project_end_date > NOW() GROUP BY  p.project_id, pm.document_type')

              ->queryAll(); */

            $projects = $connection->createCommand('SELECT *,cast(sum(amount) as UNSIGNED) as total_participation_amount from google_projectlist p LEFT JOIN project_participation pp ON p.project_id=pp.project_ref_id GROUP BY p.project_id ORDER BY p.project_end_date DESC')
                    ->queryAll();
            //   $projects = Projects::find()->asArray()->all();
        }


        /*
         * for find date diff
         * if(now()> p.project_end_date, concat(DATEDIFF(p.project_end_date,now())," Exceeded") ,concat(DATEDIFF(p.project_end_date,now())," Days To Go")) as data, */
        // echo "<pre>";
        // print_r($projects);
        // exit;

        $data = array();
        foreach ($projects as $k => $v) {

            $data[$k] = $v;
        }

        return json_encode($data);
    }

    public function actionGetData() {

        if (Yii::$app->request->isAjax) {
            $projectiId = Yii::$app->request->post('id');
            //$data = $data['id'];
            $projectquery = 'SELECT project_id,user_type.user_type,project_title,conditions, 
(SELECT COUNT(*) FROM project_likes WHERE project_likes.project_ref_id=projects.project_id) AS projectlikes,
 project_category_ref_id, projects.user_ref_id, objective, location, project_desc, estimated_project_cost, project_start_date, project_end_date,
if(user_type_id = 9, media_agency_name, "") as media_agency_name, Organization_name,
(SELECT  SUM(amount) FROM project_participation  WHERE project_participation.project_ref_id=' . $projectiId . ' GROUP BY project_ref_id) as total_participation_amount, user_profile.fname, user_profile.lname, project_category.category_name
FROM projects
JOIN project_category ON project_category.project_category_id = projects.project_category_ref_id
JOIN user_profile ON user_profile.user_ref_id = projects.user_ref_id
JOIN user ON user.id = projects.user_ref_id 
JOIN user_type ON user_type.user_type_id=user.user_type_ref_id
LEFT JOIN media_agencies ON media_agencies.media_agency_id = user.media_agency_ref_id 
WHERE project_id = ' . $projectiId . '
GROUP BY user.id';
            $project = Yii::$app->db->createCommand($projectquery)->queryAll();

            $project_data = array_shift($project);
            $rows = (new \yii\db\Query())
                    ->select(['document_name', 'document_type', 'project_ref_id'])
                    ->from('project_media')
                    ->where(['project_ref_id' => $projectiId])
                    ->limit(10)
                    ->all();

            return Yii::$app->controller->renderPartial('mapdata', [
                        'projectData' => $project_data,
                        'rows' => $rows,
                        'actionId' => '',
                    ]);
        }
    }

    public function actionIsPrivate() {
        Yii::$app->controller->enableCsrfValidation = false;
        $this->layout = false;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $data = $data['id'];
            $project = Projects::find()->asArray()->where(['project_id' => $data])->one();
            $user_type=User::find()->asArray()->where(['id' =>Yii::$app->user->id])->one();
            if ($project['project_type_ref_id'] == 2) {
                if (isset(Yii::$app->user->id) && Yii::$app->user->id != 0) {
                    $co_owners_list = array();
                    $project_coOwners = ProjectCoOwners::find()->asArray()->where(['project_ref_id' => $data])->all();
                    if (count($project_coOwners) > 0) {
                        foreach ($project_coOwners as $project_coOwners_list) {
                            $co_owners_list[] = $project_coOwners_list['user_ref_id'];
                        }
                        array_push($co_owners_list, $project['user_ref_id']);
                        if (in_array(Yii::$app->user->id, $co_owners_list)) {
                            echo "yes";
                            exit;
                        }
                    } else if ($project['user_ref_id'] == Yii::$app->user->id) {
                        echo "yes";
                        exit;
                    }

                    $requests = UserRequests::find()->asArray()->where(['project_ref_id' => $data, 'user_ref_id' => Yii::$app->user->id])->one();
                    if($user_type['is_profile_set'] == 1){
                        if (count($requests) != '') {
                            if ($requests['is_approved'] == 1) {
                                echo "yes";
                            } else {
                                echo "already_requested";
                            }
                        } else {
                            echo "request";
                        }
                    }else{
                        echo "set_profile";
                    }
                } else {
                    echo "no";
                }
            } else {
                echo "yes";
            }
        }
    }

    /* public function requestform($data)
      {
      $model = new UserRequests();
      if ($model->load(Yii::$app->request->post()) && $model->validate()) {
      $model->save();
      if ($model->sendEmail()) {
      Yii::$app->session->setFlash('request sent', 'your request has been sent');
      } else {
      Yii::$app->session->setFlash('request not sent', 'your request has not been sent');
      }
      }
      $access_request=UserRequests::find()->asArray()->where(['project_ref_id' => $data])->one();
      return Yii::$app->controller->renderPartial('request_form', [
      'model' =>  $access_request]);

      } */

//for dynamic category 
    /* public function actionGetCategory()
      {
      $rows = (new \yii\db\Query())
      ->select(['project_category_id', 'category_name'])
      ->from('project_category')
      ->where(['Status' => 'active'])
      ->limit(10)
      ->all();
      echo json_encode($rows);

      } */

    public function actionInsertRequest() {

        $id = Yii::$app->request->post();
        $model = new UserRequests();
        $model->user_ref_id = Yii::$app->user->id;
        $model->project_ref_id = $id['id'];
        $model->approved_by = 0;
        $model->is_approved = 0;
        $userDetails = \frontend\models\User::getUserDetails(Yii::$app->user->id);
        $projectdata = \frontend\models\Projects::getProjectCreatorDetails($id['id']);
        $projectCoowners = ProjectCoOwners::getProjectCoownerDetails($id['id'], Yii::$app->user->id);
        //$adminUsers = User::find()->where(['user_type_ref_id' => $userDetails[0]['user_type_ref_id'], 'user_role_ref_id' => 1])->all();
        $adminUsers = Yii::$app->db->createCommand("SELECT * FROM `user` AS u 
                    LEFT JOIN admin_assigned_user_types AS au ON au.user_ref_id=u.id
                    WHERE user_role_ref_id=1 AND u.status=1 AND au.user_type_ref_id=" . $userDetails[0]['user_type_ref_id'])->queryAll();
        if ($model->save()) {

            $emailtemplate1 = EmailTemplates::getEmailTemplate(30);

            $body1 = str_replace("{username}", ucwords($userDetails[0]['fname']) . ' ' . ucwords($userDetails[0]['lname']), $emailtemplate1[2]['descrition']);
            $body1 = str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body1);
            $body1 = $emailtemplate1[0]['descrition'] . $body1 . $emailtemplate1[1]['descrition'];
            $message = Yii::$app->mailer->compose();
            $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo($userDetails[0]['email'])
                    ->setSubject($emailtemplate1[2]['subject'])
                    ->setHtmlBody($body1)
                    ->send();
            Communique::saveMailData($id['id'], $userDetails[0]['id'], $emailtemplate1[2]['subject'], $body1, $userDetails[0]['email'], 'Unread', 1);

            $emailtemplate2 = EmailTemplates::getEmailTemplate(31);

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
            Communique::saveMailData($id['id'], $projectdata[0]['id'], $emailtemplate2[2]['subject'], $body2, $projectdata[0]['email'], 'Unread', 1);

            $emailtemplate3 = EmailTemplates::getEmailTemplate(32);

            $body3 = str_replace("{username}", ucwords($userDetails[0]['fname']) . ' ' . ucwords($userDetails[0]['lname']), $emailtemplate3[2]['descrition']);
            $body3 = str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body3);
            $body3 = $emailtemplate3[0]['descrition'] . $body3 . $emailtemplate3[1]['descrition'];
            $message = Yii::$app->mailer->compose();
            $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                    ->setTo('equippp.noreply@gmail.com')
                    ->setSubject($emailtemplate3[2]['subject'])
                    ->setHtmlBody($body3)
                    ->send();
            Communique::saveMailData($id['id'], 1, $emailtemplate3[2]['subject'], $body3, 'equippp.noreply@gmail.com', 'Unread', 1);

            $emailtemplate4 = EmailTemplates::getEmailTemplate(40);
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
                    Communique::saveMailData($id['id'], $coowners['id'], $emailtemplate4[2]['subject'], $body4, $coowners['email'], 'Unread', 1);
                }
            }

            if ($adminUsers) {
                foreach ($adminUsers as $admins) {

                    $body4 = str_replace("{username}", ucwords($userDetails[0]['fname']) . ' ' . ucwords($userDetails[0]['lname']), $emailtemplate3[2]['descrition']);
                    $body4 = str_replace("{projectname}", ucwords($projectdata[0]['project_title']), $body4);
                    $body4 = str_replace("{site_url}", SITE_URL . yii::getAlias('@web') . '/', $body4);
                    $body4 = $emailtemplate3[0]['descrition'] . $body4 . $emailtemplate3[1]['descrition'];
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                            ->setTo($admins['email'])
                            ->setSubject($emailtemplate3[2]['subject'])
                            ->setHtmlBody($body4)
                            ->send();
                    Communique::saveMailData($id['id'], $admins['id'], $emailtemplate3[2]['subject'], $body4, $admins['email'], 'Unread', 1);
                }
            }

            echo "success";
        } else {
            echo "failure";
        }
    }

    public function actionIsLogin() {

        Yii::$app->controller->enableCsrfValidation = false;
        $this->layout = false;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $data = $data['id'];

            $project = Projects::find()->asArray()->where(['project_id' => $data])->one();
            if ($project['project_type_ref_id'] == 1) {
                if (isset(Yii::$app->user->id) && Yii::$app->user->id != 0) {
                    //return Yii::getAlias('@upload') . '/frontend/web/project-participation/create/?id='.$id;
                    return 'LoggedIn';
                } else {
                    return 'NotLoggedIn';
                }
            }
        }
    }

    //for  popup 

    public function actionView($id) {
        if (!(is_numeric($id))) {
            $id = base64_decode($id);
        }
        //  $this->layout = '/main2';
		$mysqldateformat = Yii::getAlias('@mysqldateformat');
        $sql = 'SELECT `projects`.`project_id`, `project_title`, CONCAT(`fname`, " ", `lname`) as username, `participation_type`, `investment_type`, `equity_type`, `amount`, `interest_rate`, DATE_FORMAT(`projects`.`created_date`, "'.$mysqldateformat.'") as created_date '
                . 'FROM `projects` LEFT JOIN project_participation ON project_participation.project_ref_id = projects.project_id '
                . 'JOIN user_profile ON user_profile.user_ref_id = project_participation.user_ref_id '
                . 'WHERE project_participation.project_ref_id = ' . $id;
        $dataProvider = new SqlDataProvider([
                    'sql' => $sql,
                ]);

        return $this->renderPartial('pop_view', [
                    'model' => $this->findModel($id),
                    'rows' => ProjectMedia::find()->where(['project_ref_id' => $id])->all(),
                    'dataProvider' => $dataProvider,
                ]);
    }

    protected function findModel($id) {
        $this->layout = '/main2';
        if (($model = Projects::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionContact() {
        $this->layout= '/main-comingsoon';
        $this->view->title = 'Contact us';
        /* Yii::$app->mailer->compose('contactus', [
                    'userdata' => $_POST,
                    'title' => Yii::t('app', 'Contact us'),
                    'htmlLayout' => 'layouts/html'
                ])
                ->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                ->setTo('equippp.noreply@gmail.com')
                ->setSubject('A new user has contacted us')
                ->send();

        return true; */
        return $this->render('coming-soon');
    }

    public function actionComingSoon() {
       $this->layout= '/main-comingsoon';
       return $this->render('coming-soon');
    }

    public function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

    public function actionInvesterList() {

        $id = Yii::$app->request->post('pid');

        //  $this->layout = '/main2';
		$mysqldateformat = Yii::getAlias('@mysqldateformat');
        $sql = 'SELECT `projects`.`project_id`, `project_title`, CONCAT(`fname`, " ", `lname`) as username, `participation_type`, `investment_type`, `equity_type`, `amount`, `interest_rate`, DATE_FORMAT(`project_participation`.`created_date`, "'.$mysqldateformat.'") as created_date '
                . 'FROM `projects` LEFT JOIN project_participation ON project_participation.project_ref_id = projects.project_id '
                . 'JOIN user_profile ON user_profile.user_ref_id = project_participation.user_ref_id '
                . 'WHERE ((projects.user_ref_id = ' . Yii::$app->user->id . ' OR project_participation.user_ref_id = ' . Yii::$app->user->id . ') '
                . 'AND project_participation.project_ref_id = ' . $id . ')';
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

        if ($status == '2')
            $andWhere = " AND project_comments.user_ref_id = " . Yii::$app->user->id;
        else
            $andWhere = '';

        $query = new Query();
        $query->select(['project_comment_id', 'project_comments.project_ref_id', 'comments', 'DATE_FORMAT(`project_comments`.`created_date`, "%d %b %Y %I:%i %p") as created_date', 'project_comments.status', 'projects.project_title', 'status.status_name', 'user_profile.user_ref_id', 'user_profile.fname', 'user_profile.lname', 'user_profile.user_image'])
                ->from('project_comments')
                ->join('JOIN', 'user_profile', 'user_profile.user_ref_id = project_comments.user_ref_id')
                ->join('JOIN', 'status', 'status.status_id = project_comments.status')
                ->join('JOIN', 'user', 'user.id = user_profile.user_ref_id')
                ->join('JOIN', 'projects', 'projects.project_id = project_comments.project_ref_id')
                ->join('LEFT JOIN', 'project_participation', 'project_participation.project_ref_id = project_comments.project_ref_id')
                //->where(["projects.project_id" => $id])
                //->where("( (project_comments.project_ref_id = projects.project_id && project_comments.status in (2,7,8)) || (project_comments.user_ref_id != projects.project_id && project_comments.status in (7)) )")
                ->where("( user.user_type_ref_id = " . Yii::$app->session->get('userType') . " && project_comments.status = " . $status . ") " . $andWhere)
               // ->andWhere(' ("' . date('Y-m-d') . '" BETWEEN DATE_FORMAT(project_start_date, "%Y-%m-%d") AND DATE_FORMAT(project_end_date, "%Y-%m-%d"))')
                ->groupBy('project_comment_id')
                ->orderBy(['project_comments.created_date' => SORT_DESC]);

        $command = $query->createCommand();
        //print_r($command); //exit;
        $comments = $command->queryAll();
        $data = '';
        if(count($comments) > 0) {
            foreach ($comments as $comment) {

                $data .= '<div class="mt-comment" id="divComment_' . $comment['project_comment_id'] . '">
                    <div class="mt-comment-img">';
                if (!empty($comment['user_image']))
                    $userImageUrl = 'https://s3.ap-south-1.amazonaws.com/'. Yii::getAlias('@bucket') . '/uploads/profile_images/' . $comment['user_ref_id'] . '/' . $comment['user_image'];
                else
                    $userImageUrl = Yii::$app->request->baseUrl . '/images/avatar.png';
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

    public function actionIsParticipated() {
        $project_id_p = $_POST['id'];
        // $project_id_p = 12;
        $user_id = (isset(Yii::$app->user->id) ? Yii::$app->user->id : '0'); 
        $user_type = User::find()->asArray()->where(['id' =>Yii::$app->user->id])->one();
        $participated_sql = "SELECT project_participation_id,amount,participation_type FROM project_participation  WHERE project_participation.project_ref_id=$project_id_p AND project_participation.user_ref_id=$user_id";
        $participated = Yii::$app->db->createCommand($participated_sql)->queryAll();
        $participated = array_shift($participated);
        $data_count = $user_type['is_profile_set'] == 0 ? 2 : count($participated);
        if (count($participated) > 0) {
            return json_encode($participated);
        }
        else
            echo $data_count;
    }

    //for email notification at dashboard

    public function actionNotificationDashboard() {
	$mysqldateformat = Yii::getAlias('@mysqldateformat');
        $asview = array();
        $dash_notifications = Yii::$app->db->createCommand("select c.*, u.fname, u.lname, u.user_image, DATE_FORMAT(c.created_date, '".$mysqldateformat." %h:%i %p') as created_date from communique c JOIN user_profile u ON(c.created_by=u.user_ref_id) where c.view_status=0  and  c.user_ref_id=" . Yii::$app->user->id." ORDER BY c.created_date DESC")->queryAll();
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
        		
        $dash_notifications = Yii::$app->db->createCommand("SELECT read_status, project_comment_id, project_likes_id, user_image, fname, lname, created_date, comments, project_title, user_ref_id FROM (
                SELECT pc.read_status, pc.project_comment_id, '' AS project_likes_id, u.user_image,u.fname,u.lname, pc.created_date,pc.comments,p.project_title,pc.user_ref_id 
                FROM project_comments pc 
                LEFT JOIN user_profile u USING(user_ref_id) 
                LEFT JOIN projects p ON(p.project_id=pc.project_ref_id) 
                JOIN `status`s ON (status_id=pc.status)
                WHERE s.status_name='Accept' AND p.user_ref_id=" . Yii::$app->user->id."
        UNION
                SELECT pl.read_status, '' AS project_comment_id, pl.project_likes_id, u.user_image,u.fname,u.lname, pl.created_date, '' AS comments,p.project_title,pl.user_ref_id 
                FROM project_likes pl LEFT JOIN user_profile u USING(user_ref_id) 
                LEFT JOIN projects p ON(p.project_id=pl.project_ref_id) 
                WHERE p.user_ref_id=" . Yii::$app->user->id."
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

    public function actionError() {
        $this->layout = '/main2';
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception, title => "hello wolrd"]);
        }
    }
    
    public function actionSubscribe(){
        $email = $_POST['email'];
        $user_ip = $this->getUserIP();
        $subscribed_user_data = Subscription::find()->where(['email' => $email])->one();
        
        if(count($subscribed_user_data) > 0 ){
            echo json_encode(array('msg'=>'subscribed'));
        }else{
            $model = new Subscription();
            $model->email = $email;
            $model->ip_address = $user_ip;
            $model->added_on = date('Y-m-d h:i:s');
            if($model->save(false)){
                $emailTemplate = EmailTemplates::getEmailTemplate(61);
                $header = $emailTemplate[0]['descrition'];
                $body = $emailTemplate[2]['descrition'];
                $footer = $emailTemplate[1]['descrition'];     
                $msg = $header. $body. $footer;
                $mail = Yii::$app->mailer->compose();
                $mail->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                ->setTo($email)
                ->setSubject($emailTemplate[2]['subject'])
                ->setHtmlBody($msg)
                ->send(); 
                echo json_encode(array('msg'=>'success'));
            }else{
                echo json_encode(array('msg'=>'failed'));
            }
        }
        
    }
    
    public function getUserIP()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }

    public function actionMaintenance() {
        $this->layout= '/main-construction';
        return $this->render('maintenance');
    }


}

