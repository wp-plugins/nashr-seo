<?php
//==================================================== FILES Functions ====================================================\\
function mnbaa_seo_unserializeFile($file) {
	if (file_exists($file)) {
		$fh = fopen($file, 'r');
		$fileContent = fread($fh, filesize($file));
		fclose($fh);
		return unserialize($fileContent);
	} else {
		return false;
	}
}

function mnbaa_seo_serializeFile($file, $data) {
	$fh = fopen($file, 'w');
	if (fwrite($fh, serialize($data))) {
		fclose($fh);
		return true;
	} else {
		return false;
	}

}

//==================================================== Meta forms  Functions ====================================================\\

function mnbaa_seo_redirect_to($location = NULL) {
	if ($location != NULL) {
		@header("Location: {$location}");
		echo("<script>location.href = '{$location}';</script>");
		exit ;

	}
}

function mnbaa_seo_make_label($text = '', $for = '') {
	echo '<label for="'.$for.'_ID">'.$text.'</label>';
}

function mnbaa_seo_make_input($field, $value = '') {
	echo '<input type="text" name="'.$field['name'].'" id="'.$field['name'].'_ID" value="'.$value.'" size="40" />
					<br /><span class="description">'.$field['desc'].'</span><br />';
}
function mnbaa_seo_make_checkbox($field, $label,$value, $checked='') {
		
	echo '<input type="checkbox" name="'.$field['name'].'" id="'.$field['name'].'_ID" value="'.$value.'"'.$checked.'   />'.$label.'
					<br /><span class="description">'.$field['desc'].'</span><br />';
}

function mnbaa_seo_make_textarea($field, $value = '') {
	echo '<textarea name="'.$field['name'].'" id="'.$field['name'].'_ID" cols="60" rows="4">'.$value.'</textarea>
					<br /><span class="description">'.$field['desc'].'</span><br />';
}

function mnbaa_seo_make_img_input($field, $post_seo_meta = '') {
	$og_image = plugins_url( 'images/noimage.jpg', dirname(__FILE__) );
	if ($post_seo_meta) { $og_image = wp_get_attachment_image_src($post_seo_meta, 'medium'); $og_image = $og_image[0];}
	echo '<input name="'.$field['name'].'" type="hidden" class="custom_upload_image" value="'.$post_seo_meta.'" id="'.$field['name'].'_ID'.'" />
		<img src="'.$og_image.'" class="custom_preview_image" alt="" id="'.$field['name'].'_img'.'" /><br />
		<input class="custom_upload_image_button button" type="button" value="'.__('Choose Image','mnbaa-seo').'" />
		<small> <a href="#" class="custom_clear_image_button">'.__('Remove Image','mnbaa-seo').'</a></small><br clear="all" />
		<span class="description">'.$field['desc'].'</span><br />';
}

function mnbaa_seo_make_select($field = '', $post_seo_meta = '') {
	echo '<select name="'.$field['name'].'" id="'.$field['name'].'_ID">';
	foreach ($field['options'] as $option) {
		echo '<option', $post_seo_meta == $option ? ' selected="selected"' : '', ' value="'.$option.'">'.$option.'</option>';
	}
	echo '</select><br /><span class="description">'.$field['desc'].'</span><br />';
}
// function to make multi select control
function mnbaa_seo_make_multi_select($field = '', $post_seo_meta = '') {
	echo '<select name="'.$field['name'].'" id="'.$field['name'].'_ID" multiple>';
	foreach ($field['options'] as $option) {
		echo '<option', $post_seo_meta == $option ? ' selected="selected"' : '', ' value="'.$option.'">'.$option.'</option>';
	}
	echo '</select><br /><span class="description">'.$field['desc'].'</span><br />';
}



function mnbaa_seo_make_metaTage($prop, $value, $content){
	echo '<meta '.$prop.'="'.$value.'" content="'.$content.'" />
';
}

//make link met tag
function mnbaa_seo_make_linkTage($value, $content){
	echo '<link rel='.$value.' herf="'.$content.'" />
';
}

function mnbaa_seo_make_link($value, $label){
	echo '<a href='.$value.' target="_blank"  style="text-decoration:none;"/>'.$label.'</a>';
}


function mnbaa_seo_make_div_contain_li($field = '', $post_seo_meta = '',$type){?>
	
	<!-- for pro version only -->
	
	<!-- <div class="li-heads">
		<span><?php  echo __('Key Word','mnbaa-seo')?></span>
		<span class="title-span"><?php  echo __('Title','mnbaa-seo')?></span>	
        <?php if($type=='post'){ ?>
			<span><?php  echo __('Content','mnbaa-seo')?></span> 
            <span><?php  echo __('Meta Values','mnbaa-seo')?></span>
        <?php } ?>    
		<span><?php  echo __('Description','mnbaa-seo')?></span>
		<span><?php  echo __('Premalink','mnbaa-seo')?></span>  
		<span><?php  echo __('Heading','mnbaa-seo')?></span>
	</div> -->
    <input type="hidden" value="<?php echo $type ?>" name="type" id="post_type" />
	<div id="nashr-autoCompleteArea">
		<ul class="token-input-list prescription-list">
			<?php 
			if(!empty($post_seo_meta)){
				if (strpos($post_seo_meta,',') !== false) {
				$key_words=explode(',', $post_seo_meta);
				foreach ($key_words as  $value) {?>
					
					<li class="nashr-input-token">
						<p class="title"><?php echo $value ?></p>
						<p class="content_count"></p>
						<span class="token-input-delete-token" onclick="delete_li(this)">×</span>
						<input type="hidden" name="<?php echo $field['name'] ;?>[]" value="<?php echo $value; ?>" />
					</li>
					
				<?php }
				 }
				else{?>
					<li class="nashr-input-token" >
						<p class="title"><?php echo $post_seo_meta; ?></p>
						<p class="content_count"></p>
						<span class="token-input-delete-token" onclick="delete_li(this)">×</span>
						<input type="hidden" name="<?php echo $field['name'] ;?>[]" value="<?php echo $post_seo_meta; ?>" />
					</li>
					
					
				<?php }
			} ?>
						
				<li class="token-input-token-hidden">
					<p class="title"></p>
					<p class="content_count"></p>
					<span class="token-input-delete-token">×</span>
					<input type="hidden" value="" > 
				</li>
	          		
		 </ul>
		<input type="text" name=""  value="" class="nashr-autoComplete"  />
    </div>
	<?php
}

function mnbaa_seo_make_snippet_term($field = '',$post_archive=''){
	
	global $prefix;
	$post_archive_meta_data = json_decode(get_option($prefix.$post_archive.'_archive'));
	$title= $post_archive_meta_data->mnbaa_seo_title;
	if($title=='' && $post_archive=='post') $title="archive";
	elseif($title=='' && $post_archive!='post') $title=$post_archive;
	$desc= $post_archive_meta_data->mnbaa_seo_description;
	$permalink_url=get_post_type_archive_link($post_archive);
	?>
	<div id="mnbaasnippet">
		<a class="title" id="mnbaasnippet_title" href="#"><?php 
			echo $title."-wordpress";
		
		?>
		</a>
		<span class="url"><?php echo $permalink_url;?></span>
		<p class="desc">
			<span class="seo_content"><?php 
				echo $desc;
			
				// ?>
			</span>
		</p>
	</div>
	
	<?php

}
function mnbaa_seo_make_snippet_loopPage($field = ''){
	global $wp_query;
	global $prefix;
	$index_page=get_option('show_on_front'); 
	$permalink_url = get_bloginfo('url');
	$title = get_option($prefix.'title');
	$desc=get_option($prefix.'description');
	if ($index_page=='posts') {
	     //if posts page is home page
		$permalink_url = get_bloginfo('url');
		$title = get_bloginfo('name');
		$desc=get_option($prefix.'description');
	} elseif ($index_page=='page') {
	
		$post_id=get_option('page_on_front');
		$title=get_the_title( $post_id );
		if($title=='') $title= get_post_meta( $post_id, 'mnbaa_seo_title',TRUE );
		$permalink_url = get_page_link($post_id);
		$desc= get_post_meta( $post_id, 'mnbaa_seo_description',TRUE );
	}
	?>
	<div id="mnbaasnippet">
		<a class="title" id="mnbaasnippet_title" href="#"><?php 
			echo $title."-wordpress";
		
		?>
		</a>
		<span class="url"><?php echo $permalink_url;?></span>
		<p class="desc">
			<span class="seo_content"><?php 
				echo $desc;
			
				// ?>
			</span>
		</p>
	</div>
	<!-- <span class="description"><?php echo $field['desc'] ;?></span><br /> -->
	<?php

}
function mnbaa_seo_make_snippet($field = '',$post_id,$type){
	global $prefix;
	?>
	<div id="mnbaasnippet">
		<a class="title" id="mnbaasnippet_title" href="#"><?php 
		if($type=='post'){
			 $seo_title= get_post_meta( $post_id, 'mnbaa_seo_title',TRUE );
			 echo ($seo_title) ? $seo_title :(get_the_title()) ? get_the_title() :"-wordpress" ;
		}elseif($type=='term'){
			
			$single_taxonomy_meta_data = get_option( $prefix.'taxonomy_'.$post_id.'_meta');
			$title=get_cat_name($post_id) ;
			if($title=='') $title = $single_taxonomy_meta_data[$prefix.'title'];
			echo $title."-wordpress";
		}
		?>
		</a>
		<span class="url"><?php if($type=='post') echo get_permalink(); elseif($type=='term'){  
		echo get_category_link($post_id); 
		}?></span>
		<p class="desc">
			<span class="seo_content"><?php 
			if($type=='post'){
			 	$seo_desc= mb_strcut(get_post_meta( $post_id, 'mnbaa_seo_description',TRUE ),0,150);
				//echo get_post_field('post_content',$post_id);
				$post_desc=mb_strcut(strip_tags(get_post_field('post_content',$post_id)), 0, 150);
				echo ($seo_desc)? $seo_desc:($post_desc);
			}elseif($type=='term'){
				$seo_desc = $single_taxonomy_meta_data[$prefix.'description'];	
				if($seo_desc=='') $seo_desc=mb_strcut(strip_tags(category_description($post_id)), 0, 150); 
				echo $seo_desc;
			}
				// ?>
			</span>
		</p>
	</div>
	<!-- <span class="description"><?php echo $field['desc'] ;?></span><br /> -->
	<?php
}

function mnbaa_seo_make_robot($field = '',$post_id,$type){
	global $prefix;
	?>
	<div id="">
    
		
	</div>
	<!-- <span class="description"><?php echo $field['desc'] ;?></span><br /> -->
	<?php
}
?>
