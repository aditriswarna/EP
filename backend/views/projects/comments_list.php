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
	@media (max-width: 770px){
		.prj-fiter1{ padding:0;}
.btn-sbrt .searchBtn { padding-left:0;}
div#pjax-list{    margin: 0 auto;    float: left;    width: 100%;}
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
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Project Comments';
$this->params['breadcrumbs'][] = $this->title;
$dateformat = Yii::getAlias('@phpdatepickerformat');
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
//
//function loadPjaxComments() {
//    var pjaxReloadURL = $("#commentsUrl").val();
//    var pjaxContainer = "pjax-list";
//    $.pjax.reload({url: pjaxReloadURL, container: "#" + $.trim(pjaxContainer), async:false});
//}
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
    <div class="portlet-body">
    <div class="tab-content">
        <div id="suggestedProjects" class="tab-pane fade in <?php echo $suggestedActivate; ?>">
            <div style="width: 100%; float: left;" class="searchBox">
            <div class="col-sm-12 p-left0">
                <?php $form = ActiveForm::begin(); ?>
                <?php echo $form->field($model, 'project_category_ref_id', ['options' => ['class' => 'col-sm-2 col-xs-12 prj-fiter']])->dropDownList($categories, ['prompt' => 'All Categories'])->label('Project Category') ?>
                <?php //echo $form->field($model, 'project_type_ref_id', ['options' => ['class' => 'col-sm-3 col-xs-12']])->dropDownList($projectTypes, ['prompt' => 'All']) ?>
                <?php echo $form->field($model, 'status', ['options' => ['class' => 'col-sm-2 col-xs-12 prj-fiter']])->dropDownList($status, ['prompt' => 'Status']) ?>
                <div class="col-sm-2 col-xs-12 custom-calendar prj-fiter">
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
                                            $('#projectcomments-to_date').val('');  
                                            var toDate = $(this).datepicker('getDate');
                                            var fromDate = $(this).datepicker('getDate');
                                            fromDate.setDate(toDate.getDate()+1);                                
                                            $('#projectcomments-to_date').datepicker('option', 'minDate', fromDate); 
                                            }"
                                        ),
                                    ],
                            ])->textInput(['readonly' => true]);
                    ?>
                </div>
                <div class="col-sm-2 col-xs-12 custom-calendar prj-fiter">
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
                </div>
                <div class="col-sm-2 btn-sbrt prj-fiter1 ">
       <div class="col-sm-6 col-xs-12 searchBtn">
                    <?php echo Html::submitButton('<i class="icon-magnifier"></i>' , ['class' => 'btn btn-success', 'id' => 'btnSearch']) ?>
                    <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>/projects/commentslist" id="searchUrl">
                </div>
                <div class="col-sm-6 col-xs-12 searchBtn" style="padding:0;">
                    <?php echo Html::submitButton('<i class="fa fa-refresh"></i>' , ['class' => 'btn btn-success res-bnt', 'id' => 'btnReset']) ?>
                </div>
    </div>
                <?php ActiveForm::end(); ?>
                
            </div>
            
            </div>
            <p>
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
                        
                        'project_title',
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
                            'attribute' => 'comments',
                            'label' => 'Comments',
                            'value' => function($data){
                                return stripslashes($data['comments']);
                            },
                        ],
                        'created_date',
                        [
                            'attribute' => 'status_name',
                            'label' => 'Status',
                            'value' => function($data){
                                return ($data['status_name'] == 'Decline') ? "Rejected" : (($data['status_name'] == 'Accept') ? "Accepted" : $data['status_name']);
                            },
                        ],
                        /*['class' => 'yii\grid\ActionColumn',
                            'header'=>'Comment Status',
                            'template'=>'{active}',
                            'buttons'=>[
                                'active'=>function($url, $model) {
                                    if (@$model['status'] == 7) {
                                        return HTML::a('<span class="glyphicon glyphicon-ok-sign"></span>',$url,[
                                            'title' => Yii::t('yii', 'Click To Reject'),
                                            'class' => 'ajaxDelete', 
                                            'delete-url' => $url, 
                                            'pjax-container' => 'pjax-list',
                                            'data-confirm'=>'Are you sure you want to reject this project comment?',
                                            ]);
                                    } else if(@$model['status'] == 8) {
                                        return HTML::a('<span class="glyphicon glyphicon-remove-sign"></span>',$url,[
                                            'title' => Yii::t('yii', 'Click To Approve'),
                                            'class' => 'ajaxDelete', 
                                            'delete-url' => $url, 
                                            'pjax-container' => 'pjax-list',
                                            'data-confirm'=>'Are you sure you want to approve this project comment?',
                                            ]);
                                    } else {
                                        return '<div title="Project is not approved yet"><span class="glyphicon glyphicon-info-sign" ></span><div>';
                                    }

                                },


                              ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                if ($action === 'active') {
                                    return Url::toRoute(['home-page-display', 'id' => $model['project_id'], 'status'=>@$model['display_in_home_page']]);
                                } 
                            } 
                        ],*/
                                
//                        [
//                            'attribute' => 'display_in_home_page',
//                            'label' => 'Home Display',
//							
//                        ], 
                        ['class' => 'yii\grid\ActionColumn',
                            'options' => ['width' => '85'],
                            'template'=>'{view}',
                            'buttons'=>[
                                        'view'=>function ($url, $model) {     
                                          return $model['status'] == 2 ? Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                                'title' => Yii::t('yii', 'Accept'),
                                                'class'=>'ajaxAccept',
                                                'accept-url' => $url, 
                                                'pjax-container' => 'pjax-list',
                                                'rel'=>'fancybox'
                                          ]):'';
                                        }
                                      ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                return Url::toRoute(['updatecommentstatus', 'id' => $model['project_comment_id'], 'status' => $model['status']]);
                            } 
                        ],
                    ],
                ]);
               \yii\widgets\Pjax::end();
            ?>
            </p>
        </div>
    </div>
    </div>
    </div>
</div>
<input type="hidden" value="" id="updateUrl">
<input type="hidden" value="" id="ajaxContainer">
<?php


$this->registerJs(" $(document).on('ready pjax:success', function () {  var deleteUrl; 
  $('.ajaxAccept').on('click', function (e) {
    e.preventDefault();
    acceptUrl     = $(this).attr('accept-url');
    $('#updateUrl').val(acceptUrl);
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
    <h3 id="dataConfirmLabel">Update Comment Status</h3>
    <div style="text-align:right;margin-top:10px;">
        <input class="dataConfirmCancel btn btn-secondary" style="background-color: #F1C40F; border-color: #dab10d;" onclick="$('#dataConfirmModal').css('display','none');" type="button" value="Cancel">
        <input class="dataConfirmOK btn btn-primary" onclick="updateStatus('7')" type="button" value="Accept">
        <input class="dataConfirmOK btn btn-danger" style="margin-bottom: 25px!important; margin-top: 15px!important; border-radius: 3px !important;" onclick="updateStatus('8')" type="button" value="Reject">
    </div>
</div>

<script>

function updateStatus(status){
    var acceptUrl = $('#updateUrl').val();
    var pjaxContainer = $('#ajaxContainer').val();

     $.ajax({
     url:   acceptUrl,
     type: 'get',
     data: {'status': status},
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
 $('.view').fancybox({type:'ajax',loop: false});
    $('.view').on('click',function(e)
    {
     e.preventDefault();
     

    });    
    
    
    $("#projectcomments-from_date").attr("placeholder", "From Date");
    $("#projectcomments-to_date").attr("placeholder", "To Date");
    
    $('#btnSearch').on('click', function (e) {
        var searchUrl = $('#searchUrl').val();
        var pjaxContainer = 'pjax-list' ;
        var projectCategory = $('#projectcomments-project_category_ref_id').val();
        var projectType = $('#projectcomments-project_type_ref_id').val();
        var projectStatus = $('#projectcomments-status').val();
        var from = $('#projectcomments-from_date').val();
        var to = $('#projectcomments-to_date').val();
        var pjaxReloadURL = searchUrl+'?cat='+projectCategory+'&type='+projectType+'&status='+projectStatus+'&from='+from+'&to='+to;

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
                    $('#projectcomments-project_category_ref_id').val('');
                    $('#projectcomments-project_type_ref_id').val('');
                    $('#projectcomments-status').val('');
                    $('#projectcomments-from_date').datepicker('setDate', null);
                    $('#projectcomments-to_date').datepicker('setDate', null);
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