<?php

//$loader = require 'vendor/autoload.php';
//$loader->add('FeedsReaderBackend\\', __DIR__.'/src');

// @todo: not work.... strange.... using TEMPORARY dirty solution... :(

require 'src/FeedsCollector/FeedsProvider.php';
require 'src/FeedsCollector/TwitterFeedsProvider.php';
require 'src/FeedsCollector/FeedsCollector.php';


$collector = new FeedsReaderBackend\FeedsCollector();
$collector->addProvider('FeedsReaderBackend\TwitterFeedsProvider');
$data = $collector->getAllFeeds();

header('Content-Type: application/json');
print json_encode($data);


