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

class Setting extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';
    protected $casts = ['setting_category_id' => 'integer', 'position' => 'integer', 'is_front_end_access' => 'integer'];
    public $timestamps = false;
    protected $fillable = array(
        'name',
        'value',
        'is_front_end_access',
        'plugin'
    );
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        $enabled_plugins = getEnabledPlugin();
        $query->where(function ($q) use ($enabled_plugins) {
               $q->whereIn('plugin', $enabled_plugins);
               $q->orWhere('plugin', null);
            });
    }
    public function setting_category()
    {
        return $this->belongsTo('Models\SettingCategory', 'setting_category_id', 'id');
    }
}
