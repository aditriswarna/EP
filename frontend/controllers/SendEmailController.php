<?php

namespace frontend\controllers;

use yii;
use yii\web\Controller;
use frontend\models\EmailForm;
use frontend\models\Projects;
use common\models\EmailTemplates;
use frontend\models\UserProfile;
use common\models\Communique;


class SendEmailController extends Controller
{
    public function actionIndex()
    {    
        $model = new EmailForm();
        $project_ref_id = Yii::$app->getRequest()->getQueryParam('id');
        $category_ref_id = Yii::$app->getRequest()->getQueryParam('cat');
        
        
        $project = Projects::find()->where(['project_id' => $project_ref_id])->one();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $_POST['EmailForm'] != ""){
            
            extract($_POST['EmailForm']);
            
            $link = Yii::$app->urlManager->createAbsoluteUrl('/../../search-projects?id='.$project_ref_id.'&cat='.$category_ref_id);
            $loggedInuserData = UserProfile::find()->select('fname, lname')->where(['user_ref_id' => Yii::$app->user->identity->id])->one();
           
             /* send email to users*/
            if(isset($email) && $email != ''){
                $email = explode(',', $email);        
                foreach($email as $to_email){
                    
                    $userData = \common\models\User::find()->select('id')->where(['email'=>$to_email])->one();
                    $user_ref_id = !empty($userData->id)?$userData->id:'';
                    
                    $emailTemplate = EmailTemplates::getEmailTemplate(39);

                    $header = $emailTemplate[0]['descrition'];
                    $body = $emailTemplate[2]['descrition'];
                    $footer = $emailTemplate[1]['descrition'];  
                    $username = substr($to_email, 0, strpos($to_email, "@"));
                    $subject = str_replace('[name]', $loggedInuserData->fname.' '.$loggedInuserData->lname ,$emailTemplate[2]['subject']);

                    $body = str_replace(array('[content]','[username]','[project_link]'), array($message, $username, $link), $body);
                    $msg = $header. $body. $footer;
                    
                    $mail = Yii::$app->mailer->compose();
                    $mail->setFrom([\Yii::$app->params['supportEmail'] => 'EquiPPP'])
                        ->setTo($to_email)
                        ->setSubject($subject)
                        ->setHtmlBody($msg)
                        ->send();

                    Communique::saveMailData($project_ref_id, $user_ref_id, $subject, $msg, $to_email, 'Unread', Yii::$app->user->identity->id);
                }  
                return 'You have successfully sent the mail to recipients';
            }
        }  else{
            return $this->renderPartial('index',[
                'model' =>$model,
                'project' => $project,
            ]);
        }
    }

}
