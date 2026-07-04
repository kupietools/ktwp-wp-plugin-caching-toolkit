<?php
/*
 * Plugin Name:       KupieTools Caching Toolkit
 * Plugin URI:        https://michaelkupietz.com/
 * Description:       Functions for developers to implement Wordpress function caching for performance.
 * Version:           1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Michael Kupietz
 * Author URI:        https://michaelkupietz.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://michaelkupietz.com/kupietools-caching-toolkit/
 * Text Domain:       mk-plugin
 * Domain Path:       /languages
 */



// Register settings
    add_action('admin_init', function() {
        register_setting('ktwp_cache_options', 'ktwp_cache_save_post');
        register_setting('ktwp_cache_options', 'ktwp_cache_create_category');
        register_setting('ktwp_cache_options', 'ktwp_cache_edited_category');
        register_setting('ktwp_cache_options', 'ktwp_cache_delete_category');
        register_setting('ktwp_cache_options', 'ktwp_cache_create_post_tag');
        register_setting('ktwp_cache_options', 'ktwp_cache_edited_terms');
        register_setting('ktwp_cache_options', 'ktwp_cache_delete_term');
        register_setting('ktwp_cache_options', 'ktwp_cache_trash_post');
        register_setting('ktwp_cache_options', 'ktwp_cache_wp_ajax_edit-theme-plugin-file');
        register_setting('ktwp_cache_options', 'ktwp_cache_debug_logging');
    });
    


/* DISABLED, save for reference - caused dup menu.
// Add menu
add_action('admin_menu', function() {
    add_menu_page(
        'KupieTools', 
        'KupieTools',
        'manage_options',
        'kupietools',
        function() {
            echo '<div class="wrap"><h1>KupieTools</h1>';
            do_action('kupietools_sections');
            echo '</div>';
        },
        'dashicons-admin-tools'
    );
}); */

//Add to Kupietools admin menu
add_action('admin_menu', function () {
    global $menu;
    $exists = false;
    
    if ($menu) {
        foreach($menu as $item) {
            if (isset($item[0]) && $item[0] === 'KupieTools') {
                $exists = true;
                break;
            }
        }
    }
    
    if (!$exists) {
        add_menu_page(
            'KupieTools Settings',
            'KupieTools',
            'manage_options',
            'kupietools',
            function() {
                echo '<div class="wrap"><h1>KupieTools</h1>';
                do_action('kupietools_sections');
                echo '</div>';
            },
            'dashicons-admin-tools'
        );
    }
});



// Add THIS plugin's section
    add_action('kupietools_sections', function() {
        ?>
        <details class="card ktwpcache" style="max-width: 800px; padding: 20px; margin-top: 20px;" open="true">
            <summary style="font-weight:bold;">KTWP Cache Toolkit Settings</summary>
            <h3>Clear KTWP cache whenever the following Wordpress actions run:</h3>
            <form method="post" action="options.php">
                <?php
                settings_fields('ktwp_cache_options');
                ?>
                <div>
                    <p>
                        <label>
                            <input type="checkbox" name="ktwp_cache_save_post" value="1" 
                                <?php checked(get_option('ktwp_cache_save_post', '1'), '1'); ?>> 
                            <strong>Cache save_post</strong>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input type="checkbox" name="ktwp_cache_create_category" value="1"
                                <?php checked(get_option('ktwp_cache_create_category', '1'), '1'); ?>>
                            <strong>Cache create_category</strong>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input type="checkbox" name="ktwp_cache_edited_category" value="1"
                                <?php checked(get_option('ktwp_cache_edited_category', '1'), '1'); ?>>
                            <strong>Cache edited_category</strong>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input type="checkbox" name="ktwp_cache_delete_category" value="1"
                                <?php checked(get_option('ktwp_cache_delete_category', '1'), '1'); ?>>
                            <strong>Cache delete_category</strong>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input type="checkbox" name="ktwp_cache_create_post_tag" value="1"
                                <?php checked(get_option('ktwp_cache_create_post_tag', '1'), '1'); ?>>
                            <strong>Cache create_post_tag</strong>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input type="checkbox" name="ktwp_cache_edited_terms" value="1"
                                <?php checked(get_option('ktwp_cache_edited_terms', '1'), '1'); ?>>
                            <strong>Cache edited_terms</strong>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input type="checkbox" name="ktwp_cache_delete_term" value="1"
                                <?php checked(get_option('ktwp_cache_delete_term', '1'), '1'); ?>>
                            <strong>Cache delete_term</strong>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input type="checkbox" name="ktwp_cache_trash_post" value="1"
                                <?php checked(get_option('ktwp_cache_trash_post', '1'), '1'); ?>>
                            <strong>Cache trash_post</strong>
                        </label>
                    </p>
                    <p>
                        <label>
                            <input type="checkbox" name="ktwp_cache_wp_ajax_edit-theme-plugin-file" value="1"
                                <?php checked(get_option('ktwp_cache_wp_ajax_edit-theme-plugin-file', '0'), '1'); ?>>
                            <strong>Cache wp_ajax_edit-theme-plugin-file </strong> (defaults off; may cause performance issues while working on the back end, depending on your set up, as it recalculates things frequently while you&apos;re working on theme or plugin files. However, if you keep this off, a plugin like <a href="https://wordpress.org/plugins/transients-manager/" target="_blank">Transients Manager</a> is recommended, so you can manually clear the transient storage, where this plugin keeps cached values, after working on theme or plugin files.)
                        </label>
                    </p>
                </div>
				<h3>Write cache performance timing for getting and setting to debug log (when debug logging is on):</h3><p>
				Only works if KTWP Function Timer plugin is installed and WordPress debug logging is enabled. 
				</p><p>
				<b>WARNING!</b> Depending on how you use the caching functions, this can easily generate HUGE logfiles... many GB of logged data a day, even on a small, infrequently-visited site! Do <b>NOT</b> leave this running. Turn it on when you need it and then turn it off again as soon as you're done running the functions you want to measure the cache activity of. 
				</p>
	<div> <p>
                        <label>
                            <input type="checkbox" name="ktwp_cache_debug_logging" value="1" 
                                <?php checked(get_option('ktwp_cache_debug_logging', '0'), '1'); ?>> 
                            <strong>Log cache timing</strong>
                        </label>
                    </p>
		
				</div>
				
                <?php submit_button('Save Settings'); ?>
            </form>
        </details>
        <?php
    });
//}); 

$save_post = get_option('ktwp_cache_save_post','1');
$create_category = get_option('ktwp_cache_create_category','1');
$edited_category = get_option('ktwp_cache_edited_category','1');
$delete_category = get_option('ktwp_cache_delete_category','1');
$create_post_tag = get_option('ktwp_cache_create_post_tag','1');
$edited_terms = get_option('ktwp_cache_edited_terms','1');
$delete_term = get_option('ktwp_cache_delete_term','1'); 
$trash_post = get_option('ktwp_cache_trash_post','1');
$wp_ajax_edit_theme_plugin_file = get_option('ktwp_cache_wp_ajax_edit-theme-plugin-file','0');
$log_timing = get_option('ktwp_cache_debug_logging','0');

// Each will return '1' if checked, '0' if unchecked
 
// first, some useful functions

if ($save_post == '1') {add_action( 'save_post', 'setLastUpdateTimestamp' ); }
if ($create_category == '1') {add_action( 'create_category', 'setLastUpdateTimestamp' ); }
if ($edited_category == '1') {add_action( 'edited_category', 'setLastUpdateTimestamp' ); }
if ($delete_category == '1') {add_action( 'delete_category', 'setLastUpdateTimestamp' ); }
if ($create_post_tag == '1') {add_action( 'create_post_tag', 'setLastUpdateTimestamp', 10, 2 ); }
if ($edited_terms == '1') {add_action( 'edited_terms', 'setLastUpdateTimestamp', 10, 2 ); }
if ($delete_term == '1') {add_action( 'delete_term', 'setLastUpdateTimestamp', 10, 3 ); }
if ($trash_post == '1') {add_action( 'trash_post', 'setLastUpdateTimestamp' ); }
if ($wp_ajax_edit_theme_plugin_file == '1') {add_action('wp_ajax_edit-theme-plugin-file', 'setLastUpdateTimestamp',0 ); } //update theme or plugin file. We are developers, after all. Needs priority 0 to work per https://core.trac.wordpress.org/ticket/42840

function logCache($callback, $params = [], $variables = []){
	global $log_timing;
 if ( $log_timing && function_exists('ktwp_f_timer')) {
return ktwp_f_timer($callback, $params, $variables);
													 
} else 
 {return call_user_func_array($callback, $params);}
}


  if (!function_exists('setLastUpdateTimestamp')) {
function setLastUpdateTimestamp() {

/* disabling these causes more frequent updates than necessary while I'm actively poking around the back end of the site, but that's no tragedy.
if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    // If this is a revision, don't do anything.
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    
    
*/

wp_cache_flush_group('ktwp_cache');

$theNow=microtime(true); 
//echo ktwp_comment ("setting last overall update to ".$theNow);

logCache('wp_cache_set', ['mkFuncTransient_LastUpdate_all',$theNow,'ktwp_cache',2592000],["function"=>__FUNCTION__]);
// WAS wp_cache_set( 'mkFuncTransient_LastUpdate_all',$theNow,'ktwp_cache',2592000); //shouldn't need now that flushing cache, but, some caches don't support wp_cache_flush_group
}
  }

  if (!function_exists('latestPostUpdate')) {
function latestPostUpdate() {	
	
	
	$theDate =  logCache('wp_cache_get', [ 'mkFuncTransient_LastUpdate_all','ktwp_cache'],["function"=>__FUNCTION__]);
	
 //WAS   $theDate = wp_cache_get( 'mkFuncTransient_LastUpdate_all','ktwp_cache'); 
//echo ktwp_comment("last overall update was ".    $theDate);
	
if ( (! isset($theDate)) || (! $theDate > 0)) {
	
    $theDate=microtime(true);
	logCache('wp_cache_set', ['mkFuncTransient_LastUpdate_all',$theDate,'ktwp_cache',2592000],["function"=>__FUNCTION__]);
	
//WAS wp_cache_set( 'mkFuncTransient_LastUpdate_all',$theDate,'ktwp_cache',2592000);  //dont expire for 1 month }
//echo ktwp_comment ("last overall update wasn't set, setting to ".$theDate);
    }

//echo ktwp_comment ("returning last overall update as ".$theDate);
		
return $theDate; 
}
  }


  if (!function_exists('hashArguments')) {
function hashArguments($arguments = []) {
 
    return sha1(serialize($arguments));
}

  }

if (!function_exists('getFunctionTransient')) {
function getFunctionTransient($functionName, $arguments=[], $manualClearOnly=false /* return present cached version even if site has been updated since it was stored */,$funcID="" ) {
	
	/* this was check for admin screens, not logged in as admin  - but,
	 you know what, I don't know why I have it. Why recalculate everything on admin screens if a page is called in the background? If I can think of some reason I had this maybe add a settings screen checkbox to turn it on or off so I can test it. I think it's not needed, though. (UPDATE, 2026jul3... meh.... some things, like my default thumbnails, must only render on the front end, but Yoast's indexing processes content on page save while still in admin, and htmlmap was getting cached with missing thumbnails because of it! So, still don't actually need this in getFunctionTransient, but re-added this check below, where it belongs, in setFunctionTransient, so transients don't get cached while in admin mode. )
    if (is_admin()) { 
        return null;
    }*/
    
    $hashArgs = hashArguments($arguments);
	
	$transient = logCache('wp_cache_get', ['mkFuncTransient' . $functionName . $hashArgs,'ktwp_cache'],["function"=>$functionName,"arguments"=>$arguments,"manualClearOnly"=>$manualClearOnly,"funcID"=>$funcID]);
   //WAS  $transient = wp_cache_get('mkFuncTransient' . $functionName . $hashArgs,'ktwp_cache');
    
    if ($transient !== false) {
        if (!is_array($transient) || !isset($transient['data']) || !isset($transient['lastupdate'])) {
            return null;
        }
        
        $lastPostUpdate = latestPostUpdate();
        if ($transient['lastupdate'] > $lastPostUpdate || $manualClearOnly == true) {
            return $transient['data'];
        }
    }
    return null;
}
}

if (!function_exists('setFunctionTransient')) {
function setFunctionTransient($functionName, $value = null, $arguments=[],$funcID="") {
    if ((defined('WP_ADMIN') && isset($_POST['action']) && $_POST['action'] === 'edit-theme-plugin-file')) {
        return; /* don't overwrite transients while saving theme files */
    }
    if (isset($_POST['action']) && $_POST['action'] === 'edit-theme-plugin-file') {
        return;/* don't overwrite transients while saving plugin files */
    }
	if (is_admin()) { return $value; /* 2026jul3 - decided to never cache while in admin area. Yoast processes content on save, and the htmlmap shortcode was rendering, but the default thumbnail has to not run on admin screens or it fucks with the post editing, so the htmlmap in the blog etc. pages were getting cached without the thumbnails. Probably no reason to be caching things outside of users loading pages, anyway. */}
    
    $hashArgs = hashArguments($arguments);
    
    if ($value === null) {
        delete_transient('mkFuncTransient' . $functionName . $hashArgs);
        return $value;
    }
    
    $transient = [
        'data' => $value,
        'lastupdate' => microtime(true)
    ];
    logCache('wp_cache_set', ['mkFuncTransient' . $functionName . $hashArgs, $transient, 'ktwp_cache', 2592000],["function"=>$functionName,"value"=>$value,"arguments"=>$arguments,"funcID"=>$funcID]);
// WAS    wp_cache_set('mkFuncTransient' . $functionName . $hashArgs, $transient, 'ktwp_cache', 2592000);
    
    return $value;
}
}
 // end useful functions


/* SAVE FOR REFERENCE: automatically cache shortcodes... this doesn't work with plugins like photonic which queue up js files to postprocess the shortcode output. That isn't captured in the output of the shortcode, and serving the shortcode output from cache doesn't engueue the js scripts. 
 
add_filter( 'pre_do_shortcode_tag', 'pritect_whitelist_shortcodes', 10, 4 );
add_filter('do_shortcode_tag', 'cache_shortcode_output', 10, 4);

function pritect_whitelist_shortcodes( $out, $tag, $atts = array(), $m = array() ) {
	
$startcode=$tag;
	
	$childcode="";
  foreach ($atts as $key=>$value) {
	  $childcode.= " ".$key."=\"".addslashes($value)."\"";
//	   $childcode.= " ".$key."=\"".($value)."\"";
  }
 $childcode= "[".$startcode.$childcode."]";

$data = getFunctionTransient("mk_cachecode_",	$childcode); if ( $data != null) {
	echo "<!-- NEW START TRANSIENT id='' desc='do_cachecode transient "."mk_cachecode_"." code for atts ".addslashes($childcode) .", data is ".htmlspecialchars($data)." END ORIGINAL -->"; 
	return $data;}
else {return false;}
}

function cache_shortcode_output($out, $tag, $atts = array(), $m = array() ) {
    // Create a unique transient name based on shortcode and attributes
    
$startcode=$tag;
	
	$childcode="";
  foreach ($atts as $key=>$value) {
	  $childcode.= " ".$key."=\"".addslashes($value)."\"";
//	   $childcode.= " ".$key."=\"".($value)."\"";
  }
 $childcode= "[".$startcode.$childcode."]";

setFunctionTransient("mk_cachecode_", $out ,$childcode);
    echo "<!-- NEW START ORIGINAL  id=''  desc='do_cachecode original code for atts ". addslashes($childcode)."' is ".htmlspecialchars($out)."  END ORIGINAL -->";
    return $out;
}

END AUTOMATIC SHORTCODE CACHING
*/


?>