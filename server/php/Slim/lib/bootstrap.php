<?php
/**
 * API Endpoints
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Hitekwonder
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
require_once __DIR__ . '/../../config.inc.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once 'vendors/Inflector.php';
require_once 'database.php';
require_once 'core.php';
require_once 'constants.php';
require_once 'settings.php';
require_once 'auth.php';
require_once 'acl.php';
require_once 'logger.php';
use Illuminate\Pagination\Paginator;

Paginator::currentPageResolver(function ($pageName) {
    if (!empty($_GET['filter'])) {
        $filters = json_decode($_GET['filter'], true);
        if (isset($filters['skip'])) {
            $skip = $filters['skip'];
        } else {
            $skip = 0;
        }
        if (isset($filters['limit']) && $filters['limit'] != 'all') {
            $limit = $filters['limit'];
        } else {
            $limit = 20;
        }
        return ($skip / $limit) + 1;
    }
    return 1;
});
$config = ['settings' => ['displayErrorDetails' => R_DEBUG]];
global $app, $corePlugins;
$app = new Slim\App($config);
$app->add(new \pavlakis\cli\CliRequest());
$app->add(new Auth());
if (SQL_LOG) {
    $app->add(new SQLLogger());
}
$corePlugins = array(
    'Message/Message',
    'Category/Category'
);
$plugins = getEnabledPlugin();
foreach ($plugins as $plugin) {
    require_once __DIR__ . '/../plugins/' . $plugin . '/index.php';
}

function getEnabledPlugin(){
    global $corePlugins;
    $plugins = trim(SITE_ENABLED_PLUGINS,',');
    $plugins = explode(',', $plugins);
    $mappingPlugins = array();
    $newPlugins = array();
    foreach ($plugins as $plugin) {
        $isMappingPlugin = false;
        foreach ($mappingPlugins as $mappingPluginKey => $mappingPluginValue) {
            if (array_search($plugin, $mappingPluginValue) !== false) {
                $newPlugins[] = $mappingPluginKey;
                $isMappingPlugin = true;
            }
        }
        if (!$isMappingPlugin) {
            $newPlugins[] = $plugin;
        }
    }    
    $newPlugins = array_merge($newPlugins, $corePlugins);    
    $newPlugins = array_unique($newPlugins);
    return $newPlugins;
}