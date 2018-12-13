<?php
/**
 * Setting
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

class SettingCategory extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'setting_categories';

    protected $fillable = array(
        'description',
        'name',
        'is_active',
        'display_order',
        'plugin',
        'is_front_end_access'
    );
    public $rules = array(
        'name' => 'sometimes|required',
        'description' => 'sometimes|required',
        'is_active' => 'sometimes|required',
        'display_order' => 'sometimes|required'
    );

    public function scopeFilter($query, $params = array())
    {
        global $authUser;
        parent::scopeFilter($query, $params);
        $enabled_plugins = getEnabledPlugin();
        $query->where(function ($q) use ($enabled_plugins) {
               $q->whereIn('plugin', $enabled_plugins);
               $q->orWhere('plugin', null);
            });
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->where('name', 'like', "%".$search."%");
            });
        }
        if (empty($authUser) || (!empty($authUser) && $authUser['role_id'] != \Constants\ConstUserTypes::Admin)) {
            $query->where('is_active', 1);
        }
    }
}
