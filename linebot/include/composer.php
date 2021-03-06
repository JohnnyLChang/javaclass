<?php
define('__ROOT__', dirname(dirname(__FILE__)));
require(__ROOT__ .'/../vendor/autoload.php');
require_once(__ROOT__ .'/JavaClass/ClassUrlHelper.php');

require_once 'cloudinary.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('javaclass');
$log->pushHandler(new StreamHandler('php://stderr', Logger::INFO));

if (file_exists('.test')) {
  require_once './include/cloudinary_local.php';
}

$TimeZone = 'Asia/Taipei';
?>
