/**
 * Created by Relly on 2015/7/3.
 */

var alength;
var pagenum=1;
function prepage(){
    pagenum--;
    if(pagenum>0)
    {
        getpage(pagenum);
    }else if(pagenum<=0){
        pagenum=1;
        getpage(pagenum);
    }
}
function nextpage(){
    if(alength==10){
        pagenum++;
    }
    getpage(pagenum);
}
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

function receiveNewMail(){
    var type=getPar("type");
    if(!type){
        type='all';
    }
    var url=$("#emailListURL").html();
    $.ajax({
        url: url+"receiveNewMail",
        type: 'POST',
        asyns: false,
        datatype: 'json',
        data:{type:type},
        success:function(data){
            var adata = eval(data);//处理json数组
            alength = adata.length;

            var html = "";
            for(var a=0; a<alength; a++){
                var flag='';
                var file='';
                if(adata[a]['deadline']!=null&&adata['deadline']!=''){
                    flag="<span class='glyphicon glyphicon-time' style='color: #cc0000'></span>";
                }
                if(adata[a]['add_file']!=''&&adata[a]['add_file']!=null){
                    file="<span class='glyphicon glyphicon-paperclip' ></span>"
                }
                var unixTimestamp=adata[a]["createtime"]*1000;

                html = html +"<tr class='row table-tr'><td class='col-lg-1'><input type='checkbox' name='emailId' value='"+adata[a]["id"]+"' ></td>";
                html=html+"<td onclick='detail(this)' class='col-lg-2' value='"+adata[a]["id"]+"'>"+flag+adata[a]['label_id']+file+"</td>";
                html=html+"<td onclick='detail(this)' class='col-lg-3' value='"+adata[a]["id"]+"'>"+adata[a]["from"]+"</td>";
                html=html+"<td onclick='detail(this)' class='col-lg-5 mail-title' value='"+adata[a]["id"]+"'>"+adata[a]["title"]+"</td>";
                html=html+"<td onclick='detail(this)' class='col-lg-2' value='"+adata[a]["id"]+"'>"+format(unixTimestamp, 'yyyy-MM-dd HH:mm')+"</td></tr>";
            }
            document.getElementById("alist").innerHTML = html;

        }

    })
}
function getpage(pagenum) {
    var type=getPar("type");
    if(!type){
        type='all';
    }
    var url=$("#emailListURL").html();
    $.ajax({
        url: url+"getEmailList",
        type: 'POST',
        asyns: false,
        datatype: 'json',
        data:{page:pagenum,type:type},
        success:function(data){
            var adata = eval(data);//处理json数组
            alength = adata.length;

            var html = "";
            for(var a=0; a<alength; a++){
                var flag='';
                var file='';
                if(adata[a]['deadline']!=null&&adata['deadline']!=''){
                    flag="<span class='glyphicon glyphicon-time' style='color: #cc0000'></span>";
                }
                if(adata[a]['add_file']!=''&&adata[a]['add_file']!=null){
                    file="<span class='glyphicon glyphicon-paperclip' ></span>";
                }
                var unixTimestamp=adata[a]["createtime"]*1000;

              html = html +"<tr class='row table-tr'><td class='col-lg-1'><input type='checkbox' name='emailId' value='"+adata[a]["id"]+"'></td>";
              html=html+"<td onclick='detail(this)' class='col-lg-2' value='"+adata[a]["id"]+"'>"+flag+adata[a]['label_id']+file+"</td>";
               html=html+"<td onclick='detail(this)' class='col-lg-3' value='"+adata[a]["id"]+"'>"+adata[a]["from"]+"</td>";
              html=html+"<td onclick='detail(this)'  class='col-lg-5 mail-title' value='"+adata[a]["id"]+"'>"+adata[a]["title"]+"</td>";
                html=html+"<td onclick='detail(this)' class='col-lg-2' value='"+adata[a]["id"]+"'>"+format(unixTimestamp, 'yyyy-MM-dd HH:mm')+"</td></tr>";
           }
            document.getElementById("alist").innerHTML = html;

        }

    })
}

function detail(obj){

    window.location.href="email.html?id="+$(obj).attr("value");


}
var format = function(time, format){
    var t = new Date(time);
    var tf = function(i){return (i < 10 ? '0' : '') + i};
    return format.replace(/yyyy|MM|dd|HH|mm|ss/g, function(a){
        switch(a){
            case 'yyyy':
                return tf(t.getFullYear());
                break;
            case 'MM':
                return tf(t.getMonth() + 1);
                break;
            case 'mm':
                return tf(t.getMinutes());
                break;
            case 'dd':
                return tf(t.getDate());
                break;
            case 'HH':
                return tf(t.getHours());
                break;
            case 'ss':
                return tf(t.getSeconds());
                break;
        }
    })
}
function transferEmailId(){  //向“分发”按钮弹出页面传入选择的email的id
    $('#mymodal').modal('show');
    var array = new Array();
    var id = document.getElementsByName("emailId");
    var length = id.length;
    for(var i = 0;i < length;i++)
    {
        if(id[i].checked == true)
        {
            array.push(id[i]['value']);
        }
    }
    var str = array.join(",");

    $("#mailIds").attr('value',str);

}
$(function(){
    $("#refresh").click(function(){
        receiveNewMail();
    });
});
function myInterval()
{
    receiveNewMail();
}
$(document).ready(function(){
    //receiveNewMail();
    getpage(pagenum);
    setInterval("myInterval()",10000);//1000为1秒钟

});




