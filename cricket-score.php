<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: Cricket Score
Plugin URI: https://wordpress.org/plugins/ocricket-score/
Description: Get the latest Cricket Live Score for your website.
Version: 2.0.2
Author: Cricket scores
Author URI: https://fscore.net/cricket
License: GPLv2
*/

include_once('src/connectFscore.php');
include_once('src/FscoreConfig.php');

FscoreConfig::getInstance();
add_shortcode( 'cricket_score', 'connectFscore' );

?>
