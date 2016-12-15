<?php
/**
 * The base configurations.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, Language, and ABSPATH. You can get the MySQL settings from your web host.
 *
 * This file is used by the scm-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "sc-config.php" and fill in the values.
 *
 */


/*
 * Multi-Environment Config
 * 
 * Loads config file based on current environment, environment can be set
 * in either the environment variable 'SCM_ENV' or can be set based on the 
 * server hostname.
 * 
 * This also overrides the option_home and option_siteurl settings in the 
 * database to ensure site URLs are correct between environments.
 * 
 * Common environment names are as follows, though you can use what you wish:
 * 
 *   production
 *   staging
 *   development
 * 
 * For each environment a config file must exist named scm-config.{environment}.php
 * with any settings specific to that environment. For example a development 
 * environment would use the config file: scm-config.development.php
 * 
 * Default settings that are common to all environments can exist in scm-config.default.php
 * 
 * @version    1.0.1
 * @author     Studio 24 Ltd  <info@studio24.net>
 */


// Absolute path to the directory
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

// Try environment variable 'SCM_ENV'
if (getenv('SCM_ENV') !== false) {
    // Filter non-alphabetical characters for security
    define('SCM_ENV', preg_replace('/[^a-z]/', '', getenv('SCM_ENV')));
} 

// Define site host
if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && !empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
    $hostname = $_SERVER['HTTP_X_FORWARDED_HOST'];
} else {
    $hostname = $_SERVER['HTTP_HOST'];
}

// If it has been bootstrapped via SCM-CLI detect environment from --env=<environment> argument
if (PHP_SAPI == "cli" && defined('SCM_CLI_ROOT')) {
    foreach ($argv as $arg) {
        if (preg_match('/--env=(.+)/', $arg, $m)) {
            define('SCM_ENV', $m[1]);
        }
    }
	$hostname = "localhost";
}

// Filter
$hostname = filter_var($hostname, FILTER_SANITIZE_STRING);

// Try server hostname
if (!defined('SCM_ENV')) {
    // Set environment based on hostname
    include ABSPATH . '/scm-config.env.php';
}

// Are we in SSL mode?
if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ||
    (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
    $protocol = 'https://';
} else {
    $protocol = 'http://';
}

// Load default config
include ABSPATH . '/scm-config.default.php';

// Load config file for current environment
include ABSPATH . '/scm-config.' . SCM_ENV . '.php';

// Define the Site URLs if not already set in config files
if (!defined('SCM_SITEURL')) {
    define('SCM_SITEURL', $protocol . rtrim($hostname, '/'));
}
if (!defined('SCM_HOME')) {
    define('SCM_HOME', $protocol . rtrim($hostname, '/'));
}

// Define W3 Total Cache hostname
if (defined('SCM_CACHE')) {
    define('COOKIE_DOMAIN', $hostname);
}

// Clean up
unset($hostname, $protocol);

/** End of Multi-Environment Config **/


/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'scm-settings.php');
