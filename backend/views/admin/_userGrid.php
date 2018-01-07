<style>
    .notifyDiv{
        background-color: #B5EBE0;  
    }
    .clsTextbox {
        float: none;
        margin-right: 20px;
        width: 20%;
    }
    .searchBox .form-group {
        margin: 0px;
    }
    .empty{
        text-align: center;
    }
    .dropdwn{
        width: 160px;
    }
    @media (max-width: 770px){
        ul.pagination{ display:flex;}
    }
</style>
<?php
/* @var $this yii\web\View */

use yii\grid\GridView;
use yii\data\SqlDataProvider;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

if (isset($usertype[0]['user_type']) && $usertype[0]['user_type'] != '') {
    $this->title = $usertype[0]['user_type'] . ' Users';
} else {
    $this->title = 'All Users';
}
if (yii::$app->user->identity->id == '1') {
    $this->params['breadcrumbs'][] = ['label' => 'Admin Users', 'url' => ['admin_list']];
}
$this->params['breadcrumbs'][] = $this->title;
$dateformat = Yii::getAlias('@phpdatepickerformat');
$phpdateformat = Yii::getAlias('@phpdateformat');
$dataProvider = new SqlDataProvider([
    'sql' => $query,
    'totalCount' => $count,
    'pagination' => [
        'pageSize' => 10,
    ],
        ]);

$dataProvider->setSort([
    'attributes' => [
        'username',
        'email',
        'status',
        'fullName' => [
            'asc' => ['fname' => SORT_ASC, 'lname' => SORT_ASC],
            'desc' => ['fname' => SORT_DESC, 'lname' => SORT_DESC],
            'label' => 'Full Name',
            'default' => SORT_ASC
        ],
        'gender',
        'current_location',
        'user_type',
        'created_at'
    ]
]);
echo "<h1 class='box-title'>$this->title </h1>";
echo "<div class='participation-border fl-left all-userlst'>";
?> 
<div class='participation-border fl-left notifyDiv' style="display:none;">You have successfully changed the user status.</div>    

<div style="width: 100%; float: left;" class="searchBox all-adminlist">
    <div class="col-sm-12 p-left0">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-sm-2 col-xs-12 p-left0 ad-lst">
            <?php echo $form->field($model, 'userEmail')->textInput(array('placeholder' => 'Email Or Name'), ['class' => 'form-control']) ?>
        </div>
        <?php
        if (Yii::$app->getRequest()->getQueryParam('id') == '') {
            echo $form->field($model, 'user_type_ref_id', ['options' => ['class' => 'col-sm-2 col-xs-12 ad-lst dropdwn']])->dropDownList($allUserTypes, ['prompt' => 'User Type', 'onchange'=>'displayMediaAgencies(this.value)']);
        
            echo $form->field($model, 'media_agency_ref_id', ['options' => ['class' => 'col-sm-2 col-xs-12 ad-lst dropdwn', 'style' => 'display: none']])->dropDownList($mediaAgencies, ['prompt' => 'Media Agencies']);
            
        }
        ?>
        <?php echo $form->field($model, 'status', ['options' => ['class' => 'col-sm-2 col-xs-12 ad-lst dropdwn']])->dropDownList($userStatus, ['prompt' => 'Status']) ?>
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
                    'onSelect' => new \yii\web\JsExpression("function(dateStr) {
                        $('#user-to_date').val('');  
                        var toDate = $(this).datepicker('getDate');
                        var fromDate = $(this).datepicker('getDate');
                        fromDate.setDate(toDate.getDate()+1);                                
                        $('#user-to_date').datepicker('option', 'minDate', fromDate); 
                        }"
                    ),
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
                <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>/admin/user_list" id="searchUrl">
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
<?php
\yii\widgets\Pjax::begin([
    'id' => 'pjax-list',
    'timeout' => false,
    'enablePushState' => false
]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        //'username',
       
		 [
							'format' => 'raw',
                            'attribute' => 'fullName',
							 'options' => ['width' => '180'],
                            'value' => function ($model) {                               
							   if (strlen($model['fname'].$model['lname'])>17) {
                                  return  '<span style="cursor: pointer;" title="'.$model['fname'] . ' ' . $model['lname'].'">'.substr($model['fname'] . $model['lname'], 0, 17). '<b>...</b></span>';
                                   }else{
	                                 return '<span style="cursor: pointer;" title="'.$model['fname'] . ' ' . $model['lname'].'">'.$model['fname'] . ' ' . $model['lname']. '</span>';
                                }
                            }
                        ],
        'email',        
        [
            'attribute' => 'gender',
            'value' => function($model) {
                return $model['gender'] ? $model['gender'] : 'Not Assigned';
            }
        ],
        [
            'format' => 'raw',
			'attribute' => 'current_location',
            'value' => function($model) {
				
				if(strlen($model['current_location'])>30) {
                       return  '<span style="cursor: pointer;" title="'.$model['current_location'].'">'.substr($model['current_location'], 0, 30). '<b>...</b></span>';
                   }else{
	                    return  '<span style="cursor: pointer;" title="'.$model['current_location'].'">'.$model['current_location']. '</span>';
                   }
            }
        ],
        [
            'attribute' => 'user_type',
            'value' => function($model) {
                return empty($model['media_agency_ref_id']) ? $model['user_type'] : $model['user_type'].' - '.$model['media_agency_name'] ;
            }
        ],
        //'status_name',
        [
            'attribute' => 'status_name',
            'label' => 'Status',
            'value' => function($model) {
                return $model['status_name'] ? $model['status_name'] : 'Pending';
            }
        ],
        [
            'attribute' => 'created_at',
            'label' => 'Created Date',
            'format' => ['date', 'php:'.$phpdateformat]
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{active}',
            'buttons' => [

                'active' => function ($url, $model) {
                    if ($model['email_confirmed'] == 0) {
						return '<div title="User email is not yet verified"><span class="glyphicon glyphicon-info-sign" ></span><div>';
                    }else if($model['status'] == 1) {
                        return Html::a('<span class="glyphicon glyphicon-ok-sign"></span>', false, ['class' => 'ajaxDelete', 'delete-url' => $url, 'pjax-container' => 'pjax-list', 'title' => Yii::t('app', 'deactivate'), 'data-confirm' => 'Are you sure you want to deactivate this user?',]);
                    } else {
                        return Html::a('<span class="glyphicon glyphicon-remove-sign"></span>', false, ['class' => 'ajaxDelete', 'delete-url' => $url, 'pjax-container' => 'pjax-list', 'title' => Yii::t('app', 'activate'), 'data-confirm' => 'Are you sure you want to activate this user?',]);
                    }
                }
            ],
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'active') {
                    return Url::toRoute(['changestatus', 'id' => $model['id'], 'status' => $model['status'], 'email' => $model['email']]);
                }
            }
        ],
        ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('yii', 'view'),
                            'class' => 'view',
                            'rel' => 'fancybox'
                    ]);
                },
            ],
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'view') {
                    return Url::toRoute(['view', 'id' => $model['id']]);
                }
            }
        ],
    ],
]);
\yii\widgets\Pjax::end();
?>
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
        <input class="dataConfirmCancel btn btn-secondary" onclick="$('#dataConfirmModal').css('display', 'none');" type="button" value="Cancel">
        <input class="dataConfirmOK btn btn-primary" onclick="updateStatus()" type="button" value="Ok">
    </div>
</div>

<script>

    function updateStatus() {
        var deleteUrl = $('#updateUrl').val();
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

    $(function () {

        $("#user-from_date").attr("placeholder", "From Date");
        $("#user-to_date").attr("placeholder", "To Date");

        $('.view').fancybox({type: 'ajax'});
        $('.view').on('click', function (e)
        {
            e.preventDefault();


        });

        $('#btnSearch').on('click', function (e) {
            var searchUrl = $('#searchUrl').val();
            var pjaxContainer = 'pjax-list';
            var email = $('#user-useremail').val();
            var userType = $('#user-user_type_ref_id').val();
            var userStatus = $('#user-status').val();
            var from = $('#user-from_date').val();
            var to = $('#user-to_date').val();
            var utypeId = $('#utypeId').val();
            if (utypeId != "") {
                var mediaAgency = "";
                var pjaxReloadURL = searchUrl + '?id=' + utypeId + '&email=' + email + '&status=' + userStatus + '&from=' + from + '&to=' + to + '&mediaAgency=' + mediaAgency;
            }
            else {
                var mediaAgency = $('#user-media_agency_ref_id').val();
                var pjaxReloadURL = searchUrl + '?email=' + email + '&type=' + userType + '&status=' + userStatus + '&from=' + from + '&to=' + to + '&mediaAgency=' + mediaAgency;
            }

            $.ajax({
                url: searchUrl,
                type: 'get',
                data: {'id': utypeId, 'email': email, 'type': userType, 'status': userStatus, 'from': from, 'to': to, 'mediaAgency': mediaAgency},
                success: function (data) {
                    if (data) {
                        //$.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer, )});
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


        $('#btnReset').on('click', function (e) {
            var searchUrl = $('#searchUrl').val();
            var pjaxContainer = 'pjax-list';
            var utypeId = $('#utypeId').val();
            if (utypeId != "")
                var pjaxReloadURL = searchUrl + '?id=' + utypeId;
            else
                var pjaxReloadURL = searchUrl;

            $.ajax({
                url: searchUrl,
                type: 'get',
                //data: {'title': projectTitle, 'cat': projectCategory, 'type': projectType, 'status': projectStatus, 'from': from, 'to': to},
                success: function (data) {
                    if (data) {
                        $('#user-useremail').val('');
                        $('#user-user_type_ref_id').val('');
                        $('#user-status').val('');
                        $('#user-from_date').datepicker('setDate', null);
                        $('#user-to_date').datepicker('setDate', null);
                        $('#user-media_agency_ref_id').val('');
                        $('.field-user-media_agency_ref_id').hide();
                        //$.pjax.reload({url: pjaxReloadURL, container: '#' + $.trim(pjaxContainer, )});
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

    });
    
    function displayMediaAgencies(userType) {
        if(userType == '9')
            $('.field-user-media_agency_ref_id').show();
        else
            $('.field-user-media_agency_ref_id').hide();
    }

</script>