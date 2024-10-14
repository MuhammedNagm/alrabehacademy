<!DOCTYPE html>
<html lang="{{ \Language::getCode() }}" dir="{{ \Language::getDirection() }}">
<head>   
     {!! \SEO::generate() !!}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{isset($page_title)?$page_title:''}}</title>
    <link rel="shortcut icon" href="{{ \Settings::get('site_favicon') }}" type="image/png">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
     <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap 4 -->
     {!! Theme::css('roots/css/bootstrap.rtl.min.css') !!}
    <!-- Font Awesome -->
    {!! Theme::css('roots/css/font-awesome.min.css') !!}
    {!! Theme::css('plugins/select2/dist/css/select2.min.css') !!}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]> 
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Font --> 
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> --}}
    <!-- Theme style--> 
    {!! Theme::css('css/style.css') !!}
    {!! Theme::css('css/style.rtl.css') !!}

     {!! Theme::css('css/responsive.css') !!}
{!! Theme::css('css/pages.css') !!}
          @yield('css')

             @stack('child_css')

    @if(\Settings::get('google_analytics_id'))
    <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async
                src="https://www.googletagmanager.com/gtag/js?id={{ \Settings::get('google_analytics_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', "{{ \Settings::get('google_analytics_id') }}");
        </script>
    @endif



    <style type="text/css">
        .subject-meta, .opening-hours ul li {
     flex-direction: row; 
}
    </style>

</head>

<body>
    @php

    if(Auth::check()){
    $authUser = Modules\Components\LMS\Models\UserLMS::find(Auth()->id());
    }else{
     $authUser = New Modules\Components\LMS\Models\UserLMS;
    }

    @endphp

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

         @yield('content')

{{--@include('partials.news_letter') --}}

      </div>


   {{--  @if(Auth::check())
    @include('partials.messanger')
    @endif --}}


@yield('after_content')
@stack('child_after_content')
</div><!--End of Main Wrapper-->


<!-- jQuery JS-->
{!! Theme::js('roots/js/jquery.min.js') !!}
<!-- Popper JS -->
{!! Theme::js('js/popper.min.js') !!}
<!-- Bootstrap JS -->
{!! Theme::js('roots/js/bootstrap.min.js') !!}



<!-- Simple Timer -->
{!! Theme::js('js/jquery.simple.timer.js') !!}
{!! Theme::js('plugins/select2/dist/js/select2.full.min.js') !!}

 <!-- Page JS -->
{!! Theme::js('js/functions.js') !!}

{!! Theme::js('plugins/iframe-resizer/js/iframeResizer.contentWindow.min.js') !!}

@include('components.alert_message')  


    <script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    }
    
});
$('.select2-normal').select2({ width: '100%' });
</script>


@yield('js')
@stack('child_scripts')

</body>
</html>