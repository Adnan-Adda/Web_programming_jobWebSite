<?php

// Report error and check type casting
declare(strict_types=1);
error_reporting(E_ALL);

// Variables declaration's area
$site_title = 'Student jobb'; // site title
$divider = ' | '; // Separator between site's title and subpages' title

// Constants declaration's area
const TEMPLATE_PATH = 'includes/template/'; // template directory
const CSS_PATH = 'layout/css/'; // css directory
const JS_PATH = 'layout/js/'; // javascript directory
const IMG_PATH = 'layout/images/'; // images directory
const LOGO_UPLOAD_PATH = 'data/logoUpload/'; // File stores user account's data
const CLASS_PATH = 'includes/libraries/classes/'; // classes' path
const FUNC_PATH = 'includes/libraries/functions/'; // functions' path

// autoload classes on demand
spl_autoload_register(function ($class){
    include_once(CLASS_PATH . $class .".class.php");
});
// include functions
include_once (FUNC_PATH."include_functions.php");

// Database host, password and username
const HOST ='your';
const DB_NAME ='your';
const DB_USER ='your';
const DB_PASSWORD = 'your';
Db::initial(HOST,DB_NAME,DB_USER,DB_PASSWORD);


// jobb category list
$job_options = array('Administration/ledning','Apotek/medicin','Butik , dagligvaruhandel','Bygg/anläggning',
    'Data/IT','Design','Ekonomi/bank/försäkring','El/energiarbete','Fastighet/installation/skötsel',
    'Film/radio/TV','Försäljning/inköp/marknadsföring ','Hantverk','Hotell/restaurang','Industri/produktion',
    'Journalistik','Juridik','Kultur, turism/musik','Miljövård/hälsoskydd','Räddning/polis/säkerhet',
    'Sjukvård/hälsa','Skog/lantbruk','Skola/utbildning','Trafik/transport','Övrig');

// Provinces list
$provinces = array('Blekinge','Dalarnas','Gotlands','Gävleborgs','Hallands','Jämtlands','Jönköpings','Kalmar','Kronobergs',
    'Norrbottens','Skåne','Stockholms','Södermanlands','Uppsala','Värmlands','Västerbottens','Västernorrlands',
    'Västmanlands','Västra Götalands','Örebro','Östergötlands');
