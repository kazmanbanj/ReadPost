$(document).ready(function(){

    // Editor CKEDITOR
    // text editor
    ClassicEditor
    .create( document.querySelector( '#body' ) )
    .catch( error => {
        console.error( error );
    } );

    // OTHER EDITOR
    // tick all function
    $('#selectAllBoxes').click(function(event) {
        if(this.checked) {
            $('.checkBoxes').each(function(){
                this.checked = true;
            });
        } else {
            $('.checkBoxes').each(function(){
                this.checked = false;
            })
        }
    });

    // admin load screen
    var div_box = "<div id='load-screen'><div id='loading'></div></div>";
    $("body").prepend(div_box);
    $('#load-screen').delay(700).fadeOut(600, function(){
       $(this).remove();
    });
});

function loadUsersOnline() {
    $.get("functions.php?onlineusers=result", function(data) {
        $(".usersonline").text(data);
    });
}

// setInterval(function() {
//     loadUsersOnline();
// }, 500)
