<?php
/**
 * Roles configurations
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Base
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
class SQLLogger
{
    public function __invoke($request, $response, $next)
    {
        global $capsule;
        $response = $next($request, $response);
        $log = $capsule::connection()->getQueryLog();
        error_log(print_r($log, true), 3, APP_PATH . '/tmp/logs/sql.log');
        return $response;
    }
}
