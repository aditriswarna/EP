<?php
namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class ForgotPasswordForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
           // ['email', 'required'],
          //  ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
   /* public function sendEmail()
    {
        // @var $user User 
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
        }
        
        if (!$user->save()) {
            return false;
        }
        $sql = "SELECT u.email, up.fname, up.lname FROM user AS u LEFT JOIN user_profile AS up ON u.id=up.user_ref_id WHERE email='".$this->email. "'";
        $userData = yii::$app->db->createCommand($sql)->queryAll();
        return Yii::$app
            ->mailer
            ->compose('forgotPassword', 
                [
                'email'=> $this->email,
                'userdata' => $userData,
                'token'=> $user->password_reset_token,
                'title'      => Yii::t('app', 'Signup Successful'),
                'htmlLayout' => 'layouts/html'
                ])
            ->setFrom('info@euipppp.com','EquiPPP')
            ->setTo($this->email)
            ->setSubject('Password reset for ' . \Yii::$app->name)
            ->send();
    }*/
}
