var $ = jQuery;
jQuery(document).ready(function($) {
	var prefix=$("#prefix").val();
	jQuery('#'+prefix+'archive').on("change",function(){
		var option=$('#'+prefix+'archive').val();
		
		jQuery.ajax({
			
			type : "post",
			url: ajax.ajaxurl,
			data: {		  
				action: "mnbaa_seo_get_archive_meta",
				 option : option
					
				},
				
				success: function(response){
				
				var snippet= response.split("^");
				$("#mnbaasnippet").html(snippet[0]);
				
					var res = snippet[1].split("*");
					if(res[0] !=0){
						var arr = JSON.parse(res[0]);
						$.each(arr, function(key, value) {
							$('#'+key+'_ID').val(value);
							if(key==prefix+'facebook_image'){
								$('#'+key+'_img').attr("src", res[1]);		
												
							}
							
							$('#'+key+'_ID').attr('checked', true) ;
							
							if(key==prefix+'keywords'){
								if(value!=''){
									//alert("yes");
									key_word=value.split(',');
									count=(key_word.length);
									var ele="";
										for (i = 0; i < count; i++) {
											ele +='<li class="nashr-input-token"><p class="title">'+key_word[i]+'</p><p class="content_count"></p><span class="token-input-delete-token" onclick="delete_li(this)">×</span><input type="hidden" name="" value="" /></li>';
										}
										$(".token-input-list").html(ele);
									}
							}
							});
							$(".nashr-input-token .title").each(function(){
								//alert("yes");
								this_li=$(this).closest('li');
								//get_words_count($(this).html(),this_li);
						    });
					}else{
						$('#'+prefix+'archive_form')[0].reset();
						$('#'+prefix+'archive').val(option);
						$('#mnbaa_seo_facebook_image'+'_img').attr("src", res[1]);
							
					}
				}
		});	
		
 });
	
});