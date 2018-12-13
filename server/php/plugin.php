<?php
/**
 * To create minify plugin cache
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    GetlancerV3
 * @subpackage Core
 * @author     agriya <info@.com>
 * @copyright  2014-2016 Agriya
 * @license    http://agriya.com/ Agriya Licence
 * @link       http://agriya.com/
 */
require_once './Slim/lib/bootstrap.php';
if (!file_exists(SCRIPT_PATH . DS . $_GET['file'])) {
    $enabled_plugins = explode(',', SITE_ENABLED_PLUGINS);
	$corePlugins = array(
		'Message/Message',
	);
	$enabled_plugins = array_merge($enabled_plugins, $corePlugins);
    $concat = '';
    foreach ($enabled_plugins as $plugin) {
        $pluginPath = str_replace('/', '.', $plugin);
        $plugin_name = explode('/', $plugin);
        if ($plugin_name[0] === $plugin_name[1]) {
            $pluginPath = $plugin_name[0];
        }
        if (file_exists(SCRIPT_PATH . DS . 'plugins' . DS . $plugin . DS . 'default.cache.js')) {
            $concat .= file_get_contents(SCRIPT_PATH . DS . 'plugins' . DS . $plugin . DS . 'default.cache.js');
        }
        $concat .= "angular.module('hirecoworkerApp').requires.push('hirecoworkerApp." . $pluginPath . "');";
    }
    file_put_contents(SCRIPT_PATH . DS . $_GET['file'], $concat);
    header('Location:' . $_SERVER['REQUEST_URI'] . '?chrome-3xx-fix');
} else {
    echo file_get_contents(SCRIPT_PATH . DS . $_GET['file']);
}
