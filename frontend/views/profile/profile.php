<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\bootstrap\Alert;
$dateformat = Yii::getAlias('@phpdatepickerformat');
/* @var $this yii\web\View */
/* @var $model app\models\UserProfile */
/* @var $form ActiveForm */
//print_r($userdata->user_type_ref_id); exit;
?>

    <style type="text/css">
        #pac-input {
            amrgin-top: 10px;
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
    
<!--    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=places&sensor=false&key=AIzaSyDXuDv357BE0PHXkhjmjuNK_oG16IiX-oU"></script>-->
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyDL1Xs264nIq1NoVhqtdBThrBa9da3f52k"></script>
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

            
            
            <?php if(empty($userdatamodel->user_ref_id)) { ?>
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
                 latitude = parseFloat("<?php echo $userdatamodel->latitude; ?>");
                 longitude = parseFloat("<?php echo $userdatamodel->longitude; ?>");
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
//                    document.getElementById("projects-location").value = results[0].formatted_address;
//                    document.getElementById("projects-latitude").value = results[0].geometry.location.lat();
//                    document.getElementById("projects-longitude").value = results[0].geometry.location.lng();
                    
                    document.getElementById("userform-current_location").value = results[0].formatted_address;
                    document.getElementById("userform-latitude").value = results[0].geometry.location.lat();
                    document.getElementById("userform-longitude").value = results[0].geometry.location.lng();
                }
                else {
                    //$("#mapErrorMsg").html('Cannot determine address at this location.' + status).show(100);
                }
            });
        }
    </script>
    <?php 
    if($flash = Yii::$app->session->getFlash('pleasefill'))
    {
    echo Alert::widget(['options' => ['class' => 'alert-success front-noti'], 'body' => $flash]);
    }
  if($userdata->is_profile_set == 0) { ?> 
<!--<div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <h1 class="box-title">Profile Account</h1>
                    </div>-->
    <hr>
   <?php } ?>
   <div class="profile-page participation-border">
<div class="row">

    <?php $form = ActiveForm::begin([
         'method' => 'post',
        'action' => ['profile/profile'],
        'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>
<?php
if(isset($userdatamodel->dob)){
            $date = explode("-",$userdatamodel->dob);
            $dateofbirth = $date[1].'-'.$date[2].'-'.$date[0];
            }
if(isset($userdatamodel->gender))$userformmodel->gender = $userdatamodel->gender; 
if(isset($dateofbirth))$userformmodel->dob = $dateofbirth; 
if(isset($userdata_ref->member_of_parliament))$userformmodel->member_of_parliament = $userdata_ref->member_of_parliament; 
if(isset($userdata_ref->bank_sector))$userformmodel->bank_sector = $userdata_ref->bank_sector;
if(isset($userdatamodel->citizen))$userformmodel->citizen = $userdatamodel->citizen; 

if($userdata->user_type_ref_id == 3 || $userdata->user_type_ref_id == 6 || $userdata->user_type_ref_id == 4 || 
        $userdata->user_type_ref_id == 1 || $userdata->user_type_ref_id == 2 || $userdata->user_type_ref_id == 9)
{
    echo "<div class='col-xs-12 col-sm-6'>";
    echo $form->field($userformmodel, 'fname')->textInput(['value' => (isset($userdatamodel->fname)? $userdatamodel->fname : '') ]);
    echo $form->field($userformmodel, 'lname')->textInput(['value' => (isset($userdatamodel->lname)? $userdatamodel->lname : '') ]);
    echo $form->field($userformmodel, 'mobile')->textInput(['value' => (isset($userdatamodel->mobile)? $userdatamodel->mobile : '') ]);
    echo $form->field($userformmodel, 'email')->textInput(['value' => (isset($userdata->email)? $userdata->email : ''), 'readonly' => true ]);
        echo $form->field($userformmodel, 'dob')->widget(\yii\jui\DatePicker::classname(), [
			'value'  => '1232', 'dateFormat' => $dateformat, 'options' => ['class' => 'form-control'],
                        'options' => ['class' => 'form-control'],            
                        'clientOptions' => [
                            'changeMonth' => true,
                            'yearRange'=> '-70:-18',
                            'changeYear' => true,
                            'maxDate' => 0, 
                            'showOn' => 'button',
                            'buttonImage' => 'images/calendar.gif',
                            'buttonImageOnly' => true,
                            'buttonText' => 'Select date',
                            'buttonImage' => Yii::$app->request->BaseUrl.'/../images/calendar.gif',
                            //'buttonImage' => 'http://localhost/equippp/frontend/images/calendar.gif',
                        ],
		])->textInput(['readonly' => true]);
    echo $form->field($userformmodel, 'gender')->radioList(['Male' => 'Male', 'Female' => 'Female',]);
    echo $form->field($userformmodel, 'citizen')->dropDownList($countries,['prompt'=>'Select citizen']);

    echo $form->field($userformmodel, 'domicile')->textInput(['value' => (isset($userdatamodel->domicile)? $userdatamodel->domicile : '') ]);
    echo '<input id="pac-input" class="controls" type="text" placeholder="Search Box" size="50">';
    echo '<div id="map_canvas" style="height:300px; width:auto; position: relative; overflow: hidden; "></div>';
    echo $form->field($userformmodel, 'current_location')->textInput(['value' => (isset($userdatamodel->current_location)? $userdatamodel->current_location : '') ]);
    
    echo $form->field($userformmodel, 'latitude')->hiddenInput(['value' => (isset($userdatamodel->latitude)? $userdatamodel->latitude : '') ])->label(false);
    echo $form->field($userformmodel, 'longitude')->hiddenInput(['value' => (isset($userdatamodel->longitude)? $userdatamodel->longitude : '') ])->label(false);
   /* if(!$userdatamodel){
    echo $form->field($userformmodel, 'user_image')->fileInput();
    } */
    echo "</div>";    
}
echo "<div class='col-xs-12 col-sm-6'>";
if($userdata->user_type_ref_id == 3)
    {       
        echo $form->field($userformmodel, 'occupation')->textInput(['value' => (isset($userdatamodel->occupation)? $userdatamodel->occupation : '') ]);
        
    }
    if($userdata->user_type_ref_id == 6)
    {
        echo $form->field($userformmodel, 'domain_expertise')->textInput(['value' => (isset($userdatamodel->domain_expertise)? $userdatamodel->domain_expertise : '') ]);
        echo $form->field($userformmodel, 'course_details')->textInput(['value' => (isset($userdata_ref->course_details)? $userdata_ref->course_details : '') ]);
        echo $form->field($userformmodel, 'college')->textInput(['value' => (isset($userdata_ref->college)? $userdata_ref->college : '') ]);
        echo $form->field($userformmodel, 'university')->textInput(['value' => (isset($userdata_ref->university)? $userdata_ref->university : '') ]);
        echo $form->field($userformmodel, 'year_of_joining')->textInput(['value' => (isset($userdata_ref->year_of_joining)? $userdata_ref->year_of_joining : '') ]);
        echo $form->field($userformmodel, 'field_of_study')->textInput(['value' => (isset($userdata_ref->field_of_study)? $userdata_ref->field_of_study : '') ]);
    }
    if($userdata->user_type_ref_id == 4)
    {
        echo $form->field($userformmodel, 'field_of_excellence')->textInput(['value' => (isset($userdata_ref->field_of_excellence)? $userdata_ref->field_of_excellence : '') ]);
        echo $form->field($userformmodel, 'communication_address')->textInput(['value' => (isset($userdata_ref->communication_address)? $userdata_ref->communication_address : '') ]);
    }
    if($userdata->user_type_ref_id == 1)
    {
        echo $form->field($userformmodel, 'state')->textInput(['value' => (isset($userdata_ref->state)? $userdata_ref->state : '') ]);
        echo $form->field($userformmodel, 'constituency')->textInput(['value' => (isset($userdata_ref->constituency)? $userdata_ref->constituency : '') ]);
        echo $form->field($userformmodel, 'elected_year')->textInput(['value' => (isset($userdata_ref->elected_year)? $userdata_ref->elected_year : '') ]);
        echo $form->field($userformmodel, 'domain_expertise')->textInput(['value' => (isset($userdatamodel->domain_expertise)? $userdatamodel->domain_expertise : '') ]);
    }
    if($userdata->user_type_ref_id == 2)
    {
        echo $form->field($userformmodel, 'member_of_parliament')->radioList([ 'Rajya Sabha' => 'Rajya Sabha','Lok Sabha' => 'Lok Sabha']); 
        echo $form->field($userformmodel, 'state')->textInput(['value' => (isset($userdata_ref->state)? $userdata_ref->state : '') ]);
        echo $form->field($userformmodel, 'constituency')->textInput(['value' => (isset($userdata_ref->constituency)? $userdata_ref->constituency : '') ]);
        echo $form->field($userformmodel, 'elected_year')->textInput(['value' => (isset($userdata_ref->elected_year)? $userdata_ref->elected_year : '') ]);
        echo $form->field($userformmodel, 'domain_expertise')->textInput(['value' => (isset($userdatamodel->domain_expertise)? $userdatamodel->domain_expertise : '') ]);
    }
    if($userdata->user_type_ref_id == 8)
    {
        echo $form->field($userformmodel, 'department')->textInput(['value' => (isset($userdata_ref->department)? $userdata_ref->department : '') ]);
        echo $form->field($userformmodel, 'state')->textInput(['value' => (isset($userdata_ref->state)? $userdata_ref->state : '') ]);
        echo $form->field($userformmodel, 'sector')->textInput(['value' => (isset($userdata_ref->sector)? $userdata_ref->sector : '') ]);
        echo $form->field($userformmodel, 'representing_authority')->textInput(['value' => (isset($userdata_ref->representing_authority)? $userdata_ref->representing_authority : '') ]);
        echo $form->field($userformmodel, 'designation')->textInput(['value' => (isset($userdata_ref->designation)? $userdata_ref->designation : '') ]);
        echo $form->field($userformmodel, 'communication_address')->textarea(['rows' => '6','value' => (isset($userdata_ref->communication_address)? $userdata_ref->communication_address : '') ]);
        echo $form->field($userformmodel, 'mobile')->textInput(['value' => (isset($userdatamodel->mobile)? $userdatamodel->mobile : '') ]);
      /*  if(!$userdatamodel){
        echo $form->field($userformmodel, 'user_image')->fileInput();
        } */
    }
    if($userdata->user_type_ref_id == 7)
    {
        echo $form->field($userformmodel, 'bank_name')->textInput(['value' => (isset($userdata_ref->bank_name)? $userdata_ref->bank_name : '') ]);
        echo $form->field($userformmodel, 'bank_sector')->radioList([ 'Public' => 'Public','Private' => 'Private','Foreign' => 'Foreign']);
        echo $form->field($userformmodel, 'branch')->textInput(['value' => (isset($userdata_ref->branch)? $userdata_ref->branch : '') ]);
        echo $form->field($userformmodel, 'representing_authority')->textInput(['value' => (isset($userdata_ref->representing_authority)? $userdata_ref->representing_authority : '') ]);
        echo $form->field($userformmodel, 'designation')->textInput(['value' => (isset($userdata_ref->designation)? $userdata_ref->designation : '') ]);
        echo $form->field($userformmodel, 'communication_address')->textInput(['value' => (isset($userdata_ref->communication_address)? $userdata_ref->communication_address : '') ]);
        echo $form->field($userformmodel, 'mobile')->textInput(['value' => (isset($userdatamodel->mobile)? $userdatamodel->mobile : '') ]);
       /* if(!$userdatamodel){
        echo $form->field($userformmodel, 'user_image')->fileInput();
        } */
    }
    if($userdata->user_type_ref_id == 5)
    {
        echo $form->field($userformmodel, 'company_name')->textInput(['value' => (isset($userdata_ref->company_name)? $userdata_ref->company_name : '') ]);
        echo $form->field($userformmodel, 'sector')->textInput(['value' => (isset($userdata_ref->sector)? $userdata_ref->sector : '') ]);
        echo $form->field($userformmodel, 'representing_authority')->textInput(['value' => (isset($userdata_ref->representing_authority)? $userdata_ref->representing_authority : '') ]);
        echo $form->field($userformmodel, 'designation')->textInput(['value' => (isset($userdata_ref->designation)? $userdata_ref->designation : '') ]);
        echo $form->field($userformmodel, 'communication_address')->textInput(['value' => (isset($userdata_ref->communication_address)? $userdata_ref->communication_address : '') ]);
        echo $form->field($userformmodel, 'mobile')->textInput(['value' => (isset($userdatamodel->mobile)? $userdatamodel->mobile : '') ]);
     /*   if(!$userdatamodel){
        echo $form->field($userformmodel, 'user_image')->fileInput();
        } */
    }

?>
  
    
       
</div>
 <div class="crat-but">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
</div>
    <?php ActiveForm::end(); ?>

</div><!-- profile -->

<script>
    $(function(){
    <?php  if($userdata->user_type_ref_id == 3 || $userdata->user_type_ref_id == 6 || $userdata->user_type_ref_id == 4
          ||  $userdata->user_type_ref_id == 1 || $userdata->user_type_ref_id == 2 || $userdata->user_type_ref_id == 9) { ?>
        initMap();
    <?php  } ?>
    });   
</script>
  