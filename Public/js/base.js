/**
 * Created by Relly on 2015/7/4.
 */

$(function() {
    $("#edit").click(function(){
        sessionStorage.email_from="";
        sessionStorage.email_id="";
        sessionStorage.email_content="";
        window.location.href="edit.html ";
    });
    $("#inbox").click(function(){
        window.location.href="table.html ";
    });
    $("#address-list").click(function(){
        window.location.href="addBook.html";
    });

});