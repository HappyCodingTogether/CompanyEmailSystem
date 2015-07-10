/**
 * Created by Relly on 2015/7/4.
 */
$(function(){
    $('.sidebar .kind li').hover(function(){
        $('.li-line',this).stop().css('height','2px');
        $('.li-line',this).animate({
            left:'25%',
            width:'50%',
            right:'0'
        },200);
    },function(){
        $('.li-line',this).stop().animate({
            left:'50%',
            width:'0'
        },200);
    });

    $('.sidebar .role ').hover(function(){
        $('.role-line',this).stop().css('height','2px');
        $('.role-line',this).animate({
            left:'25%',
            width:'50%',
            right:'0'
        },200);
    },function(){
        $('.role-line',this).stop().animate({
            left:'50%',
            width:'0'
        },200);
    });

    $(".sidebar .search").hover(function () {
        $(".search-input").css("border","1px solid #AAAAAA");
        $(".search-input").animate({
            padding:"3px 8px",
            width:"50%"
        },200);

    },function(){
        $(".search-input").css("border","0");
        $(".search-input").animate({
            padding:"0",
            width:"0%"
        },200);
    });

    $('.button-group .fun-button').hover(function(){
        $('.fun-line',this).animate({
            width:'100%',
        },200);
    },function(){
        $('.fun-line',this).stop().animate({
            width:'0',
        },200);
    });



    $("#inbox-all").click(function(){
        $("#inbox-ul").slideToggle();
        $("#files-ul").slideUp()

    });
    $("#files").click(function(){
        $("#files-ul").slideToggle();
        $("#inbox-ul").slideUp();
    });
});


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
        window.location.href="Statistics.html";
    });
    $("#notify-close").click(function(){
        $(".notify-box").css("display","none");
    });

    $(".button-group .fun-button").click(function(){
        $("#"+$(this).attr("value")).fadeToggle();
    });

});


