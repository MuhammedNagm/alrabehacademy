<style type="text/css">
  .q_item{
    font-size: 12px;
    padding: 0px;
    /* background-color: #007bff; */
     border-color: none; 

    }    
</style>
@php
    $qskeys = array_keys($questionsList);
    $qskeysSize = count($qskeys);
    $key1index = array_search($currentQuestion[0], $qskeys);
    $diffIndexs = $qskeysSize - $key1index;
    $key2index = $key1index + 7;
    if($diffIndexs < 8 && $key1index >= 0){
      $key1index = $key1index - (8 - $diffIndexs);
      if($key1index < 0){
        $key1index = 0;
      }
    }
    $newQuestionsListArray = array_slice($questionsList, $key1index, ($key2index - $key1index) + 1, TRUE);
@endphp
<ul id="pagination questions-list-menu" class="pagination list-group list-group-unbordered">
     @if ($paginator->hasPages()) {{-- if has pages (pagination) --}}
        @if ($paginator->onFirstPage())
                  <li class="List-group-item page-item"><button class="btn btn-dark btn-sm btn-block prev"  disabled="" style="text-align: center;">@lang('developnet-lms::labels.spans.span_previous')</button></li>
          @else
                   <li class="List-group-item page-item"><a class="btn btn-dark btn-sm btn-block prev ajax-paginate" href="{{ $paginator->previousPageUrl().'&sa-scroll=true' }}" style="text-align: center;">@lang('developnet-lms::labels.spans.span_previous')</a></li>        
          @endif

{{-- {{dd(range(1, $paginator->lastPage()))}} --}}
                @foreach ($newQuestionsListArray as $key => $value)

                @php
                $questionIndex = array_search($key, $qskeys);
                $page =$questionIndex + 1;
                $url = \Request::url().'?page='.$page;
                @endphp   

            @if ($page == $paginator->currentPage())
                <li class="list-group-item page-item active q_item">
                  <a class="page-link ajax-paginate" href="javascript:;">{{str_limit(strip_tags($value),27)  }}</a>
                </li>
            @else    

                <li class="list-group-item q_item">
                  <a class="page-link ajax-paginate" href="{{ $url }}">{{str_limit(strip_tags($value),27)  }}</a>
                </li>
            @endif

 
                @endforeach       

  @endif

        @if ($paginator->hasMorePages()) 
                  <li class="List-group-item page-item"><a class="btn btn-dark btn-sm btn-block next ajax-paginate" href="{{ $paginator->nextPageUrl().'&sa-scroll=true' }}" style="text-align: center;"> @lang('developnet-lms::labels.spans.span_next')</a></li>
        @else

         <li class="List-group-item page-item"><button class="btn btn-dark btn-sm btn-block next ajax-paginate" href="javascript:;" disabled="" style="text-align: center;"> @lang('developnet-lms::labels.spans.span_next')</button></li>
                  
        @endif 

</ul>