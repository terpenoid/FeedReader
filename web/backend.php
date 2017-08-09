<?php

require(__DIR__ . '/../vendor/autoload.php');

$collector = new FeedReader\FeedsCollector();
$collector->addProvider('FeedReader\TwitterFeedsProvider');
$data = $collector->getAllFeeds();

header('Content-Type: application/json');

print json_encode($data);