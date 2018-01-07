<?php

use yii\helpers\Html;
use frontend\models\Projects;
use frontend\models\ProjectCategory;
use frontend\models\ProjectParticipation;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projects-index">

    <div class="row head-bar">
    <!--<div class="col-xs-12 col-sm-6"><span class="caption-subject font-blue-madison bold uppercase"><?php // echo Html::encode($this->title) ?></span></div>-->

        <div class="col-xs-12 col-sm-6"> 
            <span class="">
                <a class="btn btn-success" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl("../../create-project") ?>">Create New Project</a>
                <?php // echo Html::a('Create Projects', ['create'], ['class' => 'btn btn-success']) ?> 
            </span>
        </div>
    </div>
    
    
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
<script type="text/javascript">
$(document).ready(function(){ 
    $("#myTab a").click(function(e){
    	e.preventDefault();
    	$(this).tab('show');
    });
});
</script>
<style type="text/css">
        .notifyDiv{
          background-color: #B5EBE0;  
        }
		.bs-example{
		margin: 20px;
		}
        .red {
            /*color: #FFFFFF;*/
            background-color: #FFCB6B;
        }
        .green {
            /*color: #FFFFFF;*/
            background-color: #90EE90;
        }
        .searchBox .form-group {
            margin: 0px;
        }
		.portlet.light .portlet-body { padding: 10px 5px !important;}
		
</style>
<?php 
if(($flash = Yii::$app->session->getFlash('project_success')) || ($flash=Yii::$app->session->getFlash('project_created'))){
    echo Alert::widget(['options' => ['class' => 'alert-success front-noti'], 'body' => $flash]);
    }

    /*
    if(Yii::$app->getRequest()->getQueryParam('id')) {        
        $participatedActivate = 'active';
        $initiatedActivate = '';
        $allActivate = '';
    } else {
        $participatedActivate = '';
        $initiatedActivate = '';
        $allActivate = 'active';
    }
    */
    if($tabActive == 'initiated') {
        $participatedActivate = '';
        $initiatedActivate = 'active';
        $allActivate = '';
    } elseif($tabActive == 'participate') {
        $participatedActivate = 'active';
        $initiatedActivate = '';
        $allActivate = '';
    } else {
        $participatedActivate = '';
        $initiatedActivate = '';
        $allActivate = 'active';
    }
    
    $userType = Yii::$app->db->createCommand('SELECT user_type, if(user_type_id = 9, media_agency_name, "") as media_agency_name '
                                                . 'FROM user_type JOIN user ON user.user_type_ref_id = user_type.user_type_id '
                                                . 'LEFT JOIN media_agencies ON media_agencies.media_agency_id = user.media_agency_ref_id '
                                                . 'WHERE user_type_id = '.Yii::$app->session->get('userType').' AND user.id = '.Yii::$app->user->id)->queryAll();
//    echo 'SELECT user_type, if(user_type_id = 9, media_agency_name, "") as media_agency_name '
//                                                . 'FROM user_type JOIN user ON user.user_type_ref_id = user_type.user_type_id '
//                                                . 'LEFT JOIN media_agencies ON media_agencies.media_agency_id = user.media_agency_ref_id '
//                                                . 'WHERE user_type_id = '.Yii::$app->session->get('userType').' AND user.id = '.Yii::$app->user->id;
//    echo "<pre>"; print_r($userType); echo "</pre>"; die;
?>
<div class='participation-border fl-left notifyDiv' style="display:none;">You have successfully sent the mail.</div>    
<div class="bs-example projects_create">
<div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title tabbable-line">
                    <div class="">
                        <i class="icon-globe theme-font hide"></i>
                       <h1 class="box-title"><?php echo Html::encode($this->title) ?></h1>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="<?php echo $allActivate; ?>" onclick="javascript: showHideTabs('1')">
                            <a href="#allActivate" data-toggle="tab">"<?php echo $userType[0]['user_type']; echo !empty($userType[0]['media_agency_name']) ? " - ".$userType[0]['media_agency_name']:''; ?>" <span class="mobile-no-show">Projects</span></a>
                        </li>
                        <li class="<?php echo $initiatedActivate; ?>" onclick="javascript: showHideTabs('2')">
                            <a href="#initiatedActivate" data-toggle="tab"><span class="mobile-no-show">Projects</span> Initiated By Me</a>
                        </li>
                        <li class="<?php echo $participatedActivate; ?>" onclick="javascript: showHideTabs('3')">
                            <a href="#participatedActivate" data-toggle="tab"><span class="mobile-no-show">Projects</span> Participated by Me</a>
                        </li>
                    </ul>
                </div>
                <div class="portlet-body tab-lebal-none">
                    <div class="tab-content">
                        <div class="tab-pane active" id="allActivate">
                             <?php echo Yii::$app->controller->renderPartial('displayAllProjects', ['model' => $model, 'dataProvider' => $dataProvider[0], 'categories' => $categories, 'projectTypes' => $projectTypes, 'projectStatus' => $projectStatus]); ?>
                        </div>
                         <div class="tab-pane " id="initiatedActivate" style="display: none;">
                              <?php echo Yii::$app->controller->renderPartial('displayInitiatedProjects', ['model' => $model, 'dataProvider' => $dataProvider[1], 'categories' => $categories, 'projectTypes' => $projectTypes, 'projectStatus' => $projectStatus]); ?>
                        </div>
                        <div class="tab-pane" id="participatedActivate" style="display: none;">
                             <?php echo Yii::$app->controller->renderPartial('displayParticipatedProjects', ['model' => $participationModel, 'dataProvider' => $dataProvider[2], 'categories' => $categories, 'projectTypes' => $projectTypes, 'projectStatus' => $projectStatus]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>


<script>
$(function(){

         $('.view').fancybox({type:'ajax',loop: false});
        $('.view').on('click',function(e)
        {
            e.preventDefault();
           // var link=$(this).attr('href');
           
            //$('.view').removeClass('fancy_box');
             //$("a[rel=fancybox]").fancybox({type:'ajax','height':'400','width':'500'});
        });
    });
    
    function showHideTabs(tabID) {
        if(tabID === '1') {
            $('#allActivate').show();
            $('#initiatedActivate').hide();
            $('#participatedActivate').hide();
        } else if(tabID === '2') {
            $('#allActivate').hide();
            $('#initiatedActivate').show();
            $('#participatedActivate').hide();
        } else if(tabID === '3') {
            $('#allActivate').hide();
            $('#initiatedActivate').hide();
            $('#participatedActivate').show();
        } 
    }
   </script>
   

<style>
    a.fancybox-nav.fancybox-prev, a.fancybox-nav.fancybox-next{display:none;}
    .fancybox-opened{width:700px}
    .fancybox-inner{width: 100% !important;overflow-x: hidden !important;}
</style>