@push('child_css')
    <style type="text/css">
        button {
           /* width:100%;*/
           /*display: block;*/
        }

        .zwibbler-builtin-toolbar {
    width: 70px;}

    </style>
<style>


.modal-dialog {
  min-width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
  
}

.modal-content {
  height: auto;
  min-height: 99%;
  border-radius: 0;
}
</style>
@endpush
<button data-question="10" id="show_board_10" class="btn btn-default" type="button" onclick="openNav()"><i class="fa fa-pencil-square-o" ></i> المسودة</button>

@push('child_after_content')

  <!-- Modal -->

<div class="modal fade" id="board_modal" tabindex="-1" role="dialog" aria-labelledby="board_modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background: #fff">
        <div class="modal-body" dir="rtl">
             @include('partials.quiz_body.components.board.load_board_content')


          </div>



   
    </div>
  </div>
</div>

{{-- modal --}}
{{-- <div id="myNav" class="overlay-modal">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <div class="overlay-modal-content">
  </div>
</div> --}}
@endpush
@push('child_scripts')
<script>
function openNav() {
    $('#board_modal').modal('show');
  // document.getElementById("myNav").style.height = "100%";
  // console.log(11);
}

function closeNav() {
  document.getElementById("myNav").style.height = "0%";
}
</script>

<script src="/assets/themes/developnet-lms/js/zibber/zwibbler2.js"></script>
    <script type="text/javascript">
        var zwibbler = Zwibbler.create("zwibbler", {

        });

        var canvass = document.getElementsByClassName("zwibbler-main-canvas");
var canvas  = canvass[0];
var context = canvas.getContext("2d");

zwibbler.setColour("white", true);

zwibbler.useBrushTool({
    lineWidth: 2, // optional
    // strokeStyle: "erase" //clear
});


function imageObj() {
  var imgData = context.getImageData(0, 0, 1000, 1000);
  console.log(imgData);
  context.putImageData(imgData, 0, 0);
}

function imgURL() {
var src     = canvas.toDataURL("image/png"); // cache the image data source
console.log(src);
    var img     = document.createElement('img'); // create a Image Element
    img.src     = src;   //image source
  context.drawImage(img, 0, 0);
}

function clearBoard() {
zwibbler.newDocument();
}


    </script>

@endpush