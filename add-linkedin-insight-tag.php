<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*
Plugin Name: Add Linkedin Insight Tag
Plugin URI: https://github.com/techcyclist/add-linkedin-insight-tag
Description: This adds the Linkedin Insight tag right above the </body> tag.
Version: 1.0
Author: techcyclist
Author URI: https://github.com/techcyclist
License: GPL2
*/

if(!class_exists('add_linkedin_insight_tag'))
{
    class add_linkedin_insight_tag
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            add_action( 'admin_menu', 'alit_add_admin_menu' );
            add_action( 'admin_init', 'alit_settings_init' );
        } // END public function __construct

        /**
         * Activate the plugin
         */
        public static function activate()
        {
            // Do nothing
        } // END public static function activate

        /**
         * Deactivate the plugin
         */
        public static function deactivate()
        {
            // Do nothing
        } // END public static function deactivate
    } // END class WP_Plugin_Template
} // END if(!class_exists('WP_Plugin_Template'))

if(class_exists('add_linkedin_insight_tag'))
{
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('add_linkedin_insight_tag', 'activate'));
    register_deactivation_hook(__FILE__, array('add_linkedin_insight_tag', 'deactivate'));

    // instantiate the plugin class
    $add_linkedin_insight_tag = new add_linkedin_insight_tag();
}





function alit_add_admin_menu(  ) {

    add_options_page( 'Add Linkedin Insight Tag', 'Add Linkedin Insight Tag', 'manage_options', 'add_linkedin_insight_tag', 'alit_options_page' );

}


function alit_settings_init(  ) {

    register_setting( 'pluginPage', 'alit_settings' );

    add_settings_section(
        'alit_pluginPage_section',
        __( 'Settings', 'wordpress' ),
        'alit_settings_section_callback',
        'pluginPage'
    );

    add_settings_field(
        'alit_linkedin_insight_tag',
        __( 'Your _linkedin_partner_id', 'wordpress' ),
        'alit_linkedin_insight_tag_render',
        'pluginPage',
        'alit_pluginPage_section'
    );


}


function alit_linkedin_insight_tag_render(  ) {

    $options = get_option( 'alit_settings' );
    ?>
    <input type='text' name='alit_settings[alit_linkedin_insight_tag]' value='<?php echo $options['alit_linkedin_insight_tag']; ?>'>
    <?php

}


function alit_settings_section_callback(  ) {

    echo __( 'STEP 1: Generate Your Insight Code<br /> STEP 2: Enter your  _linkedin_partner_id in the field below.<br />This is a number you can find in the tag code Linkedin provides you. Example "123456"', 'wordpress' );

}


function alit_options_page(  ) {

    ?>
    <form action='options.php' method='post'>

        <h2>Add Linkedin Insight Tag</h2>

        <?php
        settings_fields( 'pluginPage' );
        do_settings_sections( 'pluginPage' );
        submit_button();
        ?>

    </form>
    <?php

}

function alit_linkedin_insight_tag_code() {
    $options = get_option( 'alit_settings' );
    if (isset($options['alit_linkedin_insight_tag'])){
        echo '<script type="text/javascript">';
        echo '_linkedin_partner_id = "'.$options['alit_linkedin_insight_tag'].'";';
        echo 'window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];';
        echo 'window._linkedin_data_partner_ids.push(_linkedin_partner_id);';
        echo '</script><script type="text/javascript">';
        echo '(function(){var s = document.getElementsByTagName("script")[0];';
        echo 'var b = document.createElement("script");';
        echo 'b.type = "text/javascript";b.async = true;';
        echo 'b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";';
        echo 's.parentNode.insertBefore(b, s);})();';
        echo '</script>';
        echo '<noscript>';
        echo '<img height="1" width="1" style="display:none;" alt="" src="https://dc.ads.linkedin.com/collect/?pid='.$options['alit_linkedin_insight_tag'].'&fmt=gif" />';
        echo '</noscript>;';
    }
}
add_action('wp_footer', 'alit_linkedin_insight_tag_code');
