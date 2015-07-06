/**
 * Created by Relly on 2015/7/3.
 */
$(document).ready(function () {
    var role_id=$("#role_id_hidden").val();

    $("#inputEmail").val(sessionStorage.email_to);
    if(sessionStorage.email_from!=""){
        $("#inputEmail1").val(sessionStorage.email_from);
    }
    if(role_id==4){
        $("#inputEmail").val(sessionStorage.email_from);
        if(sessionStorage.email_from!=""){
            $("#inputEmail1").val(sessionStorage.email_to);
        }
    }

    $("#mail_id").val(sessionStorage.email_id);
    if(sessionStorage.email_content!=""){
        $("#myEditor").html("<br/><br/><br/><br/><p>---------原始邮件---------</p>"+sessionStorage.email_content);
    }

    var um = UM.getEditor('myEditor',{
        autoHeightEnabled: false
    });


    //    UM.getEditor('myEditor').getContent()
    //UM.getEditor('myEditor').getPlainTxt()

});

$(function() {
    $("#cancel").click(function(){
        window.location.href="email.html ";
    });

    $("#sendButton").click(function(){
        $("#emailContent").attr("value",UM.getEditor('myEditor').getPlainTxt());
        //$("#myEditor").html(UM.getEditor('myEditor').getContent());
        var url=$("#sendURL").html();
        $("#mailInfo").attr('action',url);
        $("#mailInfo").submit();
    });
    $("#sendButtonTop").click(function(){
        $("#emailContent").attr("value",UM.getEditor('myEditor').getPlainTxt());
        //$("#myEditor").html(UM.getEditor('myEditor').getContent());
        var url=$("#sendURL").html();
        $("#mailInfo").attr('action',url);
        $("#mailInfo").submit();
    });
    $("#verifyButton").click(function(){
        $("#emailContent").attr("value",UM.getEditor('myEditor').getPlainTxt());
       // $("#mailInfo").action="";
        var url=$("#verifyURL").html();
        $("#mailInfo").attr('action',url);
        $("#mailInfo").submit();
    });


});