// Store locator with customisations
// - custom marker
// - custom info window (using Info Bubble)
// - custom info window content (+ store hours)

//var ICON = new google.maps.MarkerImage('../js/google_map/gps.png', null, null,
 //   new google.maps.Point(14, 13));
 

var SHADOW = new google.maps.MarkerImage('../js/google_map/medicare-shadow.png', null, null,
    new google.maps.Point(14, 13));

google.maps.event.addDomListener(window, 'load', function() {
  var map = new google.maps.Map(document.getElementById('map-canvas'), {
    center: new google.maps.LatLng(20.5937, 78.9629),  
    zoom: 6,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    panControl: true
  });
   

  var panelDiv = document.getElementById('panel');

  var data = new MedicareDataSource;

  var view = new storeLocator.View(map, data, {
    geolocation: false,
    features: data.getFeatures()
  });

  view.createMarker = function(store) {
    var markerOptions = {
      position: store.getLocation(),
      icon: geticon(store),
      shadow: SHADOW,
      title: store.getDetails().title
    };
    return new google.maps.Marker(markerOptions);
  }

 function geticon(store)
 {
    return  new google.maps.MarkerImage('../js/google_map/images/icons/'+store.getDetails().category+'.png', null, null,
   new google.maps.Point(14, 13));
   
 }
  var infoBubble = new InfoBubble;
  view.getInfoWindow = function(store) {
    if (!store) {
      return infoBubble;
    }

    var details = store.getDetails();
	//console.log(details);
	
    var html = ['<div class="store" style="overflow:hidden"><div class="title">', details.title,
      '</div><div class="address" style="visibility:hidden">', details.location.substring(1,40), '</div>',
	 '</div>']
     .join('');
    infoBubble.setContent($(html)[0]);
    return infoBubble;
	
  };

  new storeLocator.Panel(panelDiv, {
    view: view,
	 featureFilter: false
  });
  
   var features = view.getFeatures().asList();
        //createe a select-element on the fly using jQuery
       $('<h4 class="select-cat"> Select Category</h4>').appendTo('.location-search');
        list = $('<select/>')
        //put it somewhere, here it will be appended 
        //to the form created by the storeLocator
            .appendTo('.location-search').
        //apply the change-handler for the select
        change(function () {
          if(features[this.selectedIndex] == 'Select'){			
            window.location.reload(true);	
         }else{	
            view.set('featureFilter',
                new storeLocator.FeatureSet(features[this.selectedIndex]));
            view.refreshView();
                        }
        });
               
		//console.log(features);
    //populate the  select with options
    $.each(features, function (i, o) {

        list.append(new Option(o.getDisplayName()));

    });
   var c=($('#panel ul').find('li .highlighted'));
 // console.log(c.id);
    var projectId=0;
    
   $('#panel ul').on('click','li',function(e)
   {
       
     projectId=(this.id);
     var projectdata=projectId.split("-");
   
    $.ajax({
    url: getsite_url()+'site/is-private',
    type: "post",
    data:'id='+projectdata[1],
    success: function(html){ 
     
   if(html == 'request')
        {    
      fancyConfirm('<span class="fancy_msg">Are you sure you want request access for this project?</span>', function() {
           do_something('yes');
        }, function() {
            do_something('no');
        });
       }
       else if(html=='already_requested')
           {
               
             $.notify("you have already requested access for this project", "info");  
             
        /*  $.notify.addStyle('happyblue', {
            html: "<div><h2 style='text-align:center;'>☺<span data-notify-text/>☺</h2></div>",
            classes: {
              base: {
                "white-space": "nowrap",
                "background-color": "lightblue",
                "padding": "5px"
              },
              superblue: {
                "color": "white",
                "background-color": "blue"
              }
            }
          }); 
            $.notify("you have already requested access for this project", {
              style: 'happyblue',
              className: 'superblue',
              showAnimation: 'slideDown',
              showDuration: 800,
              hideDuration: 1000000,
            })*/
               
           }
       else if(html=='yes')
     {  
      $.ajax({
  url: getsite_url()+'/site/get-data',
  type: "post",
  data:'id='+projectdata[1],
  success: function(html){
    $(".project-details").html('');
    $(".project-details").append(html);
    $(".project-details").css('display','block');
    $(".project-details").addClass('prod');
	var ProList = $(".all-projects-list").outerWidth(); 
		var MapList = $(".custom-google-map").outerWidth(); 
		var PPsList = $(".project-details").outerWidth(); 
		$(".project-details").animate({marginRight : ProList});
                 map.panBy(150,0);
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
  
    //  }
   });
   
   
  /* $('.map-canvas').on('click','.store',function()
   {
    alert("hii");
   });*/
  
});
function fancyConfirm(msg,callbackYes,callbackNo) {
    var ret;

    jQuery.fancybox({
        'modal' : true,
        'content' : "<div style=\"margin:1px;width:350px;\">"+msg+"<div style=\"text-align:right;margin-top:10px;\"><input id=\"fancyconfirm_cancel\" style=\"margin:3px;padding:0px;\" type=\"button\" class=\"btn btn-pink-tp pad-tb5 size16\"  value=\"Cancel\"><input id=\"fancyConfirm_ok\" class=\"btn btn-blue-tp pad-tb5 size16\" style=\"margin:3px;padding:0px;\" type=\"button\" value=\"Ok\"></div></div>",
        'beforeShow' : function() {
            jQuery("#fancyconfirm_cancel").click(function() {
              
                
                 callbackNo();
               
                  $.fancybox.close();
                
            });
            
            jQuery("#fancyConfirm_ok").click(function() {
               
                
                 callbackYes();
              
                    
                    $.fancybox.close(); 
               
            });
        }
    });
}

    function  do_something(a){
       if(a=='yes')
       {
          
        var id=$('.highlighted').attr('id');
          id=id.split('-');
               $.ajax({
               url: getsite_url()+'site/insert-request',
               type: "post",
               data:'id='+id[1],
               success: function(html){ 
                   if(html=="success")
               
                  $.notify("request has been sent successfully", "success");
              else
                  
                $.notify("We have some problem in sending request please try later", "warning");
               }
    });
       }
    }
	function getsite_url()
        {
          var base_url=window.location.host;
           var url= "http://"+base_url+"/frontend/web/";
           return url;
            
        }
        
	
	function participateProject(id)
	{
		projectId=(id);
		var projectdata=projectId.split("-");
	   
		$.ajax({
			url: getsite_url()+'site/is-login',
			type: "post",
			data:'id='+projectId,
			success: function(data){ 
		 
				if(data == 'LoggedIn') {
					window.location.href = getsite_url()+"project-participation/create/?id="+projectId;
				}
				else if(data == "NotLoggedIn")
				{ 
					//if($(".project-details").hasClass('prod'))
					//{
					//	$(".project-details").animate({marginRight : -$(".project-details").outerWidth()}).removeClass('prod');
					//} 
			   
					$('#loginModal').trigger('click');
					// e.stopImmediatePropagation();
				}
				 
		}
	});
		
	}
     

  

