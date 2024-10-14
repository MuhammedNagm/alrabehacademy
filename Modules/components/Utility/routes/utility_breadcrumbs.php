<?php

//location
Breadcrumbs::register('address_locations', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Utility::module.location.title'), url(config('utility.models.location.resource_url')));
});

Breadcrumbs::register('address_location_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('address_locations');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//Tag
Breadcrumbs::register('tags', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Utility::module.tag.title'), url(config('utility.models.tag.resource_url')));
});

Breadcrumbs::register('tag_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('tags');
    $breadcrumbs->push(view()->shared('title_singular'));
});


//Category
Breadcrumbs::register('utility_categories', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Utility::module.category.title'), url(config('utility.models.category.resource_url')));
});

Breadcrumbs::register('utility_category_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('utility_categories');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//attribute
Breadcrumbs::register('utility_attributes', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Utility::module.attribute.title_singular'), url(config('utility.models.attribute.resource_url')));
});

Breadcrumbs::register('utility_attribute_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('utility_attributes');
    $breadcrumbs->push(view()->shared('title_singular'));
});