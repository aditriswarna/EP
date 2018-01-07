<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Project Participations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-participation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Project Participation', null , ['class' => 'btn btn-success ajaxprjparticipate', 'pid' => $projectID, 'uid' => Yii::$app->user->id]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            'participation_type',
            'investment_type',
            [
                'attribute' => 'equity_type',
                'value' => function ($data) {
                    return str_replace('_', ' ', $data['equity_type']);
                }
            ],
            [
                'attribute' => 'amount',
                'value' => function ($data) {
                    return !empty($data['amount']) ? $data['amount'] : '';
                }
            ],
            [
                'attribute' => 'interest_rate',
                'value' => function ($data) {
                    return !empty($data['interest_rate']) ? $data['interest_rate'] : '';
                }
            ],
            'created_date',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{delete}',
                'buttons'=>[                    
                    'view'=>function ($url, $model) {     
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('yii', 'view'),
                            'class'=>'view',
                            'rel'=>'fancybox'
                        ]);                                

                      },
                    'delete'=>function ($url, $model) {     
                        return $model['user_ref_id'] == yii::$app->user->id ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'delete'),                            
                            'aria-label'=>'Delete',
                            'data-confirm'=>'Are you sure you want to delete this item?',
                            'data-method'=>'post',
                            'data-pjax'=>'0'
                        ]):'';                                

                      }
                  ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        return Url::toRoute(['view', 'id' => $model['project_participation_id']]);
                    } else {
                        return Url::toRoute(['delete', 'id' => $model['project_participation_id']]);
                    }
                } 
            ],
        ],
    ]); ?>

</div>

<script>
    $(function(){
        $('.view').fancybox({type:'ajax'});
        $('.view').on('click',function(e)
        {
            e.preventDefault();
        });
    });
    var purl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('projects/get-participant-details'); ?>';
    var participantUrl = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('project-participation/create?id='.$projectID); ?>';
    function submitparticipant(){
        window.location.href=participantUrl;
    }
</script>

<?php


$this->registerJs(" $(document).on('ready pjax:success', function () {  
  $('.ajaxprjparticipate').on('click', function (e) {
  var pid;  var uid;
  
  pid     = $(this).attr('pid');
  uid     = $(this).attr('uid');
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

<div id="dataConfirmModal" class="confirm-box" style="display:none;">
    <h3 id="dataConfirmLabel" >You have already participated in the project.</br>Do you want to continue?</h3>   
    <div style="text-align:right;margin-top:10px;">
        <input class="dataConfirmCancel btn btn-secondary" onclick="$('#dataConfirmModal').css('display','none');" type="button" value="Cancel">
        <input class="dataConfirmOK btn btn-primary" onclick="submitparticipant()" type="button" value="Ok">
    </div>
</div>