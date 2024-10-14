<?php

namespace Modules\Components\Utility\Traits\Category;


use Modules\Components\Utility\Models\Category\Category;
use Modules\Components\Utility\Models\Category\ModelOption;
use Illuminate\Database\Eloquent\Model;

trait ModelHasCategory
{
    public static function bootModelHasCategory()
    {
        static::deleted(function (Model $deletedModel) {

            $deletedModel->categories()->detach();

        });
    }

    public function categories()
    {
        return $this->morphToMany(
            Category::class,
            'model',
            'utility_model_has_category',
            'model_id',
            'category_id'
        );
    }

    public function activeCategories()
    {
        return $this->categories()->where('utility_categories.status', 'active');
    }

    public function options()
    {
        return $this->morphMany(
            ModelOption::class,
            'model'
        );
    }
}
