<?php
date_default_timezone_set('Africa/Nairobi');

define('ROOT_PATH', realpath(dirname(__FILE__)) . '/');

require_once ROOT_PATH . 'get_env.php';
require_once ROOT_PATH . 'log_file.php';
require_once ROOT_PATH . 'HandleCSV.php';
require_once ROOT_PATH . 'api_login.php';
require_once ROOT_PATH . 'build_hash.php';
require_once ROOT_PATH . 'check_entry.php';
require_once ROOT_PATH . 'compare_hash.php';
require_once ROOT_PATH . 'handle_files.php';
require_once ROOT_PATH . 'prepare_request.php';