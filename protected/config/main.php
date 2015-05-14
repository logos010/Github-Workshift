<?php

require_once 'environment.php';

$common = require 'common.php';
$env = require ENV . '.php';

return CMap::mergeArray($common, $env);