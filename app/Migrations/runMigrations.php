<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\Migrations\CreatePropertiesTable;

$createPropertiesTable = new CreatePropertiesTable();
$createPropertiesTable->up();
