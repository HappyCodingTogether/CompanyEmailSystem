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
            var adata = eval(data);//处理json数组
            $("#email-title").html(adata[0]['title']);
            $("#email-from").html(adata[0]['from']);
            $("#email-date").html(adata[0]['add_up_time']);
            $("#email-to").html(adata[0]['to']);
            $("#email-content").html(adata[0]['content']);

        }

    })
}
$(function() {
    $("#reply").click(function(){

        sessionStorage.email_to=$("#email-to").html();
        sessionStorage.email_from=$("#email-from").html();
        sessionStorage.email_content=$("#email-content").html();
        sessionStorage.email_id=email_id;
        window.location.href="edit.html";
    });
    $("#return").click(function(){

        sessionStorage.email_from="";
        sessionStorage.email_content="";
        sessionStorage.email_id="";
        window.location.href="table.html ";
    });

    $("#preEmail").click(function(){
        var newEmailId=email_id-1;
        window.location.href="email.html?id="+newEmailId;
    });
    $("#nextEmail").click(function(){
        var newEmailId=email_id-(-1);
        window.location.href="email.html?id="+newEmailId;
    });

});
$(document).ready(function(){
    getdetail();
});