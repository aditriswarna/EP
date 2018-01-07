<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['user_list']];
$this->params['breadcrumbs'][] = $this->title;
	$dateformat = Yii::getAlias('@phpdatepickerformat');
?>
    <style type="text/css">
        #pac-input {
            amrgin-top: 10px;
            padding: 3px;
            left: 115px !important;
        }    
    </style>
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

            
            
            <?php if(empty($model->user_ref_id)) { ?>
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
//                    document.getElementById("projects-location").value = results[0].formatted_address;
//                    document.getElementById("projects-latitude").value = results[0].geometry.location.lat();
//                    document.getElementById("projects-longitude").value = results[0].geometry.location.lng();
                    
                    document.getElementById("createuserform-current_location").value = results[0].formatted_address;
                    document.getElementById("createuserform-latitude").value = results[0].geometry.location.lat();
                    document.getElementById("createuserform-longitude").value = results[0].geometry.location.lng();
                }
                else {
                    //$("#mapErrorMsg").html('Cannot determine address at this location.' + status).show(100);
                }
            });
        }
 </script>
<div class="user-create">

    <h1 class="box-title"><?= Html::encode($this->title) ?></h1>

    <div class="user-signup participation-border fl-left">
     <div class="row">
        <div class="col-xs-12 col-sm-6">
<?php $UsersList= (ArrayHelper::map($usertypemodel::find()->where('user_type_id in (3, 5)')->orderBy('user_type')->all(),'user_type_id','user_type'));     
            $MediaList= (ArrayHelper::map($mediatypemodel::find()->orderBy('media_agency_name')->all(),'media_agency_id','media_agency_name'));    
            ?>
    <?php $form = ActiveForm::begin(['options' => [
                'id' => 'usercreateform',
            'enctype' => 'multipart/form-data'
             ]]); ?>
    
    <?php 
  echo $form->field($model, 'email')->textInput();
    echo $form->field($model, 'validemail')->hiddenInput()->label(false);
    echo $form->field($model, 'password')->passwordInput();
    echo $form->field($model, 'confirmpassword')->passwordInput();
    echo $form->field($model, 'user_type_ref_id')->dropDownList($UsersList, ['prompt'=>'Select User Type']) ;
    echo  $form->field($model, 'media_agency_ref_id')->dropDownList($MediaList, ['prompt'=>'Select Media Agency']);
    //echo $form->field($model, 'validmediaagency')->hiddenInput()->label(false);
    echo $form->field($model, 'fname')->textInput();
    echo $form->field($model, 'lname')->textInput();
    echo $form->field($model, 'mobile')->textInput();
     echo $form->field($model,'gender')->radioList(['Male' => 'Male', 'Female' => 'Female']);
    echo $form->field($model, 'dob')->widget(\yii\jui\DatePicker::classname(), [
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
                            'buttonImage' => Yii::$app->request->BaseUrl.'/images/calendar.gif',
                            //'buttonImage' => 'http://localhost/equippp/frontend/images/calendar.gif',
                        ],
		])->textInput(['readonly' => true]);
    ?>
   
  

    
        </div>
        <div class="col-xs-12 col-sm-6">
        
        <?php
		
    
    echo $form->field($model, 'citizen')->dropDownList($countries,['prompt'=>'Select citizen']);
   echo $form->field($model, 'domicile')->textInput();
    echo '<input id="pac-input" class="controls" type="text" placeholder="Search Box" size="50">';
    echo '<div id="map_canvas" style="height:300px; width:auto; position: relative; overflow: hidden; "></div>';
    echo $form->field($model, 'current_location')->textInput();
    
    echo $form->field($model, 'latitude')->hiddenInput()->label(false);
    echo $form->field($model, 'longitude')->hiddenInput()->label(false);
		?>
        
        
        </div>
      
     </div>

  <div class="row text-center">
         <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary usersignup']) ?>
    </div>
        
        </div>
		<?php ActiveForm::end(); ?>
        </div>
</div>
   <script>       
    
var url = '<?php echo Yii::$app->urlManager->createAbsoluteUrl('admin/checkexistinguser'); ?>';
  $(".usersignup").on("click", function (event) {
        event.preventDefault();
        var usrtype = $("#createuserform-user_type_ref_id").val();
           var email = $('#createuserform-email').val();
           if($.trim(email) != ''){
           $.ajax({
                    url: url,
                    type: "post",
                    data: {email:email},
                    success: function (data) { 					
                        if(data == "true"){
                            $(".field-createuserform-email").addClass('has-error');
                            $(".field-createuserform-email .help-block").text('This email address has already been taken');    
                            $(".field-createuserform-email .help-block").show();        
                            return false;
                        }else{
                            $("#createuserform-validemail").attr("value", email);
                            $(".field-createuserform-validemail .help-block").hide();
                            $(".field-createuserform-email .help-block").text('Email cannot be blank'); 
                            $(".field-createuserform-email .help-block").hide();    
                            $( "#usercreateform" ).submit();                    
                        }                        
                    }
                });
            } else{               
                 $(".field-createuserform-email").addClass('has-error');
                 $(".field-createuserform-email .help-block").text('Email cannot be blank');
                 $(".field-createuserform-email .help-block").show();
                 return false;
            }
           
        });
//for vaklidation of media agency
       $(function()
     {        
        $(".field-createuserform-validemail .help-block").hide();   
       $(".field-createuserform-media_agency_ref_id").css('display','none');
        $("select[id^=createuserform-user_type_ref_id]").change(function(){
        var usertype = $(this).val();
        if(usertype == 9){
            $(".field-createuserform-media_agency_ref_id").css('display','block');  
        }else{
            $('#createuserform-media_agency_ref_id').val("");
            $(".field-createuserform-media_agency_ref_id").css('display','none');
        }
        });
        
        initMap();
     });
        </script>
    <style>
.field-createuserform-dob{ position:relative;}
        
    </style>