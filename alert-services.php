<?php

/**
 * Plugin Name: Alert Services Tooltip
 * Plugin URI: https://cloudxyz.github.io/
 * Description: This is a small plugin for show random tooltip alerts of services
 * Version: 1.0.0
 * Author: Devalan
 * Author URI: https://cloudxyz.github.io/
 **/

/**
 * Shortcut constant to the path of this file.
 */
define('ALERT_SERVICES_FOLDER', plugin_dir_path(__FILE__));

/**
 * Version of the plugin.
 */
define('ALERT_SERVICES_VERSION', '1.0.0');

class AlertServicesSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Alert Services',
            'Alert Services Tooltip',
            'manage_options',
            'as-conf-services',
            array($this, 'create_admin_page')
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option('as_field');
?>
        <div class="wrap">
            <h1>Alert Services Tooltip</h1>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields('as_group');
                do_settings_sections('as-conf-services');
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'as_group', // Option group
            'as_field', // Option name
            array($this, 'sanitize') // Sanitize
        );

        add_settings_section(
            'as_setting_section', // ID
            'Settings', // Title
            array($this, 'print_section_introduction'), // Callback
            'as-conf-services' // Page
        );

        add_settings_field(
            'title',
            'Title',
            array($this, 'title_callback'),
            'as-conf-services',
            'as_setting_section'
        );

        add_settings_field(
            'bg_color', // ID
            'Background Color', // Title 
            array($this, 'bg_color_callback'), // Callback
            'as-conf-services', // Page
            'as_setting_section' // Section           
        );

        add_settings_field(
            'font_color', // ID
            'Font Color', // Title 
            array($this, 'font_color_callback'), // Callback
            'as-conf-services', // Page
            'as_setting_section' // Section           
        );

        add_settings_field(
            'delay', // ID
            'Delay Transition (MS)', // Title 
            array($this, 'delay_callback'), // Callback
            'as-conf-services', // Page
            'as_setting_section' // Section           
        );

        add_settings_section(
            'as_setting_services_section', // ID
            'Services', // Title
            array($this, 'print_section_list_services'), // Callback
            'as-conf-services' // Page
        );

        add_settings_field(
            'services', // ID
            'Services', // Title 
            array($this, 'services_callback'), // Callback
            'as-conf-services', // Page
            'as_setting_services_section' // Section           
        );

        add_settings_section(
            'setting_section_shortcode', // ID
            'How Display in website', // Title
            array($this, 'print_section_shortcode'), // Callback
            'as-conf-services' // Page
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        $new_input = array();
        if (isset($input['title']))
            $new_input['title'] = sanitize_text_field($input['title']);

        if (isset($input['bg_color']))
            $new_input['bg_color'] = sanitize_text_field($input['bg_color']);

        if (isset($input['font_color']))
            $new_input['font_color'] = sanitize_text_field($input['font_color']);

        if (isset($input['delay']))
            $new_input['delay'] = sanitize_text_field($input['delay']);

        if (isset($input['services']))
            $new_input['services'] = sanitize_text_field($input['services']);

        return $new_input;
    }

    /** 
     * Section introduction
     */
    public function print_section_introduction()
    {
        print 'Enter your settings below';
    }

    /** 
     * Field title
     */
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="as_field[title]" value="%s" />',
            isset($this->options['title']) ? esc_attr($this->options['title']) : ''
        );
    }

    /** 
     * Field bg_color
     */
    public function bg_color_callback()
    {
        printf(
            '<input type="text" id="bg_color" name="as_field[bg_color]" value="%s" placeholder="#fff"/>',
            isset($this->options['bg_color']) ? esc_attr($this->options['bg_color']) : ''
        );
    }

    /** 
     * Field font_color
     */
    public function font_color_callback()
    {
        printf(
            '<input type="text" id="font_color" name="as_field[font_color]" value="%s" placeholder="#fff"/>',
            isset($this->options['font_color']) ? esc_attr($this->options['font_color']) : ''
        );
    }

    /** 
     * Section List Services
     */
    public function print_section_list_services()
    {
        print 'Fill the fields with the random text of services you offer separated with comma <code>,</code><br/> Example: <code>Item 1, Item 3, Item 3</code>';
    }

    /** 
     * Field services
     */
    public function services_callback()
    {
        printf(
            '<textarea type="text" id="services" name="as_field[services]" style="min-width: 350px; height: 150px"/>%s</textarea>',
            isset($this->options['services']) ? esc_attr($this->options['services']) : ''
        );
    }

    /** 
     * Field delay
     */
    public function delay_callback()
    {
        printf(
            '<input type="text" id="delay" name="as_field[delay]" value="%s" placeholder="5000"/>',
            isset($this->options['delay']) ? esc_attr($this->options['delay']) : ''
        );
    }

    /** 
     * Section legend
     */
    public function print_section_shortcode()
    {
        print 'For use paste this shortcode in the page where you want to show alert, or in footer for show in all site: <code>[alert-services]</code>';
    }
}

if (is_admin())
    new AlertServicesSettingsPage();

add_shortcode("alert-services", function () {
    $as_options = get_option('as_field');
    $as_title      = $as_options['title'];
    $as_bg_color   = $as_options['bg_color'];
    $as_font_color = $as_options['font_color'];
    $as_services   = $as_options['services'];
    $as_delay      = $as_options['delay'];
    wp_enqueue_style('alert-styles');
    return '
        <svg display="none">
            <defs>
                <g id="icon-bell">
                    <path xmlns="http://www.w3.org/2000/svg" fill="' . $as_font_color . '" d="M22.41,16.77,21,14.44a5.71,5.71,0,0,1-.81-2.92V8.2a8.2,8.2,0,0,0-16.4,0v3.32A5.71,5.71,0,0,1,3,14.44l-1.4,2.33a2.27,2.27,0,0,0,2,3.44H8.93a2.74,2.74,0,0,0-.09.63,3.16,3.16,0,1,0,6.32,0,2.74,2.74,0,0,0-.09-.63h5.39a2.27,2.27,0,0,0,1.95-3.44Zm-8.52,4.07a1.89,1.89,0,1,1-3.66-.63h3.54A1.9,1.9,0,0,1,13.89,20.84Zm7.45-2.41a1,1,0,0,1-.88.52H3.54a1,1,0,0,1-.87-1.53l1.4-2.33a6.94,6.94,0,0,0,1-3.57V8.2a6.94,6.94,0,0,1,13.88,0v3.32a6.94,6.94,0,0,0,1,3.57l1.4,2.33A1,1,0,0,1,21.34,18.43Z"/>
                </g>
            </defs>
        </svg>
        <!-- notifications -->
        <div class="container-notification" style="background-color:' . $as_bg_color . '; color: ' . $as_font_color . ';">
            <div class="container-display-notification">
                <div class="img-notification">
                <svg class="icon-bell" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" preserveAspectRatio="xMinYMin">
                    <use xlink:href="#icon-bell"></use>
                </svg>
                </div>
                <div class="container-info-notification">
                    <div class="text-notification">' . $as_title . '</div>
                    <div class="container-meta-notification">
                        <div class="location-notification"></div>
                        <div class="date-notification"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- list services -->
        <div class="as-list-services" style="display: none;">' . $as_services . '</div>
        <div class="as-delay" style="display: none;">' . $as_delay . '</div>
    ';
});

add_action('init', function () {
    wp_enqueue_style('alert-styles', plugin_dir_url(__FILE__) . "css/styles.css", array(), ALERT_SERVICES_VERSION);
});

add_action('wp_footer', function () {
    wp_enqueue_script('alert-scripts', plugin_dir_url(__FILE__) . "js/scripts.js", array(), strtotime('now'));
    ?>
<?php }, 1); ?>