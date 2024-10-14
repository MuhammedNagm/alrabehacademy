   @php
    $firstTeacher = $teachers->first();
   @endphp
    {!! Form::model($quiz, ['url' => url('ajax/ask_teacher/send'),'method'=>'POST','files'=>true,'class'=>'ajax-ask-teacher']) !!}
    <div class="alert-ask-teacher-message alert alert-danger" style="display: none;">
  <strong>خطأ!</strong> يجب ملء خانة الرسالة قبل الإرسال.
</div>
        <div class="media">
          <img class="mr-2 teacher-img" src="{{$firstTeacher->picture_thumb}}" alt="{{$firstTeacher->name}}" width="50" height="50">
          <div class="media-body">
      <h5 class="mt-0 mb-0"><span class="teacher-name">{{$firstTeacher->name}}</span>   
            <div class="btn-group">
  <button class="btn  btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>     

  <div class="dropdown-menu dropdown-menu-right" style="width: 253px;">
    
    @foreach($teachers as $row)
        <a class="choose-teacher-to-chat dropdown-item" href="javascript:;" data-teacher_id="{{$row->hashed_id}}" data-job_title="{{$row->job_title}}" data-teacher_name="{{$row->name}}" data-teacher_image="{{$row->picture_thumb}}"><i class="fa fa-user"></i> {{$row->name}} <br> <small>{{$row->job_title}}</small></a>
    @endforeach    

</div>
</div>
</h5> 
            
              
            <small class="teacher-job-title">{{$firstTeacher->job_title}}</small>
          </div>
        </div>
        <div class="mt-3 col-sm-12">
          <div class="border bg-light p-2 mb-2">
            <p>{{ str_limit(strip_tags($question->content),75, '...') }}</p>
            <hr>
            <small><span style="font-weight: bold;"> الاختبار : </span> {{$quiz->title}}</small>
            <input type="hidden" name="quiz_id" value="{{$quiz->hashed_id}}">
            <input type="hidden" name="question_id" value="{{$question->hashed_id}}">
            <input type="hidden" name="question_content" value="{{ str_limit(strip_tags($question->content),75, '...') }}">
            <input type="hidden" name="_id" value="{{$firstTeacher->hashed_id}}" class="input-id">
          </div>
          <div >
            <textarea row="4" class="form-control rounded-0" placeholder="اكتب استفسارك هنا" name="message-data" id="ask-message-data" required=""></textarea>
          </div>
        </div>
{!! Form::close() !!}


<script type="text/javascript">
    $( function() {  
    $('body').on('click','.choose-teacher-to-chat', function() {
      var btn = $(this);
      var teacher_id = btn.data('teacher_id');
      var job_title = btn.data('job_title');
      var teacher_name = btn.data('teacher_name');
      var teacher_image = btn.data('teacher_image');

      $('.input-id').val(teacher_id);
      $('.teacher-name').text(teacher_name);
      $('.teacher-job-title').text(job_title);
      $('.teacher-img').attr('src',teacher_image);
      


      

    });
    });

</script>
