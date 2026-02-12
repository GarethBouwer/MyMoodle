<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

// ========= DATABASE (PostgreSQL on Railway) =========

$CFG->dbtype    = 'pgsql';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'postgres.railway.internal';   // Railway internal Postgres host
$CFG->dbname    = 'railway';
$CFG->dbuser    = 'postgres';
$CFG->dbpass    = 'krXPKhTItzkebxZHRAsGMPgTNuTxagzv';
$CFG->prefix    = 'mdl_';

$CFG->dboptions = array(
    'dbpersist' => false,
    'dbsocket'  => false,
    'dbport'    => 5432,
);


// ========= PATHS =========

$CFG->wwwroot   = 'https://clementsandcox.academy';
$CFG->dirroot   = '/var/www/html';
$CFG->dataroot  = '/var/www/moodledata';
$CFG->libdir    = '/var/www/html/lib';


// ========= PROXY / RAILWAY REQUIREMENTS =========

// Railway terminates HTTPS at the edge → container sees HTTP.
// Moodle MUST be told this to avoid redirect loops.
// $CFG->reverseproxy = true;
$CFG->sslproxy     = true;

// ========= DEBUG SMTP =========
// $CFG->debug = E_ALL;
// $CFG->debugdisplay = true;
// $CFG->debugsmtp = true;


// ========= ADMIN URL SEGMENT =========

$CFG->admin = 'admin';


// ========= PERMISSIONS (OK DURING INSTALL PHASE) =========

$CFG->directorypermissions = 0777;


// ========= START MOODLE =========

// If running locally on Windows override dataroot to a workspace-local folder.
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $localdataroot = __DIR__ . DIRECTORY_SEPARATOR . 'moodledata';
    if (!file_exists($localdataroot)) {
        @mkdir($localdataroot, 0777, true);
    }
    $CFG->dataroot = $localdataroot;
}

require_once(__DIR__ . '/lib/setup.php');

// If running via web and the request used the www subdomain, redirect
// to the canonical non-www host so both DNS names are supported by DNS
// while keeping a single canonical `wwwroot`.
if (php_sapi_name() !== 'cli' && !empty($_SERVER['HTTP_HOST'])) {
    $host = strtolower($_SERVER['HTTP_HOST']);
    if (strpos($host, 'www.') === 0 && substr($host, 4) === 'clementsandcox.academy') {
        $requesturi = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        header('Location: https://clementsandcox.academy' . $requesturi, true, 301);
        exit;
    }
}
