<?php

include('vendor/autoload.php');

use App\{Application, Utils\CliReader, Utils\CliWriter};

$reader = new CliReader($argv);
$writer = new CliWriter();

$app = new Application($reader, $writer);
$app->main();
