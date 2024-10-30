<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// a helper function to lookup "env_FILE", "env", then fallback
if (!function_exists('getenv_docker')) {
	function getenv_docker($env, $default) {
		if ($fileEnv = getenv($env . '_FILE')) {
			return rtrim(file_get_contents($fileEnv), "\r\n");
		}
		else if (($val = getenv($env)) !== false) {
			return $val;
		}
		else {
			return $default;
		}
	}
}

function connectFscore($atts) {
    $settings = get_option('fscore_crecket_settings_options');

    $attsCfg = shortcode_atts(
        array(
            'period' => '',
            'height' => 1500,
        ),
        $atts,
        'cricket_score'
    );

    $prd = $attsCfg['period'];
    $period = in_array($prd, array('live', 'yesterday')) ? "/{$prd}" : '';
    $height = $attsCfg['height'];
    $isAutorefresh = isset($settings['fscore_crecket_is_autorefresh']) ? $settings['fscore_crecket_is_autorefresh'] : 0;
    $result = $isAutorefresh != 'on'
        ? ''
        : '<script> setTimeout(() => window.location.reload(), 60*1000); </script>'
    ;

    $url = getenv_docker('WORDPRESS_PLUGIN_CRICKET_URL', 'https://fscore.in');

    // check author credit link is on or not. if not set it without link via iframe
    $isLinkInsert = isset($settings['fscore_crecket_is_link_insert']) ? $settings['fscore_crecket_is_link_insert'] : 'off';

    if ($isLinkInsert != 'on') {
        $result .= <<<EOD

        <iframe
            src="{$url}/widget/cricket{$period}/results"
            marginheight="0"
            marginwidth="0"
            scrolling="auto"
            height="{$height}"
            width="100"
            frameborder="0"
            id="fscoreiframe"
            style="width: 100%; height: {$height}px; max-width: 100%"
        ></iframe>
EOD;
    } else {
        $result .= <<<EOD

        <script charset="utf8" src="{$url}/widget/cricket{$period}" type="text/javascript"></script>
        <a href="{$url}/cricket">cricket score</a>
EOD;

    }

    return $result;
}
