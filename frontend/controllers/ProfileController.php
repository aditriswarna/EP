<?php

namespace frontend\controllers;

use yii;
use frontend\models\UserForm;
use frontend\models\UserProfile;
use frontend\models\User;
use common\models\ProfileImage;
use frontend\models\UserProfileByUsertype;
use yii\web\UploadedFile;
use common\models\ResetProfilePasswordForm;
use common\models\Storage;

class ProfileController extends \yii\web\Controller
{
    public $layout;
	public function behaviors()
    {
        return [
            //'ghost-access' => [
            //   'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            //],
            /* 'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ], */
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
    
    public function actionProfile()
    {
        $this->layout = '/main2';
        $userformmodel = new UserForm();
        $countries = \common\models\User::countrieslist();
        $userdatamodel = UserProfile::find()->where(['user_ref_id' => Yii::$app->user->identity->id])->one();
        $model = !empty($userdatamodel) ? $userdatamodel : new UserProfile();  
        
        $userdata_reference = UserProfileByUsertype::find()->where(['user_ref_id' => Yii::$app->user->identity->id])->one();
        $user_ref= !empty($userdata_reference)? $userdata_reference: new UserProfileByUsertype();

        $userdata = User::find()->where(['id' => Yii::$app->user->identity->id])->one();  
       
        $model->user_ref_id = Yii::$app->user->identity->id;
        
        
        if ($userformmodel->load(Yii::$app->request->post())) {
              //posting values to model attribrutes.  
            
                if(isset($_POST['UserForm']['fname'])) $model->fname = $_POST['UserForm']['fname'];
                if(isset($_POST['UserForm']['lname'])) $model->lname = $_POST['UserForm']['lname'];
                if(isset($_POST['UserForm']['mobile'])) $model->mobile = $_POST['UserForm']['mobile'];
                if(isset($_POST['UserForm']['dob'])) $model->dob = date('Y-m-d', strtotime($_POST['UserForm']['dob'])) ;
                if(isset($_POST['UserForm']['gender'])) $model->gender = $_POST['UserForm']['gender'];
                if(isset($_POST['UserForm']['citizen'])) $model->citizen = $_POST['UserForm']['citizen'];
                if(isset($_POST['UserForm']['user_image'])) $model->user_image = $_POST['UserForm']['user_image'];
                if(isset($_POST['UserForm']['domicile'])) $model->domicile = $_POST['UserForm']['domicile'];
                if(isset($_POST['UserForm']['current_location'])) $model->current_location = $_POST['UserForm']['current_location'];
                if(isset($_POST['UserForm']['latitude'])) $model->latitude = $_POST['UserForm']['latitude'];
                if(isset($_POST['UserForm']['longitude'])) $model->longitude = $_POST['UserForm']['longitude'];
                if(isset($_POST['UserForm']['occupation'])) $model->occupation = $_POST['UserForm']['occupation'];
                if(isset($_POST['UserForm']['domain_expertise'])) $model->domain_expertise = $_POST['UserForm']['domain_expertise'];
                if(isset($_POST['UserForm']['course_details'])) $user_ref->course_details = $_POST['UserForm']['course_details'];
                if(isset($_POST['UserForm']['college'])) $user_ref->college = $_POST['UserForm']['college'];
                if(isset($_POST['UserForm']['university'])) $user_ref->university = $_POST['UserForm']['university'];
                if(isset($_POST['UserForm']['year_of_joining'])) $user_ref->year_of_joining = $_POST['UserForm']['year_of_joining'];
                if(isset($_POST['UserForm']['field_of_study'])) $user_ref->field_of_study = $_POST['UserForm']['field_of_study'];
                if(isset($_POST['UserForm']['field_of_excellence'])) $user_ref->field_of_excellence = $_POST['UserForm']['field_of_excellence'];
                if(isset($_POST['UserForm']['communication_address'])) $user_ref->communication_address = $_POST['UserForm']['communication_address'];
                if(isset($_POST['UserForm']['state'])) $user_ref->state = $_POST['UserForm']['state'];
                if(isset($_POST['UserForm']['constituency'])) $user_ref->constituency = $_POST['UserForm']['constituency'];
                if(isset($_POST['UserForm']['elected_year'])) $user_ref->elected_year = $_POST['UserForm']['elected_year'];
                if(isset($_POST['UserForm']['member_of_parliament'])) $user_ref->member_of_parliament = $_POST['UserForm']['member_of_parliament'];
                if(isset($_POST['UserForm']['department'])) $user_ref->department = $_POST['UserForm']['department'];
                if(isset($_POST['UserForm']['sector'])) $user_ref->sector = $_POST['UserForm']['sector'];
                if(isset($_POST['UserForm']['representing_authority'])) $user_ref->representing_authority = $_POST['UserForm']['representing_authority'];
                if(isset($_POST['UserForm']['designation'])) $user_ref->designation = $_POST['UserForm']['designation'];
                if(isset($_POST['UserForm']['bank_name'])) $user_ref->bank_name = $_POST['UserForm']['bank_name'];
                if(isset($_POST['UserForm']['bank_sector'])) $user_ref->bank_sector = $_POST['UserForm']['bank_sector'];
                if(isset($_POST['UserForm']['branch'])) $user_ref->branch = $_POST['UserForm']['branch'];
                if(isset($_POST['UserForm']['company_name'])) $user_ref->company_name = $_POST['UserForm']['company_name'];
                
               
                /* $profileImage = UploadedFile::getInstance($userformmodel,'user_image');  

                $ext= pathinfo($profileImage,PATHINFO_EXTENSION );
                $name=pathinfo($profileImage,PATHINFO_FILENAME);
                if($profileImage){
                    $model->user_image= $name.'_'.date("Ymdis").'.'.$ext;
                } */
                $model->save(false);     
              /*  if($model->save(false))
                {  
                    $lastId = Yii::$app->user->identity->id;
                    if($model->user_image && $name)  {  
                    $folder = Yii::getAlias('@upload') .'/frontend/web/uploads/profile_images/' . $lastId . '/';                        
                        if(!is_dir($folder)) {
                            mkdir($folder, 0777);
                        }
                    $profileImage->saveAs($folder . $model->user_image);
                    }
                } */
                
                if($user_ref->course_details != '' || $user_ref->college != '' || $user_ref->university != '' || $user_ref->year_of_joining != '' || $user_ref->field_of_study != '' || $user_ref->field_of_excellence != '' || $user_ref->communication_address != '' || $user_ref->state != '' || $user_ref->constituency != '' || $user_ref->elected_year != '' || $user_ref->member_of_parliament != '' || $user_ref->department != '' || $user_ref->sector != '' || $user_ref->representing_authority != '' || $user_ref->designation != '' || $user_ref->bank_name != '' || $user_ref->bank_sector != '' || $user_ref->branch != ''){

                    $user_ref->user_ref_id = Yii::$app->user->identity->id;
                    $user_ref->save(false);
                }
                $userdata->is_profile_set=1;
                $userdata->save(false);               
                if($userdatamodel){
                    Yii::$app->session->setFlash('profilesaved', "Profile has been updated successfully");
                }else{
                    Yii::$app->session->setFlash('profilesaved', "Profile has been saved successfully");
                }
            return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl("../../profile"));
        } else {
             
            return $this->render('profile', [
                'userformmodel' => $userformmodel, 'userdata' => $userdata, 'userdata_ref' =>$userdata_reference, 'userdatamodel' =>$userdatamodel, 'countries' => $countries
            ]);
        }
        
    }
    
    public function actionResetProfilePassword()
    {
        $this->layout = '/main2';
       
        $resetpasswordmodel = new ResetProfilePasswordForm();
        if ($resetpasswordmodel->load(Yii::$app->request->post())) {
                
                
            if($resetpasswordmodel->validate()){
                 $user = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
                 //if(isset($_POST['ResetProfilePasswordForm']['changepassword'])) $resetpasswordmodel->password = $_POST['ResetProfilePasswordForm']['changepassword'];
               //  $resetpasswordmodel->password;
                 $user->password_hash = $this->setPassword($_POST['ResetProfilePasswordForm']['changepassword']);
                  $user->save(false);
                  
                  return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl()."/site/logout?status=logout");
            }
         }
         
                 return $this->render('ResetProfilePassword', [
                'resetpasswordmodel' => $resetpasswordmodel
                ]);
    }
    

    public function actionChangeProfileImage()
    {
        $imagemodel = new ProfileImage();            
        $userprofile = UserProfile::find()->where(['user_ref_id' => Yii::$app->user->identity->id])->one();

        $userprofile = !empty($userprofile)?$userprofile:new UserProfile();
        if(isset($userprofile->user_image)){
            $old_user_image = $userprofile->user_image;
        }
        if ($imagemodel->load(Yii::$app->request->post())) {
             if(isset($_POST['ProfileImage']['user_image'])){     

                $imagemodel->user_image = $_POST['ProfileImage']['user_image'];                    
                $profileImage = UploadedFile::getInstance($imagemodel,'user_image');                    

                $ext= pathinfo($profileImage->name,PATHINFO_EXTENSION );
                $name= pathinfo($profileImage->name,PATHINFO_FILENAME);
                $profileImageName = $name . '_' . date("Ymdis") . '.' . $ext;

                $lastInsertId = Yii::$app->user->identity->id;
                $bucket = Yii::getAlias('@bucket');
                $keyname = 'uploads/profile_images/'.$lastInsertId.'/'.$profileImageName;
                $filepath = $profileImage->tempName; 
                $s3=new Storage();
                if(isset($old_user_image)){
                    $existingkeyname = 'uploads/profile_images/'.Yii::$app->user->identity->id.'/'.$old_user_image; 
                    $file = $s3->download($bucket,$existingkeyname); 
                    if(isset($file['@metadata']['effectiveUri'])){
                        $s3->delete($bucket, $existingkeyname);
                    }
                }    
                $s3->upload($bucket,$keyname,$filepath);
                $userprofile->user_ref_id = Yii::$app->user->identity->id;
                $userprofile->user_image = $profileImageName;
                $userprofile->save(false);
                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl("../../profile"));
            }  
        }
    }


     public function setPassword($password)
    {
       return Yii::$app->security->generatePasswordHash($password);
    }
    
    
    
}
