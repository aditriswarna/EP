<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            ['name', 'required', 'message'=>'Please enter name'],
            [ 'email', 'required', 'message'=>'Please enter email'],
            ['verifyCode','required', 'message'=>'Please enter captcha'],
            ['body','required', 'message'=>'Please enter message'],
            ['name', 'match', 'pattern' => '/^[a-z ]*$/i', 'message' => 'Strange name ! Check again'],
			['body', 'checkHtml'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Captcha',
			'body' => 'Message',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
	 public function checkHtml($attribute, $params)
    {
	 if (preg_match("/(<[^>]+>.*<\/[^>]+>)|((<([^>]+)>))/", $this->body))
	 {
	 $this->addError($attribute, 'HTML content is not allowed');
	 }else{
	 return true;
	 }
	
	}
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }
}
