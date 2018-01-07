<!doctype html>

    <script
      src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>
   <!--<script
      src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js">	  
    </script>-->
       <script src="../js/google_map/notify.min.js"></script>
       <!--<link href="../js/google_map/css/app.css" rel="stylesheet">-->
    <script src="../js/google_map/infowindow.js"></script>
    <script src="../js/google_map/project-list.js"></script>
    <script src="../js/google_map/mapdata-js.js"></script>
    <script src="../js/google_map/custom.js"></script>
    <script src="../js/google_map/common-script.js"></script> 
    <link rel="stylesheet" href="../js/google_map/css/storelocator.css">
     <!--<script src="../js/google_map/fancybox.js"></script>-->
    <?php //$this->registerJsFile('/js/fancybox.js', ['depends' => [yii\web\JqueryAsset::className()]]);?>
     <link rel="stylesheet" href="../js/google_map/css/fancybox.css">
     
   
    <!--<link href="../js/google_map/bootstrap.css" rel="stylesheet" type="text/css" />-->
	
	
<link href="../js/google_map/css/common-css.css" rel="stylesheet" type="text/css" />
   
  </head>
  <body>
     
      <div class="search google-search"> 
      <div class="pull-right"> <input type="button" class="view_all" value="View All" onClick="location.href='<?=Yii::$app->request->BaseUrl;?>/site/dynamic-map'"></div>
          </div>
      <div class="block-buster">
            
      <div class="custom-google-map">
        <div id="map-canvas"></div>
      </div>
      <div class="all-projects-list pull-right">
        <div id="panel"></div>
      </div>
      
      <div class="project-details">
   </div>    
          
      </div>
     <!-- <div id="request_form" style="display:none"></div>-->
 
  </body>
<script>
    $(function(){
  var total_height=$(window).height();
  var header_top=$('.navbar-fixed-top').height();
  var total_header=header_top+80;
  var project_map= total_height-total_header;
  $('#map-canvas,.all-projects-list,.project-details').css('height',project_map+'px');
    
    });
   </script>