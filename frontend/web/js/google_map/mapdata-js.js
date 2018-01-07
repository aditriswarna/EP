/**
 * @extends storeLocator.StaticDataFeed
 * @constructor
 */
function MedicareDataSource() {
  $.extend(this, new storeLocator.StaticDataFeed);

  var that = this;
  var req_id= window.location.href;
   var id=req_id.split('?id=');
  if(typeof(id[1])!='undefined')
   {
       $.post(getsite_url()+'/site/mapdata?id='+id[1], function(data) {
    that.setStores(that.parse_(data));
   });
   }
 else
 { 
  $.post(getsite_url()+'/site/mapdata', function(data) {
    that.setStores(that.parse_(data));
 
 });

}
}


/**
 * @const
 * @type {!storeLocator.FeatureSet}
 * @private
 */
/*MedicareDataSource.prototype.FEATURES_ = new storeLocator.FeatureSet(
  new storeLocator.Feature('Wheelchair-YES', 'Wheelchair access'),
  new storeLocator.Feature('Audio-YES', 'Audio')
);
*/
//for dynamic category
/*$.post('http://Equippp.local/frontend/web/site/get-category', function(data) {
var data=jQuery.parseJSON(data);
console.log(data[0]);
});*/

MedicareDataSource.prototype.FEATURES_ = new storeLocator.FeatureSet(

   new storeLocator.Feature('project_category_ref_id-0', 'Select'),
   new storeLocator.Feature('project_category_ref_id-1', 'Power'),
   new storeLocator.Feature('project_category_ref_id-2','Roads & Bridges'),
   new storeLocator.Feature('project_category_ref_id-3','Healthcare'),
   new storeLocator.Feature('project_category_ref_id-4','Environment'),
   new storeLocator.Feature('project_category_ref_id-5','Technology'),
   new storeLocator.Feature('project_category_ref_id-6','Community'),
   new storeLocator.Feature('project_category_ref_id-7','CSR'),
   new storeLocator.Feature('project_category_ref_id-8','Agriculture'),
   new storeLocator.Feature('project_category_ref_id-9','Education'),
  new storeLocator.Feature('project_category_ref_id-9','WasteManagement'),
   new storeLocator.Feature('project_category_ref_id-10','Water &Sanitation')
   
);
/**
 * @return {!storeLocator.FeatureSet}
 */
MedicareDataSource.prototype.getFeatures = function() {
  return this.FEATURES_;
};

/**
 * @private
 * @param {string} csv
 * @return {!Array.<!storeLocator.Store>}
 */
MedicareDataSource.prototype.parse_ = function(csv) {
  var stores = [];

  //var rows = csv.split('\n');
  //var headings = this.parseRow_(rows[0]);
  var rows=jQuery.parseJSON(csv);
  for (var i = 0, row; row = rows[i]; i++) {
   // row = this.toObject_(headings, this.parseRow_(row));
   //row=jQuery.parseJSON(row);
 //  console.log(row);
    var features = new storeLocator.FeatureSet;
    features.add(this.FEATURES_.getById('project_category_ref_id-' + row.project_category_ref_id));
    //features.add(this.FEATURES_.getById('Audio-' + row.Audio));

    var position = new google.maps.LatLng(row.latitude, row.longitude);

  // var shop = this.join_([row.Shp_num_an, row.Shp_centre], ', ');
    //var locality = this.join_([row.Locality, row.Postcode], ', ');
	//var description=

    var store = new storeLocator.Store(row.project_id, position, features, {
    //  title: row.project_title+this.addimage_(),
         image:'<a href="#"class="goTolink"><img src="../uploads/project_images/'+row.project_id+'/thumb/'+(row.document_name ? row.document_name  :'../../no_project_image.jpg')+'"></a>',
          title: row.project_title,
           location:row.location,
	   //address: this.join_([shop, row.Street_add, locality], '<br>'),
	   address: this.join_(['<span>User Type : </span><span class="userType">'+row.user_type,'</span><span>Project Category : </span><span class="userCat">'+row.category_name], '</span><br>'),
       // location: row.location,
	  id:row.project_id,
          category:row.project_category_ref_id
	// image:this.addimage_(row.project_image,row.project_id)
       
    });
	
    stores.push(store);
  }
// console.log(stores);
  return stores;
};

/**
 * Joins elements of an array that are non-empty and non-null.
 * @private
 * @param {!Array} arr array of elements to join.
 * @param {string} sep the separator.
 * @return {string}
 */
MedicareDataSource.prototype.join_ = function(arr, sep) {
  var parts = [];
  for (var i = 0, ii = arr.length; i < ii; i++) {
    arr[i] && parts.push(arr[i]);
  }
  return parts.join(sep);
};

/*MedicareDataSource.prototype.addimage_=function(project_image,project_id){
  var img = document.createElement("img");
   img.setAttribute("src","images/"+project_image);
  return img;
}
*/
/**
 * Very rudimentary CSV parsing - we know how this particular CSV is formatted.
 * IMPORTANT: Don't use this for general CSV parsing!
 * @private
 * @param {string} row
 * @return {Array.<string>}
 */
MedicareDataSource.prototype.parseRow_ = function(row) {
  // Strip leading quote.
  if (row.charAt(0) == '"') {
    row = row.substring(1);
  }
  // Strip trailing quote. There seems to be a character between the last quote
  // and the line ending, hence 2 instead of 1.
  if (row.charAt(row.length - 2) == '"') {
    row = row.substring(0, row.length - 2);
  }

  row = row.split('","');

  return row;
};

/**
 * Creates an object mapping headings to row elements.
 * @private
 * @param {Array.<string>} headings
 * @param {Array.<string>} row
 * @return {Object}
 */
MedicareDataSource.prototype.toObject_ = function(headings, row) {
  var result = {};
  for (var i = 0, ii = row.length; i < ii; i++) {
    result[headings[i]] = row[i];
  }
  return result;
};

/*function getsite_url()
        {
          var base_url=window.location.host;
           var url= "http://"+base_url+"/equippp/frontend/web/";
           return url;
            
        }*/