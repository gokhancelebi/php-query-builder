<?php

include __DIR__ . "/vendor/autoload.php";

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "test");
define("DB_CHARSET", "utf8");


use App\User;


$user1 = new User();
$user1->find(1)->delete();


$user2 = new User();
$user2->find(2)->delete();


$user3 = new User();
$user3->find(3)->delete();

