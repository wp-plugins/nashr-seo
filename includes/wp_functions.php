<?php
// call all hook on startup plugin 
/////////////////////////////////////////  Invalid  /////////////////////////////////////////////
// License key is Invalid
function license_Invalid() {
	//add_action( 'add_meta_boxes', 'license_Invalid_alert' );
	add_action( 'admin_menu', 'mnbaa_seo_add_licnse_page' );
}

function license_valid(){
	mnbaa_seo_run_plugin();
}
function license_Invalid_alert() {
	$screens = array( 'post', 'page' );
	foreach ( $screens as $screen ) {
		add_meta_box( 
           'seo_div',
           __('license_Invalid_alert ','mnbaa-seo'),
           'license_Invalid_alert_callback',
           $screen,
           'normal'
      );
	}
}

function license_Invalid_alert_callback($post){
?>
<div id="mytabs" class="">
	<?php echo __('License key is Invalid', 'mnbaa-seo')?>
	<br>
	<a href="admin.php?page=mnbaa_seo">insert secret key here</a>
	<br>
	<a href="http://clientarea.mnbaa.com/clientarea.php?action=products">get secret key here</a>
</div>
<?php
}

//////////////////////////////////////////  Expired  /////////////////////////////////////////////
// License key is Invalid
function license_Expired() {
	 add_action( 'add_meta_boxes', 'license_Expired_alert' );
	add_action( 'admin_menu', 'mnbaa_seo_add_menu_page' );
	//echo "EXPI";
}

function license_Expired_alert() {
	$screens = array( 'post', 'page' );
	foreach ( $screens as $screen ) {
		add_meta_box( 
           'seo_div',
           __('license_Expired_alert ','mnbaa-seo'),
           'license_Expired_alert_callback',
           $screen,
           'normal'
      );
	}
}

function license_Expired_alert_callback($post){
?>
<div id="mytabs" class="">
	<?php echo __('License key is Expired', 'mnbaa-seo')?>
	<br>
	<a href="admin.php?page=mnbaa_seo"><?php echo __('Insert secret key here', 'mnbaa-seo')?></a>
	<br>
	<a href="http://clientarea.mnbaa.com/clientarea.php?action=products"><?php echo __('get secret key here', 'mnbaa-seo')?></a>
</div>
<?php
}

//////////////////////////////////////////  Suspended  /////////////////////////////////////////////
// License key is Suspended
function license_Suspended() {
	add_action( 'add_meta_boxes', 'license_Suspended_alert' );
	add_action( 'admin_menu', 'add_seo_menu_page' );
}

function license_Suspended_alert() {
	$screens = array( 'post', 'page' );
	foreach ( $screens as $screen ) {
		add_meta_box( 
           'seo_div',
           __('license_Suspended_alert ','mnbaa-seo'),
           'license_Suspended_alert_callback',
           $screen,
           'normal'
      );
	}
}

function license_Suspended_alert_callback($post){
?>
<div id="mytabs" class="">
	<?php echo __('License key is Suspended', 'mnbaa-seo')?>
	<br>
	<a href="admin.php?page=mnbaa_seo">insert secret key here</a>
	<br>
	<a href="http://clientarea.mnbaa.com/clientarea.php?action=products">get secret key here</a>
</div>
<?php
}

function mnbaa_seo_add_meta_box() {
	$screens = json_decode(get_option('mnbaa_seo_screens'));
	
	if(sizeof($screens)>0){
		foreach ( $screens as $screen ) {
			add_meta_box( 
			   'seo_div',
			   __('Nashr SEO by <a href="http://mnbaa.com" target="_blank" style="text-decoration: none;font-size: 14px;">Mnbaa</a> ','mnbaa-seo'),
			   'mnbaa_seo_metabox_callback',
			   $screen,
			   'normal'
		  );
		}
	}
}

function mnbaa_seo_run_plugin() {
	add_action( 'add_meta_boxes', 'mnbaa_seo_add_meta_box' );
	add_action('admin_enqueue_scripts', 'mnbaa_seo_run_styles' );
	add_action( 'save_post', 'mnbaa_seo_save_meta_box_data' );
	add_action( 'wp_head', 'mnbaa_seo_header_data' );
	$screens_option = json_decode(get_option('mnbaa_seo_screens'));
	add_filter( "wp_title", 'mnbaa_seo_wp_title', 10, 2 );
	add_action( 'admin_menu', 'mnbaa_seo_add_menu_page' );
	add_action( 'admin_menu', 'mnbaa_seo_add_social_menu_page' );
	add_action( 'admin_menu', 'mnbaa_seo_add_index_page' );
	add_action( 'admin_menu', 'mnbaa_seo_add_archive_page' );
	add_action('wp_ajax_mnbaa_seo_get_word_count', 'mnbaa_seo_get_word_count');
	if ( is_admin() && ( isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] !== '' ) && (isset($_GET['post_type']) && (in_array($_GET['post_type'],$screens_option)))) {
		add_action( sanitize_text_field( $_GET['taxonomy'] ) . '_edit_form', 'mnbaa_seo_taxonomy_form' , 90, 1 );
	}
	add_action( 'edit_term', 'mnbaa_seo_update_taxonomy', 99, 3 );
	add_action( 'admin_enqueue_scripts', 'mnbaa_seo_load_admin_things' );
	add_action('wp_ajax_mnbaa_seo_get_archive_meta', 'mnbaa_seo_get_archive_meta');
	$version_option=get_option('mnbaa_seo_private_update' );
	if($version_option=='TRUE'){
		//echo "true";
		// include( plugin_dir_path( __FILE__ ) . 'includes/mnbaa_functions.php');
		// include( plugin_dir_path( __FILE__ ) . 'includes/mnbaa_functions.php');
	}
}

function mnbaa_seo_run_styles() {
	$lang=get_bloginfo("language");
	$lang=substr($lang,0, 2);
	wp_enqueue_style('autocomplete', plugins_url('../styles/autocomplete-'.$lang.'.css', __FILE__ ));
}


function mnbaa_seo_load_admin_things() {
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
}

//add seo form to category,tag,taxonomy
function mnbaa_seo_taxonomy_form ($term) {
   global $socialMediaItems;
   global $prefix;
   $single_taxonomy_meta_data = get_option( $prefix.'taxonomy_'.$term->term_id.'_meta');
   
	?>
    <div id='seo_div'><?php echo __('Nashr SEO by <a href="http://mnbaa.com" target="_blank" style="text-decoration: none;font-size: 14px;">Mnbaa</a> ','mnbaa-seo'); ?>
	<div id="mytabs">
    <input type="hidden" name="prefix" id="prefix" value="<?php echo $prefix ; ?>"  />
    <input type="hidden" value="<?php echo $term->term_id; ?>"  id="post_id"/>
		<input type="hidden" value="<?php echo $term->name; ?>"  id="search_title"/>
		<h2 class="nav-tab-wrapper" id="wpseo-tabs" style="padding:0px;">
   		<ul class="category-tabs" style="margin:0px;padding:0px;">
		<?php foreach ($socialMediaItems as $k => $item):?>
			<li><a href="#tabs-<?php echo $k?>" class="nav-tab nav-tab-active"  style="background:#f1f1f1"><?php echo ucfirst($item['label'])?></a></li>
		<?php endforeach ?>
       	</ul>
       	</h2>
       	<br class="clear" />
       
       	<?php 
       	// Add an nonce field so we can check for it later.
		wp_nonce_field( 'seo_metabox', 'seo_metabox_nonce' );
		foreach ($socialMediaItems as $k => $item):
			$itemArray = $item['val'].'_meta_fields';
			global $$itemArray;
			$fields = $$itemArray;
		?>
		<div id="tabs-<?php echo $k?>">‏
			<table class="form-table">
				<?php foreach ($fields as $field):
				?>
				<tr>
					<th><?php mnbaa_seo_make_label($field['label'], $field['name'])?> </th>
					<td>
				  <?php
					switch ($field['type']) {
						case 'snippet' :
							mnbaa_seo_make_snippet($field, $term->term_id,'term');
							break;
						case 'text' :
							mnbaa_seo_make_input($field, $single_taxonomy_meta_data[$field['name']]);
							break;

						case 'textarea' :
							mnbaa_seo_make_textarea($field, $single_taxonomy_meta_data[$field['name']]);
							break;

						case 'image' :
							mnbaa_seo_make_img_input($field, $single_taxonomy_meta_data[$field['name']]);
							break;

						case 'select' :
							mnbaa_seo_make_select($field, $single_taxonomy_meta_data[$field['name']]);
							break;
							
						case 'multi-select' :
							mnbaa_seo_make_multi_select($field, $single_taxonomy_meta_data[$field['name']]);
							break;
						case 'div' :
							mnbaa_seo_make_div_contain_li($field, $single_taxonomy_meta_data[$field['name']],"not_post");
							break;	
						case 'robots' :
							foreach ($field['fields'] as $field):
								switch ($field['type']) {
									
									case 'select' :
										mnbaa_seo_make_select($field, $single_taxonomy_meta_data[$field['name']]);
										break;
										
									case 'checkbox' :
										if($single_taxonomy_meta_data[$field['name']]==$field['value']) $checked='checked';
										else $checked='';
										mnbaa_seo_make_checkbox($field,$field['label'] ,$field['value'],$checked);
										break;	
								}
							endforeach;	
							//mnbaa_seo_make_robot($field, $term->term_id,'term');
							break;		
						default :
							break;
					}
				?>
			</td>
				</tr>
					
				<?php endforeach ?>
			</table>
		</div>
		<?php endforeach ?>
	</div></div>
    <?php
    }
	
function mnbaa_seo_update_taxonomy( $term_id, $tt_id, $taxonomy ) {
	global $socialMediaItems;
	global $prefix;
	$taxonomy_meta_data=array();
	foreach ($socialMediaItems as $k => $item)
	{	
		$itemArray = $item['val'].'_meta_fields';
		global $$itemArray;
		$fields = $$itemArray;
		
		foreach($fields as $field){
			
			if($field['type']=='robots'){
							
				foreach ($field['fields'] as $robot_field):
					
					if(isset( $_POST[$robot_field['name']] )){
						$seo_data=sanitize_text_field($_POST[$robot_field['name']]);
						$taxonomy_meta_data[$robot_field['name']]=$seo_data;
					}
								
					endforeach;
				}
			
			
			if ( ! isset( $_POST[$field['name']] ) )
			 {
				//return;
			 }
			 else
			 {
				//$field_data = sanitize_text_field( $_POST[$field['name']] );
				$field_data="";
				if($field['name']=='mnbaa_seo_keywords'){
					$key_words =$_POST[$field['name']];
					if($key_words[count($key_words)-1]== ''){
						array_pop($key_words);
					}
					foreach ($key_words as $key => $word) {
						if($word!='')
							($word === end($key_words))? $field_data .=$word : $field_data.=$word.',';
						}
					
				}
				else{
					$field_data = sanitize_text_field( $_POST[$field['name']] );
				}
				
				
				
				$taxonomy_meta_data[$field['name']]=$field_data;
				
			}
		}
	}
	
	update_option( $prefix.'taxonomy_'.$term_id.'_meta', $taxonomy_meta_data );
}	


function mnbaa_seo_metabox_callback($post){
	global $socialMediaItems;
	?>
	<div id="mytabs">
    <input type="hidden" name="prefix" id="prefix" value="<?php echo $prefix ; ?>"  />
    	<input type="hidden" value="<?php echo $post->ID; ?>"  id="post_id"/>
		<input type="hidden" value="<?php echo $post->post_title; ?>"  id="search_title"/>
		<h2 class="nav-tab-wrapper" id="wpseo-tabs" style="padding:0px;">
   		<ul class="category-tabs" style="margin:0px;padding:0px;">
		<?php foreach ($socialMediaItems as $k => $item):?>
			<li><a href="#tabs-<?php echo $k?>" class="nav-tab nav-tab-active"  style="background:#f1f1f1"><?php echo ucfirst($item['label'])?></a></li>
		<?php endforeach ?>
       	</ul>
       	</h2>
       	<br class="clear" />
       
       	<?php 
       	// Add an nonce field so we can check for it later.
		wp_nonce_field( 'seo_metabox', 'seo_metabox_nonce' );
		foreach ($socialMediaItems as $k => $item):
			$itemArray = $item['val'].'_meta_fields';
			global $$itemArray;
			$fields = $$itemArray;
		?>
		<div id="tabs-<?php echo $k?>">‏
			<table class="form-table">
				<?php foreach ($fields as $field):
					$post_seo_meta = get_post_meta($post->ID, $field['name'], true);?>
				<tr>
					<th><?php mnbaa_seo_make_label($field['label'], $field['name'])?> </th>
					<td>
				  <?php
					switch ($field['type']) {
						case 'snippet' :
							mnbaa_seo_make_snippet($field, $post->ID,'post');
							break;
						case 'text' :
							mnbaa_seo_make_input($field, $post_seo_meta);
							break;

						case 'textarea' :
							mnbaa_seo_make_textarea($field, $post_seo_meta);
							break;

						case 'image' :
							mnbaa_seo_make_img_input($field, $post_seo_meta);
							break;

						case 'select' :
							mnbaa_seo_make_select($field, $post_seo_meta);
							break;
							
						case 'multi-select' :
							mnbaa_seo_make_multi_select($field, $post_seo_meta);
							break;
						case 'div' :
							mnbaa_seo_make_div_contain_li($field, $post_seo_meta,"post");
							break;
							
							
						case 'robots' :
							foreach ($field['fields'] as $field):
								$seo_meta = get_post_meta($post->ID, $field['name'], true);
								switch ($field['type']) {
									
									case 'select' :
										mnbaa_seo_make_select($field, $seo_meta);
										break;
										
									case 'checkbox' :
										if($seo_meta==$field['value']) $checked='checked';
										else $checked='';
										mnbaa_seo_make_checkbox($field,$field['label'] ,$field['value'],$checked);
										break;	
								}
							endforeach;	
							//mnbaa_seo_make_robot($field, $term->term_id,'term');
							break;			

						default :
							break;
					}
				?>
			</td>
				</tr>
					
				<?php endforeach ?>
			</table>
		</div>
		<?php endforeach ?>
	</div>
    <?php
}

function mnbaa_seo_save_meta_box_data($post_id)
{
	global $socialMediaItems;
	// Check if our nonce is set.
	if ( ! isset( $_POST['seo_metabox_nonce'] ))
	{	
	return;
    }

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['seo_metabox_nonce'], 'seo_metabox' ))	
	{
      return;
	}
	

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] )
		if ( ! current_user_can( 'edit_page', $post_id ) )
			return;
	else
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

	foreach ($socialMediaItems as $k => $item)
	{	
		$itemArray = $item['val'].'_meta_fields';
		global $$itemArray;
		$fields = $$itemArray;

		foreach($fields as $field){
			if($field['type']=='robots'){
							
				foreach ($field['fields'] as $robot_field):
					$old_data 	= get_post_meta($post_id, $robot_field['name'], true);
					if(isset( $_POST[$robot_field['name']] )){
						$seo_data=sanitize_text_field($_POST[$robot_field['name']]);
						
					if ($seo_data && $seo_data != $old_data) 
					{
						update_post_meta($post_id, $robot_field['name'], $seo_data);
					}
					elseif ('' == $seo_data && $old_data) 
					 {
						delete_post_meta($post_id, $robot_field['name'], $old_data);
					 }

						
					}
								
					endforeach;
				
			}
			if ( ! isset( $_POST[$field['name']] ) )
			 {
				//return;
			 }
			 else
			 {
				$field_data = sanitize_text_field( $_POST[$field['name']] );
				$old_data 	= get_post_meta($post_id, $field['name'], true);
				//
				$field_data="";
				if($field['name']=='mnbaa_seo_keywords'){
					$key_words =$_POST[$field['name']];
					if($key_words[count($key_words)-1]== ''){
						array_pop($key_words);
					}
					foreach ($key_words as $key => $word) {
						if($word!='')
							($word === end($key_words))? $field_data .=$word : $field_data.=$word.',';
						}
					
				}
				else{
					$field_data = sanitize_text_field( $_POST[$field['name']] );
				}
				
				//
				if ($field_data && $field_data != $old_data) 
				{
					update_post_meta($post_id, $field['name'], $field_data);
				}
				 elseif ('' == $field_data && $old_data) 
				 {
					delete_post_meta($post_id, $field['name'], $old_data);
				}
			}
		}
	}
}

function mnbaa_seo_header_data(){
	wp_reset_query();
	
	if ( is_front_page() && is_home() ) {
	    mnbaa_seo_print_header_data('posts_page'); //if posts page is home page
	} elseif ( is_front_page() ) {
		mnbaa_seo_print_single_header_data();
	} elseif ( is_home() ) {
	    mnbaa_seo_print_header_data("posts"); //posts page
	}elseif(is_category()){
		mnbaa_seo_print_header_data("term");
	}elseif(is_tax()){
		mnbaa_seo_print_header_data("term");
	}elseif(is_archive()){
		mnbaa_seo_print_header_data("archive");
	}else{
		mnbaa_seo_print_single_header_data();
		
	}
}

function mnbaa_seo_print_single_header_data(){
	
	$post_id=get_the_ID();
	$post_type=get_post_type($post_id);
	$screens_option = json_decode(get_option('mnbaa_seo_screens'));
	global $settingSocialMediaItems;
	global $socialMediaItems;
		
	 if(in_array($post_type,$screens_option))
	 {
		echo "<!-- Mnbaa SEO plugin meta Tags Start -->"."\n";
		foreach ($socialMediaItems as $k => $item)
		{
			$itemArray = $item['val'].'_meta_fields';
			global $$itemArray;
			$fields = $$itemArray;
			
			foreach ($fields as $field)
			 {
				 
				 if($field['type']=='robots'){
					$robot_content='';		
					foreach ($field['fields'] as $robot_field):
						$robot_content_value=get_post_meta($post_id, $robot_field['name'], true);
						if($robot_content_value!='')
							$robot_content .= $robot_content_value.",";
						
					endforeach;
					$robot_content=substr($robot_content, 0, -1);
					if($robot_content!='')
						mnbaa_seo_make_metaTage($field['prop'], $field['val'], $robot_content);	
				 }else
					$content = get_post_meta($post_id, $field['name'], true);
				
				if($content!=''){
					//check if is link meta tag
					if($field['prop']=='link')
					{
						mnbaa_seo_make_linkTage($field['val'], $content);
					}
					else{
						if($field['val']=='og:image'){
							$img= wp_get_attachment_image_src($content,'full');
							$content=$img[0];
						}
						if($field['type'] !='robots')
							mnbaa_seo_make_metaTage($field['prop'], $field['val'], $content);
					}
				}
			}
		}	
			//get  different times of post
			$publish_date=get_post_time( 'c');
			//modified date
			$revision_data = wp_post_revision_title( $post ,false );
		   // $modified_date= $date_time[0].'at'.$date_time[1];
		   $modified_date=get_the_modified_date('c');
				//get the category of this post
			$cats=get_the_category( $post_id );
			//$cat='';
			foreach ($cats as $key => $value) {
				echo '<meta property="article:section" content="'.$value->name .'" />'."\n";
				
			}
		
		//get url of post
		$permalink_url = get_permalink();
		//get site title
		$site_name=get_bloginfo('name');
		//get locale
		$locale=get_locale();
		//print static meta tags 
		echo '<meta property="og:locale" content="'.$locale.'" />'."\n";
		echo '<meta property="og:url" content="'.$permalink_url.'" />'."\n";
		echo '<meta property="article:published_time" content="'.$publish_date .'" />'."\n";
		echo '<meta property="article:modified_time" content="'.$modified_date.'" />'."\n";
		echo '<meta property="og:updated_time" content="'.$modified_date.'" />'."\n";
		
	 
	
		foreach ($settingSocialMediaItems as $k => $item)
		{
			$itemArray = $item['val'].'_seo_meta_setting';
			global $$itemArray;
			$fields = $$itemArray;
			
			foreach ($fields as $seo_field) 
			{
				$seo_meta	= get_option($seo_field['name']);
				
				if($seo_meta)
				{
					mnbaa_seo_make_metaTage($seo_field['prop'], $seo_field['val'], $seo_meta);
				}	
	
			}
		}
		echo "<!-- Mnbaa SEO plugin meta Tags END -->"."\n";
		}
}

function mnbaa_seo_print_header_data($flag){
	global $prefix;
	global $wp_query;
	$screens_option = json_decode(get_option('mnbaa_seo_screens'));
	if((in_array(get_post_type(),$screens_option)) || ($flag=='posts_page' || $flag=='posts'))
	{
		if($flag=='posts_page' || $flag=='posts'){
			$object = $wp_query->get_queried_object();
		}elseif($flag=='archive'){
			$post_archive=$wp_query->query['post_type'];
			if(get_queried_object()==null) $post_archive='post';
			$post_archive_meta_data = json_decode(get_option($prefix.$post_archive.'_archive'));
		}elseif($flag=='term'){
			$object = $wp_query->get_queried_object();
			$term_id=$object->term_id;
			$single_taxonomy_meta_data = get_option( $prefix.'taxonomy_'.$term_id.'_meta');
		}
			global $settingSocialMediaItems;
			global $socialMediaItems;
		
			echo "<!-- Mnbaa SEO plugin meta Tags Start -->"."\n";
			foreach ($socialMediaItems as $k => $item)
			{
				$itemArray = $item['val'].'_meta_fields';
				global $$itemArray;
				$fields = $$itemArray;
				
				foreach ($fields as $field)
				 { 
					if($flag=='posts_page' || $flag=='posts'){
						
						if($field['type']=='robots'){
							$robot_content='';		
							foreach ($field['fields'] as $robot_field):
								
									$robot_content_option=get_option($robot_field['name']);
									if($robot_content_option !='')
										$robot_content .= $robot_content_option.",";
								
							endforeach;
							$robot_content=substr($robot_content, 0, -1);
						}else
						
						$content = get_option($field['name']);
						
					}
					elseif($flag=='term'){
						if($field['type']=='robots'){
							$robot_content='';		
							foreach ($field['fields'] as $robot_field):
								if($single_taxonomy_meta_data[$robot_field['name']]!='')
									$robot_content .= $single_taxonomy_meta_data[$robot_field['name']].",";
								
							endforeach;
							$robot_content=substr($robot_content, 0, -1);
						}else
							$content = $single_taxonomy_meta_data[$field['name']];
					}elseif($flag=='archive'){
						if($field['type']=='robots'){
							$robot_content='';		
							foreach ($field['fields'] as $robot_field):
							
								if($post_archive_meta_data->$robot_field['name']!='')
									$robot_content .= $post_archive_meta_data->$robot_field['name'].",";
								
							endforeach;
							$robot_content=substr($robot_content, 0, -1);
						}else
							$content = $post_archive_meta_data->$field['name'];
					}
					if($field['type'] =='robots'){
							mnbaa_seo_make_metaTage($field['prop'], $field['val'], $robot_content);	
					}	
					
					if($content!=''){
						//check if is link meta tag
						if($field['prop']=='link')
						{
							mnbaa_seo_make_linkTage($field['val'], $content);
						}
						else{
							if($field['val']=='og:image'){
								$img= wp_get_attachment_image_src($content,'full');
								$content=$img[0];
							}
							//echo $field['type'];
							if($field['type'] !='robots'){
								mnbaa_seo_make_metaTage($field['prop'], $field['val'], $content);
							}
							
						}
					}
				}
			}	
			
			if($flag=='posts_page') $permalink_url = get_bloginfo('url');
			elseif($flag=='posts' && get_permalink() !=get_page_link($object->ID)) $permalink_url = get_page_link($object->ID);
			elseif($flag=='posts' && get_permalink() == get_page_link($object->ID)) $permalink_url = get_bloginfo('url');
			elseif($flag=='term') $permalink_url = get_term_link( $object );
			elseif($flag=='archive'){ 
				if(get_queried_object()==null) $permalink_url = $_SERVER["REQUEST_URI"]; 
				else $permalink_url = get_post_type_archive_link($post_archive);
			}
			//get site title
			$site_name=get_bloginfo('name');
			//get locale
			$locale=get_locale();
			//print static meta tags 
			
			echo '<meta property="og:locale" content="'.$locale.'" />'."\n";
			echo '<meta property="og:url" content="'.$permalink_url.'" />'."\n";
	
		
			foreach ($settingSocialMediaItems as $k => $item)
			{
				$itemArray = $item['val'].'_seo_meta_setting';
				global $$itemArray;
				$fields = $$itemArray;
				
				foreach ($fields as $seo_field) 
				{
					$seo_meta	= get_option($seo_field['name']);
					
					if($seo_meta)
					{
						mnbaa_seo_make_metaTage($seo_field['prop'], $seo_field['val'], $seo_meta);
					}	
		
				}
			}
			echo "<!-- Mnbaa SEO plugin meta Tags END -->"."\n";
	}
}

function mnbaa_seo_wp_title(){
		wp_reset_query();
		
		if ( is_front_page() && is_home() ) {
			//$title=seo_wp_title_index_page(); //if posts page is home page
			 $title=mnbaa_seo_wp_title_single_header();
		} elseif ( is_front_page() ) {
			 $title=mnbaa_seo_wp_title_single_header('single');
		} elseif ( is_home() ) {
			//$title=seo_wp_title_index_page(); //posts page
			$title=mnbaa_seo_wp_title_single_header();
		}elseif(is_category()){
			$title=mnbaa_seo_wp_title_single_header('tax');
		}elseif(is_tax()){
			$title=mnbaa_seo_wp_title_single_header('tax');
		}elseif(is_archive()){
			$title=mnbaa_seo_wp_title_single_header('archive');
		}else{
			$title=mnbaa_seo_wp_title_single_header('single');
			
		}
		return $title." ";
}

function mnbaa_seo_wp_title_single_header($flag=''){
	global $prefix;
	$screens_option = json_decode(get_option('mnbaa_seo_screens'));
	if($flag=='single'){
		if(in_array(get_post_type(),$screens_option))
		{
			$post_id=get_the_ID();
			$title = get_post_meta($post_id, $prefix.'title', true);
			if($title=='') $title=get_the_title( $post_id );
		}else{
			$title=get_the_title( $post_id );
		}
	}elseif($flag=='tax'){
		if(in_array(get_post_type(),$screens_option))
		{
			global $wp_query;
			$object = $wp_query->get_queried_object();
			$term_id=$object->term_id;
			$single_taxonomy_meta_data = get_option( $prefix.'taxonomy_'.$term_id.'_meta');
			$title = $single_taxonomy_meta_data[$prefix.'title'];	
			if($title=='') $title=$object->name ;
		}else $title=$object->name ;
	}elseif($flag=='archive'){
		if(in_array(get_post_type(),$screens_option))
		{
			global $wp_query;
			$post_archive=$wp_query->query['post_type'];
			if(get_queried_object()==null) $post_archive='post';
			$post_archive_meta_data = json_decode(get_option($prefix.$post_archive.'_archive'));
			$title_var=$prefix.'title';
			$title = $post_archive_meta_data->$title_var;	
			if($title=='' && get_queried_object()!=null) $title=$post_archive ;
			if($title=='' && get_queried_object()==null) $title="archive" ;
		}else{
			if(get_post_type()=='post') $title="archive" ;
			else $title=get_post_type() ;
		}
	}
	else{
		
		$title = get_option($prefix.'title');
		//if ($title=='') $title=get_bloginfo('name') ;
	}
	return $title;
}
function mnbaa_seo_add_licnse_page(){
	add_menu_page( __('Nashr SEO Settings','mnbaa-seo'),__('Nashr SEO','mnbaa-seo'), 'manage_options', 'mnbaa_seo', 'mnbaa_seo_licnse_menu_page',plugins_url( '', __FILE__ ).'/../images/seo-icon.png');
}
function mnbaa_seo_add_menu_page(){
	add_menu_page( __('Nashr SEO Settings','mnbaa-seo'),__('Nashr SEO','mnbaa-seo'), 'manage_options', 'mnbaa_seo', 'mnbaa_seo_custom_menu_page',plugins_url( '', __FILE__ ).'/../images/seo-icon.png');
}
function mnbaa_seo_add_custom_license_page(){
	add_menu_page( __('Nashr SEO license','mnbaa-seo'),__('Nashr SEO license','mnbaa-seo'), 'manage_options', 'mnbaa_seo', 'mnbaa_seo_custom_license_page',plugins_url( '', __FILE__ ).'/../images/seo-icon.png');
}

function mnbaa_seo_add_index_page(){
	add_submenu_page( 
          'mnbaa_seo'  
        , __('Index Page Setting','mnbaa-seo') 
        , __('Index Page Setting','mnbaa-seo')
        , 'manage_options'
        , 'mnbaa_index_page_seo'
        , 'mnbaa_seo_index_page'
    );
	
}
function mnbaa_seo_custom_menu_page(){
	if ( isset($_POST['Submit']) ) {
		
			mnbaa_seo_update_options();
		}
		include( plugin_dir_path( __FILE__ ) . 'general_setting.php');
}


function mnbaa_seo_licnse_menu_page(){
	if ( isset($_POST['Submit']) ) {
		echo "yes";
		$license_value=$_POST['license_key'];
		if ( get_option('license_key') !== false ) {
	    // The option already exists, so we just update it.
	    update_option('license_key', $license_value );
		} else {
		    // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
		    add_option('license_key', $license_value );
		}
	//
	}
	include( plugin_dir_path( __FILE__ ) .  '../views/license_page.php');
	
}

function mnbaa_seo_add_archive_page(){
	
	add_submenu_page( 
          'mnbaa_seo'  
        , __('Archive Page Setting','mnbaa-seo') 
        , __('Archive Page Setting','mnbaa-seo')
        , 'manage_options'
        , 'mnbaa_archive_page_seo'
        , 'mnbaa_seo_archive_page'
    );
	
}

function mnbaa_seo_add_social_menu_page(){
	add_submenu_page( 
          'mnbaa_seo'  
        , __('Social Media Setting','mnbaa-seo') 
        , __('Social Media','mnbaa-seo')
        , 'manage_options'
        , 'mnbaa_social_seo'
        , 'mnbaa_seo_social_custom_menu_page'
    );
}

function mnbaa_seo_index_page(){
	global $socialMediaItems;
	?>
    <h1><?php  echo  __('Index page setting','mnbaa-seo'); ?></h1>
    <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="prefix" id="prefix" value="<?php echo $prefix ; ?>"  />
	<div id="mytabs">
		<h2 class="nav-tab-wrapper" id="wpseo-tabs" style="padding:0px;">
   		<ul class="category-tabs" style="margin:0px;padding:0px;">
		<?php foreach ($socialMediaItems as $k => $item):?>
			<li><a href="#tabs-<?php echo $k?>" class="nav-tab nav-tab-active"  style="background:#f1f1f1"><?php echo ucfirst($item['label'])?></a></li>
		<?php endforeach ?>
       	</ul>
       	</h2>
       	<br class="clear" />
       
       	<?php 
       	// Add an nonce field so we can check for it later.
		wp_nonce_field( 'seo_metabox', 'seo_metabox_nonce' );
		foreach ($socialMediaItems as $k => $item):
			$itemArray = $item['val'].'_meta_fields';
			global $$itemArray;
			$fields = $$itemArray;
		?>
        
		<div id="tabs-<?php echo $k?>">‏
			<table class="form-table">
				<?php foreach ($fields as $field):
				
				
					if ( isset($_POST['Submit']) ) {
						$seo_data='';
						if($field['type']=='robots'){
							
							foreach ($field['fields'] as $robot_field):
								if(isset( $_POST[$robot_field['name']] )){
									$seo_data=sanitize_text_field($_POST[$robot_field['name']]);
									update_option($robot_field['name'], $seo_data);
								}
								
							endforeach;
							
						}
					
						if ( ! isset( $_POST[$field['name']] ) ) {
							
							//return;
						}else{
							
							if(isset($field['label_type'])){
								//$seo_field_data = $_POST[$seo_field['name']];
								$meta_arr=explode('content',$_POST[$field['name']]);
								if(sizeof($meta_arr) !=1)
									
									$seo_field_data = rtrim(ltrim($meta_arr[1],'=\"'),'\" />');
								
								else
									$seo_field_data = sanitize_text_field( $_POST[$field['name']] );	
							}else
								//$seo_field_data = sanitize_text_field( $_POST[$field['name']] );
								$seo_field_data="";
								
								if($field['name']=='mnbaa_seo_keywords'){
									$key_words =$_POST[$field['name']];
									if($key_words[count($key_words)-1]== ''){
										array_pop($key_words);
									}
									foreach ($key_words as $key => $word) {
										if($word!='')
											($word === end($key_words))? $seo_field_data .=$word : $seo_field_data.=$word.',';
										}
									
								}
								else{
									
									$seo_field_data = sanitize_text_field( $_POST[$field['name']] );
								}
								
								
								
							update_option($field['name'], $seo_field_data);
						}
				} 
				
					
					$seo_meta = get_option($field['name']);
					?>
				<tr>
					<th><?php mnbaa_seo_make_label($field['label'], $field['name'])?> </th>
					<td>
				  <?php
					switch ($field['type']) {
						case 'snippet' :
							mnbaa_seo_make_snippet_loopPage($field);
							break;
						case 'text' :
							mnbaa_seo_make_input($field, $seo_meta);
							break;

						case 'textarea' :
							mnbaa_seo_make_textarea($field, $seo_meta);
							break;

						case 'image' :
							mnbaa_seo_make_img_input($field, $seo_meta);
							break;

						case 'select' :
							mnbaa_seo_make_select($field, $seo_meta);
							break;
							
						case 'multi-select' :
							mnbaa_seo_make_multi_select($field, $seo_meta);
							break;
						case 'div' :
							mnbaa_seo_make_div_contain_li($field, $seo_meta,"index");
							break;	
							
						case 'robots' :
							foreach ($field['fields'] as $field):
								$seo_meta = get_option($field['name']);
								switch ($field['type']) {
									
									case 'select' :
										mnbaa_seo_make_select($field, $seo_meta);
										break;
										
									case 'checkbox' :
										if($seo_meta==$field['value']) $checked='checked';
										else $checked='';
										mnbaa_seo_make_checkbox($field,$field['label'] ,$field['value'],$checked);
										break;	
								}
							endforeach;	
							//mnbaa_seo_make_robot($field, $term->term_id,'term');
							break;				

						default :
							break;
					}
				?>
			</td>
				</tr>
					
				<?php endforeach ?>
			</table>
		</div>
		<?php endforeach ?>
	</div><p class="submit">
	<input type="submit" name="Submit" id="button" value="<?php echo  __('Save','mnbaa-seo'); ?>" class="button button-primary" /></p></form>
    <?php
}

function mnbaa_seo_archive_page(){
	global $socialMediaItems;
	global $prefix;
	$post_types = get_post_types(array('public'=>'ture'));
	$archive_meta_data=array();
	$screens_option = json_decode(get_option('mnbaa_seo_screens'));
	?>
    <h1><?php  echo  __('Archive page setting','mnbaa-seo'); ?></h1>
    
    <form action="" method="post" enctype="multipart/form-data" id="<?php echo $prefix."archive_form"; ?>">
    <input type="hidden" name="prefix" id="prefix" value="<?php echo $prefix ; ?>"  />
	<div id="mytabs">
    <table class="form-table"><tr><th><?php echo  __('Select post type','mnbaa-seo') ?> </th>
	<td><select name="<?php echo $prefix.'archive' ; ?>" id="<?php echo $prefix.'archive' ; ?>">
        <option value="<?php echo  __('Select from list','mnbaa-seo') ?>"><?php echo  __('Select from list','mnbaa-seo') ?></option>
     <?php foreach($post_types as $type){
		 if($type !='page' && $type !='attachment' && (in_array($type,$screens_option)))
	 {
	 ?>  
     	<option value="<?php echo $type ; ?>"><?php echo $type; ?></option>
     <?php } }?> 
    </select></td></tr></table>
    
		<h2 class="nav-tab-wrapper" id="wpseo-tabs" style="padding:0px;">
   		<ul class="category-tabs" style="margin:0px;padding:0px;">
		<?php foreach ($socialMediaItems as $k => $item):?>
			<li><a href="#tabs-<?php echo $k?>" class="nav-tab nav-tab-active"  style="background:#f1f1f1"><?php echo ucfirst($item['label'])?></a></li>
		<?php endforeach ?>
       	</ul>
       	</h2>
       	<br class="clear" />
       
       	<?php 
       	// Add an nonce field so we can check for it later.
		wp_nonce_field( 'seo_metabox', 'seo_metabox_nonce' );
		foreach ($socialMediaItems as $k => $item):
			$itemArray = $item['val'].'_meta_fields';
			global $$itemArray;
			$fields = $$itemArray;
		?>
        
		<div id="tabs-<?php echo $k?>">‏
			<table class="form-table">
				<?php foreach ($fields as $field):
				
				
					if ( isset($_POST['Submit']) ) {
						
						if($field['type']=='robots'){
							
							foreach ($field['fields'] as $robot_field):
								if(isset( $_POST[$robot_field['name']] )){
									$seo_data=sanitize_text_field($_POST[$robot_field['name']]);
									$archive_meta_data[$robot_field['name']]=$seo_data;
								}
								
							endforeach;
							
						}
						
					
						if ( ! isset( $_POST[$field['name']] ) ) {
							
							//return;
						}else{
							if(isset($field['label_type'])){
								//$seo_field_data = $_POST[$seo_field['name']];
								$meta_arr=explode('content',$_POST[$field['name']]);
								if(sizeof($meta_arr) !=1)
									
									$seo_field_data = rtrim(ltrim($meta_arr[1],'=\"'),'\" />');
								
								else
									$seo_field_data = sanitize_text_field( $_POST[$field['name']] );	
							}else
								//$seo_field_data = sanitize_text_field( $_POST[$field['name']] );
							//update_option($field['name'], $seo_field_data);
							$seo_field_data="";
								if($field['name']=='mnbaa_seo_keywords'){
									$key_words =$_POST[$field['name']];
									if($key_words[count($key_words)-1]== ''){
										array_pop($key_words);
									}
									foreach ($key_words as $key => $word) {
										if($word!='')
											($word === end($key_words))? $seo_field_data .=$word : $seo_field_data.=$word.',';
										}
									
								}
								else{
									$seo_field_data = sanitize_text_field( $_POST[$field['name']] );
								}
							$archive_meta_data[$field['name']]=$seo_field_data;
							
						}
				} 
				
					
					//$seo_meta = get_option($field['name']);
					?>
				<tr>
					<th><?php mnbaa_seo_make_label($field['label'], $field['name'])?> </th>
					<td>
				  <?php
					switch ($field['type']) {
						case 'snippet' :
							mnbaa_seo_make_snippet_term($field, '');
							break;
						case 'text' :
							mnbaa_seo_make_input($field, '');
							break;

						case 'textarea' :
							mnbaa_seo_make_textarea($field, '');
							break;

						case 'image' :
							mnbaa_seo_make_img_input($field, '');
							break;

						case 'select' :
							mnbaa_seo_make_select($field, '');
							break;
							
						case 'multi-select' :
							mnbaa_seo_make_multi_select($field, '');
							break;
						case 'div' :
							mnbaa_seo_make_div_contain_li($field, '',"archive");
							break;	
							
						case 'robots' :
							foreach ($field['fields'] as $field):
								switch ($field['type']) {
									
									case 'select' :
										mnbaa_seo_make_select($field, '');
										break;
										
									case 'checkbox' :
										mnbaa_seo_make_checkbox($field,$field['label'] ,$field['value']);
										break;	
								}
							endforeach;			

						default :
							break;
					}
				?>
			</td>
				</tr>
					
				<?php endforeach ?>
			</table>
		</div>
		<?php endforeach ;
		
		update_option( $prefix.$_POST[$prefix.'archive'].'_archive', json_encode($archive_meta_data) );
		?>
	</div><p class="submit">
	<input type="submit" name="Submit" id="button" value="<?php echo  __('Save','mnbaa-seo'); ?>" class="button button-primary" /></p></form>
    <?php
}
function mnbaa_seo_get_archive_meta(){
	global $SEO_meta_fields;
	global $prefix;
	$option=$_POST['option'];
	echo mnbaa_seo_make_snippet_term($SEO_meta_fields[0],$option);
	echo "^";
	$archive_meta_data =get_option( $prefix.$option.'_archive');
	$archive_meta_data_array=json_decode($archive_meta_data);
	if(empty($archive_meta_data_array)){
		$og_image=plugins_url( 'images/noimage.jpg', dirname(__FILE__) );
		echo "0"."*".$og_image;
	}else{
		$og_image = wp_get_attachment_image_src($archive_meta_data_array->mnbaa_seo_facebook_image, 'medium'); 
		$og_image = $og_image[0];
		if($og_image=='') $og_image=plugins_url( 'images/noimage.jpg', dirname(__FILE__) );
		echo $archive_meta_data."*".$og_image;
	}
	die(); 
}

function mnbaa_seo_social_custom_menu_page(){
	
	global $settingSocialMediaItems;
	?>
		<form action="" method="post" enctype="multipart/form-data">
		<table class="form-table">
	<?php
	foreach ($settingSocialMediaItems as $k => $item):
		$itemArray = $item['val'].'_seo_meta_setting';
		global $$itemArray;
		$fields = $$itemArray; ?>
        <tr><th colspan="2"><h1><?php echo ucfirst($item['label']); ?></h1></th></tr>
        <?php
		foreach ($fields as $seo_field) {
			if ( isset($_POST['Submit']) ) {
				
					if ( ! isset( $_POST[$seo_field['name']] ) ) {
						
						return;
					}else{
						if(isset($seo_field['label_type'])){
							//$seo_field_data = $_POST[$seo_field['name']];
							$meta_arr=explode('content',$_POST[$seo_field['name']]);
							if(sizeof($meta_arr) !=1)
								
								$seo_field_data = rtrim(ltrim($meta_arr[1],'=\"'),'\" />');
							
							else
								$seo_field_data = sanitize_text_field( $_POST[$seo_field['name']] );	
						}else
							$seo_field_data = sanitize_text_field( $_POST[$seo_field['name']] );
						update_option($seo_field['name'], $seo_field_data);
					}
			} 
			
            $seo_meta = get_option($seo_field['name']); ?>
			<tr><th>
			<?php if(isset($seo_field['label_type'])) mnbaa_seo_make_link($seo_field['href'],$seo_field['label']) ; else mnbaa_seo_make_label($seo_field['label'], $seo_field['name']); ?>
			</th>
			<td>
			<?php
			switch($seo_field['type']) {
				//text
				case 'text':
				//$var=str_replace(\"\\","",htmlspecialchars($seo_meta));
				//$var=trim(htmlspecialchars($seo_meta), \' \\');
					mnbaa_seo_make_input($seo_field, htmlspecialchars($seo_meta));
				break;
		
			} //end switch
			?></td>
			</tr>
	<?php }
	endforeach; ?>
		
	
	</table><p class="submit">
  

	<input type="submit" name="Submit" id="button" value="<?php echo __('Save','mnbaa-seo') ?>" class="button button-primary" /></p>
</form>
<?php
 }
 
 function mnbaa_seo_activate()
{
	
    //$screen_options = 'mnbaa_seo_screens';
    $post_types = get_post_types( array('public'=>'ture'),'names' );
    $screen_value='[';
    foreach ($post_types as $post_type) {
        $screen_value=$screen_value.'"'.$post_type.'",';
    }
    $screen_value=substr($screen_value, 0, -1);
    $screen_value=$screen_value.']';
	//
	$version_value=get_option('mnbaa_seo_private_update'); 
	mnbaa_seo_update_options($screen_value,$version_value);
	
} 
function mnbaa_seo_update_options($screen_value=false,$version_value=false){
	$version_options="mnbaa_seo_private_update";
	
	if($version_value=='')$version_value="FALSE";
	if(isset($_POST['mnbaa_seo_private_update'])&& !empty($_POST['screens'])){   
		$version_value="TRUE";
	}
	if ( get_option( $version_options ) !== false ) {
	    // The option already exists, so we just update it.
	    update_option( $version_options, $version_value );
		} else {
		    // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
		    add_option( $version_options, $version_value );
		}
	//
	
	$screen_options = 'mnbaa_seo_screens';
	if(isset($_POST['screens']) && !empty($_POST['screens'])){   
		$screen_value=json_encode($_POST['screens']);
				
	}
	//check that wp option is exist in database 
	
	if ( get_option( $screen_options ) !== false ) {
	    // The option already exists, so we just update it.
	    update_option( $screen_options, $screen_value );
	} else {
	    // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
	   
	    add_option( $screen_options, $screen_value );
	}
			
}
 
?>