<?php

namespace frontend\controllers;
 
use yii\web\Controller;
use Yii;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class StatusController extends Controller {
 
    public function actionIndex() {
        \Yii::$app->db->createCommand("UPDATE projects SET project_status=:project_status WHERE project_end_date < CURDATE() AND project_status = 1")
        ->bindValue(':project_status', 4)
        ->execute();
        echo "cron service runnning";
    } 
}