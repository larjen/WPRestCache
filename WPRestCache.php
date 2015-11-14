<?php

/*
  Plugin Name: WPRestCache
  Plugin URI: https://github.com/larjen/WPRestCache
  Description: Wordpress plugin that provides an infinite cache in front of a REST API.
  Author: Lars Jensen
  Version: 1.0.0
  Author URI: http://exenova.dk/
 */

include_once(__DIR__ . DIRECTORY_SEPARATOR . "includes". DIRECTORY_SEPARATOR . "main.php");

if (is_admin()) {
    
    // include admin ui
    include_once(__DIR__ . DIRECTORY_SEPARATOR . "includes". DIRECTORY_SEPARATOR . "admin.php");

    // register activation and deactivation
    register_activation_hook(__FILE__, 'WPRestApiExtensions::activation');
    register_deactivation_hook(__FILE__, 'WPRestApiExtensions::deactivation');
    
}