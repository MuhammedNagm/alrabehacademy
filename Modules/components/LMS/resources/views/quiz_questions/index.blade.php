@extends('layouts.crud.index')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            <span style="color: #673ab7;">{{'اختبار: '.$quiz->title}}</span> - {{ $title }}
        @endslot
     
        @slot('breadcrumb')
            {{ Breadcrumbs::render('lms_quiz_questions', $quiz) }}
        @endslot
    @endcomponent
@endsection

@if($paragraph_hashed = \Request::get('paragraph'))
@php
$hideCreate = true;
@endphp

@section('actions')
@parent
       {!! ModulesForm::link(url($resource_url.'/create?paragraph='.$paragraph_hashed), trans('Modules::labels.create'),['class'=>'btn btn-success']) !!}
   

@endsection
@endif

@push('before_actions_btns')
<a class="btn btn-default" id="add_session_to_quiz" data-modal="add_session_ids_to_quiz_modal"><i class="fa fa-fw fa-plus"></i>اضف الى اختبار</a>
@endpush

@push('after_content')
  <!-- Modal -->
  <div class="modal fade" id="add_session_ids_to_quiz_modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <p>انتظر قليلاً ... .</p>
        </div>

      </div>
      
    </div>
  </div>
@endpush

@push('child_script')
<script type="text/javascript">
	$(document).ready(function(){
		$('#add_session_to_quiz').on('click', function(e){

			var contentBody = $("#add_session_ids_to_quiz_modal").modal('show').find('.modal-body');

	   $.ajax({url: "{{url('/lms/quizzes/show_quizzes_select2_list')}}", success: function(result){
              $(contentBody).html(result);
          }});
			
			


		})

	});
</script>

@endpush