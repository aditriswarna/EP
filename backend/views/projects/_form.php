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

<!--<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=places&sensor=false&key=AIzaSyDXuDv357BE0PHXkhjmjuNK_oG16IiX-oU"></script>-->
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
                $('#errorUsername').css('display', 'none');
            
                if($('#projects-participation_type').val() == "Invest" && $('#projects-investment_type').val() == "") {
                    $('#errorInvestmentType').html('Select project investment type');
                    $('#errorInvestmentType').css('display', 'block');
                    return false;
                }
                if($('#projects-username').val() == ''){                    
                    $('#errorUsername').css('display', 'block');
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
        
            function showGovtAuthority() {
                $('#govt_authority').css('display', 'none');
            
                if( $('#projects-targeted_govt_authority').val() == 'Y')
                {
                    $('#govt_authority').css('display', 'block');
                    return false;
                }
            }
       
</script>

<style type="text/css">
    .divMargin {
        width: 45%;
        float: left;
        margin: 0px 15px 0px 15px;
    }
    @media (min-width:992px){
        .page-content-wrapper .page-content {
            display: inline-block;
            width: 85%;
        }
    }
</style>

<div class="projects-form  participation-border tab-body-project">

    <?php
    $form = ActiveForm::begin(['options' => [
                    'enctype' => 'multipart/form-data'], 'class' => 'project_create']);
    ?>
    <?php
    $query = 'SELECT CONCAT(fname, " ", lname, " ", email, " ", user_type) as value, id as id '
            . 'FROM user u JOIN user_profile uf ON u.id = uf.user_ref_id '
            . 'JOIN user_type ut ON u.user_type_ref_id = ut.user_type_id '
            . 'WHERE u.status = 1 and u.user_type_ref_id in (3, 5) AND u.email_confirmed = 1;';
    $usernames = Yii::$app->db->createCommand($query)->queryAll();
    ?>
    <div class="col-xs-12 col-sm-6">
        <div class="form-group field-projects-project_category_ref_id has-success ">
            <label class="control-label">Username </label>
            <style type="text/css">
                .ui-autocomplete-input {
                    width: 100%;
                    padding: 5px;
                    margin-bottom: 10px;
                    border: 1px solid #c2cad8;
                }
                .ui-autocomplete { height: 200px; overflow-y: auto; overflow-x: hidden;}
            </style>
            <?php
            if (isset($model->project_end_date))
                $model->project_end_date = date("m-d-Y", strtotime($model->project_end_date));
            if (isset($model->project_start_date))
                $model->project_start_date = date("m-d-Y", strtotime($model->project_start_date));
            echo AutoComplete::widget([
                'model' => $model,
                'attribute' => 'username',
                'name' => 'user_name',
                'clientOptions' => [
                    'source' => $usernames,
                    //'minLength'=>'2', 
                    //'autoFill'=>true,
                    'class' => 'form-control',
                    'options' => array(
                        'minLength' => 3,
                        'autoFill' => false,
                        'focus' => 'js:function( event, ui ) {
                    $( "#projects-username" ).val( ui.item.name );
                    return false;
                }',
                        'htmlOptions' => array('class' => 'form-control', 'style' => 'width: 100%', 'autocomplete' => 'off'),
                        'select' => 'js:function( event, ui ) {
                    $("#' . Html::getInputId($model, 'attribute_id') . '").val(ui.item.id);
                    return false;
                }'
                    ),
                'select' => new JsExpression("function( event, ui ) {                    
                    $('#username').val(ui.item.id);
                }"),
                'change'=>new JsExpression("function( event, ui ) {
                    if (ui.item==null){
                        $('#projects-username').val('');
                        $('#projects-username').focus();
                        $('#errorUsername').text('Select valid username');
                        $('#errorUsername').css('display','block');
                    }else{
                        $('#errorUsername').css('display','none');
                    }
                }")],
            ]);
            ?> 
        </div>       

        <script>
            $('#projects-username').val('<?php echo isset($userDetails[0]['value']) ? $userDetails[0]['value'] : ''; ?>');
        </script>


        <?php echo Html::activeHiddenInput($model, 'username', ['id' => 'username', 'value' => isset($userDetails[0]['id']) ? $userDetails[0]['id'] : '']) ?>
        <div id="errorUsername" class="help-block" style="display: none; margin: -29px 49px 15px; color: #e73d4a;">Username cannot be blank</div>
        
        <?php echo $form->field($model, 'project_category_ref_id')->dropDownList($projectCategories, ['options' => [$model->project_category_ref_id => ['Selected' => true]]]) ?>

        <?php echo $form->field($model, 'project_title')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'project_type_ref_id')->dropDownList($projectTypes) ?>

        <?php echo $form->field($model, 'objective')->textarea(['rows' => 3]) ?>

        <?php echo $form->field($model, 'project_desc')->textarea(['rows' => 6, 'class' => 'ckeditor']) ?>
		<div id="errorProjDesc" class="help-block" style="display: none; margin: -17px 49px 15px; color: #e73d4a;">Html content is not allowed</div>

        <input id="pac-input" class="controls" type="text" placeholder="Search Location" size="50">
        <div id="map_canvas" style="height: 300px; width: 100%; position: relative; overflow: hidden; "></div>    
        <?php echo $form->field($model, 'location')->textInput(['maxlength' => true]) ?>

        <?php echo $form->field($model, 'latitude')->hiddenInput()->label(false) ?>

        <?php echo $form->field($model, 'longitude')->hiddenInput()->label(false) ?>   

<?php if (isset(Yii::$app->session['userType']) && Yii::$app->session['userType'] == 5) { ?>
    <?php echo $form->field($model, 'CSR_project_type')->dropDownList([ 'SelfFoundation' => 'SelfFoundation', 'OtherSociety' => 'OtherSociety',], ['prompt' => '']) ?>

            <?php echo $form->field($model, 'CSR_website')->textInput(['maxlength' => true]) ?>
        <?php } ?>
    </div>
    <div class="col-xs-12 col-sm-6">
        <!--</div>
        <div class="projects-form divMargin">-->
            <?php echo $form->field($model, 'conditions')->textarea(['rows' => 3]) ?>

<?php echo $form->field($model, 'targeted_govt_authority')->dropDownList([ 'N' => 'No', 'Y' => 'Yes'], ['onChange' => 'showGovtAuthority()']) ?>

        <div id="govt_authority" style="display: <?php if ($model->targeted_govt_authority == 'Y') echo 'block'; else echo 'none'; ?>">
        <?php echo $form->field($model, 'govt_authority_name')->textInput(['onBlur' => 'checkErrors()']) ?>
            <div id="errorGovtAuthorityName" class="help-block" style="display: none; margin: -17px 49px 15px; color: #e73d4a;">Government Authority Name should not be empty</div>
        </div>

        <?php echo $form->field($model, 'estimated_project_cost')->textInput(['type' => 'number', 'min' => '0']) ?>

        <?php $model->participation_type = isset($projectParticipationData['participation_type']) ? $projectParticipationData['participation_type'] : ''; ?>        
        <?php echo $form->field($model, 'participation_type')->dropDownList([ 'Support' => 'Kind', 'Invest' => 'Cash',], ['onChange' => 'checkErrors()']) ?>
        <div id="errorParticipationType" class="help-block" style="display: none; margin: -17px 49px 15px; color: #e73d4a;">Select project participation type</div>
        
        <?php $model->investment_type = isset($projectParticipationData['investment_type']) ? $projectParticipationData['investment_type'] : ''; ?>        
        <?php echo $form->field($model, 'investment_type')->dropDownList([ ''=>'Select', 'Equity' => 'Equity', 'Grant' => 'Grant',], ['onChange' => 'checkErrors()']) ?>
        <div id="errorInvestmentType" class="help-block" style="display: none; margin: -17px 49px 15px; color: #e73d4a;">Select project investment type</div>
        
        <?php $model->equity_type = isset($projectParticipationData['equity_type']) ? $projectParticipationData['equity_type'] : ''; ?>        
        <?php echo $form->field($model, 'equity_type')->dropDownList([ ''=>'Select', 'Principal_Protection' => 'Principal Protection', 'Interest_Earning' => 'Interest Earning',], ['onChange' => 'checkErrors()']) ?>
        <div id="errorEquityType" class="help-block" style="display: none; margin: -17px 49px 15px; color: #e73d4a;">Select project equity type</div>

        <?php echo $form->field($model, 'amount')->textInput(['type' => 'number', 'min' => '0', 'value' => @$projectParticipationData['amount'], 'onBlur' => 'checkErrors()']) ?>
        <div id="errorAmount" class="help-block" style="display: none; margin: -17px 49px 15px; color: #e73d4a;"></div>

        <?php echo $form->field($model, 'interest_rate')->textInput(['type' => 'number', 'value' => @$projectParticipationData['interest_rate'], 'onBlur' => 'checkErrors()']) ?>
        <div id="errorInterestRate" class="help-block" style="display: none; margin: -17px 49px 15px; color: #e73d4a;">Select project interest rate</div>

        <?php
        echo $form->field($model, 'project_start_date')->widget(DatePicker::classname(), [
            'value' => @$value, 'dateFormat' => $dateformat, 'value' => date('Y-m-d'), 'options' => ['class' => 'form-control'],
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
                        $('#projects-project_end_date').val('');     
                        $( '#projects-project_end_date' ).datepicker( 'option', 'disabled', false );
                        var toDate = $(this).datepicker('getDate');
                        var fromDate = $(this).datepicker('getDate');
                        fromDate.setDate(toDate.getDate()+1)
                        toDate.setDate(toDate.getDate()+45)
                        $('#projects-project_end_date').datepicker('option', 'minDate', fromDate);        
                        $('#projects-project_end_date').datepicker('option', 'maxDate', toDate);
                        }"),
                ],
        ])->textInput(['readonly' => true]);
        ?>

        <?php
        echo $form->field($model, 'project_end_date')->widget(DatePicker::classname(), [
            'value' => @$value, 'dateFormat' => $dateformat, 'value' => date('Y-m-d'),
            'options' => ['class' => 'form-control'],
            'clientOptions' => [
                'changeMonth' => true,
                'yearRange' => '2000:2020',
                'changeYear' => true,
                'showOn' => 'button',
                'buttonImage' => 'images/calendar.gif',
                'buttonImageOnly' => true,
                'buttonText' => 'Select date',
                'disabled' => 'true',
                'buttonImage' => Yii::$app->request->BaseUrl . '/images/calendar.gif',
                ],
        ])->textInput(['readonly' => true]);
        ?>

        <?php echo $form->field($model, 'primary_contact')->textInput(['maxlength' => true]) ?>

            <?php echo $form->field($model, 'secondary_contact')->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'primary_email_contact')->textInput(['maxlength' => true]) ?>
<!-- <div class="note-img">NOTE: You should select single image at a time</div>-->
                <?php echo $form->field($model, 'project_image[]')->fileInput(['multiple' => true, 'class' => 'multi with-preview
      accept-gif|jpg|png|jpeg', 'maxlength'=>'7']); ?>

        <div class="project-allimg">
<?php  if (isset($allProjectImages) && !empty($allProjectImages)) {
    for ($j = 0; $j < count($allProjectImages); $j++) { ?>
                <div class="projct-imgsfrm imageReload_<?php echo $allProjectImages[$j]['project_media_id']; ?>" style="float:right">

                    <?php
                    $bucket = Yii::getAlias('@bucket');
                    $keyname = 'uploads/project_images/'.$model->project_id.'/'.$allProjectImages[$j]['document_name'];
                    $s = new Storage();
                    $file = $s->download($bucket,$keyname);   
                    if(isset($allProjectImages[$j]['document_name']) && ($allProjectImages[$j]['document_type'] == 'projectImage') && isset($file['@metadata']['effectiveUri'])) {
                        ?>
                        <span class="img-spanpost"><img style="height:50px;width:50px;" src="<?php echo $file['@metadata']['effectiveUri']; ?>" /></span>
                        <span class="img-abstspan"><span data-url ="<?php echo Url::toRoute(['delete-project-image', 'pmid' => $allProjectImages[$j]['project_media_id'], 'uid' => $userDetails[0]['id'], 'dname' => $allProjectImages[$j]['document_name'], 'pid' => $allProjectImages[$j]['project_ref_id']]); ?>" pjax-container='pjax-list' data-confirm='Are you sure you want to remove this image?' class="glyphicon glyphicon-remove-sign rvme-clse ajaxDelete" data-pmid="<?php echo $allProjectImages[$j]['project_media_id']; ?>"></span></span>
                <?php
            }
            ?>
                </div>
<?php } } ?>
        </div> 

<!-- <div class="note-img">NOTE: You should select single document at a time</div>-->
                <?php echo $form->field($model, 'document_name[]')->fileInput(['multiple' => true, 'class' => 'multi with-preview projects-document accept-gif|jpg|jpeg|png|pdf|doc|docx|txt', 'maxlength'=>'7']); ?>
        <div class="project-allimg">
            <?php if (isset($allProjectDocuments) && !empty($allProjectDocuments)) {
                for ($j = 0; $j < count($allProjectDocuments); $j++) { ?>
                <div class="projct-imgsfrm imageReload_<?php echo $allProjectDocuments[$j]['project_media_id']; ?>" style="float:right">

                    <?php
                    if (isset($allProjectDocuments[$j]['document_name'])) {
                        ?>
                        <span class="img-spanpost"><?php echo $allProjectDocuments[$j]['document_name']; ?></span>
                        <span class="img-abstspan"><span data-url ="<?php echo Url::toRoute(['delete-project-image', 'pmid' => $allProjectDocuments[$j]['project_media_id'], 'uid' => $userDetails[0]['id'], 'dname' => $allProjectDocuments[$j]['document_name'], 'pid' => $allProjectDocuments[$j]['project_ref_id']]); ?>" pjax-container='pjax-list' data-confirm='Are you sure you want to remove this document?' class="glyphicon glyphicon-remove-sign rvme-clse ajaxDelete" data-pmid="<?php echo $allProjectDocuments[$j]['project_media_id']; ?>"></span></span>
        <?php
    }
    ?>
                </div>
            <?php } }?>
        </div>    
		

        <span class="add-more-icon"><a href="javascript:void(0);" class="add_more" title="Add field"><i class="icon-plus"></i></a></span>
<?php echo $form->field($model, 'embed_videos[]')->textarea(['rows' => 2,'class'=>'form-control youtube_link']);?>
        <div class="youtube_error help-block"></div>
        <div class="project-allimg doc-uploads">
<?php
if (isset($allProjectVidoes) && !empty($allProjectVidoes)) {
    for ($j = 0; $j < count($allProjectVidoes); $j++) {
        ?>
                    <div class="projct-imgsfrm imageReload_<?php echo $allProjectVidoes[$j]['project_media_id']; ?>" style="float:right">

                        <span class="img-spanpost"><?php echo $allProjectVidoes[$j]['document_name']; ?></span>
                        <span class="img-abstspan"><span data-url ="<?php echo Url::toRoute(['delete-youtube-link', 'pmid' => $allProjectVidoes[$j]['project_media_id'], 'dname' => $allProjectVidoes[$j]['document_name'], 'pid' => $allProjectVidoes[$j]['project_ref_id']]); ?>" pjax-container='pjax-list' data-confirm='Are you sure you want to remove this youtube Link?' class="glyphicon glyphicon-remove-sign rvme-clse ajaxDelete" data-pmid="<?php echo $allProjectVidoes[$j]['project_media_id']; ?>"></span></span>

                    </div>
            <?php
            }
        }
        ?>
        </div>   

    </div>
    <div class="crat-but">
<?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'onClick' => 'return checkErrors()']) ?>
<?php //echo Html::submitButton('Create' , ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>

</div>
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

<div id="dataConfirmModal" class="confirm-box" style="display:none;position: absolute;">
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
     
     
     
    // for add more for embed links

	
    $(function()
    {
        $('.add_more').on('click',function()
        {   
    
            $('.field-projects-embed_videos').append('<div><textarea id="projects-embed_videos" class="form-control" name="Projects[embed_videos][]" rows="2"></textarea><a href="javascript:void(0);" class="delete" title="Delete">Delete</a>');
    
        });  
        $('.field-projects-embed_videos').on('click','.delete',function()
        {
        
            $(this).parent().remove();  
         
        });
        
        //for ck editor instance update       
        var editor = CKEDITOR.replace('Projects[project_desc]', {
            language: 'en',
            uiColor: '#AADC6E',
            // uiColor: '#9AB8F3',
        });
        
        editor.on('change', function (evt) {  
            var text_value = evt.editor.getData(); 
            TextGrab = $(text_value).text();
            editor.updateElement(); 
            if(TextGrab != '' && TextGrab.match(/<\/?[^>]*>/g)){
                $('#cke_projects-project_desc').next().text('Html content is not allowed');
                $('.field-projects-project_desc').addClass('has-error');
                $('#cke_projects-project_desc').next().css('display','block');            
                return false;
            }else if(TextGrab != '' && !TextGrab.match(/<\/?[^>]*>/g)){
                $('#cke_projects-project_desc').next().css('display','none');
                $('.field-projects-project_desc').removeClass('has-error');
                return false;
            }
            else {                
                $('#cke_projects-project_desc').next().text('Project Description cannot be blank.');
                $('.field-projects-project_desc').addClass('has-error');
                $('#cke_projects-project_desc').next().css('display','block');
                return false;
            }  
			
        });

    });
</script>

<style>
    .confirm-box{
        margin-top: 500px;
    }
</style>