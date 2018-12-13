<?php
/**
 * EmailTemplate
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    Base
 * @subpackage Model
 * @author     Agriya <info@agriya.com>
 * @copyright  2016 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
namespace Models;

class EmailTemplate extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_templates';
    protected $casts = ['is_html' => 'integer'];
    public $timestamps = false;
    protected $fillable = array(
        'from_name',
        'reply_to',
        'subject',
        'body_content',
        'plugin'
    );
    public $rules = array(
        'from_name' => 'sometimes|required',
        'reply_to' => 'sometimes|required',
        'subject' => 'sometimes|required'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->Where('name', 'like', "%$search%");
                $q1->orWhere('subject', 'like', "%$search%");
                $q1->orWhere('body_content', 'like', "%$search%");
            });
        }
    }
}
