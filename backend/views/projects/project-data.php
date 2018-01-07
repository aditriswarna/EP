<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\web\UrlManager;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */
/* @var $form yii\widgets\ActiveForm */

$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $projectData[0]['project_id'], 'url' => ['view', 'project_id' => $projectData[0]['project_id']]];
$this->params['breadcrumbs'][] = 'Update';
?>

<style type="text/css">
    #pac-input {
        margin-top: 10px;
        padding: 3px;
        left: 115px !important;
    }
    .labels {
        color: red;
        background-color: white;
        font-family: "Lucida Grande", "Arial", sans-serif;
        font-size: 10px;
        font-weight: bold;
        text-align: center;
        width: 40px;
        border: 2px solid black;
        white-space: nowrap;
    }
</style>


<style type="text/css">
    .divMargin {
        width: 45%;
        float: left;
        margin: 0px 15px 0px 15px;
    }
	.project-label-image, .project-document-name{display:block;padding:10px 0px 0px 0px;}
	.project-label-image label, .project-document-name label{display:block;overflow:visible;position:relative;}
    .project-document-file{display:block;padding:4px 0px;}
	span.img-frm-snap{position:relative;}
	.project-allimg{display:block !important;}
	.projct-imgsfrm{padding:15px 0px 10px 0px;}
    .img-frm-snap img{position:relative;left:-8px;}
    @media (min-width:992px){
        .page-content-wrapper .page-content {
            display: inline-block;
            width: 85%;
        }
    }
</style>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyDL1Xs264nIq1NoVhqtdBThrBa9da3f52k"></script>

<script>
    /*
    function initMap() {
            
            var latitude = 17.385044;
            var longitude = 78.486671;
            
            var map = new google.maps.Map(document.getElementById('map_canvas'), {
              center: {
                lat: latitude,
                lng: longitude
              },
              zoom: 12
            });
            
            <?php //if(!empty($model->project_id)) { ?>
                latitude = parseFloat("<?php //echo $model->latitude; ?>");
                longitude = parseFloat("<?php //echo $model->longitude; ?>");
             <?php //} ?>
             var pos = {
               lat: latitude,
               lng: longitude
             };
            var mapCanvas = document.getElementById("map_canvas");
            var myCenter = new google.maps.LatLng(latitude, longitude); 
            var mapOptions = {center: myCenter, zoom: 12};
            var map = new google.maps.Map(mapCanvas,mapOptions);
            var marker = new google.maps.Marker({
                position: myCenter,
                animation: google.maps.Animation.DROP,
                draggable: true,
                raiseOnDrag: true
            });
            marker.setMap(map);
            
        }
    
        initMap();
        */
        
        function initialize() {
            var latitude = 17.385044;
            var longitude = 78.486671;
            
            <?php if(!empty($projectData[0]['project_id'])) { ?>
                latitude = parseFloat("<?php echo $projectData[0]['latitude']; ?>");
                longitude = parseFloat("<?php echo $projectData[0]['longitude']; ?>");
             <?php } ?>
            var latlng = new google.maps.LatLng(latitude, longitude);
            var myOptions = {
                zoom: 8,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(document.getElementById("map_canvas"),
                    myOptions);
            
            var mapCanvas = document.getElementById("map_canvas");
            var myCenter = new google.maps.LatLng(latitude, longitude); 
            var mapOptions = {center: myCenter, zoom: 12};
            var map = new google.maps.Map(mapCanvas,mapOptions);
            var marker = new google.maps.Marker({
                position: myCenter,
                animation: google.maps.Animation.DROP,
                //draggable: true,
                //raiseOnDrag: true
            });
            marker.setMap(map);
        }
        google.maps.event.addDomListener(window, "load", initialize);

</script>
<div class="projects-update">

    <h1 class="box-title"><?php echo Html::encode('Project Details') ?></h1>
    <div class="projects-form  participation-border">
<?php //print_r($projectData); exit;  ?>
        <div class="col-xs-12 col-sm-6">
            <div class="form-group field-projects-conditions">
                <label class="control-label">Username: </label>
<?php echo Html::input('text', 'username', $projectData[0]['fname'] . ' ' . $projectData[0]['lname'] . ' ' . $projectData[0]['email'] . ' ' . $projectData[0]['user_type'], ['readOnly' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group field-projects-conditions">
                <label class="control-label">Project Category: </label>
<?php echo Html::input('text', 'project_category', $projectData[0]['category_name'], ['readOnly' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group field-projects-conditions">
                <label class="control-label">Project Title: </label>
<?php echo Html::input('text', 'project_title', $projectData[0]['project_title'], ['readOnly' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group field-projects-conditions">
                <label class="control-label">Project Type: </label>
<?php echo Html::input('text', 'project_type', $projectData[0]['project_type'], ['readOnly' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group field-projects-conditions">
                <label class="control-label">Objective: </label>
<?php echo Html::textarea('objective', $projectData[0]['objective'], ['readOnly' => true, 'rows' => 3, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group field-projects-conditions">
                <label class="control-label">Project Description: </label>
<?php echo Html::textarea('project_desc', $projectData[0]['project_desc'], ['readOnly' => true, 'rows' => 5, 'class' => 'form-control ckeditor']) ?>
            </div>
            
            <div id="map_canvas" style="height: 300px; width:auto; position: relative; overflow: hidden; "></div>

            <div class="form-group field-projects-conditions">
                <label class="control-label">Location: </label>
<?php echo Html::input('text', 'location', $projectData[0]['location'], ['readOnly' => true, 'class' => 'form-control']) ?>
            </div>

        </div>
        <div class="col-xs-12 col-sm-6">

            <div class="form-group field-projects-conditions">
                <label class="control-label">Conditions: </label>
<?php echo Html::textarea('conditions', $projectData[0]['conditions'], ['readOnly' => true, 'rows' => 3, 'class' => 'form-control']) ?>
            </div>
            
            <div class="form-group field-projects-conditions">
                <label class="control-label">Targeted Govt Authority: </label>
                <?php $targeted_govt_authority = $projectData[0]['targeted_govt_authority'] == 'Y'?'Yes':'No'?>
<?php echo Html::input('text', 'targeted_govt_authority', $targeted_govt_authority, ['readOnly' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group field-projects-conditions" style="display: <?php if ($projectData[0]['targeted_govt_authority'] == 'N') echo 'none';
else echo 'block'; ?>">
                <label class="control-label">Govt Authority Name: </label>
<?php echo Html::input('text', 'govt_authority_name', $projectData[0]['govt_authority_name'], ['readOnly' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group field-projects-conditions">
                <label class="control-label">Estimated Project Cost: </label>
<?php echo Html::input('text', 'estimated_project_cost', $projectData[0]['estimated_project_cost'], ['readOnly' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group field-projects-conditions">
                <label class="control-label">Participation Type: </label>
            <?php if($projectData[0]['participation_type'] == 'Support'){
                    $participation_type = 'Kind';
                }else if($projectData[0]['participation_type'] == 'Invest'){
                    $participation_type = 'Cash';
                }else{
                    $participation_type = '';
                }
            ?>
            <?php echo Html::input('text', 'participation_type', $participation_type, ['readOnly' => true, 'class' => 'form-control']) ?>
            </div>

                <?php if (!empty($projectData[0]['investment_type'])) { ?>
                <div class="form-group field-projects-conditions">
                    <label class="control-label">Investment Type: </label>
                <?php echo Html::input('text', 'investment_type', $projectData[0]['investment_type'], ['readOnly' => true, 'class' => 'form-control']) ?>
                </div>
            <?php } ?>

                <?php if (!empty($projectData[0]['equity_type'])) { ?>
                <div class="form-group field-projects-conditions">
                    <label class="control-label">Equity Type: </label>
                <?php echo Html::input('text', 'equity_type', $projectData[0]['equity_type'], ['readOnly' => true, 'class' => 'form-control']) ?>
                </div>
            <?php } ?>

                <?php if (!empty($projectData[0]['amount'])) { ?>
                <div class="form-group field-projects-conditions">
                    <label class="control-label">Project Investment Amount: </label>
                <?php echo Html::input('text', 'amount', $projectData[0]['amount'], ['readOnly' => true, 'class' => 'form-control']) ?>
                </div>
            <?php } ?>

                <?php if (!empty($projectData[0]['interest_rate'])) { ?>
                <div class="form-group field-projects-conditions">
                    <label class="control-label">Interest Rate: </label>
                <?php echo Html::input('text', 'interest_rate', $projectData[0]['interest_rate'], ['readOnly' => true, 'class' => 'form-control']) ?>
                </div>
<?php } ?>

            <div class="form-group field-projects-conditions">
                <label class="control-label">Project Start Date: </label>
<?php echo Html::input('text', 'project_category', date("m-d-Y", strtotime($projectData[0]['project_start_date'])), ['readOnly' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group field-projects-conditions">
                <label class="control-label">Project End Date: </label>
<?php echo Html::input('text', 'project_category', date("m-d-Y", strtotime($projectData[0]['project_end_date'])), ['readOnly' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group field-projects-conditions">
                <label class="control-label">Primary Contact No: </label>
<?php echo Html::input('text', 'project_category', $projectData[0]['primary_contact'], ['readOnly' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group field-projects-conditions">
                <label class="control-label">Secondary Contact No: </label>
<?php echo Html::input('text', 'project_category', $projectData[0]['secondary_contact'], ['readOnly' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="form-group field-projects-conditions">
                <label class="control-label">Primary Email Contact: </label>
<?php echo Html::input('text', 'project_category', $projectData[0]['primary_email_contact'], ['readOnly' => true, 'class' => 'form-control']) ?>
            </div>

            <div class="project-allimg">
				<?php if( count(@$allProjectImages)){?>
				 <div class="project-label-image"><label class="control-label">Project Image: </label></div>
			<?php } ?>
			
                    <?php for ($j = 0; $j < count(@$allProjectImages); $j++) { ?>
                    <div class="projct-imgsfrm imageReload_<?php echo $allProjectImages[$j]['project_media_id']; ?>">

                        <?php
                        if (isset($allProjectImages[$j]['document_name'])) {
                            ?>
                            <span class="img-spanpost img-frm-snap"><img style="height:50px;width:50px;" src="https://s3.ap-south-1.amazonaws.com/<?php echo Yii::getAlias('@bucket') . '/uploads/project_images/' . $projectData[0]['project_id'] . '/' . $allProjectImages[$j]['document_name']; ?>" /></span>                            
                            <?php
                        }
                        ?>
                    </div>
<?php } ?>
            </div> 


			
            <div class="project-allimg">
			<?php if( count(@$allProjectDocuments)){?>
				 <div class="project-document-name"><label class="control-label">Document Names: </label></div>
				 
				
			<?php } ?>
			
<?php for ($j = 0; $j < count(@$allProjectDocuments); $j++) { ?>
                    <div class="projct-docsfrm project-document-file projct-docsfrm-border-none imageReload_<?php echo $allProjectDocuments[$j]['project_media_id']; ?>"> 

                        <?php
                        if (isset($allProjectDocuments[$j]['document_name'])) {
                            ?>
                            <span class="img-spanpost img-frm-snap"><?php echo $allProjectDocuments[$j]['document_name']; ?></span>                            
                            <?php
                        }
                        ?>
                    </div>
                <?php } ?>
            </div>
            
            <!-- embedded link -->
            <div class="project-allimg">
                <?php if( count(@$allProjectVideos)){?>
				 <label class="control-label">Embed Videos: </label><br/>
			<?php } ?>
                <?php
                if (isset($allProjectVideos) && !empty($allProjectVideos)) {
                    for ($k = 0; $k < count($allProjectVideos); $k++) {
                ?>
                            <div class="projct-imgsfrm imageReload_<?php echo $allProjectVideos[$k]['project_media_id']; ?>">

                                <span class="img-spanpost img-frm-snap"><?php echo $allProjectVideos[$k]['document_name']; ?></span>                               

                            </div>
                    <?php
                    }
                }
                ?>
            </div>
        </div>
        <div class="approve-buttons"> 

            <?php if ($projectData[0]['project_status'] == 2) { ?>
                <?php echo Html::a('Approve', ['projects/change-project-status', 'status' => 'approve', 'pid' => $projectData[0]['project_id'], 'uid' => $projectData[0]['user_id']], ['class' => 'btn btn-success']) ?>
                <?php echo Html::a('Reject', ['projects/change-project-status', 'status' => 'deactive', 'pid' => $projectData[0]['project_id'], 'uid' => $projectData[0]['user_id']], ['class' => 'btn btn-success']) ?>
            <?php } else if ($projectData[0]['project_status'] == 1) { ?>
                <?php echo Html::a('Reject', ['projects/change-project-status', 'status' => 'deactive', 'pid' => $projectData[0]['project_id'], 'uid' => $projectData[0]['user_id']], ['class' => 'btn btn-success']) ?>
                <?php echo Html::a('Completed', ['projects/change-project-status', 'status' => 'complete', 'pid' => $projectData[0]['project_id'], 'uid' => $projectData[0]['user_id']], ['class' => 'btn btn-success']) ?>
            <?php } else if ($projectData[0]['project_status'] == 3) { ?>
                <?php echo Html::a('Approve', ['projects/change-project-status', 'status' => 'approve', 'pid' => $projectData[0]['project_id'], 'uid' => $projectData[0]['user_id']], ['class' => 'btn btn-success']) ?>
            <?php } ?>
        </div>
    </div>
</div>


<input type="hidden" value="" id="url">
<input type="hidden" value="" id="media_id">

<script>
    function deleteImage() {
        var url = $('#url').val();
        var media_id = $('#media_id').val();
        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
                if (data) {
                    $('#dataConfirmModal').css('display', 'none');
                    $('.imageReload_' + media_id).css('display', 'none');

                }
            },
            error: function (xhr, status, error) {
                //  alert('There was an error with your request.' + xhr.responseText);
            }
        });
        return false;
    }
</script>


<style>
    .confirm-box{
        margin-top: 500px;
    }
</style>