<?php

namespace frontend\models;

use Yii;
use yii\db\Connection;
use yii\db\Query;
use frontend\models\ProjectParticipation;
use frontend\models\ProjectStatus;
use frontend\models\ProjectMedia;

/**
 * This is the model class for table "projects".
 *
 * @property integer $project_id
 * @property integer $user_ref_id
 * @property integer $project_category_ref_id
 * @property integer $project_type_ref_id
 * @property string $project_title
 * @property string $objective
 * @property string $location
 * @property string $project_desc
 * @property string $CSR_project_type
 * @property string $CSR_website
 * @property string $Organization_name
 * @property string $conditions
 * @property string $targeted_govt_authority
 * @property integer $estimated_project_cost
 * @property string $project_start_date
 * @property string $project_end_date
 * @property string $primary_contact
 * @property string $secondary_contact
 * @property string $Status
 * @property string $display_in_home_page
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 *
 * @property ActivityLog[] $activityLogs
 * @property User $userRef
 */
class Projects extends \yii\db\ActiveRecord {

    public $amount, $document_name, $from_date, $to_date, $project_title_initiated, $category_initiated, $type_initiated, $status_initiated, $from_date_initiated, $to_date_initiated,
            $project_ref_id, $participation_type, $investment_type, $interest_rate, $equity_type,$embed_videos, $category_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'projects';
    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['CSR'] = ['project_title', 'objective', 'location', 'project_desc', 'estimated_project_cost','project_start_date', 'project_end_date', 'primary_contact', 'Organization_name'];
        $scenarios['individual'] = ['project_title', 'objective', 'location', 'project_desc', 'estimated_project_cost', 'project_start_date', 'project_end_date', 'primary_contact'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules() {/* 'CSR_project_type', */ /* 'CSR_website', */
        //echo "Coming Here"; die;
        return [
            [['project_title', 'objective', 'location', 'project_desc', 'estimated_project_cost', 'project_start_date', 'project_end_date', 'primary_contact'], 'required', 'on'=>'individual'],
            [['project_title', 'objective', 'location', 'project_desc', 'estimated_project_cost','project_start_date', 'project_end_date', 'primary_contact', 'Organization_name'], 'required', 'on'=>'CSR'],
            [['project_category_ref_id', 'project_type_ref_id'], 'integer'],
            [['project_desc', 'targeted_govt_authority', 'primary_email_contact'], 'string'],
            ['primary_email_contact', 'email'],
            //[['project_start_date', 'project_end_date'], 'safe'],
            [['estimated_project_cost'], 'string', 'max' => 10],
            [['project_title', 'primary_email_contact'], 'string', 'max' => 50],
            [['latitude', 'longitude'], 'string', 'max' => 30],
            [['objective', 'conditions', 'govt_authority_name'], 'string', 'max' => 255],
            [['location'], 'string', 'max' => 255],
            [['primary_contact', 'secondary_contact'], 'match', 'pattern' => '/^[5-9]\d{9}$/'],
            [['amount', 'document_name', 'from_date', 'to_date', 'project_title_initiated', 'category_initiated', 'type_initiated', 'status_initiated', 'from_date_initiated', 'to_date_initiated',
            'project_ref_id', 'participation_type', 'investment_type', 'interest_rate', 'equity_type', 'category_name'], 'safe'],
            // [['project_end_date'], 'compare', 'compareAttribute' => 'project_start_date', 'operator' => '>', 'skipOnEmpty' => true, 'message' => '{attribute} must be greater than {compareValue} '],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'project_id' => 'Project ID',
            'project_category_ref_id' => 'Project Category',
            'project_type_ref_id' => 'Project Type',
            'project_title' => 'Project Title',
            'objective' => 'Objective',
            'location' => 'Location',
            'latitude' => 'Latitude',
            'longitude' => 'Longiude',
            'project_desc' => 'Project Description',
            'project_image' => 'Project Image',
            'CSR_project_type' => 'Csr Project Type',
            'CSR_website' => 'Csr Website',
            'conditions' => 'Conditions',
            'targeted_govt_authority' => 'Targeted Govt Authority',
            'estimated_project_cost' => 'Estimated Project Cost',
            'project_start_date' => 'Project Start Date',
            'project_end_date' => 'Project End Date',
            'primary_contact' => 'Primary Contact No',
            'secondary_contact' => 'Secondary Contact No',
            'primary_email_contact' => 'Primary Email Contact',
            'amount' => 'Project Investment Amount',
            'document_name' => 'Document Names',
            'embed_videos'  => 'Embed Video Link'
        ];
    }

    public function getProjectCategoryRefId() {
        return $this->project_category_ref_id ? $this->projects->project_category_ref_id : 'project_category_ref_id';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivityLogs() {
        return $this->hasMany(ActivityLog::className(), ['project_ref_id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRef() {
        return $this->hasOne(User::className(), ['user_id' => 'user_ref_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getProjectCategoryRef($id) {
        //return $this->hasone(ProjectCategory::className(), ['project_category_id' => 'project_category_ref_id']);

        $model = ProjectCategory::find()->where(["project_category_id" => $id])->one();
        if (!empty($model)) {
            return $model->category_name;
        }

        return null;

        //$connection = new Connection();
        //$projectCategoryList = Connection::createCommand('SELECT * FROM projects p, project_category pc where p.project_category_ref_id = pc.project_category_id AND p.project_category_ref_id = '.$this->project_category_ref_id)->queryAll();
        //return $projectCategoryList;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getProjectTypeRef($id) {
        //return $this->hasOne(ProjectType::className(), ['project_type_id' => 'project_type_ref_id']);

        $model = ProjectType::find()->where(["project_type_id" => $id])->one();
        if (!empty($model)) {
            return $model->project_type;
        }

        return null;
    }

    public static function getProjectDetailForHomePage() {
        //return $this->hasOne(ProjectType::className(), ['project_type_id' => 'project_type_ref_id']);
        
        $query1 = new Query;
        $query1->select(['projects.project_id', 'projects.project_title', 'projects.display_in_home_page', 'projects.project_desc', 'projects.project_type_ref_id', 'project_category.category_name','project_category.project_category_id','projects.project_image', 'projects.estimated_project_cost', 'projects.project_end_date', 'user_profile.fname', 'user_profile.lname', 'project_media.document_type', 'project_media.document_name', 'user.user_type_ref_id'])
                ->from('projects')
                ->join('JOIN', 'project_category', 'project_category.project_category_id = projects.project_category_ref_id')
                ->join('JOIN', 'user_profile', 'user_profile.user_ref_id = projects.user_ref_id')
                //->join('LEFT JOIN', 'project_participation', 'project_participation.project_ref_id = projects.project_id')
                ->join('LEFT JOIN', 'project_media', 'project_media.project_ref_id = projects.project_id')
                ->join('JOIN', 'user', 'user.id = projects.user_ref_id')
                ->where("( projects.display_in_home_page = 'Y' || (projects.display_in_home_page != 'Y' AND project_type_ref_id = 1) ) AND project_status = 1 AND DATE_FORMAT(projects.project_end_date, '%Y-%m-%d') >= '" . date('Y-m-d') . "'")
                ->groupBy(['project_id'])
                //->where(["project_media.document_type" => 'projectImgage'])
                ->orderby(['display_in_home_page' => 'ASC', 'project_id' => 'DESC'])
                ->limit(3);


        $command = $query1->createCommand();
        //print_r($command); exit;
        $data1 = $command->queryAll();
        
        /*
        $data2 = array();
        if(count($data1) < 3)
        {
            $query2 = new Query;
            $query2->select(['projects.project_id', 'projects.project_title', 'projects.display_in_home_page', 'projects.project_category_ref_id', 'projects.project_desc', 'project_category.category_name', 'projects.project_image', 'projects.estimated_project_cost', 'projects.project_end_date', 'user_profile.fname', 'user_profile.lname', 'project_media.document_type', 'project_media.document_name', 'user.user_type_ref_id'])
                    ->from('projects')
                    ->join('JOIN', 'project_category', 'project_category.project_category_id = projects.project_category_ref_id')
                    ->join('JOIN', 'user_profile', 'user_profile.user_ref_id = projects.user_ref_id')
                    //->where(['NOT LIKE', 'projects.display_in_home_page', 'Y'])
                    //->join('LEFT JOIN', 'project_participation', 'project_participation.project_ref_id = projects.project_id')
                    ->join('LEFT JOIN', 'project_media', 'project_media.project_ref_id = projects.project_id')
                    ->join('JOIN', 'user', 'user.id = projects.user_ref_id')
                    ->where("projects.display_in_home_page != 'Y' AND DATE_FORMAT(projects.project_end_date, '%Y-%m-%d') >= '" . date('Y-m-d') . "'")
                    ->andWhere(['project_status' => 1, 'project_type_ref_id' => 1])
                    ->groupBy(['project_id'])
                    //->where(["project_media.document_type" => 'projectImgage'])
                    ->limit(ceil(count($data1) - 3));
            $command2 = $query2->createCommand();
            //print_r($command2);
            $data2 = $command2->queryAll();
        }

        return array_merge($data1, $data2);
        * 
        */
        return $data1;
    }
    
    public static function getProjectImages($type) {
        //return $this->hasOne(ProjectType::className(), ['project_type_id' => 'project_type_ref_id']);
        if ($type == 'all') {
            $query1 = new Query;
            $query1->select(['projects.project_id', 'projects.project_title', 'projects.display_in_home_page', 'projects.project_desc', 'project_media.document_name', 'project_media.document_type', 'project_category.category_name', 'projects.project_image', 'projects.estimated_project_cost', 'projects.project_end_date'])
                    ->from('projects')
                    ->where("projects.display_in_home_page = 'Y' AND DATE_FORMAT(projects.project_end_date, '%Y-%m-%d') >= '" . date('Y-m-d') . "'")
                    ->join('LEFT JOIN', 'project_media', 'project_media.project_ref_id = projects.project_id')
                    ->join('JOIN', 'project_category', 'project_category.project_category_id = projects.project_category_ref_id')
                    ->groupBy(['project_id', 'project_ref_id'])
                    //->where(["project_media.document_type" => 'projectImgage'])
                    ->limit(4);


            $command = $query1->createCommand();
            $data1 = $command->queryAll();

            $query2 = new Query;
            $query2->select(['projects.project_id', 'projects.project_title', 'projects.display_in_home_page', 'projects.project_category_ref_id', 'projects.project_desc', 'project_media.document_name', 'project_media.document_type', 'project_category.category_name', 'projects.project_image', 'projects.estimated_project_cost', 'projects.project_end_date'])
                    ->from('projects')
                    ->where("DATE_FORMAT(projects.project_end_date, '%Y-%m-%d') >= '" . date('Y-m-d') . "'")
                    ->join('LEFT OUTER JOIN', 'project_media', 'project_media.project_ref_id = projects.project_id')
                    ->join('JOIN', 'project_category', 'project_category.project_category_id = projects.project_category_ref_id')
                    ->where(['NOT LIKE', 'projects.display_in_home_page', 'Y'])->
                    andWhere(['project_status' => 1])
                    ->groupBy(['project_id', 'project_ref_id'])
                    //->where(["project_media.document_type" => 'projectImgage'])
                    ->limit(4);
            $command2 = $query2->createCommand();
            $data2 = $command2->queryAll();



            //	$data1 = projects::find()->select('project_id,project_title,project_category_ref_id,project_desc, project_image')->where(["display_in_home_page" => 'Y'])->limit(4)->asArray()->all();
            //$data2 = projects::find()->select('project_id,project_title,project_category_ref_id,project_desc, project_image')->where(['NOT LIKE', 'display_in_home_page', 'Y'])->limit(4)->asArray()->all();
//			echo "<pre>";
//			print_r($data2); exit;
            if (!empty($data1) || !empty($data2)) {
                return array_merge($data1, $data2);
            }
        } else if ($type == 'recent') {

            $query3 = new Query;
            $query3->select(['projects.project_id', 'projects.project_title', 'projects.project_category_ref_id', 'projects.project_desc', 'project_media.document_name', 'project_media.document_type', 'project_category.category_name', 'projects.project_image', 'projects.estimated_project_cost', 'projects.project_end_date'])
                    ->from('projects')
                    ->where("DATE_FORMAT(projects.project_end_date, '%Y-%m-%d') >= '" . date('Y-m-d') . "'")
                    ->join('LEFT OUTER JOIN', 'project_media', 'project_media.project_ref_id =projects.project_id')
                    ->join('JOIN', 'project_category', 'project_category.project_category_id = projects.project_category_ref_id')->where(['project_status' => 1])->orderBy(['projects.project_id' => 'SORT_ASC'])
                    ->groupBy(['project_id', 'project_ref_id'])
                    //->where(["projects.display_in_home_page" => 'Y'])
//	->where(["project_media.document_type" => 'projectImgage'])
                    ->limit(4);

            $command3 = $query3->createCommand();
            $data3 = $command3->queryAll();

            //$data3 = projects::find()->select('project_id,project_title,project_category_ref_id,project_desc, project_image')->limit(4)->asArray()->all();

            if (!empty($data3)) {
                return $data3;
            }
        } if ($type == 'popular') {
            $query4 = new Query;
            $query4->select(['projects.project_id', 'projects.project_title', 'projects.project_category_ref_id', 'projects.project_desc', 'project_media.document_name', 'project_media.document_type', 'project_category.category_name', 'projects.project_image', 'projects.estimated_project_cost', 'projects.project_end_date'])
                    ->from('projects')
                    ->where("DATE_FORMAT(projects.project_end_date, '%Y-%m-%d') >= '" . date('Y-m-d') . "'")
                    ->join('LEFT OUTER JOIN', 'project_media', 'project_media.project_ref_id =projects.project_id')
                    ->join('JOIN', 'project_category', 'project_category.project_category_id = projects.project_category_ref_id')
                    ->groupBy(['project_id', 'project_ref_id'])->where(['project_status' => 1])
                    ->orderBy(['rand()' => SORT_DESC])
                    //->where(["projects.display_in_home_page" => 'Y'])
                    //->where(["project_media.document_type" => 'projectImgage'])
                    ->limit(4);

            $command4 = $query4->createCommand();
            $data4 = $command4->queryAll();
            //$data4 = projects::find()->select('project_id,project_title,project_category_ref_id,project_desc, project_image')->orderBy(['rand()' => SORT_DESC])->limit(4)->asArray()->all();

            if (!empty($data4)) {
                return $data4;
            }
        } else {
            $model = projects::find()->where(["project_type_ref_id" => @$id])->one();
            if (!empty($model)) {
                return $model->project_type;
            }
        }
        return null;
    }

    public static function getProjectCreatorDetails($id) {
        $sql = 'SELECT u.id, up.fname, up.lname, u.user_type_ref_id, u.email, p.project_title, p.project_id FROM projects AS p JOIN user AS u ON u.id=p.user_ref_id JOIN user_profile AS up ON up.user_ref_id = u.id WHERE project_id=' . $id;
        $projectdata = yii::$app->db->createCommand($sql)->queryAll();
        return $projectdata;
    }

    public static function getPrivateProjects() {
        $Private_projects = Yii::$app->getDb();
        $Private_projects1 = "SELECT p.project_id,p.user_ref_id,p.project_category_ref_id,p.project_type_ref_id,p.project_title,p.status,pc.category_name,pm.document_name,pm.document_type  
FROM projects p 
JOIN project_category pc ON pc.project_category_id=p.project_category_ref_id  
JOIN `user` u ON u.id= p.user_ref_id 
LEFT JOIN project_media pm ON (pm.project_ref_id=p.project_id) WHERE p.project_type_ref_id='2' AND u.user_type_ref_id<>'7' AND p.project_status='1' AND (pm.document_type='projectImage' OR pm.document_type IS NULL)
GROUP BY  pm.project_ref_id, pm.document_type";
        $command = $Private_projects->createCommand($Private_projects1)->queryAll();
        return $command;
    }

    public static function getMlaMpProjects() {
        $sql = "SELECT COUNT(*) FROM projects AS p 
            JOIN user AS u ON p.user_ref_id = u.id
            WHERE u.user_type_ref_id IN (1,2)";
        $count = yii::$app->db->createCommand($sql)->queryScalar();
        return $count;
    }

    public static function getCsrProjects() {
        $sql = "SELECT COUNT(*) FROM projects AS p 
            JOIN user AS u ON p.user_ref_id = u.id
            WHERE u.user_type_ref_id=5";
        $count = yii::$app->db->createCommand($sql)->queryScalar();
        return $count;
    }

    public static function getBankProjects() {
        $sql = "SELECT COUNT(*) FROM projects AS p 
            JOIN user AS u ON p.user_ref_id = u.id
            WHERE u.user_type_ref_id=7";
        $count = yii::$app->db->createCommand($sql)->queryScalar();
        return $count;
    }

    public function getProjectParticipated() {
        return
                        $this->hasOne(ProjectParticipation::className(), ['project_ref_id' => 'project_id'])
                        ->from(ProjectParticipation::tableName() . ' pp');
        /* ->andWhere([
          'ba.billing_year' => date('Y', strtotime("-1 month")),
          'ba.billing_month' => date('m', strtotime("-1 month")),
          ]); */
    }

    public function getProjectStatus() {
        return
                        $this->hasOne(ProjectStatus::className(), ['status_id' => 'project_status'])
                        ->from(ProjectStatus::tableName() . ' ps')->where(['document_name' => 'projectImage']);
        /* ->andWhere([
          'ba.billing_year' => date('Y', strtotime("-1 month")),
          'ba.billing_month' => date('m', strtotime("-1 month")),
          ]); */
    }
    public static function getAllProjectsForGraph($uid)
    {
        $curmonth = date('M', strtotime('-5 month'));
        $monthYr =  date("M-Y", strtotime("-5 months")); //exit;
		 $fmonth = '01-'.$monthYr;
         $tmonth = date("d-M-Y"); 
        $sql='SELECT DISTINCT p.project_title, FLOOR(SUM(pp.amount)) AS amount, p.project_id
            FROM projects AS p RIGHT JOIN project_participation as pp
            ON p.project_id=pp.project_ref_id
            WHERE p.user_ref_id='.$uid.' AND pp.created_date BETWEEN STR_TO_DATE('.'"'.$fmonth.'"'.', "%d-%b-%Y") AND DATE_ADD(STR_TO_DATE('.'"'.$tmonth.'"'.', "%d-%b-%Y"), INTERVAL 1 DAY) '
            . ' AND amount IS NOT NULL AND amount <> "" AND p.project_status=1 GROUP BY pp.project_ref_id ORDER BY pp.created_date';
        $count = yii::$app->db->createCommand($sql)->queryAll();
        return json_encode($count);
    }
    
    public static function getAllProjectsByMembersForGraph($uid)
    {
        $curmonth = date('M', strtotime('-5 month'));
         $monthYr =  date("M-Y", strtotime("-5 months")); //exit;
		 $fmonth = '01-'.$monthYr;
         $tmonth = date("d-M-Y");
        $sql='SELECT DISTINCT p.project_title, p.project_id
            FROM projects AS p RIGHT JOIN project_participation as pp
            ON p.project_id=pp.project_ref_id
            WHERE p.user_ref_id='.$uid.' AND pp.created_date BETWEEN STR_TO_DATE('.'"'.$fmonth.'"'.', "%d-%b-%Y") AND DATE_ADD(STR_TO_DATE('.'"'.$tmonth.'"'.', "%d-%b-%Y"), INTERVAL 1 DAY) '
            . '  AND p.project_status=1 GROUP BY pp.project_ref_id ORDER BY pp.created_date';
        //echo $sql; exit;
        $count = yii::$app->db->createCommand($sql)->queryAll();
        return json_encode($count);
    }
	
    public static function getMonthlyParticipationAmount()
    {
         $curmonth = date('M', strtotime('-5 month'));
		 $monthYr =  date("M-Y", strtotime("-5 months")); //exit;
		 $fmonth = '01-'.$monthYr;
         //$fmonth = '01-'.$curmonth.'-'.date("Y"); 
         $tmonth = date("d-M-Y");
        $sql='SELECT DISTINCT p.project_title, pp.project_ref_id, p.estimated_project_cost, FLOOR(SUM(pp.amount)) AS amount,
            DATE_FORMAT(pp.created_date, "%b-%Y")  AS months
            FROM `project_participation` AS pp
            LEFT JOIN projects AS p
            ON p.project_id=pp.project_ref_id
            WHERE p.user_ref_id='.Yii::$app->user->id.' AND p.project_status=1 AND
            amount IS NOT NULL AND amount <> "" AND
            pp.created_date BETWEEN STR_TO_DATE('.'"'.$fmonth.'"'.', "%d-%b-%Y") AND DATE_ADD(STR_TO_DATE('.'"'.$tmonth.'"'.', "%d-%b-%Y"), INTERVAL 1 DAY)
            GROUP BY pp.project_ref_id,DATE_FORMAT(pp.created_date, "%b-%Y")  ORDER BY pp.created_date';
        
        $count = yii::$app->db->createCommand($sql)->queryAll();
        return $count;
    }
    
    public static function getMonthlyParticipats()
    {
         $curmonth = date('M', strtotime('-5 month'));
		 $monthYr =  date("M-Y", strtotime("-5 months")); //exit;
		 $fmonth = '01-'.$monthYr;
         $tmonth = date("d-M-Y");
        $sql='SELECT p.project_title, pp.project_ref_id, FLOOR(COUNT(pp.project_participation_id)) as members,DATE_FORMAT(pp.created_date, "%b-%Y")  AS months
                FROM project_participation AS pp
                LEFT JOIN projects AS p 
                ON p.project_id=pp.project_ref_id
                WHERE p.user_ref_id='.Yii::$app->user->id.' AND p.project_status=1 AND
                pp.created_date BETWEEN STR_TO_DATE('.'"'.$fmonth.'"'.', "%d-%b-%Y") AND DATE_ADD(STR_TO_DATE('.'"'.$tmonth.'"'.', "%d-%b-%Y"), INTERVAL 1 DAY)
                GROUP BY pp.project_ref_id,DATE_FORMAT(pp.created_date, "%b-%Y")
                ORDER BY pp.created_date';
        
            $count = yii::$app->db->createCommand($sql)->queryAll();
            return $count;
        
    }
    
    public static function getParticipatsBetweenMonths($from_month,$to_month,$from_year,$to_year,$selectedprj,$checkValues)
    {
       if($from_month){
		$fmonth = '01-'.$from_month.'-'.$from_year;
        }else{
         $curmonth = date('M', strtotime('-5 month'));
		 $curyear = date('Y', strtotime('-5 month'));
         $fmonth = '01-'.$curmonth.'-'.$curyear; 
        }
        if($to_month){
		$lastdate = date('t', strtotime($to_month.','.$to_year));
        $tmonth = $lastdate.'-'.$to_month.'-'.$to_year;
        }else{
            $tmonth = date("d-M-Y");
        }
        if($selectedprj=='all'){
            $prjquery = '';   
        }else if($checkValues){
			$checkValue = join(",",$checkValues); 
			$prjquery = ' AND p.project_id IN ('.$checkValue.')';
		}
        
        $sql='SELECT p.project_title, pp.project_ref_id, p.estimated_project_cost, FLOOR(SUM(pp.amount)) AS amount,
            DATE_FORMAT(pp.created_date, "%b-%Y")  AS months
            FROM `project_participation` AS pp
            LEFT JOIN projects AS p
            ON p.project_id=pp.project_ref_id
            WHERE p.user_ref_id='.Yii::$app->user->id.' AND p.project_status=1'.$prjquery.' AND 
            amount IS NOT NULL AND amount <> "" AND
             pp.created_date BETWEEN STR_TO_DATE('.'"'.$fmonth.'"'.', "%d-%b-%Y") AND DATE_ADD(STR_TO_DATE('.'"'.$tmonth.'"'.', "%d-%b-%Y"), INTERVAL 1 DAY)
            GROUP BY pp.project_ref_id,DATE_FORMAT(pp.created_date, "%b-%Y")  ORDER BY pp.created_date';
            $count = yii::$app->db->createCommand($sql)->queryAll();
            return $count;   
    }
    
    
    public static function getMonthlyParticipatsBetweenMonths($from_month,$to_month,$from_year,$to_year,$selectedprj,$checkValues)
    {
        if($from_month){
		 //exit;
		$fmonth = '01-'.$from_month.'-'.$from_year;
        }else{
         $curmonth = date('M', strtotime('-5 month'));
		 $curyear = date('Y', strtotime('-5 month'));
         $fmonth = '01-'.$curmonth.'-'.$curyear; 
        }
        if($to_month){
		$lastdate = date('t', strtotime($to_month.','.$to_year));
        $tmonth = $lastdate.'-'.$to_month.'-'.$to_year;
        }else{
            $tmonth = date("d-M-Y");
        }
        if($selectedprj=='all'){
            $prjquery = '';   
        }else if($checkValues){
			$checkValue = join(",",$checkValues); 
			$prjquery = ' AND p.project_id IN ('.$checkValue.')';
		}
        $sql='SELECT p.project_title, pp.project_ref_id, FLOOR(COUNT(pp.project_participation_id)) as members,DATE_FORMAT(pp.created_date, "%b-%Y")  AS months
                FROM project_participation AS pp
                LEFT JOIN projects AS p 
                ON p.project_id=pp.project_ref_id
                WHERE p.user_ref_id='.Yii::$app->user->id.' AND p.project_status=1 '.$prjquery.' AND 
             pp.created_date BETWEEN STR_TO_DATE('.'"'.$fmonth.'"'.', "%d-%b-%Y") AND DATE_ADD(STR_TO_DATE('.'"'.$tmonth.'"'.', "%d-%b-%Y"), INTERVAL 1 DAY)
                GROUP BY pp.project_ref_id,DATE_FORMAT(pp.created_date, "%b-%Y")
                ORDER BY pp.created_date';
        
            $count = yii::$app->db->createCommand($sql)->queryAll();
            return $count;
        
    }
    
    public static function getParticipationAmount($project_id)
    {
        $sql='SELECT p.project_title, pp.project_ref_id, p.estimated_project_cost, SUM(pp.amount) AS amount,
            DATE_FORMAT(pp.created_date, "%b-%Y")  AS months
            FROM `project_participation` AS pp
            LEFT JOIN projects AS p
            ON p.project_id=pp.project_ref_id
            WHERE p.user_ref_id='.Yii::$app->user->id.' AND p.project_status = 1 AND p.project_id = '.$project_id;
        $count = yii::$app->db->createCommand($sql)->queryAll();
        return $count;
    }
    
    public static function getProjectParticipationAmount($project_id)
    {
        $sql='SELECT p.project_title, pp.project_ref_id, p.estimated_project_cost, SUM(pp.amount) AS totalAmount
            FROM `project_participation` AS pp
            LEFT JOIN projects AS p ON p.project_id=pp.project_ref_id
            WHERE p.project_status = 1 AND p.project_id = '.$project_id;
        $result = yii::$app->db->createCommand($sql)->queryAll();
        return $result;
    }
	
}
