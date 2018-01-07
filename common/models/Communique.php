<?php

namespace common\models;
use yii\helpers\Url;
use Yii;

/**
 * This is the model class for table "communique".
 *
 * @property integer $communique_id
 * @property integer $project_ref_id
 * @property integer $user_ref_id
 * @property string $subject
 * @property string $message
 * @property string $to_email
 * @property string $status
 * @property integer $created_by
 * @property string $created_date
 */
class Communique extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'communique';
    }

    /**
     * @inheritdoc
     */
    public $selectemail;
    public $existing_email;
    public $new_email;
    public $username;
    public $projects;
	public $search_user_ref_id;
	public $search_subject;
	public $search_project_ref_id;
	public $from_date;
	public $to_date;
	public $mailstatus;
    public function rules()
    {
        return [
            [['project_ref_id', 'user_ref_id', 'created_by'], 'integer'],
            [['subject', 'status', 'created_by', 'created_date','message'], 'required'],
           // [['message'],'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Html content is not allowed'],
            [['message', 'status'], 'string'],
            [['created_date','from_date','to_date','mailstatus'], 'safe'],
            [['subject'], 'string', 'max' => 255],
            [['to_email'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'communique_id' => 'Communique ID',
            'project_ref_id' => 'Project Ref ID',
            'user_ref_id' => 'User Ref ID',
            'subject' => 'Subject',
            'message' => 'Message',
            'to_email' => 'To Email',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
        ];
    }
    
    public static function saveMailData($project_ref_id = '', $user_ref_id = '', $subject, $message, $to_email, $status = '', $created_by) {
        $model = new Communique();
        $model->project_ref_id = $project_ref_id;
        $model->user_ref_id = $user_ref_id;
        $model->subject = $subject;
        $model->message = $message;
        $model->to_email = $to_email;
        $model->status = $status;
        $model->created_by = $created_by;
        $model->created_date = date('Y-m-d h:i:s');
        $model->save(false);
    }
}
