<?php

namespace backend\controllers;

use yii;
use backend\models\User;
use backend\models\UserForm;
use common\models\ResetProfilePasswordForm;
use yii\web\UploadedFile;
use common\models\ProfileImage;
use backend\models\UserProfile;
use common\models\Storage;


class ProfileController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->view->title = 'Profile';
        $userformmodel = new UserForm();
        $usermodel = new User();
        $imagemodel = new ProfileImage();
        $userdata = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
        $userdatamodel = UserProfile::find()->where(['user_ref_id' => Yii::$app->user->identity->id])->one();
        $resetpasswordmodel = new ResetProfilePasswordForm();

                return $this->render('index', [
                'userformmodel' => $userformmodel, 'usermodel' => $usermodel, 'resetpasswordmodel' => $resetpasswordmodel, 'userdata' => $userdata, 'userdatamodel' => $userdatamodel, 'imagemodel' => $imagemodel
            ]);
    }
    public function actionProfile()
    {
        $this->layout = '/main2';
        $userformmodel = new UserForm();
        
        $userData = User::find()->where(['id' => Yii::$app->user->identity->id])->one(); 
        $userData = !empty($userData)?$userData: new User();  
        
        $userProfileData = UserProfile::find()->where(['user_ref_id' => Yii::$app->user->identity->id])->one();;
        $model = !empty($userProfileData)?$userProfileData:new UserProfile();
               
        if ($userformmodel->load(Yii::$app->request->post())) {
                if(isset($_POST['UserForm']['username'])) $userData->username = $_POST['UserForm']['username'];
                if(isset($_POST['UserForm']['fname'])) $model->fname = $_POST['UserForm']['fname'];
                if(isset($_POST['UserForm']['lname'])) $model->lname = $_POST['UserForm']['lname'];
                if(isset($_POST['UserForm']['mobile'])) $model->mobile = $_POST['UserForm']['mobile'];
                if(isset($_POST['UserForm']['gender'])) $model->gender = $_POST['UserForm']['gender'];                
                $model->user_ref_id = Yii::$app->user->identity->id; 
                $model->save(false);
                $userData->save(false);
            
             return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/profile/index');
        }else {
             
            return $this->render('profile', [
                'userformmodel' => $userformmodel, 'userdata' => $userdata
            ]);
        }
        
    }
    
    public function actionResetProfilePassword()
    {
       
        $resetpasswordmodel = new ResetProfilePasswordForm();
        if ($resetpasswordmodel->load(Yii::$app->request->post())) {
                
                
            if($resetpasswordmodel->validate()){
                 $user = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
                 //if(isset($_POST['ResetProfilePasswordForm']['changepassword'])) $resetpasswordmodel->password = $_POST['ResetProfilePasswordForm']['changepassword'];
               //  $resetpasswordmodel->password;
                 $user->password_hash = $this->setPassword($_POST['ResetProfilePasswordForm']['changepassword']);
                  $user->save(false);
                  return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/profile/index');
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
                if(isset($_POST['ProfileImage']['user_image']))
                $imagemodel->user_image = $_POST['ProfileImage']['user_image'];                    
                $profileImage = UploadedFile::getInstance($imagemodel,'user_image');                    

                $ext= pathinfo($profileImage->name,PATHINFO_EXTENSION );
                $name= pathinfo($profileImage->name,PATHINFO_FILENAME);
                $profileImageName = $name . '_' . date("Ymdis") . '.' . $ext;

                $lastInsertId = Yii::$app->user->identity->id;
                $bucket = Yii::getAlias('@bucket');
                $keyname = 'uploads/profile_images/'.$lastInsertId.'/'.$profileImageName;
                $filepath = $profileImage->tempName;                    
                $existingkeyname = 'uploads/profile_images/'.Yii::$app->user->identity->id.'/'.$old_user_image; 

                $s3=new Storage();
                $file = $s3->download($bucket,$existingkeyname); 
                if(isset($file['@metadata']['effectiveUri'])){
                    $s3->delete($bucket, $existingkeyname);
                }
                $s3->upload($bucket,$keyname,$filepath);
                $userprofile->user_ref_id = Yii::$app->user->identity->id;
                $userprofile->user_image = $profileImageName;
                $userprofile->save(false);
                return $this->redirect(Yii::$app->getUrlManager()->getBaseUrl().'/profile/index');                 
            }
        }
    
    public function setPassword($password)
    {
       return Yii::$app->security->generatePasswordHash($password);
    }

}
