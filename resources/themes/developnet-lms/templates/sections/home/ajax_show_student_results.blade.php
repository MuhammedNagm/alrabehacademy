@if($student)
@if($student->preview_video)
@include('components.embeded_media', ['embeded' => $student->preview_video]) 
@else
<img src="{{$student->thumbnail}}" class="img-fluid" style="width: 100%;">
@endif
@endif