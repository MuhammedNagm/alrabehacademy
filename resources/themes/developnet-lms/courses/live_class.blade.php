@extends('layouts.iframe') 

<div class="main-content-wrapper">

    <!-- search -->
    <div class="main-search-from">
        <form>
            <div class="form-group">
                <input type="text" name="search-text" class="" placeholder="Search here..">
                <button type="submit" name="search" class="fa fa-search"></button>
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            </div>
        </form>
    </div>


    <div class="main-content">

        <div class="container-fluid">
            <div class="row">
                <div class="course-nav">
                    <div class="menu-span col-xs-6">
                    	<i class="fa fa-bars aria-hidden=" true></i>
                    	<a href="{{url('/')}}"><i class="fa fa-home" aria-hidden="true"></i></a>

                        {{--  <i class="fa fa-search top-search-from"></i>  @deleteSearch --}}
                    </div>
                                        	<span style="
                    	padding: 10px; font-weight: bold;
                    	">اكاديمية الرابح</span>
                    <div class="course-nav-meta col-xs-6">
                        {{-- <i class="fa fa-expand" aria-hidden="true"></i> --}}
                        <a href="{{route('courses.show', ['id' => $course->hashed_id])}}"><i class="fa fa-close"
                                                                                    aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>

            <div class="row course-leeson-section">
                <hr>
                <div class="course-side-menu wrap-breadcrumb">
                    <div class="curriculum-breadcrumb">
                        <div class="container">
                            <div class="row">
                                @if(isset($breadcrumb))
                                    <ol class="breadcrumb" style="background-color: #f5f5f5;">
                                        @foreach($breadcrumb as $row)
                                            @if($row['link'] != false)
                                                <li class="breadcrumb-item"><a
                                                            href="{{$row['link']}}">{{$row['name']}}</a></li>
                                            @else
                                                <li class="breadcrumb-item active">{{$row['name']}}</li>
                                            @endif
                                        @endforeach

                                    </ol>
                                @endif
                            </div>
                        </div>
                    </div>
                    <ul class="curriculum-sections">


                    </ul>
                </div>
                <div class="course-leeson-content">
                    @section('content')
<iframe src="{{$course->live_class_url}}" style="position:fixed; top: 52px; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;">
    Your browser doesn't support iframes
</iframe>
@endsection
                </div>
            </div>

        </div>


    </div>

</div><!--End of Main Wrapper-->

