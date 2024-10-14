<div class="modal fade" id="remainTimeModal" tabindex="-1" role="dialog" aria-labelledby="remainTimeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background: #fff">

      <div class="modal-body">

        <div class="alert alert-danger">
    @if(\Request::segment(1) == 'courses')
     <strong>تنبية !</strong> 
</div>
    @else
  <strong>تنبية !</strong> اوشك وقت الاختبار على الانتهاء هل تريد عرض الاسئلة المؤجلة قبل انتهاء الوقت؟
</div>
@endif

      </div>
      <div class="modal-footer">
      	 <a href="@if(isset($quizLogs) && $quizLogs) {{route('quizzes.delayed_questions', ['quiz' => $quiz->hashed_id, 'quiz_logs' => $quizLogs->hashed_id])}} @endif" class="btn btn-danger" style="margin-left: 10px; margin-right: 10px;">
                عرض الاسئلة المؤجلة
                    </button>
        <a href="javascript:;"  class="btn btn-secondary" data-dismiss="modal">@lang('LMS::attributes.main.label_cancel')</a>

      </div>

    </div>
  </div>
</div>
