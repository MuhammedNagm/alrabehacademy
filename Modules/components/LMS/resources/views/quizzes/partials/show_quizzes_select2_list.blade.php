 @if($no_questions)

                   {!! Form::model($quiz, ['url' => url($resource_url.'/session_questions_to_quiz'),'method'=>$quiz->exists?'PUT':'POST','files'=>true,'class'=>'ajax-form']) !!}
                  <div class="row">
                    <div class="col-md-12">
                        <p>عدد الاسئلة المضافة للذاكرة  ({{$count_questions}})</p>
                        {!! ModulesForm::select('quiz_id','LMS::attributes.coupon.quizzes', [], true, null,
                        ['class'=>'select2-ajax','data'=>[
                        'model'=>\Modules\Components\LMS\Models\Quiz::class,
                        'columns'=> json_encode(['title']),
                        'selected'=>json_encode([]),
                        ]],'select2') !!}
                         <div class="form-group  text-right"><button class="btn btn-success ladda-button" type="submit" data-style="expand-right"><span class="ladda-label"><i class="fa fa-plus"></i>  اضف</span><span class="ladda-spinner"></span></button></div>

                    </div>
                </div>

                            {!! Form::close() !!}
                            <script type="text/javascript">
                                  $(".select2-ajax").select2({
        ajax: {
            url: '{{ url('utilities/select2') }}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    query: params.term,
                    columns: $(this).data('columns'),
                    textColumns: $(this).data('text_columns'),
                    model: $(this).data('model'),
                    where: $(this).data('where'),
                    orWhere: $(this).data('or_where'),
                    join: $(this).data('join'),
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        minimumInputLength: 2,
        allowClear: true
    });
                                  $(".select2-ajax").each(function () {
        var element = $(this);

        var selected = element.data('selected');

        if (selected.length) {
            $.ajax({
                url: '{{ url('utilities/select2') }}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'json',
                delay: 250,
                data: {
                    columns: element.data('columns'),
                    model: element.data('model'),
                    selected: selected
                },
                success: function (data, textStatus, jqXHR) {

                    for (var index in data) {
                        if (data.hasOwnProperty(index)) {
                            var selection = data[index];
                            var option = new Option(selection.text, selection.id, true, true);
                            element.append(option).trigger('change');
                        }
                    }
                }
            });
        }
    });
                            </script>

@else
<div class="alert alert-danger">
  <strong>تنبيه!</strong> لا توجد اسئلة في الذاكرة, قم باضافة الاسئلة الى الذاكرة ثم حاول مرة اخرى.
</div>
@endif
                



