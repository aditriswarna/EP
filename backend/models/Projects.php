<?php

namespace backend\models;

use Yii;
use yii\db\Connection;

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

    public $amount, $document_name, $username, $from_date, $to_date, $participation_type, $investment_type, $interest_rate, $equity_type,$embed_videos;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'projects';
    }

    /**
     * @inheritdoc
     */
    public function rules() {/* 'CSR_project_type', */ /* 'CSR_website', */
        //echo "Coming Here"; die;
        return [
            [['project_title', 'objective', 'location', 'project_desc', 'estimated_project_cost', 'project_start_date', 'project_end_date', 'primary_contact'], 'required'],
            [['project_category_ref_id', 'project_type_ref_id', 'estimated_project_cost'], 'integer'],
            [['project_desc', 'targeted_govt_authority', 'primary_email_contact', 'display_in_home_page'], 'string'],
            //[['project_start_date', 'project_end_date'], 'safe'],
            [['project_title', 'primary_email_contact'], 'string', 'max' => 50],
            ['primary_email_contact', 'email'],
            [['latitude', 'longitude'], 'string', 'max' => 30],
            [['objective', 'conditions', 'govt_authority_name'], 'string', 'max' => 255],
            [['location'], 'string', 'max' => 200],
            [['primary_contact', 'secondary_contact'], 'match', 'pattern' => '/^[5-9]\d{9}$/'],
            [['amount', 'document_name', 'username', 'from_date', 'to_date', 'participation_type', 'investment_type', 'interest_rate', 'equity_type'], 'safe'],
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
            'display_in_home_page' => 'Display In Home Page',
            'username' => 'Project Created For',
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

    public static function getProjectImages($id) {

        $data = yii::$app->db->createCommand("SELECT * FROM project_media where project_ref_id =" . $id . " and document_type='projectImage'")->queryAll();

        return $data;
    }

    public static function getProjectDocuments($id) {

        $data = yii::$app->db->createCommand("SELECT * FROM project_media where project_ref_id =" . $id . " and document_type='projectDocument'")->queryAll();

        return $data;
    }

    public static function getProjectDetails($id) {

        $sql = 'SELECT p.*, pt.project_type, pc.category_name, u.id as user_id, u.email, up.fname, up.lname, ut.user_type, pp.participation_type, pp.investment_type, pp.equity_type, pp.amount, pp.interest_rate 
        FROM projects AS p 
        LEFT JOIN project_type AS pt ON p.project_type_ref_id = pt.project_type_id
        LEFT JOIN project_category AS pc ON pc.project_category_id = p.project_category_ref_id
        LEFT JOIN user AS u ON p.user_ref_id = u.id
        LEFT JOIN user_profile AS up ON up.user_ref_id = p.user_ref_id
        LEFT JOIN user_type AS ut ON ut.user_type_id = u.user_type_ref_id
        LEFT JOIN project_participation pp ON pp.project_ref_id = p.project_id
        WHERE p.project_id =' . $id;
        $allprojectdata = yii::$app->db->createCommand($sql)->queryAll();
        return $allprojectdata;
    }

    public static function getMlaMpProjects() {
        $sql = "SELECT COUNT(*) FROM projects AS p 
            LEFT JOIN user AS u ON p.user_ref_id = u.id
            WHERE u.user_type_ref_id IN (1,2) and p.project_status=1";
        $count = yii::$app->db->createCommand($sql)->queryScalar();
        return $count;
    }

    public static function getCsrProjects() {
        $sql = "SELECT COUNT(*) FROM projects AS p 
            LEFT JOIN user AS u ON p.user_ref_id = u.id
            WHERE u.user_type_ref_id=5 and p.project_status=1";
        $count = yii::$app->db->createCommand($sql)->queryScalar();
        return $count;
    }

    public static function getBankProjects() {
        $sql = "SELECT COUNT(*) FROM projects AS p 
            LEFT JOIN user AS u ON p.user_ref_id = u.id
            WHERE u.user_type_ref_id=7 and p.project_status=1";
        $count = yii::$app->db->createCommand($sql)->queryScalar();
        return $count;
    }

    public static function getAllProjectsForGraph()
    {
        $curmonth = date('M', strtotime('-5 month'));
        $monthYr =  date("M-Y", strtotime("-5 months")); //exit;
		 $fmonth = '01-'.$monthYr;
         $tmonth = date("d-M-Y"); 
        $sql='SELECT DISTINCT p.project_title, FLOOR(SUM(pp.amount)) AS amount, p.project_id
            FROM projects AS p RIGHT JOIN project_participation as pp
            ON p.project_id=pp.project_ref_id
            WHERE pp.created_date BETWEEN STR_TO_DATE('.'"'.$fmonth.'"'.', "%d-%b-%Y") AND DATE_ADD(STR_TO_DATE('.'"'.$tmonth.'"'.', "%d-%b-%Y"), INTERVAL 1 DAY) '
            . ' AND amount IS NOT NULL AND amount <> "" AND p.project_status=1 GROUP BY pp.project_ref_id ORDER BY pp.created_date';
       // echo $sql; exit;
        $count = yii::$app->db->createCommand($sql)->queryAll();
        return json_encode($count);
    }
    
     public static function getAllProjectsByMembersForGraph()
    {
        $curmonth = date('M', strtotime('-5 month'));
         $monthYr =  date("M-Y", strtotime("-5 months")); //exit;
		 $fmonth = '01-'.$monthYr;
         $tmonth = date("d-M-Y");
        $sql='SELECT DISTINCT p.project_title, p.project_id
            FROM projects AS p RIGHT JOIN project_participation as pp
            ON p.project_id=pp.project_ref_id
            WHERE pp.created_date BETWEEN STR_TO_DATE('.'"'.$fmonth.'"'.', "%d-%b-%Y") AND DATE_ADD(STR_TO_DATE('.'"'.$tmonth.'"'.', "%d-%b-%Y"), INTERVAL 1 DAY) '
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
            WHERE p.project_status=1 AND
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
                WHERE p.project_status=1 AND
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
            WHERE p.project_status=1'.$prjquery.' AND 
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
                WHERE p.project_status=1 '.$prjquery.' AND 
             pp.created_date BETWEEN STR_TO_DATE('.'"'.$fmonth.'"'.', "%d-%b-%Y") AND DATE_ADD(STR_TO_DATE('.'"'.$tmonth.'"'.', "%d-%b-%Y"), INTERVAL 1 DAY)
                GROUP BY pp.project_ref_id,DATE_FORMAT(pp.created_date, "%b-%Y")
                ORDER BY pp.created_date';	
				//echo $sql; exit;
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
            WHERE p.project_status = 1 AND amount IS NOT NULL AND amount <> "" AND p.project_id = '.$project_id;
        $count = yii::$app->db->createCommand($sql)->queryAll();
        return $count;
    }

    public static function getProjectCreatorDetails($id) {
        $sql = 'SELECT u.id, up.fname, up.lname, u.email, u.user_type_ref_id, p.project_title, p.project_id FROM projects AS p JOIN user AS u ON u.id=p.user_ref_id JOIN user_profile AS up ON up.user_ref_id = u.id WHERE project_id=' . $id;
        $projectdata = yii::$app->db->createCommand($sql)->queryAll();
        return $projectdata;
    }
    
    public static function getProjectVideos($id)
    {
      $data = yii::$app->db->createCommand("SELECT * FROM project_media where project_ref_id =".$id." and document_type='projectVideos'")->queryAll();
       return $data;
    }
}
