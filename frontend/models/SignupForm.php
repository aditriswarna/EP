<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $password;
    public $user_type_ref_id;
    public $media_agency_ref_id;
    public $user_role_ref_id;
    public $confirmpassword;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['signuppopup'] = ['email'];
        $scenarios['signuppage'] = ['email', 'password', 'confirmpassword', 'user_type_ref_id'];
        return $scenarios;
    }
    
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required','on'=>'signuppage'],
            ['email', 'email','on'=>'signuppage'],
            ['email', 'string', 'max' => 255,'on'=>'signuppage'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.','on'=>'signuppage'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.','on'=>'signuppopup'],

            ['password', 'required','on'=>'signuppage'],
            ['password', 'string', 'min' => 6,'on'=>'signuppage'],
            
            ['confirmpassword', 'required','on'=>'signuppage'],
            ['confirmpassword', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match",'on'=>'signuppage' ],
            
            ['user_type_ref_id', 'required','on'=>'signuppage'],            
            [['user_role_ref_id', 'media_agency_ref_id'], 'safe'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Password',
            'confirmpassword' => 'Confirm Password',
            'user_type_ref_id' => 'User Type',
            'user_role_ref_id' => 'User Role',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->email;
        $user->email = $this->email;
        $user->user_type_ref_id = $this->user_type_ref_id;
        $user->media_agency_ref_id = $this->media_agency_ref_id;

        $user->status = 0;
        $user->user_role_ref_id = 2;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}