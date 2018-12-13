<?php
/**
 * Message
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Getlancer V3
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

/*
 * MessageContent
*/
class MessageContent extends AppModel
{
    protected $table = 'message_contents';
    protected $fillable = array(
        'subject',
        'message'
    );
    public $rules = array();
}
