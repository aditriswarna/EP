<?php 
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\UrlManager;
?>
<style>
@media (max-width: 1024px) { 
.fancybox-opened {
width:430px !important;
left: 0 !important;
margin: 0 auto !important;
right: 0 !important;overflow:hidden;}
	
	 }
	 
@media (max-width: 991px){
/*.fancybox-opened {    width:382px !important;}*/
.fancybox-opened {
width:430px  !important;
left: 0 !important;
margin: 0 auto !important;
right: 0 !important;overflow:hidden;}

	
	}
	
	@media (max-width: 385px){
.fancybox-opened {    width:100% !important;  padding: 0 10px; left:auto !important;}
.fancybox-inner div{ width:100% !important}
	
	}
.notifyjs-corner {
    top: 70px !important;
    right: 10px !important;
}
</style>
<script>
    
    function getsite_url()
    {
        var base_url=window.location.host;
        //var url= "http://"+base_url+"/equippp/frontend/web/";
        var url = '<?php echo Yii::getAlias('@web'); ?>';
        return url;      
    }
</script>
 <script src="<?php echo Yii::getAlias('@web'); ?>/js/google_map_new/js/notify.min.js"></script>
 <script src="<?php echo Yii::getAlias('@web'); ?>/js/google_map_new/js/fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::getAlias('@web'); ?>/js/google_map_new/css/storelocator.css" />

<!--<script src="http://code.jquery.com/jquery-1.12.1.min.js"></script>-->
<!--<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>-->
<script src="https://maps.google.com/maps/api/js?libraries=places&key=AIzaSyDXuDv357BE0PHXkhjmjuNK_oG16IiX-oU"></script>
<script src="<?php echo Yii::getAlias('@web'); ?>/js/google_map_new/js/libs/handlebars.min.js"></script>
<script src="<?php echo Yii::getAlias('@web'); ?>/js/google_map_new/js/plugins/storeLocator/jquery.storelocator.js"></script>
<link  rel="stylesheet" type="text/css"  src="<?php echo Yii::getAlias('@web'); ?>/js/google_map_new/css/fancybox.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::getAlias('@web'); ?>/js/google_map_new/css/common-css.css"/>

     <script>
 Handlebars.registerHelper('ifCond', function(v1, v2, options) {
  if(v1 === v2) {
    return options.fn(this);
  }
  return options.inverse(this);
});
Handlebars.registerHelper('ifCondDays', function(v1, v2, options) {
  if(v1 < v2) {
    return options.fn(this);
  }
  return options.inverse(this);
});
 Handlebars.registerHelper('ifTrim', function(v1,options) {
     if(v1)
         {
   return v1.replace(/\D/g,'');
         }
   
});



       </script>

<?php $this->title = "Search Projects"; ?>

<div class="bh-sl-container">
    <div class="mobile-filter">Search Filters&nbsp;&nbsp;<i class="fa fa-filter"></i></div> 
    <div class="bh-sl-form-container">   
        <div class="col-sm-3 enter-location">	
            <form id="bh-sl-user-location" method="post" action="#">
                <div class="form-input">
                           <!-- <div class="auto-complete">-->				
                    <input type="text" id="bh-sl-address" name="bh-sl-address" class="form-control"/>
                   <!--</div>-->
                    <select id="bh-sl-maxdistance" name="bh-sl-maxdistance" style="display:none">
                            <option value="100">100 Miles</option>

                    </select>
                </div>

                <button id="bh-sl-submit" type="submit" style="display:none">Submit</button>
            </form>
        </div>

        <div class="col-xs-12 col-sm-9 pad-0 select-filters">		
            <div class="bh-sl-filters-container">
                <ul id="category-filters-container1" class="bh-sl-filters col-xs-12 col-sm-3">					
                    <li>
                <?php
                    if(isset($_REQUEST['cat']))
                    {
                        $cat=$_REQUEST['cat']; 
                        $model->project_category_id=$cat;    
                    }
                    if(isset($_REQUEST['id']))
                    {
                        $project_id=$_REQUEST['id'];   
                    }
                    if(isset($_REQUEST['uType']))
                    {
                        $uType=$_REQUEST['uType'];   
                        $category->user_type=$uType;    
                    }

                    $form = ActiveForm::begin(['enableClientValidation'=>false,'enableAjaxValidation'=>false,'id' => 'category']);

                    $dataPost=ArrayHelper::map(\frontend\models\ProjectCategory::find()->asArray()->all(), 'project_category_id', 'category_name');/*
                    echo $form->field($model, 'project_category_id')
                    ->dropDownList(
                    $dataPost,['prompt' => 'Select'],           
                    ['id'=>'category_name']
                    );*/


                    /*  echo Html::dropDownList('project_category_id', null,
                    $dataPost,['prompt'=>'Select']); */

                    echo $form->field($model, 'project_category_id')->dropDownList($dataPost,
                            ['prompt' => ' All Categories'],           
                            ['id'=>'user_type']
                        );

                    ActiveForm::end(); 
                ?>
                </li>
            </ul>

            <ul id="user_type_id-filter-container2" class="bh-sl-filters col-xs-12 col-sm-2">
                <?php   
                    $form = ActiveForm::begin(['enableClientValidation'=>false,'enableAjaxValidation'=>false,'id' => 'user_type']); 

                    $dataPost=ArrayHelper::map(\common\models\UserType::find()->where('user_type_id in (3, 5)')->asArray()->all(), 'user_type_id', 'user_type');

                    echo $form->field($category, 'user_type')->dropDownList(
                            $dataPost,['prompt' => 'All User Types'],           
                            ['id'=>'user_type']
                        );
                    ActiveForm::end(); 
                ?>				
            </ul>		
                <ul id="media_agency_ref_id-filter-container3" class="bh-sl-filters col-xs-12 col-sm-2" style="display:none">
                <?php   
                    $form = ActiveForm::begin(['enableClientValidation'=>false,'enableAjaxValidation'=>false,'id' => 'media_agency_ref_id']); 

                    $dataPost=ArrayHelper::map(\common\models\MediaAgencies::find()->asArray()->all(),'media_agency_id', 'media_agency_name');

                    echo $form->field($media_agency,'media_agency_name')->dropDownList(
                            $dataPost,['prompt' => 'All Media'],           
                            ['id'=>'media_agency_ref_id']
                        );
                    ActiveForm::end(); 
                ?>				
            </ul>
            <ul id="project_status-filter-container4" class="bh-sl-filters col-xs-12 col-sm-2">
                <?php   
                    $form = ActiveForm::begin(['enableClientValidation'=>false,'enableAjaxValidation'=>false,'id' => 'project_status']); 
                    $datapost=['1' => 'Active', '4' => 'Completed'];
                    echo $form->field($project_status,'status_name')->dropDownList(
                           $datapost,['prompt' => 'All Projects'],           
                            ['id'=>'project_status']
                        );
                    ActiveForm::end(); 
                ?>				
            </ul>
            <ul id="Organization_name-filter-container5" class="bh-sl-filters col-xs-12 col-sm-2">
                <?php   
                    $form = ActiveForm::begin(['enableClientValidation'=>false,'enableAjaxValidation'=>false,'id' => 'Organization_name']); 
                    
                    $datapost=ArrayHelper::map(\common\models\Projects::find('Organization_name')->where('Organization_name != " "')->groupBy('Organization_name')->asArray()->all(),'Organization_name', 'Organization_name');
                    echo $form->field($project,'Organization_name')->dropDownList(
                           $datapost,['prompt' => 'Organization Name'],           
                            ['id'=>'Organization_name']
                        );
                    ActiveForm::end(); 
                ?>				
            </ul> 
            <ul id="estimated_project_cost-filter-container6" class="bh-sl-filters col-xs-12 col-sm-2">
                <?php   
                 /*   $form = ActiveForm::begin(['enableClientValidation'=>false,'enableAjaxValidation'=>false,'id' => 'estimated_project_cost']); 
                    $datapost=['0 > 500000' => '0 > 500000', '500000 > 1000000' => '500000 > 1000000', '> 1000000'=> '> 1000000'];
                    echo $form->field($project,'estimated_project_cost')->dropDownList(
                           $datapost,['prompt' => 'Budget'],           
                            ['id'=>'estimated_project_cost']
                        );
                    ActiveForm::end(); */
                ?>				
            </ul>

            <!-- <ul id="project_type-filter" class="bh-sl-filters">
                <li class="pub_pri">
                        <input type="radio" name="type"  id="public" value="1"<?php // echo ((Yii::$app->request->get('sector')=="")? "checked=checked":(Yii::$app->request->get('sector')==1)?'checked=checked':"");?> style="display: none;">
                        <label for="public" class="pub-button <?php // echo ((Yii::$app->request->get('sector')=="")? "active":(Yii::$app->request->get('sector')==1)?'active':"")?>">Public</label> <span class="divider"></span>

                        <input type="radio" name="type"  id="private" value="2" style="display: none;"<?php // echo (Yii::$app->request->get('sector')==2 ? 'checked=checked':"");?>>
                         <label for="private" class="pri-button <?php // echo (Yii::$app->request->get('sector')==2 ? 'active':"");?>">Private</label>
                </li>					
            </ul> -->
                <div class="col-xs-4 col-sm-2 reset-but">
                    <input type="button" class="view_all_bt" value="Reset" onclick="location.href='<?php echo Yii::$app->request->baseUrl; ?>/../../search-projects'">
                    <div class="bh-sl-loading pull-right"></div>
                </div>

            </div>
        </div>
    </div>

    <div id="bh-sl-map-container" class="bh-sl-map-container">
        <div id="bh-sl-map" class="bh-sl-map"></div>
        <div class="bh-sl-loc-list">
            <ul class="list"></ul>
        </div>
        <div class="project-details" style="display:none"></div>
    </div>        
</div> 


<script>
    $(function() {
           
        $('#bh-sl-map-container').storeLocator({
                'maxDistance': true,

                'taxonomyFilters' : {
                        'category' : 'category-filters-container1',
                        'user_type_id' : 'user_type_id-filter-container2',
                        'media_agency_ref_id': 'media_agency_ref_id-filter-container3',
                        'project_status':'project_status-filter-container4',
                        'project_type_ref_id':'project_type-filter',
                        'Organization_name':'Organization_name-filter-container5'
                       // 'estimated_project_cost':'estimated_project_cost-filter-container6'                        
                        //'city' : 'city-filter',
                        //'postal': 'postal-filter'
                },

              //  markerDim:{ height: 20, width: 20 }
        });
	
           /*  $('.auto-complete').storeLocator({
			'autoComplete': true
		});*/
              
            
      
        
        // var projectId=0;
    
   $('.list').on('click','li',function(e)
   {
      projectId=$(this).find('.project_id').text(); 
  
      if(projectId!="")
   {
    // var projectdata=projectId.split("-");
    $.ajax({
    url: getsite_url()+'site/is-private',
    type: "post",
    data:'id='+projectId,
    success: function(html){
        if(html == 'set_profile')
            {    
              fancyConfirm('<span class="fancy_msg">Please set your profile before requesting access for this project </span>', function() {
                   do_something('redirect_to_profile_page');
                }, function() {
                    do_something('no');
                });
            }
     
        else if(html == 'request')
        {    
            fancyConfirm('<span class="fancy_msg">Are you sure you want to request access for this project?</span>', function() {
               do_something('yes');
            }, function() {
                do_something('no');
            });
         }
        else if(html=='already_requested')
           {
               
             $.notify("You have already requested access for this project", "info");  
                  
           }
       else if(html=='yes')
     {       
        if (window.matchMedia("(max-width: 1024px)").matches) {
            //$('.project-detail-black-pane').css({'min-height' : '645px', 'white-space' : 'inherit','overflow':'hidden'});
        } 
       $(".project-details").css('display','block');
     $( $(".project-details").html(' Please wait loading..... '))
      $.ajax({
  url: getsite_url()+'/site/get-data',
  type: "post",
  data:'id='+projectId,
  success: function(html){
    $(".project-details").html('');
    $(".project-details").append(html); 
    if (window.matchMedia("(max-width: 1024px)").matches) {
            //$('.project-detail-black-pane').css({'min-height' : '645px', 'white-space' : 'inherit','overflow':'hidden'});
    } 
     $(".project-details").css('display','block');
       if($(window).width() >=740) 
        {
    $(".project-details").addClass('prod');
	var ProList = $(".bh-sl-loc-list").outerWidth(); 
        //console.log(ProList);
		var MapList = $(".bh-sl-map").outerWidth(); 
               
		var PPsList = $(".project-details").outerWidth();
                
		$(".project-details").animate({marginRight : ProList});
             //  var maps =new google.maps.Map('bh-sl-map');
                map.panBy(250,0);
            }
  },
  error: function (request, status, error) {
                alert(request.responseText);
              }
});    
    }
          else{ 
           
     if($(".project-details").hasClass('prod'))
         {
         $(".project-details").animate({marginRight : -$(".project-details").outerWidth()}).removeClass('prod');
         }
          $('#loginModal').trigger('click');
         // e.stopImmediatePropagation();
          }
             
    }
    });
  
    }
   });
   
   
  
function fancyConfirm(msg,callbackYes,callbackNo) {
    var ret;
    jQuery.fancybox({
        'modal' : true,
        'content' : "<div style=\"margin:1px;width:350px;\">"+msg+"<div style=\"text-align:right;margin-top:10px;\"><input id=\"fancyConfirm_ok\" class=\"btn btn-blue-tp pad-tb5 size16\" style=\"margin:3px;padding:0px;\" type=\"button\" value=\"OK\"><input id=\"fancyconfirm_cancel\" style=\"margin:3px;padding:0px;\" type=\"button\" class=\"btn btn-pink-tp pad-tb5 size16\"  value=\"Cancel\"></div></div>",
        'beforeShow' : function() {             
            jQuery("#fancyconfirm_cancel").click(function() {
              
                
                 callbackNo();
               
                  $.fancybox.close();
                
            });
            
            jQuery("#fancyConfirm_ok").click(function() {
               
                
                 callbackYes();
              
                    
                    $.fancybox.close(); 
               
            });
        },
        afterLoad: function () {  
            if (window.matchMedia('(max-width: 750px)').matches) {                
               // $(this).scrollTop($(document).height()); 
               // $(this).css('margin-top','100%');
                $('.fancybox-opened').css('top','50%');
                // $(window).scrollTop($(document).height());
            }                          
        },
    });
}

    function  do_something(a){
       if(a=='yes')
       {
          var id=$('.list-focus .project_id').text();
       //   id=id.split('-');
               $.ajax({
               url: getsite_url()+'site/insert-request',
               type: "post",
               data:'id='+id,
               success: function(html){ 
                   if(html=="success")               
                        $.notify("Your Request has been sent successfully", "success");
                    else
                  
                        $.notify("We have some problem in sending request please try later", "warning");
                    }
                });
       }
       else if(a == 'redirect_to_profile_page'){
           window.location.replace(getsite_url()+"/profile");
       }
    }
	
        
	
	
       
    
    $('.pub-button').bind('click',function()
    {   
    $('.pri-button').removeClass('active'); 
    $('.pub-button').addClass('active');
        
    });

$('.pri-button').bind('click',function()
    {  
    $('.pub-button').removeClass('active'); 
    $('.pri-button').addClass('active'); 
        
    });
    
    //for media partner
    $('#usertype-user_type').on('change',function()
    {
    if($('#usertype-user_type').val()=="9")
        {
    $('#media_agency_ref_id-filter-container3').css('display','block');
        }
        else{
           $('#media_agency_ref_id-filter-container3').css('display','none');   
        }
    });
    
    
     });
  
</script>
   <!--for showing slidet data on click-->
<?php 
if(isset($project_id)&&$project_id)
{?>
   <script>
      $(window).load(function(){  
   $(".bh-sl-loc-list #project_id_<?=$project_id;?>").trigger('click');
       });
</script>
    
<?php
}
?>

<!-- for fixing map to window size-->
<script>
    $(function(){
  var total_height=$(window).height();
  var header_top=$('.navbar-fixed-top').height();
  var total_header=header_top+60;
  var project_map= total_height-total_header;
  $('.bh-sl-loc-list,.bh-sl-map,.project-details').css('height',project_map+'px');
   
   $('.form-control').on('change',function()
{
  $('.project-details').css('display','none'); 
        
});

$('input[type="radio"]').on('click', function(e) {
   $('.project-details').css('display','none'); 
});

$('.mobile-filter').on('click',function(){
   $('.bh-sl-form-container').toggleClass('showdiv');
    });
    });
   </script>

<!--<script src="../js/google_map/showprojects/dist/assets/js/plugins/storeLocator/jquery.storelocator.js"></script>-->
   
