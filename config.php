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

$CFG->wwwroot   = 'https://mymoodle-production.up.railway.app';
$CFG->dirroot   = '/var/www/html';
$CFG->dataroot  = '/var/www/moodledata';
$CFG->libdir    = '/var/www/html/lib';


// ========= PROXY / RAILWAY REQUIREMENTS =========

// Railway terminates HTTPS at the edge → container sees HTTP.
// Moodle MUST be told this to avoid redirect loops.
// $CFG->reverseproxy = true;
$CFG->sslproxy     = true;

// ========= DEBUG SMTP =========
$CFG->debug = E_ALL;
$CFG->debugdisplay = true;
$CFG->debugsmtp = true;


// ========= ADMIN URL SEGMENT =========

$CFG->admin = 'admin';


// ========= PERMISSIONS (OK DURING INSTALL PHASE) =========

$CFG->directorypermissions = 0777;


// ========= START MOODLE =========

require_once(__DIR__ . '/lib/setup.php');
