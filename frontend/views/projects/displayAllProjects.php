<?php
    use yii\grid\GridView;
    use yii\data\SqlDataProvider;
    use yii\helpers\Html;
    use frontend\models\Projects;
    use yii\helpers\Url;
    use yii\widgets\Pjax;
    use yii\widgets\ActiveForm;
    use yii\jui\DatePicker;
	$dateformat = Yii::getAlias('@phpdatepickerformat');
	$phpdateformat = Yii::getAlias('@phpdateformat');
?>
<style>
    .help-block {
        display: none;
    }
	.bs-example span.glyphicon.glyphicon-ok{ color:#797979 !important; background:#eaeaea !important; }
	.bs-example span.glyphicon.glyphicon-ok:hover{ color:#fff !important; background:#36c6d3 !important; }
</style>
<!--style="width: 100%; float: left;"-->
<div  class="searchBox row">
<div class="pull-left col-sm-11 rgt-padding0 colsm11-12">
    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->field($model, 'project_title', ['options' => ['class' => 'clsDropdown col-sm-2 col-xs-12 allProjects']])->textInput(array('placeholder' => 'Project Title'))->label(false) ?>
    <?php echo $form->field($model, 'project_category_ref_id', ['options' => ['class' => 'clsDropdown col-sm-2 col-xs-12 allProjects']])->dropDownList($categories, ['prompt' => 'Category'])->label(false) ?>
    <?php echo $form->field($model, 'project_type_ref_id', ['options' => ['class' => 'clsDropdown col-sm-2 col-xs-12 allProjects']])->dropDownList($projectTypes, ['prompt' => 'Project Type'])->label(false) ?>
    <?php echo $form->field($model, 'project_status', ['options' => ['class' => 'clsDropdown col-sm-2 col-xs-12 allProjects']])->dropDownList($projectStatus, ['prompt' => 'Status'])->label(false) ?>
    <div class="col-sm-2 col-xs-12 custom-calendar">
        <?php echo $form->field($model, 'from_date')->widget(DatePicker::classname(), [
                        'value'  => @$value, 'dateFormat' => $dateformat, 'value' => date('Y-m-d'), 'options' => ['class' => 'clsDropdown col-sm-3 col-xs-12'],
                        'clientOptions' => [
						
                            'changeMonth' => true,
                            'yearRange' => "2000:2070",
                            'changeYear' => true,
                            'showOn' => 'button',
                            'buttonImage' => 'images/calendar.gif',
                            'buttonImageOnly' => true,
                            'buttonText' => 'Select date',
                            'buttonImage' => Yii::$app->request->BaseUrl.'/../images/calendar.gif',   
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
    <div class="col-sm-2 col-xs-12 custom-calendar">
        <?php echo $form->field($model, 'to_date')->widget(DatePicker::classname(), [
                        'value'  => @$value, 'dateFormat' => $dateformat, 'value' => date('Y-m-d'), 'options' => ['class' => 'clsDropdown col-sm-3 col-xs-12'],
                        'clientOptions' => [
                            'changeMonth' => true,
                            'yearRange' => "2000:2070",
                            'changeYear' => true,
                            'showOn' => 'button',
                            'buttonImage' => 'images/calendar.gif',
                            'buttonImageOnly' => true,
                            'buttonText' => 'Select date',
                            'buttonImage' => Yii::$app->request->BaseUrl.'/../images/calendar.gif',                        
                        ],
                ])->textInput(['readonly' => true]);
        ?>
    </div>
    </div>
    
    <div class="pull-right col-sm-1 btn-sbrt">
    <div class="col-sm-6 col-xs-12 searchBtn" style="padding:0;">
        <?php echo Html::submitButton('<i class="fa fa-search ic-srch"></i>' , ['class' => 'btn btn-success', 'id' => 'btnAllSearch']) ?>
        <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>/projects/index" id="searchUrl">
    </div>
    <div class="col-sm-6 col-xs-12 searchBtn" style="padding:0;">
        <?php echo Html::submitButton('<i class="fa fa-refresh"></i>' , ['class' => 'btn btn-success res-bnt', 'id' => 'btnReset']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
    Pjax::begin([
                'id' => 'pjax-list',
                'timeout' => false,
                'enablePushState'=>false
            ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
         'options' =>['class'=>'table-responsive'],
        'rowOptions' => function ($model, $index, $widget, $grid){
            if($model['projectParticipationId'] == $model['project_id']) {
                return ['class' => 'red'];
            } if($model['user_ref_id'] == Yii::$app->user->id){
                return ['class' => 'green'];
            }
          },
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
               // 'format' =>  ['date', 'php:'.$phpdateformat],
            ],
            [
                'attribute' => 'project_end_date',
                'label' => 'End Date',
                //'format' =>  ['date', 'php:'.$phpdateformat],
            ],
            //'Status',
            [
                'attribute' => 'status_name',
                'label' => 'Status',
                /* 'value' => function ($data) {
                    if($data['project_type_ref_id'] == 2 && $data['status_name'] == 1)
                        $status = 'Active';
                    elseif($data['project_type_ref_id'] == 2 && empty($data['status_name']))
                        $status = 'Pending';
                    else
                        $status = $data['status_name'];
                    
                    return $status;
                    return (empty($data['Status'])) ? 'Pending' : ($data['Status'] == 1) ? 'Active' : $data['Status'];
                } */
            ],
            [
                'attribute' => 'created_date',
                'label' => 'Created Date',
                'format' =>  ['date', 'php:'.$phpdateformat],
            ],
            
           ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'buttons'=>[
                            'view'=>function($url,$model) { 
                              return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                      'title' => Yii::t('yii', 'view'),
                                  'class'=>'view',
                                  'rel'=>'fancybox'
                              ]);                                

                            }
                          ],
                'urlCreator' => function ($action, $model, $key, $index) {
                        return Url::toRoute([$action, 'id' => $model['project_id'], 'actionId' => 'view']);
                } 
            ],
                    
             [
                'format'=>'raw',
                'header'=>'<div class="vert-text">Express <br> Your <br> Interest</div>',
                'value' => function($data){
                if ($data['project_status'] == 1 && strtotime($data['project_end_date']) >= strtotime(date('Y-m-d h:i:s'))) {
                    return HTML::a('<span class="glyphicon glyphicon-ok"></span>',null,[
                                        'title' => Yii::t('yii', 'Express your interest'),
                                        'class' => 'ajaxprjparticipate', 
                                        'participanturl' => Yii::$app->urlManager->createAbsoluteUrl('project-participation/create?id='.$data['project_id']), 
                                        'pid' => $data['project_id'],
                                        'uid' => Yii::$app->user->id,
                                        'pjax-container' => 'pjax-list',
                                        ]);
                }else{
                    return '';
                }
                    //return Html::a($data['project_title'], $url, ['title' => $data['project_title']]);
                }
            ],

          /*  ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{update}',
                'buttons'=>[
                            'update' => function ($url, $model) { 

                              return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                      'title' => Yii::t('yii', 'update'),
                              ]);                                

                            },
                            'view'=>function($url,$model) { 
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
                            'title' => Yii::t('yii', 'Add Co-owner'),
                        ]);                                

                }
            ],
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'addCoowner') {
                    return Url::toRoute(['project-co-owners/index', 'id' => $model['project_id']]);
                } else {
                    return Url::toRoute([$action, 'id' => $model['project_id']]);
                }
            }],*/
        ],
    ]);
    Pjax::end();
?>

<script>
    $(function(){
        $("#projects-from_date").attr("placeholder", "From Date");
        $("#projects-to_date").attr("placeholder", "To Date");
        
        $('#btnAllSearch').on('click', function (e) {
            var searchUrl = $('#searchUrl').val();
            var pjaxContainer = 'pjax-list' ;
            var projectTitle = $('.allProjects #projects-project_title').val();
            var projectCategory = $('.allProjects #projects-project_category_ref_id').val();
            var projectType = $('.allProjects #projects-project_type_ref_id').val();
            var projectStatus = ($('.allProjects #projects-project_status').val() > 0) ? $('.allProjects #projects-project_status').val() : "";
            var from = $('#projects-from_date').val();
            var to = $('#projects-to_date').val();
            var pjaxReloadURL = searchUrl+'?title='+projectTitle+'&cat='+projectCategory+'&type='+projectType+'&status='+projectStatus+'&from='+from+'&to='+to;

             $.ajax({
                url:   searchUrl,
                type: 'get',
                data: {'title': projectTitle, 'cat': projectCategory, 'type': projectType, 'status': projectStatus, 'from': from, 'to': to},
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
                        $('#projects-project_title').val('');
                        $('#projects-project_category_ref_id').val('');
                        $('#projects-project_type_ref_id').val('');
                        $('#projects-project_status').val('');
                        $('#projects-from_date').datepicker('setDate', null);
                        $('#projects-to_date').datepicker('setDate', null);
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
    });
    var purl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('projects/get-participant-details'); ?>';
    function submitparticipant(){
        window.location.href=participantUrl;
    }
</script>
<div id="dataConfirmModal" class="confirm-box" style="display:none;">
    <h3 id="dataConfirmLabel" >You have already participated in the project.</br>Do you want to continue?</h3>   
    <div style="text-align:right;margin-top:10px;">
        <input class="dataConfirmCancel btn btn-secondary" onclick="$('#dataConfirmModal').css('display','none');" type="button" value="Cancel">
        <input class="dataConfirmOK btn btn-primary" onclick="submitparticipant()" type="button" value="Ok">
    </div>
</div>


<?php


$this->registerJs(" $(document).on('ready pjax:success', function () {  var deleteUrl; 
  $('.ajaxprjparticipate').on('click', function (e) {

var pid;  var uid;
  
  pid     = $(this).attr('pid');
  uid     = $(this).attr('uid');
  participantUrl     = $(this).attr('participanturl');
                   $.ajax({
                url: purl,
                type: 'post',
                data: {
                    pid:pid,uid:uid
			 },
                success: function(data) {
                    if(data>0){
                        $('#dataConfirmLabel').text($(this).attr('data-confirm'));
                        $('#dataConfirmModal').css('display','block');
                    }else{
                    window.location.href=participantUrl;
                    }
                }
            });
   
    return false;
 
});

    $(document).on('pjax:timeout', function(event) {
      // Prevent default timeout redirection behavior
      event.preventDefault()
    });
}); 

");

?>