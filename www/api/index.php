<?php
ini_set('html_errors','off');
require_once '../../vendor/autoload.php';
$config = require_once '../../src/config/config.php';

session_start();

$GLOBALS['cache'] = new Memcached;
$GLOBALS['cache']->addServers($config['memcached']);
if (PROD) {
    $GLOBALS['cache']->set('oauth', getenv('IRON_CACHE_TOKEN').' '.getenv('IRON_CACHE_PROJECT_ID').' general');
}

/* ************************ CONFIGURES DATABASE ACCESS ************************ */
$db = $config['database'];
\LaravelArdent\Ardent\Ardent::configureAsExternal([
    'driver'    => 'pgsql',
    'host'      => $db['host'],
    'port'      => isset($db['port'])? $db['port'] : null,
    'database'  => $db['name'],
    'username'  => $db['user'],
    'password'  => $db['pass'],
    'charset'   => 'utf8',
//    'collation' => 'utf8_unicode_ci'
], 'en');


/* ************************ CONFIGURES THE API OBJECTS ************************ */
$r = new Luracast\Restler\Restler(PROD);
$r->setBaseUrls('/api');

$skip = ['.', '..'];
foreach (scandir('../../src/API') as $file) {
    if (!in_array($file, $skip)) {
        $name  = strtok($file, '.');
        $class = "\\Shop\\API\\$name";
        $url = property_exists($class, 'url')? $class::$url : null;
        $r->addAPIClass($class, $url);
    }
}
$r->addAPIClass(\Luracast\Restler\Explorer::class);
$r->handle();