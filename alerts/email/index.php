<?php
require_once __DIR__ .  '/../../push_api/functional/load_files.php';

require_once EMAIL_ALERT_PATH . 'EmailHelper.php';

$email = (new EmailHelper())->send(['vinonyi21@gmail.com'], 'nothings', 'another nothing', 'more nothing');

logThis(4, json_encode($email));