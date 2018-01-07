<style>
    .notifyDiv{
      background-color: #B5EBE0;  
    }
</style>
<?php
/* @var $this yii\web\View */

use yii\grid\GridView;
use yii\data\SqlDataProvider;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Private Projects Approval Requests';
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$dataProvider = new SqlDataProvider([
    'sql' => $query,
    'totalCount' => $count,    
    'pagination' => [
        'pageSize' => 10,
    ],
]);

$dataProvider->setSort([
    'attributes' => [
        'project_title',
        'project_type',
        'RequestedBy' => [
            'asc' => ['fname' => SORT_ASC, 'lname' => SORT_ASC],
            'desc' => ['fname' => SORT_DESC, 'lname' => SORT_DESC],
            'label' => 'Requested By',
            'default' => SORT_ASC
        ],
        'approved_on',
    ]
]);

echo "<h1 class='box-title'>$this->title </h1>";
echo "<div class='participation-border fl-left'>";
?> 
<div class='participation-border fl-left notifyDiv' style="display:none;">You have successfully changed the user status.</div>    
<?php
\yii\widgets\Pjax::begin([
    'id' => 'pjax-list',
    'timeout' => false,
    'enablePushState'=>false
]);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'project_title',
        'project_type',
        [
            'attribute'=>'RequestedBy', 
            'format' => 'raw',
            'value' => function($model) {
                return $model['fname'].' '.$model['lname'];
            }
        ],  
        [
            'attribute'=>'approved_on', 
            'value' => function($model) {
                return $model['approved_on'] ? date(Yii::getAlias('@phpdateformat'),strtotime($model['approved_on'])) : 'Approval Pending';
            }
        ], 
       // 'approved_on:datetime',   
        ['class' => 'yii\grid\ActionColumn',
            'template'=>'{view}{approve}',
            'buttons'=>[
                'view'=>function ($url, $model) {     
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('yii', 'view'),
                            'class'=>'view',
                            'rel'=>'fancybox'
                    ]);                                

                  },
                'approve'=>function($url, $model) {
                    if ($model['is_approved'] == 1) {
                        return HTML::a('<span class="glyphicon glyphicon-ok-sign"></span>',$url,[
                            'title' => Yii::t('yii', 'Approved'),
                            'class' => 'ajaxDelete', 
                            'delete-url' => $url, 
                            'pjax-container' => 'pjax-list',
                            'data-confirm'=>'Are you sure you want to reject the request?',
                            ]);
                    }else{
                        return HTML::a('<span class="glyphicon glyphicon-remove-sign"></span>',$url,[
                            'title' => Yii::t('yii', 'Not Approved'),
                            'class' => 'ajaxDelete', 
                            'delete-url' => $url, 
                            'pjax-container' => 'pjax-list',
                            'data-confirm'=>'Are you sure you want to approve the request?',
                            ]);
                    }                    
                },                  
              ],
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'approve') {
                    return Url::toRoute(['changestatus', 'id' => $model['user_request_id'], 'status' => $model['is_approved']]);
                } else {
                    return Url::toRoute([$action, 'id' => $model['project_id']]);
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
 $('.view').fancybox({type:'ajax'});
    $('.view').on('click',function(e)
    {
     e.preventDefault();
     

    });
});
    
</script>
<style>
    a.fancybox-nav.fancybox-prev, a.fancybox-nav.fancybox-next{display:none;}
    .fancybox-opened{width:650px !important;}
    .fancybox-inner{width: 100% !important;overflow-x: hidden !important;}
	@media (max-width: 740px){
.grid-view {overflow-x: scroll; float:none !important; }
.fl-left{ display:block;}
	}
</style>
