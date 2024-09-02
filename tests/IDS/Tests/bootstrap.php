<?php


define('IDS_TEMP_DIR', sys_get_temp_dir());
define('IDS_FILTER_CACHE_FILE', IDS_TEMP_DIR . "/default_filter.cache");

define('IDS_CONFIG', realpath(__DIR__ . '/../../..') . '/' . "lib/IDS/Config/Config.ini.php");
define('IDS_FILTER_TYPE', "xml");
define('IDS_FILTER_SET', realpath(__DIR__ . '/../../..') . '/' . "lib/IDS/default_filter.xml");
define('IDS_FILTER_SET_XML', realpath(__DIR__ . '/../../..') . '/' . "lib/IDS/default_filter.xml");
define('IDS_FILTER_SET_JSON', realpath(__DIR__ . '/../../..') . '/' . "lib/IDS/default_filter.json");

