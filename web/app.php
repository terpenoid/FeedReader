<?php

require(__DIR__ . '/../vendor/autoload.php');

$config = Atmega64\Settings::getInstance();



var_dump($config);
var_dump($config->get('ddd'));
var_dump($config->get('sss'));
var_dump($config->get('aaa'));


$config->set('aaa', 11111111111);

var_dump($config);
var_dump($config->get('aaa'));


$config2 = Atmega64\Settings::getInstance();
var_dump($config2);

