<?php

include_once('libs/logger/Logger.php');

	
const AD_REQUEST_DELAY = 1;
const ZP_REQUEST_DELAY = 1;

const CURL_MAX_TIMEOUT = 10;

const PARTNER_CODE_AD = '1';
const PARTNER_CODE_ZP = '2';

const PARTNER_MARKUP_AD = 0.35;
const PARTNER_MARKUP_ZP = 0.25;


// Tell log4php to use our configuration file.
$logConfig = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'fcore'  . DIRECTORY_SEPARATOR . 'config.xml' ;
Logger::configure($logConfig);
const LOGGER_NAME = 'autoLogger';


// MySQL
const DB_SERVER= 'localhost';
const DB_LOGIN = 'zmoyxqzk_db_userx';
const DB_PASSWORD = 'FGbVCrpk39MnZX';
const DB_DATABASE = 'zmoyxqzk_wpsh';

//Data settings
//Cache size
const DATA_CACHE_MAX_SIZE = 20;

//items
const ITEMS_ON_PAGE = 20;
const DEFAULT_SORT_PARAM = 'name';
const DEFAULT_SORT_ORDER = 'ASC';


//link structure
const LINK_ACTION = 'act';
const LINK_LINEUP = 'lineups'; 
const LINK_MODEL = 'models';
const LINK_GROUPS = 'groups';
const LINK_ITEMS = 'items';
const LINK_SEARCH = 'search';


//blog
const POSTS_ON_PAGE = 10;


//pages
const BLOG_ARTICLES_PAGE = 'blog-article';


define('CURRENT_HOST', $_SERVER['SERVER_NAME']);





?>