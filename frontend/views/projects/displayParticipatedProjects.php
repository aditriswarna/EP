<?php
    use yii\widgets\ListView;
    use yii\widgets\Pjax;
    use yii\widgets\ActiveForm;
    use yii\jui\DatePicker;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\grid\GridView;
    use frontend\models\Projects;
	$dateformat = Yii::getAlias('@phpdatepickerformat');
	$phpdateformat = Yii::getAlias('@phpdateformat');
?>
<div class="searchBox row">
<div class="pull-left col-sm-12 rgt-padding0">
    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->field($model, 'participation', ['options' => ['class' => 'clsDropdown col-sm-2 col-xs-12']])->dropDownList(array('Invest' => 'Cash', 'Support' => 'Kind'), ['prompt' => 'Participation'])->label(false) ?>
    <?php echo $form->field($model, 'investment_type', ['options' => ['class' => 'col-sm-2 col-xs-12']])->dropDownList(array('Equity' => 'Equity', 'Grant' => 'Grant'), ['prompt' => 'Investment Type']) ?>
    <?php echo $form->field($model, 'equity_type', ['options' => ['class' => 'col-sm-2 col-xs-12']])->dropDownList(array('Principal Protection' => 'Principal Protection', 'Interest Earning' => 'Interest Earning'), ['prompt' => 'Equity Type']) ?>
    <div class="col-sm-2 col-xs-12 custom-calendar">
        <?php echo $form->field($model, 'from_date_participate')->widget(DatePicker::classname(), [
                        'value'  => @$value, 'dateFormat' => $dateformat, 'value' => date('Y-m-d'), 'options' => ['placeholder' => 'From Date', 'class' => 'clsDropdown col-sm-3 col-xs-12'],
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
                                $('#projectparticipation-to_date_participate').val('');  
                                var toDate = $(this).datepicker('getDate');
                                var fromDate = $(this).datepicker('getDate');
                                fromDate.setDate(toDate.getDate()+1);                                
                                $('#projectparticipation-to_date_participate').datepicker('option', 'minDate', fromDate); 
                                }"
                            ),
                        ],
                ])->textInput(['readonly' => true])->label('From Date');
        ?>
    </div>
    <div class="col-sm-2 col-xs-12 custom-calendar">
        <?php echo $form->field($model, 'to_date_participate')->widget(DatePicker::classname(), [
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
    </div>
       <div class="col-sm-2 btn-sbrt">
    <div class="col-sm-4 col-xs-12 searchBtn p-left0">
        <?php echo Html::submitButton('<i class="icon-magnifier"></i>' , ['class' => 'btn btn-success', 'id' => 'btnParticipateSearch']) ?>
        <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>/projects/index" id="searchUrl_participate">
    </div>
    <div class="col-sm-5 col-xs-12 searchBtn" style="padding:0;">
        <?php echo Html::submitButton('<i class="fa fa-refresh"></i>' , ['class' => 'btn btn-success res-bnt', 'id' => 'btnParticipateReset']) ?>
    </div></div>
    </div>
 
    
    <?php ActiveForm::end(); ?>
</div>

<?php
    Pjax::begin([
                'id' => 'pjax-list-participated',
                'timeout' => 5000,
                'enablePushState'=>false
            ]);
//    echo ListView::widget([
//                'dataProvider' => $dataProvider,
//                'options' => [
//                    'tag' => 'div',
//                    'class' => 'list-wrapper table-responsive',
//                    'id' => 'list-wrapper',
//                ],
//                'itemView' => function ($model, $key, $index, $widget) {
//                    return $this->render('_list_result',['model' => $model]);
//                },
//                'layout' => "{summary}\n{items}\n<tr><td>{pager}</td></tr>",
//                'options' => [
//                  'tag' => 'table',
//                    ],
//                'itemOptions' => [
//                    'tag' => false,
//                ],
//                'viewParams' => [
//                    'latlon' => @$latlon
//                ],
//            ]);
    
    
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
                'attribute' => 'category_name',
                'label' => 'Category',
                'value' => function ($data) {
                    return $data['category_name'];
                }
            ],
            [
                'attribute' => 'user_ref_id',
                'label' => 'Owner',
                'value' => function ($data) {
                    $projectOwner = Projects::getProjectCreatorDetails($data['project_id']);
                    return $projectOwner[0]['fname'].' '.$projectOwner[0]['lname'];
                }
            ],
            [
                'attribute' => 'participation_type',
                'label' => 'Expressed Interest',
                'value' => function ($data) {
                    return $data['participation_type'] == 'Invest' ? 'Cash' : 'Kind';
                }
            ],
            'investment_type',
            [
                'attribute' => 'equity_type',
                'value' => function ($data) {
                    return ($data['equity_type'] != null) ? str_replace("_", " ", $data['equity_type']) : '-NA-';
                }
            ],
            [
                'attribute' => 'amount',
                'label' => 'Amount',
                'value' => function ($data) {
                    return (abs($data['amount']) > 0) ? $data['amount'] : '-NA-';
                }
            ],
            [
                'attribute' => 'interest_rate',
                'label' => 'Interest',
                'value' => function ($data) {
                    return ($data['interest_rate'] > 0) ? $data['interest_rate'] : '-NA-';
                }
            ],
            [
                'attribute' => 'created_date',
                'label' => 'Expressed Date',
                'format' =>  ['date', 'php:'.$phpdateformat],
                /*'value' => function ($data) {
                    return $data['created_date'];
                }*/
            ],
            

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'buttons'=>[
                        'view'=>function ($url, $model) {     
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
        ],
    ]);
    
    
    Pjax::end();
?>


<script>
    $(function(){
        $("#projectparticipation-from_date_participate").attr("placeholder", "From Date");
        $("#projectparticipation-to_date_participate").attr("placeholder", "To Date");
        
        $('#btnParticipateSearch').on('click', function (e) {
            var searchUrl_participate = $('#searchUrl_participate').val();
            var pjaxContainer_participate = 'pjax-list-participated' ;
            var participation_participate = $('#projectparticipation-participation').val();
            var investmentType = $('#projectparticipation-investment_type').val();
            var equityType = $('#projectparticipation-equity_type').val();
            var from_participate = $('#projectparticipation-from_date_participate').val();
            var to_participate = $('#projectparticipation-to_date_participate').val();
            var pjaxReloadURL_participate = searchUrl_participate+'?participation='+participation_participate+'&iType='+investmentType+'&eType='+equityType+'&from='+from_participate+'&to='+to_participate+'&participate=participate';
            
             $.ajax({
                url:   searchUrl_participate,
                type: 'get',
                data: {'participation': participation_participate, 'from': from_participate, 'to': to_participate, 'participate': 'participate'},
                success: function(data){
                    if(data){
                        //$.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer, )});
                        $.pjax.reload({url: pjaxReloadURL_participate, container: '#' + $.trim(pjaxContainer_participate), async:false});
                        return false;
                    }
                },
                error: function (xhr, status, error) {
                   alert('There was an error with your request.' + xhr.responseText);
                 }
             }); 
             return false;
        });
        
        $('#btnParticipateReset').on('click', function (e) {
            var searchUrl_participate = $('#searchUrl_participate').val();
            var pjaxContainer = 'pjax-list-participated' ;
            var pjaxReloadURL = searchUrl_participate;

             $.ajax({
                url:   searchUrl_participate,
                type: 'get',
                //data: {'title': projectTitle, 'cat': projectCategory, 'type': projectType, 'status': projectStatus, 'from': from, 'to': to},
                success: function(data){
                    if(data){ 
                        $('#projectparticipation-participation').val('');
                        $('#projectparticipation-investment_type').val('');
                        $('#projectparticipation-equity_type').val('');
                        $('#projectparticipation-from_date_participate').datepicker('setDate', null);
                        $('#projectparticipation-to_date_participate').datepicker('setDate', null);
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
        
      //for display full title
    $('[data-toggle="tooltip"]').tooltip();
    });
    
 
   
</script>
