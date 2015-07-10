/**
 * Created by Relly on 2015/7/3.
 */
function getPar(par){
    //获取当前URL
    var local_url = document.location.href;
    //获取要取得的get参数位置
    var get = local_url.indexOf(par +"=");
    if(get == -1){
        return false;
    }
    //截取字符串
    var get_par = local_url.slice(par.length + get + 1);
    //判断截取后的字符串是否还有其他get参数
    var nextPar = get_par.indexOf("&");
    if(nextPar != -1){
        get_par = get_par.slice(0, nextPar);
    }
    return get_par;
}
var type=getPar("type");
$(document).ready(function () {
    var role_id=$("#role_id_hidden").val();
    if(type=="backmail"){
        document.getElementById( "inputEmail1" ).disabled = true;
    }
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
var  fileID=0;
$(function() {
    $("#cancel").click(function(){
        window.location.href="index.html";
    });
    //文件名显示
    $(".file-id").change(function(){
        $("#filename"+$(this).attr("src")).html($(this).val());
    });
    $(".remove-icon").click(function(){
        $("#file"+$(this).attr("src")).val("");
        $("#li"+$(this).attr("src")).hide();
    });

    $("#addfiles").click(function(){
        fileID++;
        $("#fujian").show();
        $("#li"+fileID).show();
        //var html="<li class='file-li'><a href='javascript:void(0);' class='a-upload' ><input type='file' class='file-id' src='"+fileID+"' id='file"+fileID+"'  name='file"+fileID+"'><span class='brow'>浏览</span><span id='filename"+fileID+"'></span></a> <a class='remove-icon' ><span class='glyphicon glyphicon-remove'></span></a></li>"
        //var html="<div class='col-sm-10'> <label style='display: inline'><input style='margin-right: 0px' id='file"+fileID+"' readonly type='file' name='file"+fileID+"'><span class='glyphicon glyphicon-remove'></span></label></div> ";
        //$("#file-ul").append(html);
        //var id="file"+fileID;
        //$("#id").click();
    });
    $("#sendMailBtn").click(function(){
        if(type=="backmail"){
            $("#emailContent").attr("value",UM.getEditor('myEditor').getPlainTxt());
            //$("#myEditor").html(UM.getEditor('myEditor').getContent());
            var url=$("#backmail").html();
            $("#mailInfo").attr('action',url);
            $("#mailInfo").submit();

        }else{
            $("#emailContent").attr("value",UM.getEditor('myEditor').getContent());
            //$("#myEditor").html(UM.getEditor('myEditor').getContent());
            var url=$("#sendURL").html();
            $("#mailInfo").attr('action',url);
            $("#mailInfo").submit();
        }

    });


    $("#verifyButton").click(function(){
        $("#emailContent").attr("value",UM.getEditor('myEditor').getContent());
       // $("#mailInfo").action="";
        var url=$("#verifyURL").html();
        $("#mailInfo").attr('action',url);
       $("#mailInfo").submit();
    });


});