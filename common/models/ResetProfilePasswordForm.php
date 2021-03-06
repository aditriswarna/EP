<?php

namespace common\models;
use common\models\User;
use yii\base\Model;
use Yii;
error_reporting(0);
/**
 * Password reset form
 */
class ResetProfilePasswordForm extends Model
{
    public $password;
    public $changepassword;
    public $reenterpassword;

   /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
            ['password', 'validatePassword'],
            [['password','changepassword','reenterpassword'], 'required'],
            [['changepassword','reenterpassword'], 'string', 'min' => 6],
            ['reenterpassword', 'compare', 'compareAttribute'=>'changepassword', 'message'=>"Passwords don't match" ]
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    
    public function attributeLabels()
    {
        return [
            //'user_profile_id' => 'User Profile ID',
            //'user_ref_id' => 'User Ref ID',
            'password' => 'Old Password',
            'changepassword' => 'Change Password',
            'reenterpassword' => 'Re-enter Password',
            ];
    }
    
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $userData = User::find()->where(['id' => Yii::$app->user->id])->one();
            $user = $this->getUser($userData->username);
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect password.');
            }
        }
    }
    
     protected function getUser($username)
    {
        $user = User::findByUsername($username);
        return $user;
    }
   
}