<style>
    .notifyDiv{
        background-color: #B5EBE0;  
    }
    
    .searchBox .form-group {
        margin: 0px;
    }
    
</style>
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MediaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Media Agencies';
$this->params['breadcrumbs'][] = $this->title;
$dateformat = Yii::getAlias('@phpdatepickerformat');
$phpdateformat = Yii::getAlias('@phpdateformat');
?>
<div class="media-agencies-index">

    <h1 class='box-title'><?= Html::encode($this->title) ?></h1>
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>
    
 
<div class='participation-border fl-left all-userlst'>
    <div class='participation-border fl-left notifyDiv' style="display:none;">You have successfully changed the agency status.</div>
    <div style="width: 100%; float: left;" class="searchBox all-adminlist">
        <div class="col-sm-12 p-left0">
            <?php $form = ActiveForm::begin(); ?>
            <div class="col-sm-2 col-xs-12 p-left0 ad-lst">
                <?php echo $form->field($model, 'media_agency_name')->textInput(array('placeholder' => 'Media Agency Name'), ['class' => 'form-control']) ?>
            </div>
            <?php echo $form->field($model, 'status', ['options' => ['class' => 'col-sm-2 col-xs-12 ad-lst dropdwn']])->dropDownList($mediaAgencyStatus, ['prompt' => 'Status']) ?>
            <div class="col-sm-2 col-xs-12 custom-calendar">
                <?php
                echo $form->field($model, 'from_date')->widget(DatePicker::classname(), [
                    'value' => @$value, 'dateFormat' => $dateformat, 'value' => date('Y-m-d'), 'options' => ['class' => 'clsDropdown col-sm-3 col-xs-12'],
                    'clientOptions' => [
                        'changeMonth' => true,
                        'yearRange' => "2015:(date('Y')+5)",
                        'changeYear' => true,
                        'showOn' => 'button',
                        'buttonImage' => 'images/calendar.gif',
                        'buttonImageOnly' => true,
                        'buttonText' => 'Select date',
                        'buttonImage' => Yii::$app->request->BaseUrl . '/images/calendar.gif',
                    ],
                ])->textInput(['readonly' => true]);
                ?>
            </div>
            <div class="col-sm-2 col-xs-12 custom-calendar">
                <?php
                echo $form->field($model, 'to_date')->widget(DatePicker::classname(), [
                    'value' => @$value, 'dateFormat' => $dateformat, 'value' => date('Y-m-d'), 'options' => ['class' => 'clsDropdown col-sm-3 col-xs-12'],
                    'clientOptions' => [
                        'changeMonth' => true,
                        'yearRange' => "2015:(date('Y')+5)",
                        'changeYear' => true,
                        'showOn' => 'button',
                        'buttonImage' => 'images/calendar.gif',
                        'buttonImageOnly' => true,
                        'buttonText' => 'Select date',
                        'buttonImage' => Yii::$app->request->BaseUrl . '/images/calendar.gif',
                    ],
                ])->textInput(['readonly' => true]);
                ?>
            </div>
            <div class="col-sm-2 btn-sbrt">
                <div class="col-sm-6 col-xs-12 searchBtn">
                    <?php echo Html::submitButton('<i class="icon-magnifier"></i>', ['class' => 'btn btn-success', 'id' => 'btnSearch']) ?>
                    <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>/media/index" id="searchUrl">
                    <?php
                        $userTypeId = Yii::$app->getRequest()->getQueryParam('id') ? Yii::$app->getRequest()->getQueryParam('id') : "";
                    ?>
                    <input type="hidden" value="<?php echo $userTypeId; ?>" id="utypeId">
                </div>
                <div class="col-sm-6 col-xs-12 searchBtn" style="padding:0;">
                    <?php echo Html::submitButton('<i class="fa fa-refresh"></i>', ['class' => 'btn btn-success res-bnt', 'id' => 'btnReset']) ?>
                </div>
            </div>
        </div>


        <?php ActiveForm::end(); ?>
    </div>







        <p>
            <?php echo Html::a('Create Media Agencies', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <?php Pjax::begin([
                'id' => 'pjax-list',
                'timeout' => false,
                'enablePushState' => false
            ]);
        ?>
        <?php echo GridView::widget([
            'dataProvider' => $dataProvider,
           // 'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

               // 'media_agency_id',
                'media_agency_name',
                [
                    'attribute' => 'status',
                    'label' => 'Status',
                    'value' => function($model) {
                        return $model['status'] == 1 ? 'Active' : 'Inactive';
                    }
                ], 
                [
                    'attribute' => 'created_date',
                    'format' => ['date', 'php:'.$phpdateformat]
                ],                    

                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{view}{update}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                        'title' => Yii::t('yii', 'view'),
                                        'class' => 'view',
                                        'rel' => 'fancybox'
                            ]);
                        }
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'view') {
                            return Url::toRoute(['view', 'id' => $model['media_agency_id']]);
                        } else {
                            return Url::toRoute([$action, 'id' => $model['media_agency_id']]);
                        }
                    }    
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{active}',
                    'buttons' => [

                        'active' => function ($url, $model) {
                            if ($model['status'] == 1) {
                                return Html::a('<span class="glyphicon glyphicon-ok-sign"></span>', false, ['class' => 'ajaxUpdate', 'update-url' => $url, 'pjax-container' => 'pjax-list', 'title' => Yii::t('app', 'deactivate'), 'data-confirm' => 'Are you sure you want to deactivate this user?',]);
                            } else {
                                return Html::a('<span class="glyphicon glyphicon-remove-sign"></span>', false, ['class' => 'ajaxUpdate', 'update-url' => $url, 'pjax-container' => 'pjax-list', 'title' => Yii::t('app', 'activate'), 'data-confirm' => 'Are you sure you want to activate this user?',]);
                            }
                        }
                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'active') {
                            return Url::toRoute(['changestatus', 'id' => $model['media_agency_id'], 'status' => $model['status']]);
                        }
                    }
                ],
            ],
        ]); 
        \yii\widgets\Pjax::end();
        ?>

        <input type="hidden" value="" id="activeUrl">
        <input type="hidden" value="" id="ajaxContainer">
    </div>
</div>
<?php
$this->registerJs(" $(document).on('ready pjax:success', function () {  var deleteUrl; 
  $('.ajaxUpdate').on('click', function (e) {
    e.preventDefault();
    updateUrl     = $(this).attr('update-url');
    $('#activeUrl').val(updateUrl);
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
    $('#search-form').on('pjax:end', function() {
        $.pjax.reload({container:'# + $.trim(pjaxContainer)'});  //Reload GridView
    });
}); 

");                
?>
<div id="dataConfirmModal" class="confirm-box" style="display:none;">
    <h3 id="dataConfirmLabel" >Please Confirm</h3>   
    <div style="text-align:right;margin-top:10px;">
        <input class="dataConfirmCancel btn btn-secondary" onclick="$('#dataConfirmModal').css('display', 'none');" type="button" value="Cancel">
        <input class="dataConfirmOK btn btn-primary" onclick="updateStatus()" type="button" value="Ok">
    </div>
</div>
<script>
    $(function(){
        $('.view').fancybox({type: 'ajax'});
        $('.view').on('click', function (e)
        {
            e.preventDefault();
        });
        
        $("#mediaagencies-from_date").attr("placeholder", "From Date");
        $("#mediaagencies-to_date").attr("placeholder", "To Date");
    });

    function updateStatus() {
        var deleteUrl = $('#activeUrl').val();
        var pjaxContainer = $('#ajaxContainer').val();

        $.ajax({
            url: deleteUrl,
            type: 'get',
            success: function (data) {
                if (data) {
                    $('#dataConfirmModal').css('display', 'none');
                    $.pjax.reload({container: '#' + $.trim(pjaxContainer)});
                    $('.notifyDiv').slideDown('slow', function () {
                        $(this).delay(2000).fadeOut(1000);
                    });
                }
            },
            error: function (xhr, status, error) {
                // alert('There was an error with your request.' + xhr.responseText);
            }
        });
        return false;
    }
    
    /* Reset Filter*/
    $('#btnReset').on('click', function (e) {
        var searchUrl = $('#searchUrl').val();
        var pjaxContainer = 'pjax-list';            
        var pjaxReloadURL = searchUrl;

        $.ajax({
            url: searchUrl,
            type: 'post',          
            success: function (data) {
                if (data) {
                    $('#mediaagencies-media_agency_name').val('');                       
                    $('#mediaagencies-status').val('');
                    $('#mediaagencies-created_date').datepicker('setDate', null);                       

                    $.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer), async: false});
                    return false;
                }
            },
            error: function (xhr, status, error) {
                alert('There was an error with your request.' + xhr.responseText);
            }
        });
        return false;
    });
    
    
    /* Search Filter*/
    $('#btnSearch').on('click', function (e) {        
        var searchUrl = $('#searchUrl').val();
        var pjaxContainer = 'pjax-list'; 
        var mediaAgencyName = $('#mediaagencies-media_agency_name').val();
        var status = $('#mediaagencies-status').val();
        var from = $('#mediaagencies-from_date').val();
        var to = $('#mediaagencies-to_date').val();
        var pjaxReloadURL = searchUrl + '?media_agency_name=' + mediaAgencyName + '&status=' + status + '&from_date=' + from + '&to_date=' + to;
        $.ajax({
            url: searchUrl,
            type: 'get',
            data: {'media_agency_name': mediaAgencyName, 'status': status, 'from_date': from, 'to_date': to},
            success: function (data) {               
                if (data) {
                    $.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer), async: false});
                    return false;
                }
            },
            error: function (xhr, status, error) {
                alert('There was an error with your request.' + xhr.responseText);
            }
        });
        return false;
    }); 
</script>