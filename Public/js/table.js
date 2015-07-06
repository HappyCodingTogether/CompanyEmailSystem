/**
 * Created by Relly on 2015/7/3.
 */

var alength;
function prepage(){
    var pagenum=$("#nextpage").attr('data-value');
    pagenum--;
    if(pagenum>0)
    {
        getpage(pagenum);
    }else if(pagenum<=0){
        pagenum=1;
        getpage(pagenum);
    }
    $("#nextpage").attr('data-value',pagenum);
}
function nextpage(){
    var pagenum=$("#nextpage").attr('data-value');
    if(alength==10){
        pagenum++;
    }
    $("#nextpage").attr('data-value',pagenum);
    getpage(pagenum);

}

function receiveNewMail(){
    var url=$("#emailListURL").html();

    $.ajax({
        url: url+"receiveNewMail",
        type: 'POST',
        asyns: false,
        datatype: 'json',
        data:{},
        success:function(data){
            var adata = eval(data);//处理json数组
            alength = adata.length;

            var html = "";
            for(var a=0; a<alength; a++){
                var unixTimestamp=adata[a]["createtime"]*1000;

                html = html +"<tr class='row table-tr'><td class='col-lg-1'><input type='checkbox' name='emailId' value='"+adata[a]["id"]+"' ></td>";
                html=html+"<td onclick='detail(this)' class='col-lg-1' value='"+adata[a]["id"]+"'>"+"标签"+"</td>";
                html=html+"<td onclick='detail(this)' class='col-lg-3' value='"+adata[a]["id"]+"'>"+adata[a]["from"]+"</td>";
                html=html+"<td onclick='detail(this)' class='col-lg-5 mail-title' value='"+adata[a]["id"]+"'>"+adata[a]["title"]+"</td>";
                html=html+"<td onclick='detail(this)' class='col-lg-3' value='"+adata[a]["id"]+"'>"+format(unixTimestamp, 'yyyy-MM-dd HH:mm:ss')+"</td></tr>";
            }
            document.getElementById("alist").innerHTML = html;

        }

    })
}
function getpage(pagenum) {
    var url=$("#emailListURL").html();
    $.ajax({
        url: url+"getEmailList",
        type: 'POST',
        asyns: false,
        datatype: 'json',
        data:{page:pagenum},
        success:function(data){
            var adata = eval(data);//处理json数组
            alength = adata.length;

            var html = "";
            for(var a=0; a<alength; a++){
                var unixTimestamp=adata[a]["createtime"]*1000;

              html = html +"<tr class='row table-tr'><td class='col-lg-1'><input type='checkbox' name='emailId' value='"+adata[a]["id"]+"'></td>";
              html=html+"<td onclick='detail(this)' class='col-lg-1' value='"+adata[a]["id"]+"'>"+"标签"+"</td>";
               html=html+"<td onclick='detail(this)' class='col-lg-3' value='"+adata[a]["id"]+"'>"+adata[a]["from"]+"</td>";
              html=html+"<td onclick='detail(this)'  class='col-lg-5 mail-title' value='"+adata[a]["id"]+"'>"+adata[a]["title"]+"</td>";
                html=html+"<td onclick='detail(this)' class='col-lg-3' value='"+adata[a]["id"]+"'>"+format(unixTimestamp, 'yyyy-MM-dd HH:mm:ss')+"</td></tr>";
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
$(document).ready(function(){
   receiveNewMail();
});




