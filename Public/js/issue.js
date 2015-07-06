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
    $.ajax({
        url: "getemaillist",
        type: 'POST',
        asyns: false,
        datatype: 'json',
        data:{page:pagenum},
        success:function(data){
            getpage(1);
        }

    })
}
function getpage(pagenum) {


    $.ajax({
        url: "getemaillist",
        type: 'POST',
        asyns: false,
        datatype: 'json',
        data:{page:pagenum},
        success:function(data){
            var adata = eval(data);//处理json数组
            alength = adata.length;
           // alert(data);
            var html = "";
            //alert(adata[1]);
            for(var a=0; a<alength; a++){
              html = html +"<tr class='row'><td class='col-md-1'><input type='checkbox' value=''></td>";
              html=html+"<td class='col-md-2'>"+"标签"+"</td>";
               html=html+"<td class='col-md-2'>"+adata[a]["from"]+"</td>";
              html=html+"<td class='col-md-2'>"+adata[a]["title"]+"</td>";html=html+"<td class='col-md-2'>"+adata[a]["add_up_time"]+"</td>";

           }
            document.getElementById("alist").innerHTML = html;


        }

    })
}


