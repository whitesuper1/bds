/*global google */
/*global Modernizr */
/*global InfoBox */
/*global googlecode_regular_vars*/
var gmarkers = [];
var current_place=0;
var actions=[];
var categories=[];
var vertical_pan=-190;
var map_open=0;
var vertical_off=150;
var pins='';
var markers='';
var infoBox = null;
var category=null;
var width_browser=null;
var infobox_width=null;
var wraper_height=null;
var info_image=null;
var map;
var found_id;
var selected_id         =   '';
var javamap;
var oms;

function initialize(){
    "use strict";

    var mapOptions = {
             flat:false,
             noClear:false,
             zoom: parseInt(googlecode_regular_vars.page_custom_zoom),
             scrollwheel: false,
             draggable: true,
             center: new google.maps.LatLng(googlecode_regular_vars.general_latitude, googlecode_regular_vars.general_longitude),
             mapTypeId: google.maps.MapTypeId.ROADMAP,
             streetViewControl:false,
             mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP]
            },
            disableDefaultUI: true
           };
       
    
    if(  document.getElementById('googleMap') ){
        map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);
    }else{
        return;
    }
    google.maps.visualRefresh = true;
    
  
    if(mapfunctions_vars.show_g_search_status==='yes'){
        set_google_search(map)
    }
  


    if(mapfunctions_vars.map_style !==''){
       var styles = JSON.parse ( mapfunctions_vars.map_style );
       map.setOptions({styles: styles});
    }
  
  
  
  
    
    google.maps.event.addListener(map, 'tilesloaded', function() {
     jQuery('#gmap-loading').remove();
    });

    if (Modernizr.mq('only all and (max-width: 1025px)')) {
        map.setOptions({'draggable': false});
    }

    
    if(googlecode_regular_vars.generated_pins==='0'){
        pins=googlecode_regular_vars.markers;
        markers = jQuery.parseJSON(pins);
    }else{
        if( typeof( googlecode_regular_vars2) !== 'undefined' && googlecode_regular_vars2.markers2.length > 2){          
            pins=googlecode_regular_vars2.markers2;
            markers = jQuery.parseJSON(pins);                 
        }           
    }
    

    
    if (markers.length>0){
        setMarkers(map, markers);
    }
    
    if(googlecode_regular_vars.idx_status==='1'){
        placeidx(map,markers);
    }

    //set map cluster
    map_cluster();
   
    /*function scrollwhel(event){
        if(map.scrollwheel===true){
            event.stopPropagation();
        }
    }
    
    google.maps.event.addDomListener(document.getElementById('googleMap'), 'mousewheel', scrollwhel);
    google.maps.event.addDomListener(document.getElementById('googleMap'), 'DOMMouseScroll', scrollwhel);
    */
    
    oms = new OverlappingMarkerSpiderfier(map, {markersWontMove: true, markersWontHide: true,keepSpiderfied :true,legWeight:3});
    setOms(gmarkers);
 
}
///////////////////////////////// end initialize
/////////////////////////////////////////////////////////////////////////////////// 
 
 
if (typeof google === 'object' && typeof google.maps === 'object') {                                         
    google.maps.event.addDomListener(window, 'load', initialize);
}

  