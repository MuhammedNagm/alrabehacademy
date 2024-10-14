<?php

namespace Modules\Components\Utility\Providers;

use Modules\Components\Utility\Models\Category\Attribute;
use Modules\Components\Utility\Models\Category\Category;
use Modules\Components\Utility\Models\Rating\Rating;
use Modules\Components\Utility\Models\Tag\Tag;
use Modules\Components\Utility\Models\Wishlist\Wishlist;
use Modules\Components\Utility\Policies\Category\AttributePolicy;
use Modules\Components\Utility\Policies\Category\CategoryPolicy;
use Modules\Components\Utility\Policies\Rating\RatingPolicy;
use Modules\Components\Utility\Policies\Tag\TagPolicy;
use Modules\Components\Utility\Policies\Wishlist\WishlistPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class UtilityAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Rating::class => RatingPolicy::class,
        Wishlist::class => WishlistPolicy::class,
        Tag::class => TagPolicy::class,
        Attribute::class => AttributePolicy::class,
        Category::class => CategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
