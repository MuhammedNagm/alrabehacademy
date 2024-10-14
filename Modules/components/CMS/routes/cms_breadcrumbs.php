<?php

// CMS
Breadcrumbs::register('cms', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('CMS::module.cms.title'));
});

//Page
Breadcrumbs::register('pages', function ($breadcrumbs) {
    $breadcrumbs->parent('cms');
    $breadcrumbs->push(trans('CMS::module.page.title'), url(config('cms.models.page.resource_url')));
});

Breadcrumbs::register('page_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('pages');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('page_show', function ($breadcrumbs) {
    $breadcrumbs->parent('pages');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//Post
Breadcrumbs::register('posts', function ($breadcrumbs) {
    $breadcrumbs->parent('cms');
    $breadcrumbs->push(trans('CMS::module.post.title'), url(config('cms.models.post.resource_url')));
});

Breadcrumbs::register('post_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('posts');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('post_show', function ($breadcrumbs) {
    $breadcrumbs->parent('posts');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//News
Breadcrumbs::register('news', function ($breadcrumbs) {
    $breadcrumbs->parent('cms');
    $breadcrumbs->push(trans('CMS::module.news.title'), url(config('cms.models.news.resource_url')));
});

Breadcrumbs::register('news_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('news');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('news_show', function ($breadcrumbs) {
    $breadcrumbs->parent('news');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//Category
Breadcrumbs::register('categories', function ($breadcrumbs) {
    $breadcrumbs->parent('cms');
    $breadcrumbs->push(trans('CMS::module.category.title'), url(config('cms.models.category.resource_url')));
});

Breadcrumbs::register('category_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('categories');
    $breadcrumbs->push(view()->shared('title_singular'));
});