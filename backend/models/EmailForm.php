<?php

namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "user_type".
 *
 * @property integer $user_type_id
 * @property string $user_type
 *
 * @property User[] $users
 */
class EmailForm extends Model
{
    public $email;   
    public $message;
    public $project_ref_id;
    public $user_ref_id;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
         //   [['email', 'message'], 'required'],
        //    ['email', 'email'],
           // [['user_type'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email Id',          
        ];
    }
    
}
