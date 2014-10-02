<?php

//////////////////////////////////////////  /////  ///////////////////////////////////////////////
//////////////////////////////////////////  Valid running  ///////////////////////////////////////////////
//////////////////////////////////////////  /////  ///////////////////////////////////////////////
function seo_add_meta_box() {
	$screens = json_decode(get_option('mnbaa_seo_screens'));
	
	if(sizeof($screens)>0){
		foreach ( $screens as $screen ) {
			add_meta_box( 
			   'seo_div',
			   __('Nashr SEO by <a href="http://mnbaa.com" target="_blank" style="text-decoration: none;font-size: 14px;">Mnbaa</a> ','mnbaa-seo'),
			   'seo_metabox_callback',
			   $screen,
			   'normal'
		  );
		}
	}
}

function run_mnbaa_seo_plugin() {
	add_action( 'add_meta_boxes', 'seo_add_meta_box' );
	add_action( 'save_post', 'seo_save_meta_box_data' );
	add_action( 'wp_head', 'seo_header_data' );
	add_filter( "wp_title", 'seo_wp_title', 10, 2 );
	add_action( 'admin_menu', 'add_seo_menu_page' );
	add_action( 'admin_menu', 'add_seo_social_menu_page' );
	
	
}
function seo_metabox_callback($post){
	global $socialMediaItems;
	?>
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
					$post_seo_meta = get_post_meta($post->ID, $field['name'], true);?>
				<tr>
					<th><?php make_label($field['label'], $field['name'])?> </th>
					<td>
				  <?php
					switch ($field['type']) {
						case 'text' :
							make_input($field, $post_seo_meta);
							break;

						case 'textarea' :
							make_textarea($field, $post_seo_meta);
							break;

						case 'image' :
							make_img_input($field, $post_seo_meta);
							break;

						case 'select' :
							make_select($field, $post_seo_meta);
							break;
							
						case 'multi-select' :
							make_multi_select($field, $post_seo_meta);
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

function seo_save_meta_box_data($post_id)
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
			if ( ! isset( $_POST[$field['name']] ) )
			 {
				return;
			 }
			 else
			 {
				$field_data = sanitize_text_field( $_POST[$field['name']] );
				$old_data 	= get_post_meta($post_id, $field['name'], true);
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

function seo_header_data(){
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
			$content = get_post_meta($post_id, $field['name'], true);
			
			if($content!=''){
				//check if is link meta tag
				if($field['prop']=='link')
				{
					make_linkTage($field['val'], $content);
				}
				else{
					if($field['val']=='og:image'){
						$img= wp_get_attachment_image_src($content,'full');
						$content=$img[0];
					}
					make_metaTage($field['prop'], $field['val'], $content);
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
				make_metaTage($seo_field['prop'], $seo_field['val'], $seo_meta);
			}	

		}
	}
 	echo "<!-- Mnbaa SEO plugin meta Tags END -->"."\n";
 	}	
}

function seo_wp_title(){
	global $prefix;
	$post_id=get_the_ID();
	$title = get_post_meta($post_id, $prefix.'title', true);
	return $title;
}

function add_seo_menu_page(){
	add_menu_page( __('Nashr SEO Settings','mnbaa-seo'),__('Nashr SEO','mnbaa-seo'), 'manage_options', 'mnbaa_seo', 'seo_custom_menu_page',plugins_url( '', __FILE__ ).'/../images/seo-icon.png');
}
function add_seo_custom_license_page(){
	add_menu_page( __('Nashr SEO license','mnbaa-seo'),__('Nashr SEO license','mnbaa-seo'), 'manage_options', 'mnbaa_seo', 'seo_custom_license_page',plugins_url( '', __FILE__ ).'/../images/seo-icon.png');
}

function add_seo_social_menu_page(){
	add_submenu_page( 
          'mnbaa_seo'  
        , __('Social Media Setting','mnbaa-seo') 
        , __('Social Media','mnbaa-seo')
        , 'manage_options'
        , 'mnbaa_social_seo'
        , 'social_seo_custom_menu_page'
    );
}

function seo_custom_menu_page(){
	if ( isset($_POST['Submit']) ) {
			
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
    
	<form action="" method="post" enctype="multipart/form-data">
    <table class="form-table">
    <tr>
       
        <th colspan="2"><h1><?php  echo  __('General Settings','mnbaa-seo'); ?></h1></th>
        </tr>
   
        
        <tr>
      
        <th colspan="2"><h1><?php  echo  __('Posts and pages which seo available to them','mnbaa-seo'); ?></h1></th>
        </tr>
        <?php  
        $post_types = get_post_types(array('public'=>'ture'));
		$screens_option = json_decode(get_option('mnbaa_seo_screens'));
		?>
        
        <tr>
			<th>
			 <?php  echo  __('Select all','mnbaa-seo'); ?>
			</th>
			<td>
				<input type="checkbox" name="sample" class="selectall" <?php if(sizeof($screens_option)== sizeof($post_types)) echo "checked"; ?> />
			</td>
         </tr>   
        
        <?php foreach ( $post_types as $post_type ) {?>
        <tr>
			<th>
			 <?php echo $post_type ; ?>	
			</th>
			<td>
				<input type="checkbox" name="screens[]" value="<?php echo $post_type ; ?>" <?php if (($screens_option) && in_array($post_type, $screens_option)) echo "checked" ;  ?>>
			</td>
         </tr>   
		<?php
		 }  
		 ?>	
	</table><p class="submit">
  

	<input type="submit" name="Submit" id="button" value="<?php echo  __('Save','mnbaa-seo'); ?>" class="button button-primary" /></p>
    </table>
    </form>
 
<?php 
}

function social_seo_custom_menu_page(){
	
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
			<?php if(isset($seo_field['label_type'])) make_link($seo_field['href'],$seo_field['label']) ; else make_label($seo_field['label'], $seo_field['name']); ?>
			</th>
			<td>
			<?php
			switch($seo_field['type']) {
				//text
				case 'text':
				//$var=str_replace(\"\\","",htmlspecialchars($seo_meta));
				//$var=trim(htmlspecialchars($seo_meta), \' \\');
					make_input($seo_field, htmlspecialchars($seo_meta));
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
?>