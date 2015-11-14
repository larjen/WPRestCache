<?php

class WPRestCache {

    // values to use internally in the plugin, do not customize

    static $debug = false;
    static $plugin_name = "WPRestCache";

    /*
     * Activation
     */

    static function activation() {
        update_option(self::$plugin_name . "_MESSAGES", []);
        update_option(self::$plugin_name . "_ACTIVE", false);
        self::add_message('Plugin WPRestCache activated.');
    }

    /*
     * Deactivation
     */

    static function deactivation() {

        self::clear_schedule();
        self::add_message('Plugin WPRestCache deactivated.');
    }

    /*
     * Schedules a wipe of the cache to occur in 5 minutes
     */

    static function schedule_wipe_of_cache() {

        // only schedule wipe if the cache wiping is activated
        if (get_option(self::$plugin_name . "_ACTIVE") == true) {

            // unschedule previous schedule
            self::clear_schedule();

            //gives the unix timestamp for today's date + 1 minute
            $start = time() + (5 * 60);

            // schedule wipe of cache in 5 minutes, when cache has been wiped
            // the scheduler will be cleared so this does not repeat hourly
            wp_schedule_single_event($start, 'WPRestCacheWipeCache');
        }
    }

    /*
     * Wipe the cache, then clear the schedule
     */

    static function do_scheduled_cache_wipe() {

        self::clear_schedule();

        // only wipe the cache if the cache wiping is activated
        if (get_option(self::$plugin_name . "_ACTIVE") == true) {
            self::wipe_cache();
        }
    }

    /*
     * Clears the schedule
     */

    static function clear_schedule() {
        // unschedule previous schedule
        wp_clear_scheduled_hook('WPRestCacheWipeCache');
    }

    /*
     * Copies one directory to another
     */

    static function recurse_copy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($src . '/' . $file)) {
                    self::recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    /*
     * Deploy cache
     */

    static function deploy_cache() {

        $source_dir = __DIR__ . DIRECTORY_SEPARATOR . "rest-api";
        $destination_dir = ABSPATH . "rest-api";

        self::recurse_copy($source_dir, $destination_dir);
        self::wipe_cache();

        self::add_message('Plugin WPRestCache deploying cache mechanism<br /> from: ' . $source_dir . '<br /> to: ' . $destination_dir);
    }

    /*
     * Wipe cache
     */

    static function wipe_cache() {

        $cache_dir = ABSPATH . "rest-api" . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR;

        @mkdir($cache_dir);

        $files = glob($cache_dir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        self::add_message('Plugin WPRestCache wiped cache at : ' . $cache_dir . '.');
    }

    /*
     * Adds messages
     */

    static function add_message($message) {
        $messages = get_option(self::$plugin_name . "_MESSAGES");
        array_push($messages, date("Y-m-d H:i:s") . " - " . $message);

        // keep the amount of messages below 10
        if (count($messages) > 10) {
            $temp = array_shift($messages);
        }

        update_option(self::$plugin_name . "_MESSAGES", $messages);
    }
}

// register wp hooks
add_action('save_post', 'WPRestCache::schedule_wipe_of_cache');

// add action to wipe cache
add_action('WPRestCacheWipeCache', 'WPRestCache::do_scheduled_cache_wipe');

