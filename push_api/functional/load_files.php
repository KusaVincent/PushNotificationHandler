<?php
require_once __DIR__ .  '/../../path.php';

date_default_timezone_set('Africa/Nairobi');

require_once FUNC_PATH . 'json.php';
require_once FUNC_PATH . 'get_env.php';
require_once FUNC_PATH . 'log_file.php';
require_once FUNC_PATH . 'api_login.php';
require_once FUNC_PATH . 'build_hash.php';
require_once FUNC_PATH . 'check_entry.php';
require_once FUNC_PATH . 'compare_hash.php';
require_once FUNC_PATH . 'handle_files.php';
require_once FUNC_PATH . 'prepare_request.php';

require_once OOP_PATH . 'HandleCSV.php';