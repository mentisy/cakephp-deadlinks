<?php
declare(strict_types=1);

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Database\Connection;
use Cake\Database\Driver\Sqlite;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\DateTime;
use Cake\Mailer\TransportFactory;
use Cake\TestSuite\Fixture\SchemaLoader;

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

define('PLUGIN_ROOT', dirname(__DIR__));
const ROOT = PLUGIN_ROOT . DS . 'tests' . DS . 'test_app';
const TMP = PLUGIN_ROOT . DS . 'tmp' . DS;
const LOGS = TMP . 'logs' . DS;
const CACHE = TMP . 'cache' . DS;
const APP = ROOT . DS . 'src' . DS;
const APP_DIR = 'src';
const CAKE_CORE_INCLUDE_PATH = PLUGIN_ROOT . '/vendor/cakephp/cakephp';
const CORE_PATH = CAKE_CORE_INCLUDE_PATH . DS;
const CAKE = CORE_PATH . APP_DIR . DS;

const CONFIG = __DIR__ . DS . 'test_app' . DS . 'config' . DS;
const TESTS = __DIR__ . DS;
const TEST_FILES = TESTS . 'test_files' . DS;

ini_set('intl.default_locale', 'nb_NO');

require PLUGIN_ROOT . '/vendor/autoload.php';
require CORE_PATH . 'config/bootstrap.php';

Configure::write('debug', true);

Configure::write('App', [
    'namespace' => 'TestApp',
    'encoding' => 'UTF-8',
    'defaultLocale' => 'nb_NO',
    'defaultTimezone' => 'APP_DEFAULT_TIMEZONE', 'Europe/Oslo',
]);

if (!getenv('db_dsn')) {
    putenv('db_dsn=sqlite:///:memory:');
}

ConnectionManager::setConfig('test', [
    'url' => getenv('db_dsn'),
    'username' => 'root',
    'password' => 'root',
    'database' => 'title',
    'className' => Connection::class,
    'driver' => Sqlite::class,
    'persistent' => false,
    'timezone' => 'UTC',
    'encoding' => 'utf8',
    'flags' => [],
    'cacheMetadata' => true,
    'quoteIdentifiers' => false,
    'log' => false,
]);

Configure::write('EmailTransport', [
    'default' => [
        'className' => 'Debug',
    ],
]);

Configure::write('Email', [
    'default' => [
        'transport' => 'default',
        'from' => 'you@localhost',
    ],
]);

TransportFactory::setConfig('default', [
    'className' => 'Debug',
]);

if (!file_exists(TMP)) {
    mkdir(TMP);
    mkdir(TMP . 'cache/models', 0770);
    mkdir(TMP . 'cache/persistent', 0770);
}

Cache::setConfig([
    'default' => [
        'engine' => 'File',
        'path' => CACHE,
    ],
    '_cake_core_' => [
        'className' => 'File',
        'prefix' => 'deadlinks_core_',
        'path' => CACHE . 'persistent/',
        'serialize' => true,
        'duration' => '+10 seconds',
    ],
    '_cake_model_' => [
        'className' => 'File',
        'prefix' => 'deadlinks_model_',
        'path' => CACHE . 'models/',
        'serialize' => 'File',
        'duration' => '+10 seconds',
    ],
]);
(new SchemaLoader())->loadSqlFiles(PLUGIN_ROOT . DS . 'tests' . DS . 'schema.sql');

DateTime::setTestNow('2021-07-10 23:11:11');
session_id('cli');
