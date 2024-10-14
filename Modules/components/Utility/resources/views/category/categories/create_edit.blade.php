@extends('layouts.crud.create_edit')

@section('css')
@endsection

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('utility_category_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-8">
            @component('components.box')
                {!! ModulesForm::openForm($category) !!}
                <div class="row">
                    <div class="col-md-6">
                        {!! ModulesForm::text('name','Utility::attributes.category.name',true) !!}
                        {!! ModulesForm::text('slug','Utility::attributes.category.slug',true) !!}
                        {!! ModulesForm::radio('status','Modules::attributes.status',true, trans('Modules::attributes.status_options')) !!}
                        {!! ModulesForm::select('module','Utility::attributes.tag.module', \Utility::getUtilityModules()) !!}
                        {!! ModulesForm::select('parent_id', 'Utility::attributes.category.parent_cat', \Category::getCategoriesList(null,true, false, null, $category->exists?[$category->id]:[]), false, null, [], 'select2') !!}
                        {!! ModulesForm::checkbox('is_featured', 'Utility::attributes.category.is_featured', $category->is_featured) !!}
                        {!! ModulesForm::select('category_attributes[]','Utility::attributes.category.attributes', \Category::getAttributesList(),
                        false, $category->categoryAttributes()->pluck('attribute_id'), ['multiple'=>true], 'select2') !!}
                    </div>
                    <div class="col-md-6">
                        @if($category->hasMedia($category->mediaCollectionName))
                            <img src="{{ $category->thumbnail }}" class="img-responsive" style="max-width: 100%;"
                                 alt="Thumbnail"/>
                            <br/>
                            {!! ModulesForm::checkbox('clear', 'Utility::attributes.category.clear') !!}
                        @endif
                        {!! ModulesForm::file('thumbnail', 'Utility::attributes.category.thumbnail') !!}
                        {!! ModulesForm::textarea('description','Utility::attributes.category.description') !!}
                    </div>
                </div>

                {!! ModulesForm::customFields($category, 'col-md-6') !!}

                <div class="row">
                    <div class="col-md-12">
                        {!! ModulesForm::formButtons() !!}
                    </div>
                </div>

                {!! ModulesForm::closeForm($category) !!}
            @endcomponent
        </div>
    </div>
@endsection
