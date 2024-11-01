<?php
define('ST_EZ_ONLINE_LIBRARY_URL', plugins_url('', __FILE__));

function st_ez_online_library_get_default_options() {
    $options = array(
        'thumbnail' => '',
        'file' => ''
    );
    return $options;
}

function st_ez_online_library_options_init() {
    $st_ez_online_library_options = get_option('st_ez_online_library_options');

    // Are our options saved in the DB?
    if (false === $st_ez_online_library_options) {
        // If not, we'll save our default options
        $st_ez_online_library_options = st_ez_online_library_get_default_options();
        add_option('st_ez_online_library_options', $st_ez_online_library_options);
    }

    // In other case we don't need to update the DB
}

// Initialize Theme options
add_action('init', 'st_ez_online_library_options_init');

function st_ez_online_library_admin_menu() {
    add_menu_page('EZ Online Library', 'EZ Online Library', 'manage_options', 'st_ez_online_library', 'st_ez_online_library_option_page', plugins_url('st_ez_online_library/images/icon.png'));
}

add_action('admin_menu', 'st_ez_online_library_admin_menu');

function st_ez_online_library_option_page() {
    global $wpdb;
    global $table_suffix;

    $table_suffix = "st_ez_online_library";

    $table_name = $wpdb->prefix . $table_suffix;
    ?>

    <div class="wrap">
        <?php
        
        if (isset($_REQUEST['Delete'])) {
			
            if(isset($_REQUEST['checkbox']))
			{
				$i=0;
                     
				foreach($_REQUEST['checkbox']  as $chkid)
				{
					echo $chkid;
					$wpdb->query("DELETE FROM $table_name WHERE st_ez_online_library_id = " . $chkid ."");                                        
					$i++;
				}
				echo "<div id=\"message\" class=\"updated fade\"><p><strong>$i Docs(s) Deleted Successfully!</strong></p></div>";
			}
        }
        
        if (isset($_REQUEST['file_upload'])) {
			
            $file_name = $_REQUEST['st_ez_online_library_options'];
            
            if($file_name['thumbnail'] == NULL){
                $file_name['thumbnail'] = ST_EZ_ONLINE_LIBRARY_URL . "/images/pdf_logo.jpg";
            }
            $wpdb->insert($table_name, array('file_path' => $file_name['file'], 'thumbnail_path' => $file_name['thumbnail']), array('%s', '%s'));
            $options = array(
                'thumbnail' => '',
                'file' => ''
            );
            add_option('st_ez_online_library_options', $options);
        }
        ?>

        <div id="icon-themes" class="icon32"><br /></div>

        <h2>EZ Online Library</h2>
        <div class="postbox-container" style="width:70%;padding-right:25px;">
            <div class="metabox-holder">
                <div class="meta-box-sortables">
                    <!-- If we have any error by submiting the form, they will appear here -->
                    <?php settings_errors('st_ez_online_library-settings-errors'); ?>

                    <form id="file_upload" action="<?php echo $_SERVER['PHP_SELF'] . "?page=st_ez_online_library"; ?>" method="post" enctype="multipart/form-data">

                        <?php
                        settings_fields('st_ez_online_library_options');
                        do_settings_sections('ST_EZ_ONLINE_LIBRARY');
                        ?>                                

                        <p class="submit">
						    <input type="hidden" name="file_upload" id="file_upload" value="true" />
                            <input name="st_ez_online_library_options[submit]" id="submit_options_form" type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'ST_EZ_ONLINE_LIBRARY'); ?>" />
                        </p>

                    </form>

                </div>
            </div>

            <div id="toc" class="postbox">
                <div class="handlediv" title="Click to toggle"><br /></div>
                <h3 class="hndle"><span>Files List</span></h3>
                <div class="inside">
                    <script type="text/javascript" charset="utf-8">
                        $(document).ready(function() {
                            $('#display_data').dataTable( {
                                "aaSorting": [[ 1, "desc" ]]
                            } );
                        } );
                    </script>
                    <?php
                    $table_result = $wpdb->get_results("Select * FROM $table_name ORDER BY st_ez_online_library_id DESC");
                    echo "<form id=\"myform\" action=\"" . $_SERVER["PHP_SELF"] . "?page=st_ez_online_library\" method=\"post\">";
                    echo "<div class=\"dataTables_wrapper\" role=\"grid\">";
                    echo "<table class=\"display\" id=\"display_data\" style=\"width:100%;\" >";
                    echo "<thead><tr><th><input type='checkbox' name='checkall' onclick='checkedAll();'> Select All </th><th>Id</th><th>File Name</th><th>Thumbnail</th></tr></thead>";
                    echo "<tbody>";
                    
                    echo "<input type=\"submit\" name=\"Delete\" value=\"Delete\" id=\"btnsubmit\" class=\"button\" />";
                    
                    foreach ($table_result as $table_row) {
                        echo "<tr>";
                        echo "<input type=\"hidden\" name=\"st_ez_online_library_id\" value=\"" . $table_row->st_ez_online_library_id . "\" />";
                        echo "<td><input type='checkbox' name='checkbox[]' value='" . $table_row->st_ez_online_library_id . "'></input></td>";
                        echo "<td>" . $table_row->st_ez_online_library_id . "</td>";
                        $file_name = explode("/", $table_row->file_path);                        
                        echo "<td>" . $file_name[8] . "</td>";
                        echo "<td><img src='" . $table_row->thumbnail_path . "' width='50' height='50'/></td>";
                    }

                    echo "</table>";
                    echo "<div style=\"clear:both;\"></div>";
                    echo "</div>";
                    echo "</form>"
                    ?>
                </div>
            </div>

        </div>
        <div class="postbox-container side" style="width:20%;">
            <div class="metabox-holder">
                <div class="meta-box-sortables">
                    <div id="toc" class="postbox">
                        <div class="handlediv" title="Click to toggle"><br /></div>
                        <h3 class="hndle"><span>How to Use</span></h3>
                        <div class="inside">
							<ol>
								<li><strong>Upload Document</strong><br/>
								Upload pdf, doc, or any type of file.<br/>
								Upload a thumbnail for display purpose<br/>
								</li>
								<li><strong>Use Short Code [ez_online_library]</strong><br/>
								Use shortcode [ez_online_library] on the page/ post where you want to display the Library</li>
                        </div>
                    </div>
                    <div id="toc" class="postbox">
                        <div class="handlediv" title="Click to toggle"><br /></div>
                        <h3 class="hndle"><span>Show your Support</span></h3>
                        <div class="inside">
                            <p>
                                <strong>Want to help make this plugin even better? All donations are used to improve this plugin, so donate $20, $50 or $100 now!</strong>
                            </p>
                            <a href="http://sanskrutitech.in/wordpress-plugins/st-ez-online-library/">Donate</a>
                            <p>Or you could:</p>
                            <ul>
                                <li><a href="#">Rate the plugin 5 star on WordPress.org</a></li>
                                <li><a href="#">Help out other users in the forums</a></li>
                                <li>Blog about it &amp; link to the <a href="http://sanskrutitech.in/wordpress-plugins/st-ez-online-library/">plugin page</a></li>				
                            </ul>
                        </div>
                    </div>
                    <div id="toc" class="postbox">
                        <div class="handlediv" title="Click to toggle"><br /></div>
                        <h3 class="hndle"><span>Connect With Us </span></h3>
                        <div class="inside">
                            <a class="facebook" href="https://www.facebook.com/sanskrutitech"></a>
                            <a class="twitter" href="https://twitter.com/#!/sanskrutitech"></a>
                            <a class="googleplus" href="https://plus.google.com/107541175744077337034/posts"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">-->
            <!--
    <?php //if (version_compare($wp_version, '2.7alpha', '<')) {      ?>
        jQuery('.postbox h3').prepend('<a class="togbox"></a> ');
    <?php // }      ?>
        jQuery('.postbox h3').click( function() { jQuery(jQuery(this).parent().get(0)).toggleClass('closed'); } );
        jQuery('.postbox .handlediv').click( function() { jQuery(jQuery(this).parent().get(0)).toggleClass('closed'); } );
        jQuery('.postbox.close-me').each(function() {
            jQuery(this).addClass("closed");
        });
        //-->
        <!--        </script>  
    </div>
    <?php
}

function st_ez_online_library_options_settings_init() {
    register_setting('st_ez_online_library_options', 'st_ez_online_library_options', 'st_ez_online_library_options_validate');

    // Add a form section for Uploading Document
    add_settings_section('st_ez_online_library_settings_header', __('Upload Document', 'ST_EZ_ONLINE_LIBRARY'), 'st_ez_online_library_settings_header_text', 'ST_EZ_ONLINE_LIBRARY');

	add_settings_field('st_ez_online_library_setting_file', __('File', 'ST_EZ_ONLINE_LIBRARY'), 'st_ez_online_library_setting_file', 'ST_EZ_ONLINE_LIBRARY', 'st_ez_online_library_settings_header');
	
    // Add Thumbnail uploader
    add_settings_field('st_ez_online_library_setting_thumbnail', __('Thumbnail', 'ST_EZ_ONLINE_LIBRARY'), 'st_ez_online_library_setting_thumbnail', 'ST_EZ_ONLINE_LIBRARY', 'st_ez_online_library_settings_header');

    // Add Current Image Preview
    add_settings_field('st_ez_online_library_setting_thumbnail_preview', __('Preview', 'ST_EZ_ONLINE_LIBRARY'), 'st_ez_online_library_setting_thumbnail_preview', 'ST_EZ_ONLINE_LIBRARY', 'st_ez_online_library_settings_header');

    
}

add_action('admin_init', 'st_ez_online_library_options_settings_init');

function st_ez_online_library_settings_header_text() {
    ?>
    <p><?php _e('Upload Document for Online Library.', 'ST_EZ_ONLINE_LIBRARY'); ?></p>
    <?php
}
function st_ez_online_library_setting_file() {
    $st_ez_online_library_options = get_option('st_ez_online_library_options');
    ?>
    <input type="text" id="file_url" name="st_ez_online_library_options[file]" value="<?php echo esc_url($st_ez_online_library_options['file']); ?>" />
    <input id="upload_file_button" type="button" class="button" name="upload_file" value="<?php _e('Upload file', 'ST_EZ_ONLINE_LIBRARY'); ?>" />
    <span class="description"><?php _e('Upload a file that you want your readers to download.', 'ST_EZ_ONLINE_LIBRARY'); ?></span><br>

    <?php
}

function st_ez_online_library_setting_thumbnail() {
    $st_ez_online_library_options = get_option('st_ez_online_library_options');
    ?>
    <input type="text" id="thumbnail_url" name="st_ez_online_library_options[thumbnail]" value="<?php echo esc_url($st_ez_online_library_options['thumbnail']); ?>" />
    <input id="upload_thumbnail_button" type="button" class="button" name="upload_thumbnail" value="<?php _e('Upload Thumbnail', 'ST_EZ_ONLINE_LIBRARY'); ?>" />
    <span class="description"><?php _e('Upload an image for the thumbnail.', 'ST_EZ_ONLINE_LIBRARY'); ?></span><br>    
    <?php
}

function st_ez_online_library_setting_thumbnail_preview() {
    $st_ez_online_library_options = get_option('st_ez_online_library_options');
    ?>
    <div id="upload_thumbnail_preview" style="min-height: 100px;">
        <img style="max-width:100%;" src="<?php echo esc_url($st_ez_online_library_options['thumbnail']); ?>" />
    </div>
    <?php
}


function st_ez_online_library_options_validate($input) {
    $default_options = ST_EZ_ONLINE_LIBRARY_get_default_options();
    $valid_input = $default_options;

    $submit = !empty($input['submit']) ? true : false;
    $reset = !empty($input['reset']) ? true : false;

    if ($submit) {
        $valid_input['thumbnail'] = $input['thumbnail'];
        $valid_input['file'] = $input['file'];
    } elseif ($reset) {
        $valid_input['thumbnail'] = $default_options['thumbnail'];
        $valid_input['file'] = $default_options['file'];
    }
    return $valid_input;
}

function ST_EZ_ONLINE_LIBRARY_options_enqueue_scripts() {
    wp_register_script('st_ez_online_library-upload', ST_EZ_ONLINE_LIBRARY_URL . '/js/upload.js', array('jquery', 'media-upload', 'thickbox'));

    wp_enqueue_script('jquery');

    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');

    wp_enqueue_script('media-upload');
    wp_enqueue_script('st_ez_online_library-upload');
}

add_action('admin_enqueue_scripts', 'ST_EZ_ONLINE_LIBRARY_options_enqueue_scripts');


?>