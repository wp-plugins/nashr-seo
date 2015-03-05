<?php
function mnbaa_seo_get_word_count(){
	$keyword= $_POST['keyword'];
	$type=$_POST['type'];
	$content=$_POST['content'];
	$post_archive=$_POST['option'];
	$pattern = "/<h1>(.*?)<\/h1>/";
	$countH1Word=0;
	preg_match_all($pattern,$content,$headings, PREG_SET_ORDER);
	foreach ($headings as $value) {
		$countH1Word +=substr_count(strtolower($value[0]), strtolower($keyword));
	 }
	
	$str="";
	if($type=='index'){
		$meta_values=array();
		$index_page=get_option('show_on_front'); 
		if ($index_page=='posts') {
			 //if posts page is home page
			$premalink = get_bloginfo('url');
			$title = get_bloginfo('name');
			$seo_desc=get_option($prefix.'description');
		} elseif ($index_page=='page') {
		
			$post_id=get_option('page_on_front');
			//$title= get_post_meta( $post_id, 'mnbaa_seo_title',TRUE );
			$title=get_the_title( $post_id );
			$premalink = get_page_link($post_id);
			$seo_desc= get_post_meta( $post_id, 'mnbaa_seo_description',TRUE );
		}
	}elseif($type=='archive'){
		$meta_values=array();
		global $prefix;
		$post_archive_meta_data = json_decode(get_option($prefix.$post_archive.'_archive'));
		$title= $post_archive_meta_data->mnbaa_seo_title;
		$seo_desc= $post_archive_meta_data->mnbaa_seo_description;
		$premalink=get_post_type_archive_link($post_archive);
	}elseif($type=='post' || $type=='not_post'){
		if($type=='not_post') $meta_values=array();
		$title=$_POST['title'];
		$id=$_POST['id'];
		$premalink=get_permalink($id);
		$meta_values = get_post_meta($id);
		$seo_desc=$_POST['seo_desc'];
	}
	$count_in_metavalue= 0;
	if(! empty($meta_values)){
		foreach ($meta_values as  $value) {
			
			if(in_array($keyword, $value)){
				$count ++;
			}
		}
	}
	$countTitleWord=substr_count(strtolower($title), strtolower($keyword));
	// echo $countTitleWord;
	$countContentWord=substr_count(strtolower($content), strtolower($keyword));
	$countDescWord=substr_count(strtolower($seo_desc),strtolower($keyword));
	$countPremaWord=substr_count(strtolower($premalink), strtolower($keyword));
	//
	//percent  ofexisting keyword
	$Keyword_density=0;
	($countTitleWord>0)?$Keyword_density+=20:$Keyword_density+=0;
	($countContentWord>0)?$Keyword_density+=20:$Keyword_density+=0;
	($countDescWord>0)?$Keyword_density+=15:$Keyword_density+=0;
	($countPremaWord>0)?$Keyword_density+=15:$Keyword_density+=0;
	($count_in_metavalue>0)?$Keyword_density+=15:$Keyword_density+=0;
	($countH1Word>0)?$Keyword_density+=15:$Keyword_density+=0;
	
	// response
	
	echo "<p>".$countTitleWord."</p>";
	if($type=="post") echo "<p>".$countContentWord."</p>";
	echo "<p>".$countDescWord."</p><p>".$countPremaWord."</p>";
	if($type=="post")echo "<p>".$count_in_metavalue."</p>";
	echo "<p>".$countH1Word."</p>".'#'.$Keyword_density;
	die();
}
?>