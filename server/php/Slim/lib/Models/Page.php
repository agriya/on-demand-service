<?php
/**
 * Page
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

class Page extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pages';
    protected $casts = ['is_active' => 'integer', 'language_id' => 'integer'];
    protected $fillable = array(
        'title',
        'page_content',
        'is_active',
        'language_id'
    );
    public $rules = array(
        'title' => 'sometimes|required',
        'page_content' => 'sometimes|required'
    );
    public function language()
    {
        return $this->belongsTo('Models\Language', 'language_id', 'id');
    }
    public function scopeFilter($query, $params = array())
    {
        parent::scopeFilter($query, $params);
        if (!empty($params['q'])) {
            $query->where(function ($q1) use ($params) {
                $search = $params['q'];
                $q1->orWhereHas('language', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
                $q1->orWhere('title', 'like', "%$search%");
                $q1->orWhere('page_content', 'like', "%$search%");
            });
        }
        if (!empty($params['lang_code'])) {
            $language_id = '';
            $language = Language::select('id')->where('iso2', $params['lang_code'])->first();            
            if (!empty($language)) {
                $language_id = $language->id;
            }
            $query->where('language_id', $language_id);
        }
    }
}
