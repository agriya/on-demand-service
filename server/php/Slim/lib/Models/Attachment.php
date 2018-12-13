<?php
/**
 * Attachment
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

use Illuminate\Database\Eloquent\Relations\Relation;

class Attachment extends AppModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'attachments';
    protected $fillable = array(
        'class',
        'foreign_id',
        'filename',
        'dir',
        'mimetype',
        'filesize',
        'height',
        'width',
        'thumb',
        'description'
    );
    public function foreign()
    {
        return $this->morphTo(null, 'class', 'foreign_id')->with('attachments');
    }
    protected static function boot()
    {
        Relation::morphMap(['UserAvatar' => User::class, 'ListingPhoto' => User::class, 'MessageContent' => MessageContent::class]);
        global $authUser;
        parent::boot();
        self::saved(function ($data) use ($authUser) {
            if (!empty($data['class']) && $data['class'] == 'ListingPhoto') {
                $count = Attachment::where('foreign_id', $data['foreign_id'])->where('class', $data['class'])->count();
                UserProfile::where('user_id', $data['foreign_id'])->update(['photo_count' => $count]);
            }
        });
        self::deleted(function ($data) use ($authUser) {
            if (!empty($data['class']) && $data['class'] == 'ListingPhoto') {
                $count = Attachment::where('foreign_id', $data['foreign_id'])->where('class', $data['class'])->count();
                UserProfile::where('user_id', $data['foreign_id'])->update(['photo_count' => $count]);
            }
        });
    }     
}
