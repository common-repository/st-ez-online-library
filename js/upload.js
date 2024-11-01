
	
jQuery(document).ready(function($) { 
		$('#upload_thumbnail_button').click(function() { 
		tb_show('Upload a Thumbnail', 'media-upload.php?referer=st_ez_online_library&type=image&TB_iframe=true&post_id=0', false); 
		window.send_to_editor = function(html) { 
		var image_url = $('img',html).attr('src'); 
		$('#thumbnail_url').val(image_url); 
		tb_remove(); 
		$('#upload_thumbnail_preview img').attr('src',image_url); 
		//$('#submit_options_form').trigger('click');
		};
		return false;
		}); 
}); 
jQuery(document).ready(function($) { 
		$('#upload_file_button').click(function() { 
		tb_show('Upload a File', 'media-upload.php?referer=st_ez_online_library&type=file&TB_iframe=true&post_id=0', false); 
		window.send_to_editor = function(html) { 
		var pdf_url = $(html).attr('href');                 
		$('#file_url').val(pdf_url);
		tb_remove();                
		//$('#submit_options_form').trigger('click');
		};
		return false;
		}); 
});	
