<?php

namespace backend\controllers;

use Yii;
use common\models\MediaAgencies;
use common\models\search\MediaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;
use backend\models\Status;

/**
 * MediaController implements the CRUD actions for MediaAgencies model.
 */
class MediaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'ghost-access' => [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
            /* 'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ], */
        ];
    }

    /**
     * Lists all MediaAgencies models.
     * @return mixed
     */
    public function actionIndex()
    {        
//        $searchModel = new MediaSearch();
//        $queryParams = Array();
//        $params['MediaSearch'] = Yii::$app->request->queryParams;
        
        $model = new MediaAgencies();
        
        $mediaAgencyName = Yii::$app->getRequest()->getQueryParam('media_agency_name') ? Yii::$app->getRequest()->getQueryParam('media_agency_name') : "";
        $status = Yii::$app->getRequest()->getQueryParam('status') ? Yii::$app->getRequest()->getQueryParam('status') : "";
        $fromDate = Yii::$app->getRequest()->getQueryParam('from_date') ? Yii::$app->getRequest()->getQueryParam('from_date') : "";
        $toDate = Yii::$app->getRequest()->getQueryParam('to_date') ? Yii::$app->getRequest()->getQueryParam('to_date') : "";
        
        $where = "";
        if(!empty($media_agency_name) || !empty($status) || !empty($fromDate) || !empty($toDate)) {
            $where .= (!empty($media_agency_name)) ? " AND media_agency_name LIKE '%".$media_agency_name."%'" : "";
            $where .= (!empty($status)) ? " AND status = ".$status : "";
            $where .= (!empty($fromDate)) ? " AND DATE_FORMAT(created_date, '%Y-%m-%d') >= '" . date("Y-d-m", strtotime(str_replace("-", "/", $fromDate))) . "'" : "";
            $where .= (!empty($toDate)) ? " AND DATE_FORMAT(created_date, '%Y-%m-%d') <= '" . date("Y-d-m", strtotime(str_replace("-", "/", $toDate))) . "'" : "";
        }
        
        $SQL = "select media_agency_id, media_agency_name, status, created_date from media_agencies where 1 " . $where;
        
        $sql_data_count = count(yii::$app->db->createCommand($SQL)->queryAll());
        
        $dataProvider = new SqlDataProvider([
            'sql' => $SQL,
            'totalCount' => $sql_data_count,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        
//        foreach($params as $key=>$value){
//            $queryParams[$key] = $value;
//            if(array_key_exists('from_date',$value)){
//                $queryParams[$key]['from_date'] = $value['from_date'] != '' ? date('Y-d-m',  strtotime($value['from_date'])): '';                
//            }  
//            if(array_key_exists('to_date',$value)){
//                $queryParams[$key]['to_date'] = $value['to_date'] != '' ? date('Y-d-m',  strtotime($value['to_date'])): '';
//            } 
//        }
        
//        print_r($queryParams);
        
//        $dataProvider = $searchModel->search($queryParams);
        
        $mediaAgencyStatus = ArrayHelper::map(Status::find()->where('status_id IN (1,3)')->all(), 'status_id', 'status_name');

        return $this->render('index', [
            'model' => $model,
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'mediaAgencyStatus' => $mediaAgencyStatus,
        ]);
    }

    /**
     * Displays a single MediaAgencies model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderPartial('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MediaAgencies model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MediaAgencies();

        if ($model->load(Yii::$app->request->post())) {
            
            $model->media_agency_name = $_POST['MediaAgencies']['media_agency_name'];
            $model->created_date = date('Y-m-d H:i:s');
            $model->created_by = yii::$app->user->identity->id;
            $model->save(false);
            
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MediaAgencies model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MediaAgencies model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MediaAgencies model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MediaAgencies the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MediaAgencies::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionChangestatus(){
        $id= Yii::$app->getRequest()->getQueryParam('id');
        $status = Yii::$app->getRequest()->getQueryParam('status');
        
        $model = MediaAgencies::find()->where(['media_agency_id' => $id, 'status'=>$status])->one(); 
        $model->status = $status == 1?3:1;            
        $model->save();
        
        return true;
    }
}
