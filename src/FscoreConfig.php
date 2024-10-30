<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Class
 */
class FscoreConfig

{
    /*--------------------------------------------*
     * Attributes
     *--------------------------------------------*/
    /** Refers to a single instance of this class. */
    private static $instance = null;

    /* Saved options */
    public $options;

    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/
    /**
     * Creates or returns an instance of this class.
     *
     * @return  AzscoreThemeOptions A single instance of this class.
     */
    public static function getInstance() {

        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;

    } // end getInstance;

    /**
     * Initializes the plugin by setting localization, filters, and administration functions.
     */
    private function __construct() {

        // Add the page to the admin menu
        add_action('admin_menu', array(&$this, 'addPage'));

        // Register page options
        add_action('admin_init', array(&$this, 'registerPageOptions'));

        // Get registered option
        $this->options = get_option('fscore_crecket_settings_options');

    }

    /*--------------------------------------------*
     * Functions
     *--------------------------------------------*/

    /**
     * Function that will add the options page under Setting Menu.
     */
    public function addPage() {
        add_options_page('Theme Options', 'Cricket Live Score', 'manage_options', __FILE__, array($this, 'displayPage'));
    }

    /**
     * Function that will display the options page.
     */
    public function displayPage() {
    ?>
    <div class='wrap'>
		<h1><?php esc_html_e('Cricket Live Score Settings', 'fscore_crecket-widget'); ?></h1>
		<div id="poststuff" class="metabox-holder">
			<div class="widget">
                <form method="post" action="options.php">
                <?php
                    submit_button();
                    settings_fields(__FILE__);
                    do_settings_sections(__FILE__);
                ?>
                </form>
                <div>
                    <small>
                        When you activate the "Enabled" option, it signifies:<br/>
                        <blockquote>
                            * Disabling ads within your plugin <br/>
                            * Enabling the plugin to work on your website using a shortcode [cricket_score] <br/>
                            * Cricket Live Score will work without using an iframe <br/>
                            * Activating the display of a link to the author's website <br/>
                        </blockquote>
                        On the other hand, if you choose the "Disabled" setting, it conveys:<br/>
                        <blockquote>
                            * Activating on iframe the display of ads within your plugin and cricket scores <br/>
                            * Enabling the plugin to work on your website using an iframe with the ability to use the "height" parameter. Example: [cricket_score height=1500] </br>
                            * Disabling the display of a link to the author's website <br/>
                        </blockquote>
                    </small>
                </div>
                <br/>
                <br/>
                <h3>Using the Plugin:</h3>
                <ul>
                    <ol>
                        Utilize the [cricket_score] shortcode to display live scores wherever you desire.
                    </ol>
                    <ol>
                        Customize the display with additional attributes:
                        <blockquote>
                            * For instance, you can specify the period as: "live"; "yesterday". Example: [cricket_score period=live] </br>
                            * Also, you can customize the frame height using the "height" parameter. Example: [cricket_score height=800] <br/>
                        </blockquote>
                    </ol>
                </ul>
			</div>
		</div>
	</div>
    <?php
    }

    /**
     * Function that will register admin page options.
     */
    public function registerPageOptions() {
        // Add Section for option fields
        add_settings_section('fscore_crecket_section', 'Settings', array($this, 'displaySection'), __FILE__);
        add_settings_field('fscore_crecket_autorefresh', 'Autorefresh', array($this, 'isAutorefreshSettingsField'), __FILE__, 'fscore_crecket_section');
        add_settings_field('fscore_crecket_clink', 'Activation of shortcode', array($this, 'isLinkInsertSettingsField'), __FILE__, 'fscore_crecket_section');

        // Register Settings
        register_setting(__FILE__, 'fscore_crecket_settings_options', array($this, 'validateOptions'));
    }

    /**
     * Function that will validate all fields.
     */
    public function validateOptions($fields) {
        $valid_fields = array();

        //author link
        $c_link = trim($fields['fscore_crecket_is_link_insert']);
        $valid_fields['fscore_crecket_is_link_insert'] = strip_tags(stripslashes($c_link));

        $autorefresh = trim($fields['fscore_crecket_is_autorefresh']);
        $valid_fields['fscore_crecket_is_autorefresh'] = strip_tags(stripslashes($autorefresh));

        return apply_filters('validateOptions', $valid_fields, $fields);
    }

    /**
     * Callback function for settings section
     */
    public function displaySection() { /* Leave blank */ }

    public function isAutorefreshSettingsField() {
        $val = isset($this->options['fscore_crecket_is_autorefresh']) ? $this->options['fscore_crecket_is_autorefresh'] : 'off';

        $selected_one=array('on' => '', 'off' => '');
        $selected_one[$val] = 'selected="selected"';
        echo <<<EOD
        <div>
        <select name="fscore_crecket_settings_options[fscore_crecket_is_autorefresh]">
            <option value="off" {$selected_one['off']}>Disabled</option>
            <option value="on" {$selected_one['on']}>Enabled</option>
        </select>
        </div>
EOD;
    }

    public function isLinkInsertSettingsField() {
        $val = isset($this->options['fscore_crecket_is_link_insert']) ? $this->options['fscore_crecket_is_link_insert'] : 'off';

        $selected_one=array('on' => '', 'off' => '');
        $selected_one[$val] = 'selected="selected"';
        echo <<<EOD
        <div>
        <select name="fscore_crecket_settings_options[fscore_crecket_is_link_insert]">
            <option value="off" {$selected_one['off']}>Disabled</option>
            <option value="on" {$selected_one['on']}>Enabled</option>
        </select>
        </div>
EOD;
    }
}
