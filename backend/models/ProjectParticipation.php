<?php

namespace backend\models;

use Yii;
use yii\db\Connection;
use yii\db\Query;

/**
 * This is the model class for table "project_participation".
 *
 * @property integer $project_participation_id
 * @property integer $project_ref_id
 * @property integer $user_ref_id
 * @property string $participation_type
 * @property string $investment_type
 * @property string $equity_type
 * @property double $amount
 * @property double $interest_rate
 * @property integer $created_by
 * @property string $created_date
 *
 * @property Projects $projectRef
 * @property User $userRef
 */
class ProjectParticipation extends \yii\db\ActiveRecord
{
    public $from_date, $to_date,$username;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_participation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_ref_id', 'user_ref_id', 'participation_type', 'created_by', 'created_date','created_by'], 'required'],
            [['project_ref_id', 'user_ref_id', 'created_by'], 'integer'],
            [['participation_type', 'investment_type', 'equity_type'], 'string'],
            [['amount', 'interest_rate'], 'number'],
            [['created_date', 'from_date', 'to_date','username'], 'safe'],
            
            ['investment_type', 'required', 'when' => function($model) {
                return $model->participation_type == 'Invest';
            }, 'whenClient' => "function (attribute, value) {
                return $('#projectparticipation-participation_type').val() == 'Invest';
            }"],
            [['equity_type', 'amount'], 'required', 'when' => function($model) {
                return $model->investment_type == 'Equity';
            }, 'whenClient' => "function (attribute, value) {
                return $('#projectparticipation-investment_type').val() == 'Equity';
            }"]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_participation_id' => 'Project Participation ID',
            'project_ref_id' => 'Project Ref ID',
            'user_ref_id' => 'Username',
            'participation_type' => 'Participation Type',
            'investment_type' => 'Cash',
            'equity_type' => 'Equity Type',
            'amount' => 'Amount',
            'interest_rate' => 'Interest Rate',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectRef()
    {
        return $this->hasOne(Projects::className(), ['project_id' => 'project_ref_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRef()
    {
        return $this->hasOne(User::className(), ['id' => 'user_ref_id']);
    }
    
    public static function getProjectNameRef($pid, $uid)
    {
        //return $this->hasone(ProjectCategory::className(), ['project_category_id' => 'project_category_ref_id']);
	/*
        $model = Projects::find()->where(["project_id" => $id])->one();
        if(!empty($model)){
            return $model->project_title;
        }
        */
        
        //$connection = new Connection();
        
        $query = new Query;
        $query->select('username, project_title')
            ->from('user, projects, project_participation')
            ->where('user.id = projects.user_ref_id AND projects.project_id = project_participation.project_ref_id'
                    . ' AND project_participation.project_ref_id = '.$pid.' AND project_participation.user_ref_id = '.$uid)
            ->limit(10);
        $command = $query->createCommand();
        $result = $command->queryAll();
        return $result;
        
//	$projectCategoryList = Connection::createCommand('SELECT * FROM projects p, project_participation pp, user u '
//                . ' where p.project_id = pp.project_ref_id AND pp.user_ref_id = u.id AND p.project_ref_id = '.$id)->queryAll();
//	return $projectCategoryList;
    }
    
    public static function getProjectParticipantsDetails($pid, $uid)
    {
        $sql = 'SELECT pp.project_participation_id, u.id, u.email, up.fname, up.lname, pp.user_ref_id FROM project_participation AS pp LEFT JOIN user as u ON u.id=pp.user_ref_id LEFT JOIN user_profile as up ON up.user_ref_id=u.id WHERE pp.project_ref_id='.$pid.' AND pp.user_ref_id!='.$uid;
        $projectdata = yii::$app->db->createCommand($sql)->queryAll();
        return $projectdata;
    }
}
