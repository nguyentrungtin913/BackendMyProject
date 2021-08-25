<?php
ini_set('serialize_precision', 14);
ini_set('precision', 14);

use \core\ezy;
use \lib\cookie;

/**
 * Fix date time
 */
//print_r(DateTimeZone::listIdentifiers()); exit;
//date_default_timezone_set('Asia/Ho_Chi_Minh');

/**
 * Declare main path
 */

define( 'root_path', dirname( __FILE__ ) ."/" );
define( 'IN_ROOT', 1 );
define( 'is_init', true );

global $info; //For phpunit (null if not use global)
$info = [];

include_once 'vendor/autoload.php';
require(root_path. "config.inc.php");

/**
 * Display errors for each environment
 */

// if ( isset($info["ezy_env"]) )
// {
//     // Staging
//     if ( in_array($info["ezy_env"], ["test", "raw"]) ) {
//         error_reporting(E_ALL ^ E_DEPRECATED);
//     }
//     // Developement
//     else if ( in_array($info["ezy_env"], ["development", "staging"]) ) {
//         error_reporting(E_ALL ^ E_DEPRECATED & ~E_NOTICE);
//     }
//     // Production
//     else {
//         error_reporting(E_ALL ^ E_DEPRECATED & ~E_WARNING & ~E_NOTICE );
//     }

//     ini_set("display_errors", 0);
// } 
error_reporting(0);
/**
 * Start a session
 */
    
 
if ( ! session_id() )
{
    session_start();
}

/**
 * Main vars
 */
global $CMS; //For phpunit (null if not use global)
$CMS = new stdClass();
$CMS->class = new stdClass();
$CMS->api = new stdClass();
$CMS->core = new stdClass();

$CMS->input = array();
$CMS->vars = &$info;
$CMS->lang = &$lang;
$CMS->errormsg = isset($_SESSION['msg'])?$_SESSION['msg']:'';
$CMS->is_error = 0;

$tpl = new stdClass();

// Load app config
require(root_path. "config.app.php");

// Load site config
require(root_path. "config.route.php");

/**
 * Check function exists
 */

$required_functions = array(
    "xml_parser_create" => "php-xml",
    "curl_init" => "php-curl",
    "exif_imagetype" => "php-exif",
    "mb_substr" => "php-mbstring"
);

// Let's check!
foreach ($required_functions as $func_name => $func_lib)
{
    if (!function_exists($func_name)) {
        exit("EzyPHP: Please install {$func_lib}");
    }
}

if ( in_array($info["ezy_env"], ["staging", "production"]) )
{
    $required_classes = array(
        "Imagick" => "imagick",
        "ZipArchive" => "ziparchive"
    );

    foreach ($required_classes as $func_name => $func_lib)
    {
        if (!class_exists($func_name)) {
            if ( isset($_GET['phpinfo']) ) { phpinfo(); }
            $image = new $func_lib();
            exit("EzyPHP: Please install {$func_lib} (Class)");
        }
    }
}

/**
 * Load core files.
 */

$ezy_path = array(
    root_path."kernel/models/",
    root_path."kernel/api/",
    root_path."kernel/lib/",
);

foreach ( $ezy_path as $dir ) {
    $dir_content = \scandir($dir);
    foreach ($dir_content as $file) {
        if (substr(strtolower($file), -3, 3) == "php" && substr(strtolower($file), -9, 9) != ".html.php" ) {
            require_once $dir . $file;
        }
    }
}
require root_path."kernel/core.php";
ezy::$run_migrate = false; // Prevent migration

// Enable send Message Sql
\lib\db::$notify_slack = true;
 
// Old vars
$CMS->core->page_title = ezy::$title;

/**
 * EzyWeb Detector
 */

$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $info['host'];
 
$site_routed = isset($site_routes[$host]) ? $site_routes[$host] : '';
ezy::$user_dir = 'gea';
 
// Root vars
if ( isset($site_routed) == true )
{
    // Vars
    $CMS->vars['root_domain'] = isset($site_routed['domain']) ? $site_routed['domain'] : '';
    $CMS->vars['parent_domain'] = isset($site_routed['domain']) ? $site_routed['domain'] : '';
    $CMS->vars['theme'] = isset($site_routed['theme']) ? $site_routed['theme'] : "";
    $temp_parse = isset($site_routed['domain']) ? parse_url($site_routed['domain']) : '';
    $info['db_name'] = isset($site_routed['db']) ? $site_routed['db'] : $info['db_name']; // Set Database name
    if(!empty($site_routed['is_web_us']))
    {     
        define("is_web_us", $site_routed['is_web_us'] );  
    }
    if(!empty($site_routed['is_web_vn']))
    {
        define("is_web_vn", $site_routed['is_web_vn'] );
    } 

    // Use form 1: nails; 2: mer; 3: bds
    if(!empty($site_routed['type_web']))
    {
        define("type_web", $site_routed['type_web'] );
    }   

    // Cookie
    cookie::$domain = ".".(isset($temp_parse["host"]) ? $temp_parse["host"] : ''); // Set cookie domain

    // User dir
    ezy::$user_dir = isset($site_routed['upload']) ? $site_routed['upload'] : '';
}

// Check cron
if ( defined("is_cron") == true OR defined("is_api") == true)
{
    // Init core
    new ezy;
}

/**
 * Routes, find the app match with the hostname
 */

else if ( isset($site_routed) == true )
{
  

    // Init core
    new ezy;

    date_default_timezone_set(isset($CMS->vars['timezone_id']) ? $CMS->vars['timezone_id'] : date_default_timezone_get());
 
     
}

/**
 * Client, try to load homepage if no route is found.
 */

else
{
    // Init global
    ezy::init_global();

    // Set custom error
    $tpl->msg = isset($CMS->lang['site_notfound']) ? $CMS->lang['site_notfound'] : '';

    // Output error page
    echo ezy::load_layout("error_maintenance");
    exit;
}