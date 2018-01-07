<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "email_templates".
 *
 * @property integer $mail_template_id
 * @property string $template_name
 * @property string $descrition
 * @property string $created_date
 * @property integer $created_by
 * @property string $modified_date
 * @property integer $modified_by
 * @property string $subject
 */
class EmailTemplates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_templates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_name', 'descrition', 'created_date', 'created_by'], 'required'],
            [['descrition'], 'string'],
            [['created_date', 'modified_date'], 'safe'],
            [['created_by', 'modified_by'], 'integer'],
            [['template_name'], 'string', 'max' => 100],
            [['subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mail_template_id' => 'Mail Template ID',
            'template_name' => 'Template Name',
            'descrition' => 'Descrition',
            'created_date' => 'Created Date',
            'created_by' => 'Created By',
            'modified_date' => 'Modified Date',
            'modified_by' => 'Modified By',
            'subject' => 'Subject',
        ];
    }
    
    public static function getEmailTemplate($tid)
    {
        if($tid){
        $sql='SELECT * FROM email_templates WHERE mail_template_id IN (1,'.$tid.',2)';
        }else{
            $sql='SELECT * FROM email_templates WHERE mail_template_id IN (1,2)';
        }
       // echo $sql; exit;
         $result = yii::$app->db->createCommand($sql)->queryAll();
        return $result;
    }
}
