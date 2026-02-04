<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

// 1. DATABASE SETUP (PostgreSQL on Railway)
$CFG->dbtype    = 'pgsql';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'mymoodle.railway.internal';  // value from RAILWAY_PRIVATE_DOMAIN
$CFG->dbname    = 'railway';                      // POSTGRES_DB
$CFG->dbuser    = 'postgres';                     // POSTGRES_USER
$CFG->dbpass    = 'krXPKhTItzkebxZHRAsGMPgTNuTxagzv';             // POSTGRES_PASSWORD
$CFG->prefix    = 'mdl_';                         // table prefix (leave as mdl_)

$CFG->dboptions = array(
    'dbpersist' => false,
    'dbsocket'  => false,
    'dbport'    => 5432,
);

// 2. PATHS
$CFG->wwwroot  = 'https://mymoodle-production.up.railway.app/';      // e.g. https://mymoodle-production.up.railway.app
$CFG->dirroot  = '/var/www/html';
$CFG->dataroot = '/var/www/moodledata';

// 3. ADMIN USERNAME (URL path, not the login)
$CFG->admin = 'admin';

$CFG->directorypermissions = 02777;

require_once(__DIR__ . '/lib/setup.php');
