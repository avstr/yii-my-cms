$(document).ready(function(){

    $(".add_comment").click(function(){
        $('#comment_pid').val($(this).attr("comment_id"));
        document.location.href='#form_comment';
        $('#comment_title').focus();
    });

});