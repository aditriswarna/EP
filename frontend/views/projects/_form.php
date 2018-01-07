<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\web\UrlManager;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Url;
use common\models\Storage;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */
/* @var $form yii\widgets\ActiveForm */
$dateformat = Yii::getAlias('@phpdatepickerformat');
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
<!--<script type="text/javascript" src="http://www.google.com/jsapi"></script>-->
<!--<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=places&sensor=false&key=AIzaSyDXuDv357BE0PHXkhjmjuNK_oG16IiX-oU"></script>-->
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyDL1Xs264nIq1NoVhqtdBThrBa9da3f52k"></script>
<!--<script type="text/javascript" src="Scripts/marker.js"></script>-->
<script type="text/javascript">

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

            
            
            <?php if(empty($model->project_id)) { ?>
                var startPos;
                var geoSuccess = function(position) {
                    startPos = position;
                    //alert(startPos.coords.latitude+"______"+startPos.coords.longitude);
                    //document.getElementById('startLat').value = startPos.coords.latitude;
                    //document.getElementById('startLon').value = startPos.coords.longitude;
                    latitude = startPos.coords.latitude
                    longitude = position.coords.longitude;
                };
                navigator.geolocation.getCurrentPosition(geoSuccess);
             <?php } else { ?>
                 latitude = parseFloat("<?php echo $model->latitude; ?>");
                 longitude = parseFloat("<?php echo $model->longitude; ?>");
             <?php } ?>
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
            
            google.maps.event.addListener(marker, 'dragend', function () {
                geocodePosition(marker.getPosition());
            });

            var searchBox = new google.maps.places.SearchBox(document.getElementById('pac-input'));
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(document.getElementById('pac-input'));
            google.maps.event.addListener(searchBox, 'places_changed', function() {
                searchBox.set('map_canvas', null);
                
                var places = searchBox.getPlaces();
                
                marker.setMap(null);

                var bounds = new google.maps.LatLngBounds();
                var i, place;
                for (i = 0; place = places[i]; i++) {
                    (function(place) {
                        var marker = new google.maps.Marker({
                            position: place.geometry.location,
                            draggable: true,
                            raiseOnDrag: true
                        });
                        marker.bindTo('map', searchBox, 'map');
                        marker.setMap(null);
                        google.maps.event.addListener(marker, 'map_changed', function() {
                            if (!this.getMap()) {
                                this.unbindAll();
                            }
                        //geocodePosition(marker.getPosition());
                        });
                        google.maps.event.addListener(marker, 'dragend', function () {
                            geocodePosition(marker.getPosition());
                        });
                        bounds.extend(place.geometry.location);
                        
                        geocodePosition(marker.getPosition());
                        
                    }(place));

                }
                map.fitBounds(bounds);
                searchBox.set('map', map);
                map.setZoom(Math.min(map.getZoom(),12));

            });
        }
        
        function geocodePosition(pos) {
            geocoder = new google.maps.Geocoder();
            geocoder.geocode ({
                latLng: pos
            },
            function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    document.getElementById("projects-location").value = results[0].formatted_address;
                    document.getElementById("projects-latitude").value = results[0].geometry.location.lat();
                    document.getElementById("projects-longitude").value = results[0].geometry.location.lng();
                }
                else {
                    //$("#mapErrorMsg").html('Cannot determine address at this location.' + status).show(100);
                }
            });
        }
        
        /*
        window.onload = function() {
            //alert("Hello");
            var startPos;
            var geoSuccess = function(position) {
                startPos = position;
                alert(startPos.coords.latitude+"______"+startPos.coords.longitude);
                document.getElementById('startLat').value = startPos.coords.latitude;
                document.getElementById('startLon').value = startPos.coords.longitude;
            };
            navigator.geolocation.getCurrentPosition(geoSuccess);
        };
        */

        //google.maps.event.addDomListener(window, 'load', init);
</script>
<!--<script src="https://maps.googleapis.com/maps/api/js?libraries=places&sensor=false&key=AIzaSyDXuDv357BE0PHXkhjmjuNK_oG16IiX-oU&callback=initMap"></script>-->
<!--<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDL1Xs264nIq1NoVhqtdBThrBa9da3f52k&callback=initMap"></script>-->

<script>
            
    $(function () {
var estimated_project_cost = document.getElementById('projects-estimated_project_cost');
		var projectsamount = document.getElementById('projects-amount');

estimated_project_cost.onkeydown = function(e) {
    if(!((e.keyCode > 95 && e.keyCode < 106)
      || (e.keyCode > 47 && e.keyCode < 58) 
      || e.keyCode == 8)) {
        return false;
    }
}
projectsamount.onkeydown = function(e) {
    if(!((e.keyCode > 95 && e.keyCode < 106)
      || (e.keyCode > 47 && e.keyCode < 58) 
      || e.keyCode == 8)) {
        return false;
    }
}
<?php if (empty($projectParticipationData['investment_type'])) { ?>
                    $('.field-projects-investment_type').attr('style', 'display: none');
<?php }
if (empty($projectParticipationData['equity_type'])) {
    ?>
                    $('.field-projects-equity_type').attr('style', 'display: none');
<?php }
if (empty($projectParticipationData['amount'])) {
    ?>
                    $('.field-projects-amount').attr('style', 'display: none');
<?php }
if (empty($projectParticipationData['interest_rate'])) {
    ?>
                    $('.field-projects-interest_rate').attr('style', 'display: none');
<?php } 
if (empty($model['govt_authority_name'])) {
    ?>
            $('#govt_authority').css('display', 'none');
<?php } ?>
                       /* $('#projects-investment_type').val(0);
                        $('#projects-equity_type').val(0);
                        $('#projects-amount').val('');
                        $('#projects-interest_rate').val(''); */
        
                $('#projects-participation_type').on('change', function() {
                    if($('#projects-participation_type').val() == "Support") {
                        $('.field-projects-investment_type').attr('style', 'display: none');
                        $('.field-projects-equity_type').attr('style', 'display: none');
                        $('.field-projects-amount').attr('style', 'display: none');
                        $('.field-projects-interest_rate').attr('style', 'display: none');

                        $('#projects-investment_type').val(0);
                        $('#projects-equity_type').val(0);
                        $('#projects-amount').val('');
                        $('#projects-interest_rate').val('');
                    } else if($('#projects-participation_type').val() == "Invest") {
                        $('.field-projects-investment_type').attr('style', 'display: block');
                        //$('.field-projects-equity_type').attr('style', 'display: block');
                        //$('.field-projects-amount').attr('style', 'display: block');
                        //$('.field-projects-interest_rate').attr('style', 'display: block');
                    } else {
                        $('.field-projects-investment_type').attr('style', 'display: none');
                        $('.field-projects-equity_type').attr('style', 'display: none');
                        $('.field-projects-amount').attr('style', 'display: none');
                        $('.field-projects-interest_rate').attr('style', 'display: none');
                    }
                });

                $('#projects-investment_type').on('change', function() {
                    if($('#projects-investment_type').val() == "Grant") {
                        $('.field-projects-equity_type').attr('style', 'display: none');
                        $('.field-projects-amount').attr('style', 'display: block');
                        $('.field-projects-interest_rate').attr('style', 'display: none');
                        $('#projects-equity_type').val('');
                        $('#projects-amount').val('');
                        $('#projects-interest_rate').val('');
                    } else if($('#projects-investment_type').val() == "Equity") {
                        $('.field-projects-equity_type').attr('style', 'display: block');
                        $('.field-projects-amount').attr('style', 'display: none');
                        $('.field-projects-interest_rate').attr('style', 'display: none');
                    } else {
                        $('.field-projects-equity_type').attr('style', 'display: none');
                        $('.field-projects-amount').attr('style', 'display: none');
                        $('.field-projects-interest_rate').attr('style', 'display: none');
                    }
                });

                $('#projects-equity_type').on('change', function() {
                    if($('#projects-equity_type').val() == "Interest_Earning") {
                        $('.field-projects-interest_rate').attr('style', 'display: block');
                        $('.field-projects-amount').attr('style', 'display: block');
                    } else if($('#projects-equity_type').val() == "Principal_Protection") {
                        $('.field-projects-interest_rate').attr('style', 'display: none');
                        $('.field-projects-amount').attr('style', 'display: block');
                    
                        $('#projects-interest_rate').val('');
                    } else {
                        $('.field-projects-interest_rate').attr('style', 'display: none');
                        $('.field-projects-amount').attr('style', 'display: none');
                    
                        $('#projects-interest_rate').val('');
                    }
                });
            });
       
            function checkErrors() {

                $('#errorParticipationType').css('display', 'none');
                $('#errorInvestmentType').css('display', 'none');
                $('#errorEquityType').css('display', 'none');
                $('#errorAmount').css('display', 'none');
                $('#errorInterestRate').css('display', 'none');
            
                if($('#projects-participation_type').val() == "Invest" && $('#projects-investment_type').val() == "") {
                    $('#errorInvestmentType').html('Select project investment type');
                    $('#errorInvestmentType').css('display', 'block');
                    return false;
                }
            
                if($('#projects-participation_type').val() == "Invest" && $('#projects-investment_type').val() == "Grant" && 
                    $('#projects-amount').val().trim() == "") {
                    $('#errorAmount').html('Project Cash Amount should not be empty');
                    $('#errorAmount').css('display', 'block');
                    return false;
                }
            
                if($('#projects-participation_type').val() == "Invest" && $('#projects-investment_type').val() == "Equity" && 
                    $('#projects-equity_type').val() == "") {
                    $('#errorEquityType').html('Project Equity Type should not be empty');
                    $('#errorEquityType').css('display', 'block');
                    return false;
                }
            
                if($('#projects-participation_type').val() == "Invest" && $('#projects-investment_type').val() == "Equity" && 
                    $('#projects-equity_type').val() == "Principal_Protection" && $('#projects-amount').val().trim() == "") {
                    $('.field-projects-equity_type').css('display','block');
                    $('.field-projects-amount').css('display','block');
                    $('#errorAmount').html('Project Cash Amount should not be empty');
                    $('#errorAmount').css('display', 'block');
                    $('#errorInterestRate').css('display', 'none');
                    return false;
                }
            
                if($('#projects-participation_type').val() == "Invest" && $('#projects-investment_type').val() == "Equity" && 
                    $('#projects-equity_type').val() == "Interest_Earning") {
                    var flag = true;
                    if($('#projects-amount').val().trim() == "") {
                        $('#errorAmount').html('Project Cash Amount should not be empty');
                        $('#errorAmount').css('display', 'block');
                        flag = false;
                    }
                    if($('#projects-interest_rate').val().trim() == "") {
                        $('#errorInterestRate').html('Project Interest Rate should not be empty');
                        $('#errorInterestRate').css('display', 'block');
                        flag = false;
                    }
                    return flag; 
                }
            
                if( parseInt($('#projects-estimated_project_cost').val()) > 0 && parseInt($('#projects-amount').val()) > 0 &&
                    (parseInt($('#projects-amount').val()) > parseInt($('#projects-estimated_project_cost').val())) )
                {
                    $('#errorAmount').html('Project Investment Amount should not be greater than Estimated Project Cost');
                    $('#errorAmount').css('display', 'block');
                    return false;
                }
            
                $('#errorGovtAuthorityName').css('display', 'none');
            
                if($('#projects-targeted_govt_authority').val() == 'Y' && $('#projects-govt_authority_name').val() == '')
                {
                    $('#errorGovtAuthorityName').css('display', 'block');

                    return false;
                }
                if($('.youtube_error').text()!="")
                {    
                  return false;  
                }
                $('.projects-document').each(function () {
                    var valid = true;                                       
                    for (var i = 0; i < this.files.length; i++)
                    {                        
                        var imageSize = this.files[i].size;                         
                        if (imageSize > 45388608)
                        {
                           //show an alert to the user
                           alert(this.files[i].name+ " Allowed file size exceeded. (Max. 40 MB)")

                           //reset file upload control
                            // this.value = null;
                            valid =  false;
                        }else {
                            valid = true;                           
                        }
                    }            

                     return valid;
                 }); 
                return true;
            }
           
        
            function showGovtAuthority() {
                $('#govt_authority').css('display', 'none');
            
                if( $('#projects-targeted_govt_authority').val() == 'Y')
                {
                    $('#govt_authority').css('display', 'block');
                    return false;
                }
            }
            
            $(function()
           {    
            $('.field-projects-embed_videos').on('blur','.youtube_link',function()
             {  
              var youtube_src=($(this).val());
              if(youtube_src)
               {
            if(youtube_src.match('https://www.youtube.com/embed'))
             {
              $('.youtube_error').text();
                return true;
             }
             else
                 {
                   $('.youtube_error').text('please enter valid youtube link');
                    return false;
                 }
                 }
                 else
                    {
                       $('.youtube_error').text('');  
                        return true;   
                     }
           }); 
              
           });
</script>

<style type="text/css">
    .divMargin {
        width: 45%;
        float: left;
        margin: 0px 15px 0px 15px;
    }
    @media (min-width:992px){
        .page-content-wrapper .page-content {
            /* display: inline-block;
             width: 85%;*/
        }
    }
   /* .formHide, #errorGovtAuthorityName {
        display: none;
    } */
    #errorGovtAuthorityName {
        display: none;
    }
</style>
    <?php
    if (isset($model->project_end_date))
        $model->project_end_date = date("d-m-Y", strtotime($model->project_end_date));
    if (isset($model->project_start_date))
        $model->project_start_date = date("d-m-Y", strtotime($model->project_start_date));
    ?>
<div class="projects-form participation-border ">

        <?php
        $form = ActiveForm::begin(['options' => [
                        'enctype' => 'multipart/form-data', 'class' => 'project_create']]);
        ?>

    <div class="col-xs-12 col-sm-6">
        <?php echo $form->field($model, 'project_category_ref_id')->dropDownList($projectCategories, ['options' => [$model->project_category_ref_id => ['Selected' => true]]]) ?>

        <?php echo $form->field($model, 'project_title')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'project_type_ref_id')->dropDownList($projectTypes) ?>

        <?php echo $form->field($model, 'objective')->textarea(['rows' => 3]) ?>

        <?php echo $form->field($model, 'project_desc')->textarea(['rows' => 6, 'class' => 'ckeditor']) ?>
		<div id="errorProjDesc" class="help-block" style="display: none; margin: -17px 49px 15px; color: #e73d4a;">Html content is not allowed</div>
        
<!--        <input id="startLat" class="controls" type="text" placeholder="Search Location" size="50">
        <input id="startLon" class="controls" type="text" placeholder="Search Location" size="50">-->
        
        <input id="pac-input" class="controls" type="text" placeholder="Search Location" size="50" style="left: 115px;">
        <div id="map_canvas" style="height: 300px; width:auto; position: relative; overflow: hidden; "></div>    
        <?php echo $form->field($model, 'location')->textInput(['maxlength' => true]) ?>

        <?php echo $form->field($model, 'latitude')->hiddenInput()->label(false) ?>

        <?php echo $form->field($model, 'longitude')->hiddenInput()->label(false) ?>   

        <?php if (isset(Yii::$app->session['userType']) && Yii::$app->session['userType'] == 5) { ?>
            <?php // echo $form->field($model, 'CSR_project_type')->dropDownList([ 'SelfFoundation' => 'SelfFoundation', 'OtherSociety' => 'OtherSociety',], ['prompt' => '']) ?>
            <?php echo $form->field($model, 'Organization_name')->textInput(['maxlength' => true]) ?>
            <?php // echo $form->field($model, 'CSR_website')->textInput(['maxlength' => true]) ?>
<?php } ?>
    </div>
    <div class="col-xs-12 col-sm-6">
<?php echo $form->field($model, 'conditions')->textarea(['rows' => 3]) ?>

        <?php echo $form->field($model, 'targeted_govt_authority')->dropDownList([ 'N' => 'No', 'Y' => 'Yes'], ['onChange' => 'showGovtAuthority()']) ?>

        <div id="govt_authority" class='formHide'>
        <?php echo $form->field($model, 'govt_authority_name')->textInput(['onBlur' => 'checkErrors()']) ?>
            <div id="errorGovtAuthorityName" class="help-block" style="display: none; margin: -17px 49px 15px; color: #e73d4a;">Government Authority Name should not be empty</div>
        </div>

        <?php echo $form->field($model, 'estimated_project_cost')->textInput(['type' => 'number', 'min' => '0']) ?>
        
        <?php $model->participation_type = isset($projectParticipationData['participation_type']) ? $projectParticipationData['participation_type'] : ''; ?>        
        <?php echo $form->field($model, 'participation_type')->dropDownList([ 'Support' => 'Kind', 'Invest' => 'Cash',], ['onChange' => 'checkErrors()']) ?>
        <div id="errorParticipationType" class="help-block" style="display: none; margin: -17px 49px 15px; color: #e73d4a;">Select project participation type</div>
        
        <?php $model->investment_type = isset($projectParticipationData['investment_type']) ? $projectParticipationData['investment_type'] : ''; ?>
        <?php echo $form->field($model, 'investment_type')->dropDownList([ ''=>'Select','Equity' => 'Equity', 'Grant' => 'Grant',], ['onChange' => 'checkErrors()']) ?>
        <div id="errorInvestmentType" class="help-block" style="display: none; margin: -17px 49px 15px; color: #e73d4a;">Select project investment type</div>

        <?php $model->equity_type = isset($projectParticipationData['equity_type']) ? $projectParticipationData['equity_type'] : ''; ?>        
        <?php echo $form->field($model, 'equity_type')->dropDownList([ ''=>'Select', 'Principal_Protection' => 'Principal Protection', 'Interest_Earning' => 'Interest Earning',], ['onChange' => 'checkErrors()']) ?>
        <div id="errorEquityType" class="help-block" style="display: none; margin: -17px 49px 15px; color: #e73d4a;">Select project equity type</div>
        <?php //}
        //if($model->project_id && !empty($projectParticipationData['amount'])) { 
        ?>
        <?php echo $form->field($model, 'amount')->textInput(['type' => 'number', 'min' => '0', 'value' => @$projectParticipationData['amount'], 'onBlur' => 'checkErrors()']) ?>
        <div id="errorAmount" class="help-block" style="display: none; margin: -17px 49px 15px; color: #e73d4a;"></div>
        <?php //}
        //if($model->project_id && !empty($projectParticipationData['interest_rate'])) { 
        ?>
        <?php echo $form->field($model, 'interest_rate')->textInput(['type' => 'number', 'min' => '0', 'value' => @$projectParticipationData['interest_rate'], 'onBlur' => 'checkErrors()']) ?>
        <div id="errorInterestRate" class="help-block" style="display: none; margin: -17px 49px 15px; color: #e73d4a;">Select project interest rate</div>
        <?php //} ?>

        <?php
        echo $form->field($model, 'project_start_date')->widget(DatePicker::classname(), [
            'value' => @$value, 'dateFormat' => $dateformat, 'value' => date('Y-m-d'), 'options' => ['class' => 'form-control'],
            'clientOptions' => [
                'changeMonth' => true,
                'yearRange' => "2000:(date('Y')+5)",
                'changeYear' => true,
                'showOn' => 'button',
                'buttonImage' => 'images/calendar.gif',
                'buttonImageOnly' => true,
                'buttonText' => 'Select date',
                'buttonImage' => Yii::$app->request->BaseUrl . '/../images/calendar.gif',
                'onSelect' => new \yii\web\JsExpression("function(dateStr) {
                        $('#projects-project_end_date').val('');     
                        $( '#projects-project_end_date' ).datepicker( 'option', 'disabled', false );
                        var toDate = $(this).datepicker('getDate');
                        var fromDate = $(this).datepicker('getDate');
                        fromDate.setDate(toDate.getDate()+1)                        
                        $('#projects-project_end_date').datepicker('option', 'minDate', fromDate); 
                        }"),
                /*  toDate.setDate(toDate.getDate()+14600),  $('#projects-project_end_date').datepicker('option', 'maxDate', toDate);*/
                ],
        ])->textInput(['readonly' => true]);
        ?>

        <?php
        echo $form->field($model, 'project_end_date')->widget(DatePicker::classname(), [
            'value' => @$value, 'dateFormat' => $dateformat, 'value' => date('Y-m-d'),
            'options' => ['class' => 'form-control'],
            'clientOptions' => [
                'changeMonth' => true,
                'yearRange' => '2000:2070',
                'changeYear' => true,
                'showOn' => 'button',
                'buttonImage' => 'images/calendar.gif',
                'buttonImageOnly' => true,
                'buttonText' => 'Select date',
                'disabled' => 'true',
                'buttonImage' => Yii::$app->request->BaseUrl . '/../images/calendar.gif',
                ],
        ])->textInput(['readonly' => true]);
        ?>

<?php echo $form->field($model, 'primary_contact')->textInput(['maxlength' => true, 'value' => !empty($userData[0]['mobile']) ? $userData[0]['mobile'] : (!empty($model->primary_contact) ? $model->primary_contact : '')]) ?>

            <?php echo $form->field($model, 'secondary_contact')->textInput(['maxlength' => true]) ?>

                <?php echo $form->field($model, 'primary_email_contact')->textInput(['maxlength' => true, 'value' => !empty($userData[0]['email']) ? $userData[0]['email'] : (!empty($model->primary_email_contact) ? $model->primary_email_contact : '')]) ?>

                <?php echo $form->field($model, 'project_image[]')->fileInput(['multiple' => true, 'class' => 'multi with-preview accept-gif|jpg|png|jpeg', 'maxlength'=>'7']); ?>

        <div class="project-allimg">
                <?php for ($j = 0; $j < count(@$allProjectImages); $j++) { ?>
                <div class="projct-imgsfrm imageReload_<?php echo $allProjectImages[$j]['project_media_id']; ?>">

                    <?php
                    $bucket = Yii::getAlias('@bucket');
                    $keyname = 'uploads/project_images/'.$model->project_id.'/'.$allProjectImages[$j]['document_name'];
                    $s = new Storage();
                    $file = $s->download($bucket,$keyname);                    
                    if (isset($allProjectImages[$j]['document_name']) && isset($file['@metadata']['effectiveUri'])) {
                    ?>
                        <span class="img-spanpost"><img style="height:50px;width:50px;" src="<?php echo $file['@metadata']['effectiveUri']; ?>" /></span>
                        <span class="img-abstspan"><span data-url ="<?php echo Url::toRoute(['delete-project-image', 'pmid' => $allProjectImages[$j]['project_media_id'], 'uid' => $userData[0]['id'], 'dname' => $allProjectImages[$j]['document_name'], 'pid' => $allProjectImages[$j]['project_ref_id']]); ?>" pjax-container='pjax-list' data-confirm='Are you sure you want to delete this image?' class="glyphicon glyphicon-remove-sign rvme-clse ajaxDelete" data-pmid="<?php echo $allProjectImages[$j]['project_media_id']; ?>"></span></span>
                    <?php
                    } 
                    ?>
                </div>
            <?php } ?>
        </div> 

                <?php echo $form->field($model, 'document_name[]')->fileInput(['multiple' => true, 'class' => 'multi with-preview projects-document accept-gif|jpg|png|jpeg|pdf|doc|docx|txt', 'maxlength'=>'7']); ?>

        <div class="project-allimg doc-uploads">
                <?php if (isset($allProjectDocuments) && !empty($allProjectDocuments)) {
                    for ($j = 0; $j < count($allProjectDocuments); $j++) {
                        ?>
                    <div class="projct-imgsfrm imageReload_<?php echo $allProjectDocuments[$j]['project_media_id']; ?>">

                        <?php
                        if (isset($allProjectDocuments[$j]['document_name'])) {
                        ?>
                            <span class="img-spanpost"><?php echo $allProjectDocuments[$j]['document_name']; ?></span>
                            <span class="img-abstspan"><span data-url ="<?php echo Url::toRoute(['delete-project-image', 'pmid' => $allProjectDocuments[$j]['project_media_id'], 'uid' => $userData[0]['id'], 'dname' => $allProjectDocuments[$j]['document_name'], 'pid' => $allProjectDocuments[$j]['project_ref_id']]); ?>" pjax-container='pjax-list' data-confirm='Are you sure you want to remove this document?' class="glyphicon glyphicon-remove-sign rvme-clse ajaxDelete" data-pmid="<?php echo $allProjectDocuments[$j]['project_media_id']; ?>"></span></span>
                        <?php
                        }
                        ?>
                    </div>
                <?php }
                }
                ?>
        </div> 
<span class="add-more-icon"><a href="javascript:void(0);" class="add_more" title="Add field"><i class="icon-plus"></i></a></span>
<?php echo $form->field($model, 'embed_videos[]')->textarea(['rows' => 2,'class'=>'form-control youtube_link']);?>
<div class="youtube_error help-block"></div>
 <div class="project-allimg doc-uploads">
                <?php if (isset($allProjectVidoes) && !empty($allProjectVidoes)) {
                    for ($j = 0; $j < count($allProjectVidoes); $j++) {
                        ?>
                    <div class="projct-imgsfrm imageReload_<?php echo $allProjectVidoes[$j]['project_media_id']; ?>">

                            <span class="img-spanpost"><?php echo $allProjectVidoes[$j]['document_name']; ?></span>
                            <span class="img-abstspan"><span data-url ="<?php echo Url::toRoute(['delete-youtube-link', 'pmid' => $allProjectVidoes[$j]['project_media_id'], 'dname' => $allProjectVidoes[$j]['document_name'], 'pid' => $allProjectVidoes[$j]['project_ref_id']]); ?>" pjax-container='pjax-list' data-confirm='Are you sure you want to remove this youtube Link?' class="glyphicon glyphicon-remove-sign rvme-clse ajaxDelete" data-pmid="<?php echo $allProjectVidoes[$j]['project_media_id']; ?>"></span></span>

                    </div>
    <?php }
}
?>
        </div> 

    </div>
    <div class="crat-but" >

        <div class="form-group">
    <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'onClick' => 'return checkErrors()']) ?>        
        </div>
    </div>

    <?php ActiveForm::end();
    ?>
    <?php
    $this->registerJs(" $(document).on('ready', function () { 
  $('.ajaxDelete').on('click', function (e) {
    e.preventDefault();
    
    $('#url').val($(this).attr('data-url'));
    $('#media_id').val($(this).attr('data-pmid'));
    
    $('#dataConfirmLabel').text($(this).attr('data-confirm'));
    $('#dataConfirmModal').css('display','block');
   
    return false;
 
});
  
}); 

");
    ?>
</div>
<input type="hidden" value="" id="url">
<input type="hidden" value="" id="media_id">

<div id="dataConfirmModal" class="confirm-box" style="display:none;position:absolute;">
    <h3 id="dataConfirmLabel" >Please Confirm</h3>   
    <div style="text-align:right;margin-top:10px;">
        <input class="dataConfirmCancel btn btn-secondary" onclick="$('#dataConfirmModal').css('display','none');" type="button" value="Cancel">
        <input class="dataConfirmOK btn btn-primary" onclick="deleteImage()" type="button" value="Ok">
    </div>
</div>  
<script>
    $(function(){
        initMap();
    });   
    function deleteImage(){
        var url = $('#url').val();
        var media_id= $('#media_id').val();
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data){       
                if(data){                 
                    $('#dataConfirmModal').css('display','none');                 
                    $('.imageReload_'+media_id).css('display','none');
               
                }
            },
            error: function(xhr, status, error) {
                //  alert('There was an error with your request.' + xhr.responseText);
            }
        }); 
        return false;
    }
	
    $('.project_create').on('beforeValidate', function (event, messages, deferreds) {
        for(var instanceName in CKEDITOR.instances) { 
		
            CKEDITOR.instances[instanceName].updateElement();
			 var TextGrab = CKEDITOR.instances[instanceName].getData();
			 TextGrab = $(TextGrab).text();
			if(TextGrab.match(/<\/?[^>]*>/g)) {
			$('#errorProjDesc').css('display', 'block');
			return false;
			}else{
			$('#errorProjDesc').css('display', 'none');
			return true;
			}
        }
		
        
    });

    // for add more for embed links

    $(function()
    {
        $('.add_more').on('click',function()
        {   
    
            $('.field-projects-embed_videos').append('<div><textarea id="projects-embed_videos" class="form-control youtube_link" name="Projects[embed_videos][]" rows="2"></textarea><a href="javascript:void(0);" class="delete" title="Delete">Delete</a>');
    
        });  
        $('.field-projects-embed_videos').on('click','.delete',function()
        {
        
            $(this).parent().remove();  
         
        });        
        
    });

</script>

<style>
    .confirm-box{
        margin-top: 500px;
    }
</style>
