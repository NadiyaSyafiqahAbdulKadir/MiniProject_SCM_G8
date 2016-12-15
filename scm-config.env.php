<?php
/**
 * Setup environments
 * 
 * Set environment based on the current server hostname, this is stored
 * in the $hostname variable
 * 
 * You can define the current environment via: 
 *     define('SCM_ENV', 'production');

 * @version    1.0
 * @author     Studio 24 Ltd  <info@studio24.net>
 */


/*
 * Set environment based on hostname
 *
 *
 */
switch ($hostname) {
    case 'localhost.dev':
        define('SCM_ENV', 'development');
        break;
    
    case 'staging.localhost':
        define('SCM_ENV', 'staging');
        break;

    case 'localhost':
    default: 
        define('SCM_ENV', 'production');
}

