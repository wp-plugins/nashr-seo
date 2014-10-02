<?php
//==================================================== FILES Functions ====================================================\\
function unserializeFile($file) {
	if (file_exists($file)) {
		$fh = fopen($file, 'r');
		$fileContent = fread($fh, filesize($file));
		fclose($fh);
		return unserialize($fileContent);
	} else {
		return false;
	}
}

function serializeFile($file, $data) {
	$fh = fopen($file, 'w');
	if (fwrite($fh, serialize($data))) {
		fclose($fh);
		return true;
	} else {
		return false;
	}

}

//==================================================== Meta forms  Functions ====================================================\\

function redirect_to($location = NULL) {
	if ($location != NULL) {
		@header("Location: {$location}");
		echo("<script>location.href = '{$location}';</script>");
		exit ;

	}
}

function make_label($text = '', $for = '') {
	echo '<label for="'.$for.'_ID">'.$text.'</label>';
}

function make_input($field, $value = '') {
	echo '<input type="text" name="'.$field['name'].'" id="'.$field['name'].'_ID" value="'.$value.'" size="40" />
					<br /><span class="description">'.$field['desc'].'</span><br />';
}

function make_textarea($field, $value = '') {
	echo '<textarea name="'.$field['name'].'" id="'.$field['name'].'_ID" cols="60" rows="4">'.$value.'</textarea>
					<br /><span class="description">'.$field['desc'].'</span><br />';
}

function make_img_input($field, $post_seo_meta = '') {
	$og_image = plugins_url('mnbaa_seo/images','').'/noimage.jpg';
	if ($post_seo_meta) { $og_image = wp_get_attachment_image_src($post_seo_meta, 'medium'); $og_image = $og_image[0];}
	echo '<input name="'.$field['name'].'" type="hidden" class="custom_upload_image" value="'.$post_seo_meta.'" />
		<img src="'.$og_image.'" class="custom_preview_image" alt="" /><br />
		<input class="custom_upload_image_button button" type="button" value="'.__('Choose Image','mnbaa-seo').'" />
		<small> <a href="#" class="custom_clear_image_button">'.__('Remove Image','mnbaa-seo').'</a></small><br clear="all" />
		<span class="description">'.$field['desc'].'</span><br />';
}

function make_select($field = '', $post_seo_meta = '') {
	echo '<select name="'.$field['name'].'" id="'.$field['name'].'_ID">';
	foreach ($field['options'] as $option) {
		echo '<option', $post_seo_meta == $option ? ' selected="selected"' : '', ' value="'.$option.'">'.$option.'</option>';
	}
	echo '</select><br /><span class="description">'.$field['desc'].'</span><br />';
}
// function to make multi select control
function make_multi_select($field = '', $post_seo_meta = '') {
	echo '<select name="'.$field['name'].'" id="'.$field['name'].'_ID" multiple>';
	foreach ($field['options'] as $option) {
		echo '<option', $post_seo_meta == $option ? ' selected="selected"' : '', ' value="'.$option.'">'.$option.'</option>';
	}
	echo '</select><br /><span class="description">'.$field['desc'].'</span><br />';
}



function make_metaTage($prop, $value, $content){
	echo '<meta '.$prop.'="'.$value.'" content="'.$content.'" />
';
}

//make link met tag
function make_linkTage($value, $content){
	echo '<link rel='.$value.' herf="'.$content.'" />
';
}

function make_link($value, $label){
	echo '<a href='.$value.' target="_blank"  style="text-decoration:none;"/>'.$label.'</a>';
}

////////////////////////////// activation hook ////////////////////
function Mnbaa_seo_activate()
{
    $screen_options = 'mnbaa_seo_screens';
    $post_types = get_post_types( array('public'=>'ture'),'names' );
    $screen_value='[';
    foreach ($post_types as $post_type) {
        $screen_value=$screen_value.'"'.$post_type.'",';
    }
    $screen_value=substr($screen_value, 0, -1);
    $screen_value=$screen_value.']';
    if ( get_option( $screen_options ) !== false ) {
                // The option already exists, so we just update it.
                update_option( $screen_options, $screen_value );
            } else {
                // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
               
                add_option( $screen_options, $screen_value );
            }
} 
?>