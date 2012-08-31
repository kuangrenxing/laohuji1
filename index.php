<?php
require_once('./common/define.php');
require_once('./common/config.php');
require_once('./common/function.php');
require_once('./common/oauth.conf.php');

Globals::requireClass('Controller');
Controller::runController(null, $config);
