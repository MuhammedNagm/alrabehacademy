<div class="form-group   "><label for="categories[]">تحويل رابط اليوتيوب</label>

                          <p style="text-aligan:right" dir="rtl">
لا تنسى تكتب داخل فئات التنسيق كلمة alrabeh  في حالة فيديو او alrabeh-pdf في حالة بي دي اف

</p>
<input type="text" id="getUrlValue" placeholder="ضع الرابط هنا"> 
<button id="generateUrl" type="button"> توليد </button>
<br />
<br />
رابط اليوتيوب هو : <br/> <span id="myId" style="color: orange;"></span>
                        </div>

                    </div>

 @push('js')
 <script type="text/javascript">
 	function getId(url) {
if(url.includes('google')){

return url.replace('view', 'preview');
}else{
    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;

}
    var match = url.match(regExp);

    if (match && match[2].length == 11) {
        return "https://www.youtube.com/embed/"+match[2];
    } else {
        return 'error';
    }
}




$('#generateUrl').on('click', function(){
var myUrl = getId($('#getUrlValue').val());

$('#myId').html( myUrl);


});
 </script>
 @endpush                       