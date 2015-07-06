/**
 * Created by soulmatewsj on 2015/7/4.
 */
function prepage(){
    var pagenum=$("#nextpage").attr('data-value');
    pagenum--;
    if(pagenum>0)
    {
        window.location.href="manager.html?page="+pagenum;
    }else if(pagenum<=0){
        pagenum=1;
        window.location.href="manager.html?page="+pagenum;
    }
    $("#nextpage").attr('data-value',pagenum);
}
function nextpage(){
    var pagenum=$("#nextpage").attr('data-value');
    var userCount=$("#userCount").html();
    if(userCount==10){
        pagenum++;
    }
    $("#nextpage").attr('data-value',pagenum);
    window.location.href="manager.html?page="+pagenum;

}

