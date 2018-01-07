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
<div class="searchBox row">
<div class="pull-left col-sm-11 rgt-padding0 colsm11-12">
    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->field($model, 'project_title_initiated', ['options' => ['class' => 'clsDropdown col-sm-2 col-xs-12 initiatedProjects']])->textInput(array('placeholder' => 'Project Title'))->label(false) ?>
    <?php echo $form->field($model, 'category_initiated', ['options' => ['class' => 'clsDropdown col-sm-2 col-xs-12 initiatedProjects']])->dropDownList($categories, ['prompt' => 'Category'])->label(false) ?>
    <?php echo $form->field($model, 'type_initiated', ['options' => ['class' => 'clsDropdown col-sm-2 col-xs-12 initiatedProjects']])->dropDownList($projectTypes, ['prompt' => 'Project Type'])->label(false) ?>
    <?php echo $form->field($model, 'status_initiated', ['options' => ['class' => 'clsDropdown col-sm-2 col-xs-12 initiatedProjects']])->dropDownList($projectStatus, ['prompt' => 'Status'])->label(false) ?>
    <div class="col-sm-2 col-xs-12 custom-calendar">
        <?php echo $form->field($model, 'from_date_initiated')->widget(DatePicker::classname(), [
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
                                $('#projects-to_date_initiated').val('');  
                                var toDate = $(this).datepicker('getDate');
                                var fromDate = $(this).datepicker('getDate');
                                fromDate.setDate(toDate.getDate()+1);
                                $('#projects-to_date_initiated').datepicker('option', 'minDate', fromDate); 
                                }"
                            ),
                        ],
                ])->textInput(['readonly' => true])->label('From Date');
        ?>
    </div>
    <div class="col-sm-2 col-xs-12 custom-calendar">
        <?php echo $form->field($model, 'to_date_initiated')->widget(DatePicker::classname(), [
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
                ])->textInput(['readonly' => true])->label('To Date');
        ?>
        
        <?php /*echo $form->field($model, 'to_date_initiated')->widget(DatePicker::className(),
                    [
                        'options' => ['class' => 'form-control clsDropdown col-sm-3 col-xs-12'],
                        'clientOptions' =>[
                            'dateFormat' => 'dd-mm-yyyy',
                            'changeMonth'=> true,
                            'changeYear'=> true,
                            'autoSize'=>true,
                'yearRange'=>'1900:'.(date('Y')+1),
                            'showOn'=> "button",
                            'buttonImage'=> Yii::$app->request->BaseUrl.'/../images/calendar.gif',
                            ]])->label(false) */ ?>
    </div>
    </div>
    
    <div class="pull-right col-sm-1 btn-sbrt">
    <div class="col-sm-6 col-xs-12 searchBtn" style="padding:0;">
        <?php echo Html::submitButton('<i class="fa fa-search ic-srch"></i>' , ['class' => 'btn btn-success', 'id' => 'btnInitiatedSearch']) ?>
        <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>/projects/index" id="searchUrl_initiated">
    </div>
    <div class="col-sm-6 col-xs-12 searchBtn" style="padding:0;">
        <?php echo Html::submitButton('<i class="fa fa-refresh"></i>' , ['class' => 'btn btn-success res-bnt', 'id' => 'btnInitiatedReset']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
    Pjax::begin([
                'id' => 'pjax-list-initiated',
                'timeout' => false,
                'enablePushState'=>false
            ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
          'options' =>['class'=>'table-responsive'],
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
               // 'format' =>  ['date', 'php'.$phpdateformat],
             ],
            [
                'attribute' => 'status_name',
                'label' => 'Status',
             ],
             [
               'attribute' => 'project_co_owner_id',
               'label' => 'Owner Type',
               'value' => function ($data) {
                 return ($data['project_co_owner_id'] > 0) ? 'Co-owner' : 'Owner';
               }
             ],
            [
                'attribute' => 'created_date',
                'label' => 'Created Date',
                'format' =>  ['date', 'php:'.$phpdateformat],
            ],
                     
            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Add co-owner',
            'template'=>'{addCoowner}',
            'buttons'=>[
            'addCoowner' => function ($url, $model) {  
                if ($model['status_name']== 'Active') {
                    return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
                                'title' => Yii::t('yii', 'Add Co-owner'),
                            ]);                                

                    }
                    else{
                        return '';
                    }
                }
            ],
            'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::toRoute(['project-co-owners/index', 'id' => $model['project_id']]);
            }],

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{update}',
                'buttons'=>[
                            'update' => function ($url, $model) {  
                                if ($model['status_name']== 'Pending') {
                                  return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                          'title' => Yii::t('yii', 'update'),
                                  ]); 
                                }else{
                                    return '';
                                }
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
                        return Url::toRoute([$action, 'id' => $model['project_id'], 'actionId' => 'view']);
                    }
                } 
            ]            
        ],
    ]);
    Pjax::end();
?>
    
    <?php
$this->registerJs(" $(document).on('ready pjax:success', function () {
    $(document).on('pjax:timeout', function(event) {
      // Prevent default timeout redirection behavior
      event.preventDefault()
    });
}); 

");




?>

<script>
    $(function(){
        $("#projects-from_date_initiated").attr("placeholder", "From Date");
        $("#projects-to_date_initiated").attr("placeholder", "To Date");
        
        $('#btnInitiatedSearch').on('click', function (e) {
            var searchUrl_initiated = $('#searchUrl_initiated').val();
            var pjaxContainer_initiated = 'pjax-list-initiated' ;
            var projectTitle_initiated = $('.initiatedProjects #projects-project_title_initiated').val();
            var projectCategory_initiated = $('.initiatedProjects #projects-category_initiated').val();
            var projectType_initiated = $('.initiatedProjects #projects-type_initiated').val();
            var projectStatus_initiated = $('.initiatedProjects #projects-status_initiated').val();
            var from_initiated = $('#projects-from_date_initiated').val();
            var to_initiated = $('#projects-to_date_initiated').val();
            var pjaxReloadURL_initiated = searchUrl_initiated+'?title='+projectTitle_initiated+'&cat='+projectCategory_initiated+'&type='+projectType_initiated+'&status='+projectStatus_initiated+'&from='+from_initiated+'&to='+to_initiated+'&initiated=initiated';

             $.ajax({
                url:   searchUrl_initiated,
                type: 'get',
                data: {'title': projectTitle_initiated, 'cat': projectCategory_initiated, 'type': projectType_initiated, 'status': projectStatus_initiated, 'from': from_initiated, 'to': to_initiated, 'initiated': 'initiated'},
                success: function(data){
                    if(data){ 
                        //$.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer, )});
                        $.pjax.reload({url: pjaxReloadURL_initiated, container: '#' + $.trim(pjaxContainer_initiated), async:false, reload: true,});
                        return false;
                    }
                },
                error: function (xhr, status, error) {
                   alert('There was an error with your request.' + xhr.responseText);
                 }
             }); 
             return false;
        });
        
        $('#btnInitiatedReset').on('click', function (e) {
            var searchUrl_initiated = $('#searchUrl_initiated').val();
            var pjaxContainer = 'pjax-list-initiated' ;
            var pjaxReloadURL = searchUrl_initiated;

             $.ajax({
                url:   searchUrl_initiated,
                type: 'get',
                //data: {'title': projectTitle, 'cat': projectCategory, 'type': projectType, 'status': projectStatus, 'from': from, 'to': to},
                success: function(data){
                    if(data){ 
                        $('#projects-project_title_initiated').val('');
                        $('#projects-category_initiated').val('');
                        $('#projects-type_initiated').val('');
                        $('#projects-status_initiated').val('');
                        $('#projects-from_date_initiated').datepicker('setDate', null);
                        $('#projects-to_date_initiated').datepicker('setDate', null);
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
</script>