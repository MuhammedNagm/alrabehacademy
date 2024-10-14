<?php

namespace Modules\Components\LMS\Models;

use Modules\Foundation\Models\BaseModel;
use Modules\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\Filesystem\Filesystem;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;


class StudentResult extends BaseModel implements HasMedia
{
    use PresentableTrait, HasMediaTrait;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'lms.models.student_result';
    protected $table = "lms_students_results";
    public $mediaCollectionName = 'lms-student-result-thumbnail';

//    protected static $logAttributes = ['title', 'slug'];
  

    protected $guarded = ['id'];


  public function categories()
    {
        return $this->morphToMany(Category::class, 'lms_categoriable');
    }


    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }



         /**
     * @return null|string
     * @throws \Spatie\MediaLibrary\Exceptions\InvalidConversion
     */
    public function getThumbnailAttribute()
    {
        $media = $this->getFirstMedia($this->mediaCollectionName);

        if ($media) {
            return $media->getUrl();
        } else {
            return asset(config($this->config . '.default_image'));
        }
    }






}
