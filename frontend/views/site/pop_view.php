<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\models\Projects;
use frontend\models\ProjectCategory;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = $model->project_id;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->project_title;
$phpdateformat = Yii::getAlias('@phpdateformat');

?>

<link href="<?php echo Yii::$app->getUrlManager()->getBaseUrl(); ?>/css/innerpagestyle.css" rel="stylesheet">

<div class="projects-view popup-cont">

   <!-- <h1><?php //echo Html::encode($this->title)  ?></h1>-->

  



    <div class="custom_pop">
        

        <div class="">
            <div class="pop_title">
                <h1 class="align-left"><?php echo $model->project_title; ?></h1>
            </div>
        <div class="carousel">
               
 <div id="carousel-example-generic1" class="carousel slide overflow-hidden" data-ride="carousel">
   
           
              <?php
            if(count($rows)>0)
             {
             $project_image=array();
             $project_document=array();
            foreach($rows as $key =>$projets)
             { 
             if($projets['document_type']=='projectImage')
             {
              $project_image[$key]['project_ref_id']=$projets['project_ref_id'];
              $project_image[$key]['document_name']=$projets['document_name'];
             }
             if($projets['document_type']=='projectDocument')
             {
               $project_document[$key]['project_ref_id']= $projets['project_ref_id'];
               $project_document[$key]['document_name']=$projets['document_name'];   
                 
             }
             } 
           
            
             if(count($project_image)>0)
             {
             $activeimage=0;
             echo '<ol class="carousel-indicators">';
             foreach($project_image as $key =>$projets)
            { 
           
            $activeimage++;
            if(count($project_image)>1){
                 ?>
             <li data-target="#carousel-example-generic1" data-slide-to="<?=$key;?>" class="<?php echo ($activeimage==1?'active':'');?>"></li>
           <!-- <li data-target="#carousel-example-generic" data-slide-to="<?=$key;?>" class=""></li>
            <li data-target="#carousel-example-generic" data-slide-to="<?=$key;?>" class="active"></li>-->
           <?php }}?>
          </ol>    
           <div class="carousel-inner max-height-330" role="listbox">
               <?php 
               $activeimage=0;
              foreach($project_image as $projets)
              { 
           
                $activeimage++;  
                ?>
            <div class="item <?php echo ($activeimage==1?'active':'');?>">
             <img src="<?php echo SITE_URL. Yii::getAlias('@web').'/uploads/project_images/'.$projets['project_ref_id'].'/'.$projets['document_name'];?>" alt="slide" class="width-100pc">
            </div>
               <?php } 
              if(count($project_image)>1){?>
                 <a class="left carousel-control" href="#carousel-example-generic1" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic1" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
         <?php 
             } }
               else{
                  ?>
               <div class="item active no_imgg">
                           <img src="<?php echo SITE_URL. Yii::getAlias('@web').'/uploads/project_images/no_project_image.jpg'; ?>" alt="No_image" class="width-100px">
                       <?php
                       }
                   } else {
                       ?>
                       <div class="item active no_imgg">
                           <img src="<?php echo SITE_URL. Yii::getAlias('@web').'/uploads/project_images/no_project_image.jpg'; ?>" alt="No_image" class="width-100px">
                       <?php }
                       ?>  
                   </div>
                       
               </div>
         
           
          <?php
          
           if(count($rows)>0)
           {
          if( count( $project_document)>0)
              {
	echo'<div class="row"><div class="col-md-6"><h4 class="category">Documents</h4><ul clas  s="doc-list">'; 
              foreach($project_document as $key=>$projets)
              {  
                  ?> 
            
                   <li><a class="document" href="<?php echo SITE_URL. Yii::getAlias('@web').'/uploads/project_images/'.$projets['project_ref_id'].'/'.$projets['document_name'];?>">Click here to see Document</a>   </li>
                    
         <?php  }
		 echo '</ul></div></div>';}
           }
                 ?>
                   </div>
         <div class=" col-xs-12 ">
                <div class="row style-odd"><div class="col-md-6"><h4>Project location</h4>
                        <p><?php echo $model->location; ?></p></div>
                    <div class="col-md-6"><h4>Project Cost</h4>
                        <p><?php echo $model->estimated_project_cost; ?></p></div>
                </div>
                <div class="row style-even"><div class="col-md-6"><h4>Start Date</h4>
                        <p><?php echo date($phpdateformat, strtotime($model->project_start_date)); ?></p></div>
                    <div class="col-md-6"><h4>End Date</h4>
                        <p><?php echo date($phpdateformat, strtotime($model->project_end_date)); ?></p></div>
                </div>
                <h3>About Project</h3>
                <div   class="mygrid-wrapper-div"><p><?php echo $model->objective; ?></p></div>

            </div>
                    </div>
                    <!-- close for multiple images-->

                    <!-- Controls -->
<!--                    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>-->
                </div>
                <div class="row">
                    <!--<div class="col-md-12 pad-tb10">
                    
                                    <a href="#"  class="size20"><span class="fa fa-map-marker"></span><input type="text" class="add-input"  placeholder="Address"></a>
                    
                    
                    </div>-->
                    <div class="col-xs-12 pad-tb20">

                        <!--<p>Share:<span ><a class="social-iconsfa btn-blue1" href="#"> <span class="fa fa-facebook"> Face Book</span></a></span>
                            <span ><a class="social-iconsfa btn-blue1" href="#"> <span class="fa fa-twitter"> Twitter</span></a></span>
                            <span ><a class="social-iconsfa btn-blue1" href="#"> <span class="fa fa-google-plus"> Google Plus</span></a></span>

                        </p>-->
                    </div>
                </div>



            </div>
            
        </div>
    </div>  
    <?php
    if(isset(Yii::$app->user->id) && Yii::$app->user->id!=0)
    {?>
    <div class="pop-tabs">
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#Description">Description</a></li>
                
                <li><a data-toggle="tab" href="#Comments">Comments</a></li>
                <li><a data-toggle="tab" href="#Investors">Investors List</a></li>
            </ul>
            <br>
            <div class="tab-content mar-btm10">
                <div id="Description" class="tab-pane fade in active">
                    <p><?php echo $model->project_desc; ?></p></div>
                
                
                <div id="Comments" class="tab-pane fade">
                    <div class="row">
                        
                    <!-- <div class="col-md-12">
                            <textarea cols="60" rows="3" placeholder="Comments"></textarea>
                        </div> -->
                    </div>
                </div>
                
                <div id="Investors" class="tab-pane fade">
                    <div class="divOverflow pop-table">
                <?php
                    echo yii\grid\GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            //['attribute' => 'category_name', 'value' => 'project_category.category_name'],

                            'username',
                            'participation_type',
                            [
                                'attribute' => 'investment_type',
                                'value' => function ($data) {
                                    return !empty($data['investment_type']) ? $data['investment_type'] : "";
                                }
                            ],
                            [
                                'attribute' => 'equity_type',
                                'value' => function ($data) {
                                    return !empty($data['equity_type']) ? $data['equity_type'] : "";
                                }
                            ],
                            [
                                'attribute' => 'amount',
                                'value' => function ($data) {
                                    return !empty($data['amount']) ? $data['amount'] : "";
                                }
                            ],
                            [
                                'attribute' => 'interest_rate',
                                'value' => function ($data) {
                                    return !empty($data['interest_rate']) ? $data['interest_rate'] : "";
                                }
                            ],
                            'created_date',
                            
                            //['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]);

                ?>
                </div>
                </div>
                      

            </div>
        </div></div>
    <?php } ?>
</div>
<script>
    $(function(){
       $(".summary").hide(); 
    });
</script>

<style>
.popup-cont h4{color: #52B6B8;}
.popup-cont h3{color: #d85c6f}
.popup-cont h1{font-size: 24px;color: #d85c6f; margin-top:0;}
.popup-cont p {
    font-size:14px;
    font-weight: 300;
    font-family: 'Source Sans Pro', "Helvetica Neue", Helvetica, Arial, sans-serif;
}
.popup-cont .mygrid-wrapper-div{overflow-y: auto; height:auto; padding:0px;}
/*.fancybox-close{top: 5px; right: 10px;  background: url("close-icon.png") no-repeat;}*/
.popup-cont{background: #FFF;}
.fancybox-inner::-webkit-scrollbar-track
{
	
	background-color: #FFF;
}

.fancybox-inner::-webkit-scrollbar
{
	width: 6px;
	background-color: #FFF;
}

.fancybox-inner::-webkit-scrollbar-thumb
{
	background-color: #52B6B8;
	
}
.fancybox-inner::-webkit-scrollbar-track
{
	
	background-color: #FFF;
}
.fancybox-overlay-fixed{    background: rgba(0, 0, 0, 0.77);}
.divOverflow {
    height: 200px;
    overflow-y: auto;
}
</style>