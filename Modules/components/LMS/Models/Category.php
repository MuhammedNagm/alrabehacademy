<?php

namespace Modules\Components\LMS\Models;

use Modules\Foundation\Models\BaseModel;
use Modules\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Category extends BaseModel implements HasMedia
{
    use PresentableTrait, HasMediaTrait;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'lms.models.category';
    protected $table = "lms_categories";

    public $mediaCollectionName = 'lms-category-thumbnail';


//    protected static $logAttributes = ['name', 'slug'];
    private $descendants = [];
    private $parents = [];

    protected $guarded = ['id'];

        public function courses()
    {
        return $this->morphedByMany(Course::class, 'lms_categoriable');
    }

    public function quizzes()
    {
        return $this->morphedByMany(Quiz::class, 'lms_categoriable');
    }
    public function books()
    {
        return $this->morphedByMany(\Modules\Components\LMS\Models\Book::class, 'lms_categoriable');
    }



        public function child_plans()
    {
        return $this->morphedByMany(Plan::class, 'lms_categoriable');
    }

    public function plans()
    {
        return $this->morphToMany(Plan::class, 'lms_plannable');
    }

    // public function books()
    // {
    //     return $this->belongsToMany(Book::class,
    //         'lms_categoriables',
    //         "category_id",
    //         "lms_categoriable_id")
    //         ->where("lms_categoriable_type", "book");
    // }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = make_slug($value);
    }


    public function parentCategory()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function childrenCategories()
    {
        return $this->hasMany(self::class, "parent_id");
    }

         public function hasChildren(){
            if($this->childrenCategories->count()){
                return true;
            }

            return false;
        }

    public function findDescendants(Category $category){
            $this->descendants[] = $category->id;

            if($category->hasChildren()){
                foreach($category->childrenCategories()->where('status',1)->get() as $child){
                    $this->findDescendants($child);
                }
            }
        }

      public function getDescendants(Category $category){
            $this->findDescendants($category);
            return $this->descendants;
        }  

          public function findParents(Category $category){
            $this->parents[] = $category->id;

            if($category->parent_id){
                $category = $this->where('id',$category->parent_id)->first();
                if($category){
                    $this->findParents($category);
                }

                    
               
            }
        }

      public function getParents(Category $category){
            $this->findParents($category);
            return $this->parents;
        }





          
    /**
     * @return null|string
     * @throws \Spatie\MediaLibrary\Exceptions\InvalidConversion
     */
    public function getThumbnailAttribute()
    {
        $media = $this->getFirstMedia($this->mediaCollectionName);

        return asset('/img/default-cat.png');

        if ($media) {
            return $media->getUrl();
        } else {
            return asset(config($this->config . '.default_image'));
        }
    }

}
