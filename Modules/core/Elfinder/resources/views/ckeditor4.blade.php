@extends('layouts.blank')

@section('title',$title)
@section('css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"/>
    <!-- elFinder CSS (REQUIRED) -->
{{--    <link rel="stylesheet" type="text/css" href="{{ asset($dir.'/css/elfinder.min.css') }}">--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/elfinder/2.1.65/css/elfinder.full.min.css" integrity="sha512-dUHdbvKDId1Idj/ehvkymeKyPGkD2LXoN7TIxU4KUDnQTBNg8uiZkuZpX+VUwp68Xd+0vCfz0KAk00tSSj6r5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="{{ asset($dir.'/css/theme.css') }}">
@endsection
@section('content_header')
    @component('components.content_header')

        @slot('page_title')
            {{ $title }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('file-manager') }}
        @endslot

    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Element where elFinder will be created (REQUIRED) -->
            <div id="elfinder"></div>
        </div>
    </div>
@endsection

@section('js')
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <!-- elFinder JS (REQUIRED) -->
{{--    <script src="{{ asset($dir.'/js/elfinder.min.js') }}"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/elfinder/2.1.65/js/elfinder.full.min.js" integrity="sha512-jIedDR9LnK693DwdSqmTJ0jHfhP7cVgpvwlJ6jBHzYcohppiVRz2eFOhsh6tcvvjyLl/B4rw9+U5AcaPcAppTg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    @if($locale)
        <!-- elFinder translation (OPTIONAL) -->
        <script src="{{ asset($dir."/js/i18n/elfinder.$locale.js") }}"></script>
    @endif

    <!-- elFinder initialization (REQUIRED) -->
    <script type="text/javascript" charset="utf-8">
        // Helper function to get parameters from the query string.
        function getUrlParam(paramName) {
            var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
            var match = window.location.search.match(reParam) ;
            return (match && match.length > 1) ? match[1] : '' ;
        }
        $(document).ready(function() {
            var funcNum = getUrlParam('CKEditorFuncNum');
            var elf = $('#elfinder').elfinder({
                // set your elFinder options here
                <?php if($locale){ ?>
                lang: '<?= $locale ?>', // locale
                <?php } ?>
                customData: {
                    _token: '<?= csrf_token() ?>'
                },
                url: '{{ route("file-manager.connector") }}',  // connector URL
                soundPath: '<?= asset($dir.'/sounds') ?>',
                getFileCallback: function(file) {
                    window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
                    window.close();
                }
            }).elfinder('instance');

            // Set an interval to check for the class
            setInterval(function() {
                $('.elfinder-cwd-view-icons').removeClass('ui-state-disabled');
            }, 100); // Check every 100 milliseconds
        });



    </script>
@endsection
