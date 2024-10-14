@extends('layouts.crud.create_edit')

@section('css')
<style type="text/css">
    .display-flex{
        display: flex;
        align-items: flex-end;
        margin-bottom: 10px
    }
     .display-flex div:first-child{
        margin: 0 2px;
     }

</style> 
@endsection
 
@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('lms_lesson_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">


            {!! Form::model($lesson, ['url' => url($resource_url.'/'.$lesson->hashed_id),'method'=>$lesson->exists?'PUT':'POST','files'=>true,'class'=>'ajax-form']) !!}
            <div class="row">
                <div class="col-md-8">
                    @component('components.box', ['box_title' => __('LMS::attributes.main.general_head')])
                        {!! ModulesForm::text('title','LMS::attributes.main.name',true) !!}
                        {{-- {!! ModulesForm::text('slug','LMS::attributes.main.slug',true) !!} --}}
                        {!! ModulesForm::textarea('lesson_text',trans('LMS::attributes.lessons.input_lesson_text_intro'),false,null,['class'=>'ckeditor']) !!}
                     
                    @endcomponent

                    @component('components.box', ['box_title' => __('LMS::attributes.main.settings_head')])
                         <div style="display: flex;">
                                @php 
                                $lesson_type = [
                                    'standard' => __('LMS::attributes.lessons.standard'),
                                    'video' => __('LMS::attributes.lessons.video'),
                                    'quiz' => __('LMS::attributes.lessons.quiz'),
                                    'audio' => __('LMS::attributes.lessons.audio'),
                                    'docs' => __('LMS::attributes.lessons.docs'),
                                ]

                                
                                @endphp
                            {!! ModulesForm::select('type','LMS::attributes.lessons.lesson_type', $lesson_type,false,$lesson->exists?$lesson->type: 'standard') !!}
                        
                            </div>
                        <div class=" display-flex">
                            <div >
                                {!! ModulesForm::number('duration','LMS::attributes.lessons.lesson_duration',false,$lesson->exists?$lesson->duration: 0.00,['min'=>0])!!}   
                            </div> 
                            <div >
                                    @php 
                                    $duration_unit = [
                                        'minute' => __('LMS::attributes.main.minute'),
                                        'hour' => __('LMS::attributes.main.hour'),
                                        'day' => __('LMS::attributes.main.day'),
                                        'week' => __('LMS::attributes.main.week'),
                                    ]

                                    
                                    @endphp
                                {!! ModulesForm::select('duration_unit',' ', $duration_unit,false,$lesson->exists?$lesson->duration_unit:'minute') !!}
                            </div> 
                        </div>  
                        <br>
                      {{--   {!! ModulesForm::checkbox('private','LMS::attributes.lessons.lesson_private',false) !!} --}}

                      {!! ModulesForm::checkbox('show_lesson_intro','LMS::attributes.lessons.show_lesson_intro',$lesson->show_lesson_intro >= 1?true:false,true) !!}

                        {{-- {!! ModulesForm::checkbox('preview','LMS::attributes.lessons.lesson_preview',$lesson->preview >= 1?true:false,true) !!} --}}

                        {{-- {!! ModulesForm::checkbox('allow_comments','LMS::attributes.main.allow_comments',$lesson->allow_comments >= 1?true:false,true ) !!} --}}
                             
                    @endcomponent
                 @php
                 $lesson_parts = $lesson->lesson_parts()->get();
                 $text_explanation   = $lesson_parts->where('type', 'text_explanation')->first();
                 $video_explanation  = $lesson_parts->where('type', 'video_explanation')->first();
                 $slide_explanation  = $lesson_parts->where('type', 'slide_explanation')->first();
                 // $live_class_url     = $lesson_parts->where('type', 'live_class_url')->first();
                 $drafts_explanation = $lesson_parts->where('type', 'file_explanation');

                 @endphp

                 @component('components.box', ['box_title' => __('LMS::attributes.lessons.text_explanation')])
                 <input type="hidden" name="text_explanation[type]" value="text_explanation">

                        {!! ModulesForm::text('text_explanation[name]','LMS::attributes.main.title',true,$text_explanation?$text_explanation->name:'شرح نصي',['required' => 'true']) !!}
                        {{-- {!! ModulesForm::text('slug','LMS::attributes.main.slug',true) !!} --}}
                        {!! ModulesForm::textarea('text_explanation[content]',trans('LMS::attributes.main.content'),false,$text_explanation?$text_explanation->content:null,['class'=>'ckeditor']) !!}

                        {!! ModulesForm::radio('text_explanation[status]','LMS::attributes.main.status',true, trans('LMS::attributes.main.status_options'),$text_explanation?$text_explanation->status:0) !!}

                             
                    @endcomponent

{{--                 @component('components.box', ['box_title' => __('LMS::attributes.lessons.live_class_url')])
                 <input type="hidden" name="live_class_url[type]" value="live_class_url">

                        {!! ModulesForm::text('live_class_url[name]','LMS::attributes.main.title',true,$live_class_url?$live_class_url->name:'فيديو',['required' => 'true']) !!}
                         <input type="hidden" name="live_class_url[embeded_source]" value="google_driver">
                         <input type="hidden" name="live_class_url[embeded_type]" value="video">

                       {!! ModulesForm::text('live_class_url[embeded_url]','LMS::attributes.main.video_link',false,$live_class_url?$live_class_url->embeded_url:null) !!} --}}
                          {{-- <small> {{__('LMS::attributes.main.preview_video_hint')}} </small> --}}

 {{--                        {!! ModulesForm::radio('live_class_url[status]','LMS::attributes.main.status',true, trans('LMS::attributes.main.status_options'),$live_class_url?$live_class_url->status:0) !!}

                             
                @endcomponent --}}

                @component('components.box', ['box_title' => __('LMS::attributes.lessons.video_explanation')])
                 <input type="hidden" name="video_explanation[type]" value="video_explanation">

                        {!! ModulesForm::text('video_explanation[name]','LMS::attributes.main.title',true,$video_explanation?$video_explanation->name:'فيديو',['required' => 'true']) !!}
                         <input type="hidden" name="video_explanation[embeded_source]" value="google_driver">
                         <input type="hidden" name="video_explanation[embeded_type]" value="video">

                       {!! ModulesForm::text('video_explanation[embeded_url]','LMS::attributes.main.video_link',false,$video_explanation?$video_explanation->embeded_url:null) !!}
                          {{-- <small> {{__('LMS::attributes.main.preview_video_hint')}} </small> --}}

                        {!! ModulesForm::radio('video_explanation[status]','LMS::attributes.main.status',true, trans('LMS::attributes.main.status_options'),$video_explanation?$video_explanation->status:0) !!}

                             
                    @endcomponent

                    @component('components.box', ['box_title' => __('LMS::attributes.lessons.slide_explanation')])
                    <input type="hidden" name="slide_explanation[type]" value="slide_explanation">

                     {!! ModulesForm::text('slide_explanation[name]','LMS::attributes.main.title',true,$slide_explanation?$slide_explanation->name:'اسلايد',['required' => 'true']) !!}
                         <input type="hidden" name="slide_explanation[embeded_source]" value="google_driver">
                         <input type="hidden" name="slide_explanation[embeded_type]" value="slide">
                     {!! ModulesForm::text('slide_explanation[embeded_url]','LMS::attributes.main.slide_link',false,$video_explanation?$video_explanation->embeded_url:null) !!}
                          {{-- <small> {{__('LMS::attributes.main.preview_video_hint')}} </small> --}}

                     {!! ModulesForm::radio('slide_explanation[status]','LMS::attributes.main.status',true, trans('LMS::attributes.main.status_options'),$slide_explanation?$slide_explanation->status:0) !!}

                             
                    @endcomponent   

{{--   @component('components.box', ['box_title' => __('LMS::attributes.lessons.drafts_explanation')])
<div class="row">
     <div class="col-md-12">
                               

        <div class="table-responsive">
            <table id="values-table" width="100%" class="table table-striped">
                <thead>
                <tr>
                    <th> العنوان</th>
                     <th>الرابط</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr></tr>

                @if($drafts_explanation->count())

                    @foreach($drafts_explanation as $file)
                        <tr id="tr_{{ $loop->index }}" data-index="{{ $loop->index }}">

                            <td>
                                <div class="form-group">

                                    <input name="quiz_videos[{{ $loop->index }}][title]" type="text"
                                           value="{{ $file->title }}" class="form-control"/>
                                </div>
                            </td>

      <td>
                                <div class="form-group">

                                    <input name="quiz_videos[{{ $loop->index }}][file_url]" type="text"
                                           value="{{ $file->file_url }}" class="form-control"/>
                                </div>
      </td>
      <td>
            
                                <button type="button" class="btn btn-danger btn-sm remove-value" style="margin:0;"
                                        data-index="{{ $loop->index }}"><i
                                            class="fa fa-minus-circle"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @else
                <tr></tr>


                @endif
                </tbody>
            </table>
        </div>

        <button type="button" class="btn btn-success btn-sm" id="add-value" data-input="checkbox">
            <i class="fa fa-plus"></i> اضافة رابط
        </button>

    </div>
</div>
       
  @endcomponent  --}} 
 
                </div>
                <div class="col-md-4">
                    @component('components.box')
                    {!! ModulesForm::text('preview_video','LMS::attributes.lessons.input_lesson_video_intro',false) !!}
                    <small> {{__('LMS::attributes.main.preview_video_hint')}} </small>
                    @endcomponent
                    @component('components.box')
                        @if($lesson->hasMedia($lesson->mediaCollectionName))
                            <img src="{{ $lesson->thumbnail }}" class="img-responsive" style="max-width: 100%;"
                                 alt="Thumbnail"/>
                            <br/>
                            {!! ModulesForm::checkbox('clear', 'LMS::attributes.main.clear') !!}
                        @endif
                        {!! ModulesForm::file('thumbnail', 'LMS::attributes.main.featured_image') !!}

                        {!! ModulesForm::select('categories[]','LMS::attributes.main.categories', \LMS::getCategoriesList(),false,null,['multiple'=>true], 'select2') !!}

                       {{-- {!! ModulesForm::select('tags[]','LMS::attributes.main.tags', \LMS::getTagsList(),false,null,['class'=>'tags','multiple'=>true], 'select2') !!} --}}
                        {!! ModulesForm::radio('status','LMS::attributes.main.status',true, trans('LMS::attributes.main.status_options'),$lesson->exists?$lesson->status:1) !!}

                        <div>

                          @include('LMS::partials.youtube_url')


{{-- <iframe width="100%" height="300" src="//jsfiddle.net/egytech/brdhm17n/25/embedded/result/" allowfullscreen="allowfullscreen" allowpaymentrequest frameborder="0"></iframe> --}}
                 @endcomponent

                    
 

                    <div class="clearfix"></div>

                </div>
            </div>
        

            <div class="row">
                @component('components.box')

                    {!! ModulesForm::customFields($lesson) !!}

                    <div class="row">
                        <div class="col-md-12">
                            {!! ModulesForm::formButtons() !!}
                        </div>
                    </div>
                @endcomponent
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@endsection

@section('js')
@endsection