/**
 * init elements on page loading and ajax complete
 */
function initThemeElements() {


    $.ajaxSetup({
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        }
    });

}

$( function() {

$('.discpaction').on("cut copy paste",function(e) {
      e.preventDefault();
   });
  });


    $( function() {

    $(".disable_right_click").on("contextmenu",function(e){
   return false;
});
  });



//Loading  
$(window).on('load', function() { 
  $('.Loading-overlay .loading').fadeOut(200,
    function()
    {
      $("body").css("overflow","auto");
      $(this).parent().fadeOut(200,
        function()
        {  
          $(this).remove();
        });
    });
 });
$(document).ready(function() {
    
    'use strict';
    
       //Favourite Scripts
     $('body').on('click','.favourite-action',function(e){
      var url = $(this).data('url');
      var parent_div = $(this).parents('.add-to-fav').closest('.add-to-fav');
      $.get(url, function(response){
        if(response.success){
            parent_div.html(response.view);

        }else{
            parent_div.before(response.view);
        }


        

      });
e.stopImmediatePropagation();

});



    
    ///////////footer////////////
    var footerHeight=$('footer.wrap-footer').height();
    $('.main-content').css("margin-bottom",footerHeight);

    ///////////nav-bar////////////
    var iScrollPos = 0;
    $('header.default').removeClass('inv');
    $('header').css('position',"absolute");
    $(  window).on('scroll',function(){
        
        var iCurScrollPos = $(this).scrollTop();
        if (iCurScrollPos > iScrollPos) {
            //Scrolling Down
            $('header.default').removeClass('inv');
            $('header').css('position',"absolute");
            
        } 
        else if(iCurScrollPos ==0){
            $('header.default').removeClass('inv');
            $('header').css('position',"absolute");
            $('.toolbar').css('display','block');
        }
        else {
           //Scrolling Up
            $('header.default').addClass('inv');
            $('header').css('position',"fixed");
            $('.toolbar').css('display','none');
            
        }
        iScrollPos = iCurScrollPos;
        
     
    });
        
    // coure-lesson
    $('.nav-menu .dropdown').hover(function(){
       $(this).find('.dropdown-menu').fadeToggle();
    });
    //search   
   $(".top-search-from ").click(function(){
            $('.main-search-from').slideDown(400);
    });
    $('.main-search-from .close').click(function() {
        $('.main-search-from').slideUp(400);
    });
    
    
    /////// coure-lesson////////////
    $('.course-nav-meta .fa-expand').on('click',function(){
       $('.course-leeson-section').toggleClass('mycollapse');
    });

    $('.menu-span .fa-bars').on('click',function(){
       $('.course-side-menu').toggleClass('show-side-menu');
    });

    $('.curriculum-sections  >li:first-child').find('.curriculum-section-content').addClass('show');


    /*replay to the comment */

     $(".reply").on('click',function(){
       $(this).next().slideToggle(800);
     });
    
    $('.delete').on('click',function(){
        $(this).parent().remove();
    });



$('body').on('click', '[data-action]', function (e) {
    e.preventDefault();

    var $element = $(this);

    var action = $element.data('action');
    var url = $element.prop('href');

    if (action === 'logout') {
        $.ajax({
            url: url,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
            },
            error: function (data, textStatus, jqXHR) {
            },
            complete: function (data) {
                window.location = "/";
            }
        });
    }
});

}); //end document


