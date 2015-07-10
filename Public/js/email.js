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

var email_id=getPar("id");
function getdetail() {
    var url=$("#emailListURL").html();
    $.ajax({
        url: url+"getDetail",
        type: 'POST',
        asyns: false,
        datatype: 'json',
        data:{id:email_id},
        success:function(data){
            var adata =eval("("+data+")");//处理json数组
            //alert(adata.title);
            $("#email-title").html(adata.title);
            $("#email-from").html(adata.from);
            $("#email-date").html(adata.add_up_time);
            $("#email-to").html(adata.to);
            $("#email-content").html(adata.content);
            if(adata.deadline!=null&&adata.deadline!=""){
                $("#deadline").html(adata.deadline);
                $("#deadlineLi").attr("style","display:block");
            }

            var html="";
            if(adata.addFiles!=null){
                for(var i=0;i<adata.addFiles.length;i++){
                    url=adata.addFiles[i]['path'];
                    html+="<ul class='attchment-ul'><li class='attchment-li'>"+
                    "<a href='"+adata.addFiles[i]['path']+"' class='attchment-a'>"+adata.addFiles[i]['name']+"</a>"+
                    "<a href='"+adata.addFiles[i]['path']+"' class='attchment-icon'><span class='glyphicon glyphicon-save'></span></a></li></ul><br>";
                }
                $("#attachment").html(html);
            }

        }

    });
}


$(function() {
    var firstId=$("#firstId").html();
    var lastId=$("#lastId").html();
    $("#reply").click(function(){

        sessionStorage.email_to=$("#email-to").html();
        sessionStorage.email_from=$("#email-from").html();
        sessionStorage.email_content=$("#email-content").html();
        sessionStorage.email_id=email_id;
        window.location.href="edit.html";
    });
    $("#transmit").click(function(){

        sessionStorage.email_to=$("#email-to").html();
        sessionStorage.email_from="";
        sessionStorage.email_content=$("#email-content").html();
        sessionStorage.email_id=email_id;
        window.location.href="edit.html";
    });
    $("#return").click(function(){

        sessionStorage.email_from="";
        sessionStorage.email_content="";
        sessionStorage.email_id="";
        window.location.href="table.html";
    });

    $("#preEmail").click(function(){
        var newEmailId=email_id-1;
        //alert(newEmailId>lastId);
        if(newEmailId>=lastId){
            window.location.href="email.html?id="+newEmailId;
        }

    });
    $("#nextEmail").click(function(){
        var newEmailId=email_id-(-1);
        if(newEmailId<=firstId){
            window.location.href="email.html?id="+newEmailId;
        }

    });
    $("#passMail").click(function() {
        var url=$("#pass").html();
        $.ajax({
            url: url+"passmail",
            type: 'POST',
            asyns: false,
            datatype: 'json',
            data:{id:email_id},
            success:function(data){
               if(data){
                   window.location.href="table.html";
               }
            }

        })
    });
    $("#editMail").click(function() {
        sessionStorage.email_to=$("#email-to").html();
        sessionStorage.email_from=$("#email-from").html();
        sessionStorage.email_content=$("#email-content").html();
        sessionStorage.email_id=email_id;
        window.location.href="edit.html?type=editmail";
    });
    $("#backMail").click(function() {
        sessionStorage.email_to=$("#email-to").html();
        sessionStorage.email_from=$("#email-from").html();
        sessionStorage.email_content=$("#email-content").html();
        sessionStorage.email_id=email_id;
        window.location.href="edit.html?type=backmail";
    });
    $("#dealButton").click(function(){
        var url=$("#dealURL").val();
        var qaram={
            email_id:email_id
        };
        $.post(url,qaram,function(answer){
            if(answer=='OK'){
                var indexURL=$('#indexURL').val();
                window.location.href=indexURL;
            }
        },'json');
    });

});
$(document).ready(function(){
    getdetail();
});