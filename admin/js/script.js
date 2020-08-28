$(document).ready(function(){

    // Editor CKEDITOR
    // ClassicEditor
    // .create( document.querySelector( '#body' ) )
    // .catch( error => {
    //     console.error( error );
    // } );

    // OTHER EDITOR
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

});

