<?php
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
$this->title = 'Sent';
$dateformat = Yii::getAlias('@phpdatepickerformat');		
$phpdateformat = Yii::getAlias('@phpdateformat');
?>
<style type="text/css">
    .tableHeadingBG table thead tr th {
        background-color: #808080 !important;
        color: #ffffff !important;
    }
   .group-btn-search{right:150px;}
    @media only screen and (max-width: 650px) {
         .ui-datepicker{
            top:130px !important;
            left:30px !important;
        }
    }

    .custom-calendar .ui-datepicker-trigger {
        right: 6px !important;
    }
    .form-control {
        width: 145px !important;
    }
   
</style>
<h1 class="box-title"><?php echo Html::encode($this->title) ?></h1>
<div class="participation-border fl-left all-userlst">
<div class="searchBox row">
<div class="pull-left col-sm-11 rgt-padding0 colsm11-12">
    <?php $form = ActiveForm::begin(); ?>
	<?php echo $form->field($model, 'search_user_ref_id', ['options' => ['class' => 'clsDropdown col-sm-2 col-xs-12 communiqueInbox']])->textInput(array('placeholder' => 'Username'))->label(false) ?>
    <?php echo $form->field($model, 'search_subject', ['options' => ['class' => 'clsDropdown col-sm-2 col-xs-12 communiqueInbox']])->textInput(array('placeholder' => 'Subject'))->label(false) ?>
   
	<?php echo $form->field($model, 'search_project_ref_id', ['options' => ['class' => 'clsDropdown col-sm-2 col-xs-12 communiqueInbox']])->textInput(array('placeholder' => 'Reference to'))->label(false) ?>
	
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
                            'buttonImage' => Yii::$app->request->BaseUrl.'/images/calendar.gif',  
                            'onSelect' => new \yii\web\JsExpression("function(dateStr) {
                                $('#communique-to_date').val('');  
                                var toDate = $(this).datepicker('getDate');
                                var fromDate = $(this).datepicker('getDate');
                                fromDate.setDate(toDate.getDate()+1);                                
                                $('#communique-to_date').datepicker('option', 'minDate', fromDate); 
                                }"
                            ),
                        ],
                ])->textInput(['readonly' => true])->label('');
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
                            'buttonImage' => Yii::$app->request->BaseUrl.'/images/calendar.gif',          
                        ],
                ])->textInput(['readonly' => true])->label('');
        ?>
    </div>
    </div>
    
    <div class="pull-right col-sm-1 btn-sbrt">
    <div class="col-sm-6 col-xs-12 searchBtn group-btn-search" style="padding:0;">
        <?php echo Html::submitButton('<i class="fa fa-search ic-srch"></i>' , ['class' => 'btn btn-success', 'id' => 'btnInboxSearch']) ?>
        <input type="hidden" value="<?php echo Yii::$app->request->BaseUrl; ?>/communique/sent-mails" id="searchUrl_initiated">
    </div>
    <div class="col-sm-6 col-xs-12 searchBtn group-btn-search" style="padding:0;">
        <?php echo Html::submitButton('<i class="fa fa-refresh"></i>' , ['class' => 'btn btn-success res-bnt', 'id' => 'btnCommuniqueReset']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="sentmails send-infomail" id="sent">
<?php Pjax::begin([
                'id' => 'pjax-list-initiated',
                'timeout' => false,
                'enablePushState'=>false
            ]);
    if($count){
                    echo yii\grid\GridView::widget([
                        'dataProvider' => $dataProvider,
						'options' =>['class'=>'tableHeadingBG table-responsive'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                             [ 
                                'attribute' => 'fullname',
                                'label' => 'Username',
                            ],
                            'subject',
                            [ 
                                'attribute' => 'projecttitle',
                                'label' => 'Reference to',
                            ],
                            [ 
                                'attribute' => 'created_date',
                                'label' => 'Date',
                                'format' =>  ['date', 'php:'.$phpdateformat.' h:i:s A'],
                            ],
                        ],
                        
                        'rowOptions' => function($model) {
                            return ['class' => 'mailinbox', 'mailid' => $model['communique_id'],'onclick' => "javascript:openmailbox(".$model['communique_id'].",'sent')"];
                    }
                    ]);
}else{
        echo "<div class='nodata'>No sent mails</div>";
    }
	Pjax::end();
                ?>
</div>
    </div>
<script>
    $('.sentmails table thead tr').addClass('trclass');
	var hurl = window.location.href;
		if (hurl.indexOf("inbox#inbox/") >= 0){
		$('.searchBox').hide();
		}
    var mailviewurl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('communique/mail-view'); ?>';
    
    function openmailbox(mailid,tbox){
	$('.searchBox').hide();
        if(tbox=='inbox'){
        location.hash = 'inbox/'+mailid;
    }else if(tbox=='sent'){
        location.hash = 'sent/'+mailid;
    }
    }
    function opencontent(mailid){
           var mailid = mailid;
            $('#sent').empty();
            $.ajax({
                    url: mailviewurl,
                    type: "post",
                    dataType: "html",
                    data: {mailid:mailid},
                    success: function (data) {
                        $('#sent').html(data);
                        
                    }
                });
       }
       
     
    $(function(){ 
       var redirecturl ='<?php echo Yii::$app->urlManager->createAbsoluteUrl('communique/sent-mails'); ?>';
        var what_to_do = document.location.hash;    
   if (what_to_do.indexOf("sent/") >= 0){
            var msgid = what_to_do.split('sent/')[1];
            opencontent(msgid);
        }else if (what_to_do.indexOf("inbox/") >= 0){
            var msgid = what_to_do.split('inbox/')[1];
            opencontent(msgid);
        }
    window.onhashchange = function() {   
       var what_to_do = document.location.hash;    
   if (what_to_do.indexOf("sent/") >= 0){
            var msgid = what_to_do.split('sent/')[1];
            opencontent(msgid);
        }else if (what_to_do.indexOf("inbox/") >= 0){
            var msgid = what_to_do.split('inbox/')[1];
            opencontent(msgid);
        }else if(what_to_do == ''){
            window.location.reload(redirecturl);
        }
    }
    
    $('#communique-from_date, #communique-to_date').addClass('input-datepicker');
});

$("#communique-from_date").attr("placeholder", "From Date");
  $("#communique-to_date").attr("placeholder", "To Date");
$('#btnInboxSearch').on('click', function (e) {
var pjaxContainer_initiated = 'pjax-list-initiated' ;
			var searchUrl_initiated = $('#searchUrl_initiated').val();
            var communique_subject = $('#communique-search_subject').val();
            var communique_user_ref_id = $('.communiqueInbox #communique-search_user_ref_id').val();
            var communique_prj_ref_id = $('.communiqueInbox #communique-search_project_ref_id').val();
            var communique_from_date = $('#communique-from_date').val();
			var communique_to_date = $('#communique-to_date').val();
			var mailstatus = $('#communique-mailstatus').val();
            var pjaxReloadURL_initiated = searchUrl_initiated+'?subject='+communique_subject+'&user_ref='+communique_user_ref_id+'&prj_ref='+communique_prj_ref_id+'&from_date='+communique_from_date+'&to_date='+communique_to_date+'&mailstatus='+mailstatus;
             $.ajax({
                url:   searchUrl_initiated,
                type: 'get',
                data: {'subject': communique_subject, 'user': communique_user_ref_id, 'project': communique_prj_ref_id, 'from_date': communique_from_date, 'to_date': communique_to_date, 'mailstatus': mailstatus},
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
		
		$('#btnCommuniqueReset').on('click', function (e) {
            var searchUrl_initiated = $('#searchUrl_initiated').val();
            var pjaxContainer = 'pjax-list-initiated' ;
            var pjaxReloadURL = searchUrl_initiated;

             $.ajax({
                url:   searchUrl_initiated,
                type: 'get',
                //data: {'title': projectTitle, 'cat': projectCategory, 'type': projectType, 'status': projectStatus, 'from': from, 'to': to},
                success: function(data){
                    if(data){ 
							$('#communique-search_subject').val('');
							$('.communiqueInbox #communique-search_user_ref_id').val('');
							$('.communiqueInbox #communique-search_project_ref_id').val('');
							$('#communique-from_date').datepicker('setDate', null);
							$('#communique-to_date').datepicker('setDate', null);
							$('#communique-mailstatus').val('');
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
</script>
