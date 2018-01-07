<style>
    .notifyDiv{
      background-color: #B5EBE0;  
    }
    .clsDropdown {
        float: left;
        margin-right: 20px;
        width: 20%;
    }
    .searchBox .form-group {
        margin: 0px;
    }
    .empty{
        text-align: center;
    }
</style>
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use frontend\models\Projects;
use frontend\models\ProjectCategory;
use frontend\models\ProjectParticipation;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;
$dateformat = Yii::getAlias('@phpdatepickerformat');
$phpdateformat = Yii::getAlias('@phpdateformat');
/*
$query = (new \yii\db\Query())
    ->select('user_id, username')
    ->from('user')
//	->joinwith('project_category')
    ->limit(10);

// Create a command. You can get the actual SQL using $command->sql
$command = $query->createCommand();

// Execute the command:
$rows = $command->queryAll();
print_r($rows);
*/

?>
<div class="projects-index">

      <div class="row head-bar">
      <div> <?php echo Html::a('Create New Project', ['create'], ['class' => 'btn btn-success']) ?></div>
      <h1 class="box-title"><?php echo Html::encode($this->title) ?></h1>
      </div>
    
    
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">-->
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!--<script src="<?php //echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/../views/jQueryFileUpload/js/jquery.min.js"></script>-->

<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>-->
<script type="text/javascript">
$(document).ready(function(){ 
    $("#myTab a").click(function(e){
    	e.preventDefault();
    	$(this).tab('show');
    });
});
</script>

<?php //print_r($data); echo  "project_category_ref_id". $model->project_category_ref_id; 
    //echo Projects::getProjectCategoryRef(4);

    if(Yii::$app->getRequest()->getQueryParam('id')) {        
        $participatedActivate = 'in active';
        $initiatedActivate = '';
        $suggestedActivate = '';
    } else {
        $participatedActivate = '';
        $initiatedActivate = '';
        $suggestedActivate = 'active';
    }
?>
<div class="bs-example projects_create">
<div class="portlet light ">
    <div class="portlet-title tabbable-line">
    <ul class="nav nav-tabs" id="myTab">
        <li class="<?php echo $suggestedActivate; ?>"><a href="#suggestedProjects">All Projects</a></li>
<!--        <li class="<?php echo $initiatedActivate; ?>"><a href="#initiated">Projects initiated by Me</a></li>
        <li class="<?php echo $participatedActivate; ?>"><a href="#participated">Projects Participated by Me</a></li>-->
    </ul>
    </div>
    <div class="portlet-body tab-lebal-none tab-body-project">
    <div class="tab-content">
        <div id="suggestedProjects" class="tab-pane fade in <?php echo $suggestedActivate; ?>">
            <div class="searchBox row">
            <div class="pull-left col-sm-11 rgt-padding0">
                <?php $form = ActiveForm::begin(); ?>
                <?php echo $form->field($model, 'project_title', ['options' => ['class' => 'col-sm-2 col-xs-12 allProjects p-left0 prj-fiter']])->textInput(array('placeholder' => 'Project Title'))->label(false) ?>
                <?php echo $form->field($model, 'project_category_ref_id', ['options' => ['class' => 'col-sm-2 col-xs-12 p-left0 prj-fiter']])->dropDownList($categories, ['prompt' => 'Category']) ?>
                <?php echo $form->field($model, 'project_type_ref_id', ['options' => ['class' => 'col-sm-2 col-xs-12 p-left0 prj-fiter']])->dropDownList($projectTypes, ['prompt' => 'Project Type']) ?>
                <?php echo $form->field($model, 'Status', ['options' => ['class' => 'col-sm-2 col-xs-12 p-left0 prj-fiter']])->dropDownList($projectStatus, ['prompt' => 'Status']) ?>
                <div class="col-sm-2 col-xs-12 p-left0 custom-calendar prj-fiter">
                    <?php echo $form->field($model, 'from_date')->widget(DatePicker::classname(), [
                                    'value'  => @$value, 'dateFormat' => $dateformat, 'value' => date('Y-m-d'), 'options' => ['class' => 'col-sm-3 col-xs-12'],
                                    'clientOptions' => [
                                        'changeMonth' => true,
                                        'yearRange' => "2000:2070",
                                        'changeYear' => true,
                                        'showOn' => 'button',
                                        'buttonImage' => 'images/calendar.gif',
                                        'buttonImageOnly' => true,
                                        'buttonText' => 'Select date',
                                        'buttonImage' => Yii::$app->request->BaseUrl.'/images/calendar.gif',
                                        'onSelect' => new \yii\web\JsExpression("function(dateStr) {
                                            $('#projects-to_date').val('');  
                                            var toDate = $(this).datepicker('getDate');
                                            var fromDate = $(this).datepicker('getDate');
                                            fromDate.setDate(toDate.getDate()+1);                                
                                            $('#projects-to_date').datepicker('option', 'minDate', fromDate); 
                                            }"
                                        ),
                                    ],
                            ])->textInput(['readonly' => true]);
                    ?>
                </div>
                <div class="col-sm-2 col-xs-12 p-left0 custom-calendar prj-fiter">
                    <?php echo $form->field($model, 'to_date')->widget(DatePicker::classname(), [
                                    'value'  => @$value, 'dateFormat' => $dateformat, 'value' => date('Y-m-d'), 'options' => ['class' => 'col-sm-3 col-xs-12'],
                                    'clientOptions' => [
                                        'changeMonth' => true,
                                        'yearRange' => "2000:2070",
                                        'changeYear' => true,
                                        'showOn' => 'button',
                                        'buttonImage' => 'images/calendar.gif',
                                        'buttonImageOnly' => true,
                                        'buttonText' => 'Select date',
                                        'buttonImage' => Yii::$app->request->BaseUrl.'/images/calendar.gif',                        
                                    ],
                            ])->textInput(['readonly' => true]);
                    ?>
                </div></div>
                <div class="pull-right col-sm-1 btn-sbrt prj-fiter1">
                <div class="col-sm-6 col-xs-12 searchBtn p-left0">
                    <?php echo Html::submitButton('<i class="icon-magnifier"></i>' , ['class' => 'btn btn-success', 'id' => 'btnSearch']) ?>
                    <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>/projects/index" id="searchUrl">
                </div>
                <div class="col-sm-6 col-xs-12 searchBtn p-left0" style="padding:0;">
                    <?php echo Html::submitButton('<i class="fa fa-refresh"></i>' , ['class' => 'btn btn-success res-bnt', 'id' => 'btnReset']) ?>
                </div></div>
                
                <?php ActiveForm::end(); ?>
                </div>
          
            <div class='participation-border fl-left notifyDiv' style="display:none;">You have successfully changed the project status.</div>    
            <?php
            \yii\widgets\Pjax::begin([
                'id' => 'pjax-list',
                'timeout' => false,
                'enablePushState'=>false
            ]);
                
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        
						 [
							'format' => 'raw',
                            'attribute' => 'project_title',
							 'options' => ['width' => '150'],
                            'value' => function ($data) {                               
							   if (strlen($data['project_title'])>17) {
                                  return  '<span style="cursor: pointer;" title="'.$data['project_title'].'">'.substr($data['project_title'], 0, 17). '<b>...</b></span>';
                                   }else{
	                                 return '<span style="cursor: pointer;" title="'.$data['project_title'].'">'.$data['project_title']. '</span>';
                                }
                            }
                        ],
                        
                        [
                            'attribute' => 'project_category_ref_id',
                            'label' => 'Category',
                            'value' => function($data){
                                return Projects::getProjectCategoryRef($data['project_category_ref_id']);
                            },
                        ],
                        [
                            'attribute' => 'project_type_ref_id',
                            'label' => 'Type',
                            'value' => function($data){
                                return Projects::getProjectTypeRef($data['project_type_ref_id']);
                            },
                        ],
                        [
							'format' => 'raw',
                            'attribute' => 'location',
                            'value' => function ($data) {                               
							   if (strlen($data['location'])>30) {
                                  return  '<span style="cursor: pointer;" title="'.$data['location'].'">'.substr($data['location'], 0, 30). '<b>...</b></span>';
                                   }else{
	                                 return '<span style="cursor: pointer;" title="'.$data['location'].'">'.$data['location']. '</span>';
                                }
                            }
                        ],
                        [
                            'attribute' => 'project_start_date',
                            'label' => 'Start Date',
                            'format' =>  ['date', 'php:'.$phpdateformat],
                            'options' => ['width' => '100']
                        ],
                        [
                            'attribute' => 'project_end_date',
                            'label' => 'End Date',
                            'format' =>  ['date', 'php:'.$phpdateformat],
                            'options' => ['width' => '100']
                        ],
						[
                            'attribute' => 'created_date',
                            'label' => 'Created Date',
                            'format' =>  ['date', 'php:'.$phpdateformat],
                            'options' => ['width' => '100']
                        ],
                       // 'status_name',
                        ['class' => 'yii\grid\ActionColumn',
                            'header'=>'Status',
                            'template'=>'{active}',
                            'buttons'=>[
                                'active'=>function($url, $model) {
                                    if(@$model['project_status']==1)
                                    {
                                     return 
                                     HTML::a('<span class="glyphicon glyphicon-thumbs-up"></span>',$url,[
                                                'title' => Yii::t('yii', 'Approved'),
                                            ]);       
                                    } 
                                    else  if(@$model['project_status']==2)
                                    { 
                                     return 
                                        HTML::a('<span class="glyphicon glyphicon-exclamation-sign"></span>',$url,[
                                                 'title' => Yii::t('yii', 'Pending'),
                                            ]);       
                                    }  
                                    else if(@$model['project_status']==3)
                                    {
                                        return 
                                     HTML::a('<span class="glyphicon glyphicon-ban-circle"></span>',$url,[
                                                'title' => Yii::t('yii', 'rejected'),
                                            ]);   

                                    }
                                    else if(@$model['project_status']==4)
                                    {  
                                      return 
                                      HTML::a('<span class="glyphicon glyphicon-flag"></span>',$url,[
                                                'title' => Yii::t('yii', 'Completed'),
                                            ]);       
                                    }
                                },
                            ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                if ($action === 'active') {
                                    return Url::toRoute(['project-data', 'id' => $model['project_id'], 'status'=>@$model['project_status']]);
                                } 
                            } 
                        ],
//                        [
//                            'attribute' => 'display_in_home_page',
//                            'label' => 'Home Display',
//							
//                        ], 
                        ['class' => 'yii\grid\ActionColumn',
                            'header'=>'Home Display',
                            'template'=>'{active}',
                            'buttons'=>[
                                'active'=>function($url, $model) {
                                    if (@$model['display_in_home_page'] == "Y" && @$model['project_status']==1) {
                                        return HTML::a('<span class="glyphicon glyphicon-ok-sign"></span>',$url,[
                                            'title' => Yii::t('yii', 'deactivate'),
                                            'class' => 'ajaxDelete', 
                                            'delete-url' => $url, 
                                            'pjax-container' => 'pjax-list',
                                            'data-confirm'=>'Are you sure you want to remove this project from home page?',
                                            ]);
                                    }else if(@$model['display_in_home_page'] == "N" && @$model['project_status']==1){
                                        return HTML::a('<span class="glyphicon glyphicon-remove-sign"></span>',$url,[
                                            'title' => Yii::t('yii', 'activate'),
                                            'class' => 'ajaxDelete', 
                                            'delete-url' => $url, 
                                            'pjax-container' => 'pjax-list',
                                            'data-confirm'=>'Are you sure you want to display this project on home page?',
                                            ]);
                                    }else{
                                        return '<div title="Project is not approved yet"><span class="glyphicon glyphicon-info-sign" ></span><div>';
                                    }

                                },


                              ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                if ($action === 'active') {
                                    return Url::toRoute(['home-page-display', 'id' => $model['project_id'], 'status'=>@$model['display_in_home_page']]);
                                } 
                            } 
                        ],                        
                        
                        ['class' => 'yii\grid\ActionColumn',
                            'options' => ['width' => '85'],
                            'template'=>'{view}{update}',
                            'buttons'=>[
                                        'update' => function ($url, $model) {     
                                          return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                                  'title' => Yii::t('yii', 'update'),
                                          ]);                                

                                        },
                                        'view'=>function ($url, $model) {     
                                          return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                                  'title' => Yii::t('yii', 'view'),
                                              'class'=>'view',
                                              'rel'=>'fancybox'
                                          ]);                                

                                        }
                                      ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                if ($action === 'update') {
                                    return Url::toRoute(['update', 'id' => $model['project_id']]);
                                } else {
                                    return Url::toRoute([$action, 'id' => $model['project_id']]);
                                }
                            } 
                        ],

                        ['class' => 'yii\grid\ActionColumn',
                        'template'=>'{addCoowner}',
                        'buttons'=>[
                        'addCoowner' => function ($url, $model) {     
                            return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
                                        'title' => Yii::t('yii', 'Add co owner'),
                                    ]);                                

                            }
                        ],
                        'urlCreator' => function ($action, $model, $key, $index) {
                            if ($action === 'addCoowner') {
                                return Url::toRoute(['project-co-owners/index', 'id' => $model['project_id']]);
                            } else {
                                return Url::toRoute([$action, 'id' => $model['project_id']]);
                            }
                        }],
                         ['class' => 'yii\grid\ActionColumn',
                        'template'=>'{projectParticipation}',
                        'buttons'=>[
                        'projectParticipation' => function ($url, $model) {     
                            return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, [
                                        'title' => Yii::t('yii', 'Express your interest'),
                                    ]);                                

                            }
                        ],
                        'urlCreator' => function ($action, $model, $key, $index) {
                            if ($action === 'projectParticipation') {
                                return Url::toRoute(['project-participation/create', 'id' => $model['project_id']]);
                            } else {
                                return Url::toRoute([$action, 'id' => $model['project_id']]);
                            }
                        }]
                                
                              
                    ],
                ]);
               \yii\widgets\Pjax::end();
            ?>
           
        </div>
    </div>
    </div>
    </div>
</div>
<input type="hidden" value="" id="updateUrl">
<input type="hidden" value="" id="ajaxContainer">
<?php
                $this->registerJs(" $(document).on('ready pjax:success', function () {  var deleteUrl; 
  $('.ajaxDelete').on('click', function (e) {
    e.preventDefault();
    deleteUrl     = $(this).attr('delete-url');
    $('#updateUrl').val(deleteUrl);
    var pjaxContainer = $(this).attr('pjax-container');
    $('#ajaxContainer').val(pjaxContainer);
    
    $('#dataConfirmLabel').text($(this).attr('data-confirm'));
    $('#dataConfirmModal').css('display','block');
   
    return false;
 
});
    $(document).on('pjax:timeout', function(event) {
      // Prevent default timeout redirection behavior
      event.preventDefault()
    });
}); 

");
                ?>

</div>

<div id="dataConfirmModal" class="confirm-box" style="display:none;">
    <h3 id="dataConfirmLabel" >Please Confirm</h3>   
    <div style="text-align:right;margin-top:10px;">
        <input class="dataConfirmCancel btn btn-secondary" onclick="$('#dataConfirmModal').css('display','none');" type="button" value="Cancel">
        <input class="dataConfirmOK btn btn-primary" onclick="updateStatus()" type="button" value="Ok">
    </div>
</div>

<script>

function updateStatus(){
    var deleteUrl = $('#updateUrl').val();
    var pjaxContainer = $('#ajaxContainer').val();

     $.ajax({
     url:   deleteUrl,
     type: 'get',
     success: function(data){
       if(data){ 
           $('#dataConfirmModal').css('display','none');
           $.pjax.reload({container: '#' + $.trim(pjaxContainer)});
           $('.notifyDiv').slideDown('slow',function () {
               $(this).delay(2000).fadeOut(1000);
           });           
       }
     },
     error: function(xhr, status, error) {
     //  alert('There was an error with your request.' + xhr.responseText);
     }
     }); 
     return false;
 }    

$(function(){
    $("#projects-from_date").attr("placeholder", "From Date");
    $("#projects-to_date").attr("placeholder", "To Date");
        
    $('.view').fancybox({type:'ajax',loop: false});
    $('.view').on('click',function(e)
    {
        e.preventDefault();
    });    
    
  
  $('#btnSearch').on('click', function (e) {
        var searchUrl = $('#searchUrl').val();
        var pjaxContainer = 'pjax-list' ;
        var projectTitle = $('#projects-project_title').val();
        var projectCategory = $('#projects-project_category_ref_id').val();
        var projectType = $('#projects-project_type_ref_id').val();
        var projectStatus = $('#projects-status').val();
        var from = $('#projects-from_date').val();
        var to = $('#projects-to_date').val();
        var pjaxReloadURL = searchUrl+'?title='+projectTitle+'&cat='+projectCategory+'&type='+projectType+'&status='+projectStatus+'&from='+from+'&to='+to;

         $.ajax({
            url:   searchUrl,
            type: 'get',
            data: {'cat': projectCategory, 'type': projectType, 'status': projectStatus, 'from': from, 'to': to},
            success: function(data){
                if(data){ 
                    //$.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer, )});
                    $.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer), async:false});
                    return false;
                }
            },
            error: function (xhr, status, error) {
               alert('There was an error with your request.' + xhr.responseText);
             }
         }); 
         return false;
    });
    
    $('#btnReset').on('click', function (e) {
        var searchUrl = $('#searchUrl').val();
        var pjaxContainer = 'pjax-list' ;
        var pjaxReloadURL = searchUrl;

         $.ajax({
            url:   searchUrl,
            type: 'get',
            //data: {'title': projectTitle, 'cat': projectCategory, 'type': projectType, 'status': projectStatus, 'from': from, 'to': to},
            success: function(data){
                if(data){ 
                    //$.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer, )});
                    $('#projects-project_title').val('');
                    $('#projects-project_category_ref_id').val('');
                    $('#projects-project_type_ref_id').val('');
                    $('#projects-status').val('');
                    $('#projects-from_date').datepicker('setDate', null);
                    $('#projects-to_date').datepicker('setDate', null);
                    $.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer), async:false});
                    return false;
                }
            },
            error: function (xhr, status, error) {
               alert('There was an error with your request.' + xhr.responseText);
             }
         }); 
         return false;
    });
  
});
    
</script>
<style>
    a.fancybox-nav.fancybox-prev, a.fancybox-nav.fancybox-next{display:none;}
    .fancybox-opened{width:700px !important;}
    .fancybox-inner{width: 100% !important;overflow-x: hidden !important;}
</style>