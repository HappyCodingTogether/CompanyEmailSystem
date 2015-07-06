/**
 * Created by Relly on 2015/6/28.
 */

$(function() {
    $(".btn").click(function(){
        var flag=1;
        if($("#username").val()==""){
            flag=0;
           $("#verify-username").css("display","block");
        }
        else{
            $("#verify-username").css("display","none");
        }
        if($("#password").val()==""){
            flag=0;
            $("#verify-password").css("display","block");
        }
        else{
            $("#verify-password").css("display","none");
        }
        if(flag){
            $("#loginInfo").submit();
        }

    });
});



