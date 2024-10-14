@extends('layouts.crud.create_edit')
 
@section('css')
  <style>
    .display-flex{
        display: flex;
        align-items: flex-end;
        margin-bottom: 10px
    }
     .display-flex div:first-child{
        margin: 0 2px;
     }
     .flex{
        display: flex;
     }
     .flex div:first-child{
        margin-left: 10px;
     }
      @media(max-width: 360px){
        .flex{
            flex-direction: column;

        }
  </style>
@endsection

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('lms_quiz_create_edit') }}
        @endslot
    @endcomponent
@endsection 

@section('content')
    <div class="row">
        <div class="col-md-12">

            {!! Form::model($quiz, ['url' => url($resource_url.'/'.$quiz->hashed_id),'method'=>$quiz->exists?'PUT':'POST','files'=>true,'class'=>'']) !!}
            <div class="row">
                <div class="col-md-8">
                    @component('components.box', ['box_title' => __('LMS::attributes.quizzes.general_head')])
                        {!! ModulesForm::text('title','LMS::attributes.main.name',true) !!}
                        {{-- {!! ModulesForm::text('slug','LMS::attributes.main.slug',true) !!} --}}
                        {!! ModulesForm::textarea('content',trans('LMS::attributes.main.content'),true,null,['class'=>'ckeditor']) !!}
                     
                    @endcomponent
              
                
                   

                    @component('components.box', ['box_title' => __('LMS::attributes.main.settings_head')])
                    <div class="row">
                      <div class="col-md-12">
                        @php
                        $check_answers = [1 => 'نعم',
                        0 => 'لا'];
                        @endphp
                        {!! ModulesForm::radio('show_check_answer','LMS::attributes.quizzes.can_check_answer',false, $check_answers, $quiz->show_check_answer?:1) !!}
                      </div>
                    </div>
<div class="row">
    <div class="col-md-6"> 
       {{-- {!! ModulesForm::checkbox('show_questions_title','LMS::attributes.quizzes.show_questions_title',$quiz->show_questions_title?:false) !!} --}}
    
                            </div> 
                            <input type="hidden" name="question_per_page" value="1">
{{--                     <div class="col-md-6"> 
                                {!! ModulesForm::number('question_per_page','LMS::attributes.quizzes.question_per_page',false, $quiz->exists?$quiz->question_per_page: 1,['min'=>1])!!}  
                            </div>  --}}
                          </div>
                         
                        <div class=" display-flex">
                            <div> 
                                {!! ModulesForm::number('duration','LMS::attributes.quizzes.quiz_duration',false, $quiz->exists?$quiz->duration: 0,['min'=>0])!!}  
                            </div> 
                            <div>
                                    @php 
                                    $duration_unit = [
                                        'minute' => __('LMS::attributes.main.minute'),
                                        // 'hour' => __('LMS::attributes.main.hour'),
                                        // 'day' => __('LMS::attributes.main.day'),
                                        // 'week' => __('LMS::attributes.main.week'),
                                    ]

                                    
                                    @endphp
                                {!! ModulesForm::select('duration_unit',' ', $duration_unit,false,$quiz->duration_unit?:'minute') !!}
                            </div> 
                        </div>  

                        <div class="flex">
                          {!! ModulesForm::number('passing_grade','LMS::attributes.quizzes.passing_grade',false,$quiz->exists?$quiz->passing_grade:'50',['min'=>0])!!} 
                          {!! ModulesForm::number('retake_count','LMS::attributes.quizzes.retake_count',false,$quiz->exists?$quiz->retake_count:'100000',['min'=>0])!!}
                        </div>
                         <input type="hidden" name="price" value="0">

{{--   <div class="flex">
       {!! ModulesForm::number('price','LMS::attributes.main.price',true,null,['min'=>0])!!}
                                {!! ModulesForm::number('sale_price','LMS::attributes.courses.sale_price',false,null,['min'=>0])!!}
                            </div> --}}
                            <hr>
                            @php
                            if(!$sub_quiz){
                             $sub_quiz = new \Modules\Components\LMS\Models\Quiz;
                            }
                            @endphp

                            <div class="row">
                    <div class="col-md-7">
                                            <div class=" display-flex">
                            <div> 
                        {!! ModulesForm::number('sub_quiz[sub_quiz_questions_num]','LMS::attributes.quizzes.sub_quiz_questions_num',false, $sub_quiz->sub_quiz_questions_num?: 0,['min'=>0])!!}  
                      </div>
                                                  <div>
                                    @php 
                                    $sub_quiz_type = [
                                        0 => 'الاسئلة مرتبة',
                                        1 => 'اسئلة عشوائية',
   
                                    ]

                                    
                                    @endphp
                                {!! ModulesForm::select('sub_quiz[sub_quiz_random]',' ', $sub_quiz_type,false,$sub_quiz->sub_quiz_random?:0) !!}
                            </div> 
                    </div>

                        
                      </div>
                              <div class="col-md-5">
                      <div class=" display-flex">
                            <div> 
                                {!! ModulesForm::number('sub_quiz[duration]','LMS::attributes.quizzes.sub_quiz_duration',false, $sub_quiz->duration?: 1,['min'=>1])!!}  
                            </div> 
                            <div>
                                    @php 
                                    $duration_unit = [
                                        'minute' => __('LMS::attributes.main.minute'),
                                        // 'hour' => __('LMS::attributes.main.hour'),
                                        // 'day' => __('LMS::attributes.main.day'),
                                        // 'week' => __('LMS::attributes.main.week'),
                                    ]

                                    
                                    @endphp
                                {!! ModulesForm::select('sub_quiz[duration_unit]',' ', $duration_unit,false,$sub_quiz->duration_unit?:'minute') !!}
                            </div> 
                        </div> 

                      </div>

                      </div>

                      <hr>



{{--                             <div class="row">
                              <div class="col-md-6">
                            {!! ModulesForm::select('author_id','LMS::attributes.courses.author', \LMS::getAuthorsList(),true) !!}
                            </div>
                            </div> --}}
                       {{--  {!! ModulesForm::checkbox('preview','LMS::attributes.quizzes.quiz_preview',$quiz->preview >= 1?true:false,true) !!}

                        {!! ModulesForm::checkbox('pagination_questions','LMS::attributes.quizzes.pagination_questions',$quiz->pagination_questions >= 1?true:false) !!}

                        {!! ModulesForm::checkbox('review_questions','LMS::attributes.quizzes.review_questions',$quiz->review_questions >= 1?true:false) !!}

                        

                        {!! ModulesForm::checkbox('skip_question','LMS::attributes.quizzes.skip_question',$quiz->skip_question >= 1?true:false) !!}

                        {!! ModulesForm::checkbox('show_check_answer','LMS::attributes.quizzes.show_check_answer',$quiz->show_check_answer >= 1?true:false) !!}

                        {!! ModulesForm::checkbox('show_hint','LMS::attributes.quizzes.show_hint',$quiz->show_hint >= 1?true:false) !!}

                        {!! ModulesForm::checkbox('allow_comments','LMS::attributes.main.allow_comments',$quiz->allow_comments >= 1?true:false) !!}
                         --}}
                        
                    @endcomponent
               @component('components.box', ['box_title' => __('LMS::attributes.quizzes.videos_explanations')])
                  <small> {{__('LMS::attributes.main.preview_video_hint')}} </small>
                      

      <div class="col-md-12">
                               

        <div class="table-responsive">
            <table id="values-table" width="100%" class="table table-striped">
                <thead>
                <tr>
                    <th{{--  width="40%" --}}> العنوان</th>
                     <th>الرابط</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr></tr>
                @php
                $quiz_videos = $quiz->hint_videos()->get();
                @endphp
                @if($quiz_videos->count())

                    @foreach($quiz_videos as $video)
                        <tr id="tr_{{ $loop->index }}" data-index="{{ $loop->index }}">

                            <td>
                                <div class="form-group">

{{-- <input type="hidden" name="quiz_videos[{{ $loop->index }}][video_id]" value="{{$video->id}}"> --}}
                                    <input name="quiz_videos[{{ $loop->index }}][title]" type="text"
                                           value="{{ $video->title }}" class="form-control"/>
                                </div>
                            </td>

      <td>
                                <div class="form-group">

                                    <input name="quiz_videos[{{ $loop->index }}][file_url]" type="text"
                                           value="{{ $video->file_url }}" class="form-control"/>
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
                      @endcomponent

                        @component('components.box', ['box_title' => __('LMS::attributes.quizzes.add_paragraph')])
                  <div class="row">
                    <div class="col-md-6">
                      @php
                      // dd($quiz->questions()->where('lms_questions.question_type', 'paragraph')->pluck('lms_questions.id')->toArray());
                      @endphp
                              {!! ModulesForm::select('paragraphs[]','LMS::attributes.quizzes.add_paragraphs_from_exists',\LMS::getQuestionsParagraphsList(),false,$quiz->questions()->where('question_type', 'paragraph')->pluck('lms_questions.id')->toArray(),['multiple'=>"multiple"],'select2') !!}
{{--                         {!! ModulesForm::select('paragraphs[]','LMS::attributes.quizzes.add_paragraphs_from_exists', [], false, null,
                        ['class'=>'select2-ajax','multiple'=>"multiple",'data'=>[
                        'model'=>\Modules\Components\LMS\Models\Question::class,
                        'columns'=> json_encode(['title']),
                        'selected'=>json_encode($quiz->questions()->where('question_type', 'paragraph')->pluck('lms_questions.id')->toArray()),
                        ]],'select2') !!} --}}
                    </div>
                </div>
                <hr>
                <h4> <input type="checkbox" name="create_new_paragraph" value="1"> {{__('LMS::attributes.quizzes.create_new_paragraph')}}</h4>

                     <div class="row" style="background-color: #c9e6ff; padding: 20px;">
                      <div class="col-sm-12">
                            {!! ModulesForm::text('create_paragraph[title]','LMS::attributes.main.title',true) !!}

                        {!! ModulesForm::textarea('create_paragraph[content]',trans('LMS::attributes.main.content'),true,null,['class'=>'ckeditor']) !!}
                        <input type="hidden" value="paragraph" name="create_paragraph[question_type]">

                          </div>
                      </div>     
{{--                         <div class="row">
                          <div class="col-sm-12">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                  <a href="javascript:;" class="margin btn btn-block btn-info btn-sm" onclick="createQuestionModal()">
                                    @lang('LMS::attributes.quizzes.add_as_new')
                                  </a>
                                </div>
                                   <div class="col-md-6 col-sm-6 col-xs-6">
                                  <a href="javascript:;" class="margin btn btn-block btn-success btn-sm" onclick="selectQuestionModal()">
                                    @lang('LMS::attributes.quizzes.select')
                                  </a>
                                </div>
                              </div>
                              </div> --}}

                                <hr>
                                 <div class="row">
                                   <div class="col-sm-12">
                                     <ul id="questions-list-group"> 
{{--                                       @if(!empty($quiz->questions))
                                @foreach($quiz->questions()->orderBy('pivot_order', 'asc')->get() as $question)

                                      <li id="liQ_{{$question->id }}" class="list-group-item  sortable"> 
                                      <span> <i class="fa fa-arrows-alt  grabbing handle"></i> {{ str_limit(strip_tags($question->content), 100, '...')  }}  <input  class="get-questions-list" type="hidden" value="{{ $question->id }}">  </span>
                                      
                                      <span class="pull-right dd-nodrag"><div class="item-actions">
                                        <div class="btn-group pull-right">
                                          <button type="button" class="btn btn-sm btn-default dropdown-toggle" style="padding: 2px 8px 0 8px;"
                                          data-toggle="dropdown"
                                          aria-expanded="false"><i class="fa fa-ellipsis-v" style="font-size: 1.2em;"></i></button>
                                          <ul class="dropdown-menu" role="menu">
                                            <li>
                                              <a href="javascript:;" class=""  onclick="editQuestionModal('{{ $question->id }}')" data-id="{{ $question->id }}"><i class="fa fa-pen"></i> {{__('LMS::attributes.main.edit')}} </a>
                                           
                                          </li>
                                          <li>
                                         <a href="javascript:;"  data-id="{{ $question->id }}" onclick="removeItem('{{ $question->id }}')"><i class="fa fa-minus-circle"></i> {{__('LMS::attributes.main.remove')}}</a>

                                        </li>
                                      
                                  </ul>
                                </div>
                              </div>
                            </span>
                                        
                                      </li> 
                                      @endforeach 
                                          
                                      @endif --}}
                                      <div class="addSortRow"></div>
                                
                             </ul> 
                             </div>
                        </div>
                        <!-- /.row -->
                    @endcomponent
                               
                  </div>
                  <div class="col-md-4">

                    @component('components.box')
                      {!! ModulesForm::text('preview_video','LMS::attributes.main.preview_video',false) !!}
                      <small> {{__('LMS::attributes.main.preview_video_hint')}} </small>
                      @endcomponent
                      @component('components.box')
                        
                        @if($quiz->hasMedia($quiz->mediaCollectionName))
                            <img src="{{ $quiz->thumbnail }}" class="img-responsive" style="max-width: 100%;"
                                 alt="Thumbnail"/>
                            <br/>
                            {!! ModulesForm::checkbox('clear', 'LMS::attributes.main.clear') !!}
                        @endif
                        {!! ModulesForm::file('thumbnail', 'LMS::attributes.main.featured_image') !!}

                        
                          {!! ModulesForm::select('categories[]','LMS::attributes.main.category', \LMS::getCategoriesList(),false,null,['multiple'=>false], 'select2') !!}
                             {!! ModulesForm::select('tags[]','LMS::attributes.main.tags', \LMS::getTagsList(),false,null,['class'=>'tags','multiple'=>true], 'select2') !!}

                          {!! ModulesForm::radio('show_in_plan','LMS::attributes.main.show_in_plan',false, trans('LMS::attributes.main.yes_no'),$quiz->exists?$quiz->show_in_plan:1, []) !!}   
                        
                          {!! ModulesForm::radio('status','LMS::attributes.main.status',true, trans('LMS::attributes.main.status_options'),1) !!}
                          <br>
                          @if(!$quiz->exists)
                          {!! ModulesForm::checkbox('numbers_in_arabic','LMS::attributes.main.numbers_in_arabic',true) !!}
                          @else
                          {!! ModulesForm::checkbox('numbers_in_arabic','LMS::attributes.main.numbers_in_arabic',$quiz->numbers_in_arabic >= 1?true:false) !!}
                          @endif
                          <br>
                          @if(!$quiz->exists)
                           {!! ModulesForm::checkbox('is_featured','LMS::attributes.main.featured',true) !!}
                            @else
                            {!! ModulesForm::checkbox('is_featured','LMS::attributes.main.featured',$quiz->is_featured >= 1?true:false) !!}
                            @endif
                          <br>
                          @if(!$quiz->exists)
                          {!! ModulesForm::checkbox('is_standlone','LMS::attributes.quizzes.is_standlone',true) !!}
                          @else
                          {!! ModulesForm::checkbox('is_standlone','LMS::attributes.quizzes.is_standlone',$quiz->is_standlone >= 1?true:false) !!}
                          @endif
                      @endcomponent
                      
                      <div class="clearfix"></div>
                  </div>

            </div>
        

            <div class="row">
                @component('components.box')

                    {!! ModulesForm::customFields($quiz) !!}

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

@include('LMS::quizzes.partials.modals')


@section('js')
<script src="/assets/themes/admin/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/assets/themes/admin/plugins/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
  $( function() {
    $( "#questions-list-group" ).sortable({ 
        handle : '.handle', 
    }); 
});
</script>

@include('LMS::quizzes.partials.ajax_crud_question')

<script type="text/javascript">


  $('body').on('submit', '.ajax-quiz-form-2', function (event) {
    event.preventDefault();

      var arr_questions_ids = [];

    $('.get-questions-list').each(function(i, k){
          arr_questions_ids.push($(k).val());
        
});


    var questions_input = $('<input>').attr({
    type: 'hidden',
    id: 'arr_questions_ids',
    name: 'arr_questions_ids',
   }).appendTo('form');

    questions_input.val(arr_questions_ids);

    $form = $(this);

    ajax_form($form);
});



</script>

<script type="text/javascript">
      $('body').on('click', '.disable-answer-btn', function () {
        var index = $(this).data('index');
        $("#tr_" + index).remove();
    });
</script>

  <script>
          function removeItem(id){
        $.ajax({url: "/lms/ajax/questions/"+id+"/{{$quiz_session_id}}/remove_from_quiz", success: function(result){
             $("#liQ_" + id).remove();
        }});
          }     
          
    </script>

  <script>

    

       function selectQuestionModal() {
             var contentBody = $('#crudQuestionModal').modal('show').find('.modal-body');
             $.ajax({url: "{{ route('ajax.questions.list', ['session_id' => $quiz_session_id]) }}", success: function(result){
              $(contentBody).html(result);
              
            
        }});
        }

        function createQuestionModal() {
             var contentBody = $('#crudQuestionModal').modal('show').find('.modal-body');
             $.ajax({url: "{{ route('ajax.questions.create', ['session_id' => $quiz_session_id]) }}", success: function(result){
              $(contentBody).html(result);
               CKEDITOR.replaceAll('ckeditor2');
               // getCkeditorBasics();
 
        }});
        }

        function editQuestionModal(id) {
             var contentBody = $('#crudQuestionModal').modal('show').find('.modal-body');
             $.ajax({url: "/lms/ajax/questions/"+id+"/{{$quiz_session_id}}/edit", success: function(result){
              $(contentBody).html(result);
              CKEDITOR.replaceAll('ckeditor2');
              getCkeditorBasics();
        }});
        }

    </script>

    <script type="text/javascript">
      function  getCkeditorBasics(){
        CKEDITOR.config.toolbar = [
   ['Styles','Format','Font','FontSize'],
   '/',
   ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','Find','Replace','-','Outdent','Indent','-','Print'],
   '/',
   ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
   ['Image','Table','-','Link','Flash','Smiley','TextColor','BGColor','Source','maximize']
] ;
      }
    </script>


    {{-- explanisions videos --}}

    <script type="text/javascript">

        rates_init = function () {



            if ($("#values-table").length > 0) {
                $(document).on('click', '#add-value', function () {

                    var index = $('#values-table tr:last').data('index');
                    var inputType = $("#add-value").attr('data-input');
                    if (isNaN(index)) {
                        index = 0;
                    } else {
                        index++;
                    }

                    $('#values-table tr:last').after('<tr id="tr_' + index + '" data-index="' + index + '"><td><div class="form-group"><input name="quiz_videos[' + index + '][title]" type="text"' +
                        'value="" class="form-control"/></div></td>'+ '<td><div class="form-group"><input name="quiz_videos[' + index + '][file_url]" type="text"' +
                        'value="" class="form-control"/></div></td>' + 

                        // '<td><div class="form-group"><div class="upload-file-area" data-input="thumbnail"><span class="btn btn-info btn-file ">{{-- {!!__('Zidan::labels.browse') !!} --}}<input accept=".xlsx,.xls,image/*,.doc, .docx,.ppt, .pptx,.txt,.pdf" class="form-control " id="thumbnail" name="new_rates[' + index + '][thumbnail]" type="file"></span>&nbsp;&nbsp;<span class="file-name"></span><br><br><img src="#" alt="" class="preview hidden" width="100"></div></div></td>' +
                        // '<input name="new_rates[' + index + '][is_correct]" class="input_type" type=' + inputType +
                        // ' value="1" /></td>' +
                        '<td><div class="form-group"><button type="button" class="btn btn-danger btn-sm remove-value" style="margin:0;" data-index="' + index + '">'
                        + '<i class="fa fa-minus-circle"></i></button></div></td>' +
                        '</tr>');
                });

                $(document).on('click', '.remove-value', function () {
                    var index = $(this).data('index');
                    $("#tr_" + index).remove();
                });
            }
        };

        window.initFunctions.push('rates_init');
</script>
@endsection
