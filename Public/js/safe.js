/**
 * Created by soulmatewsj on 2015/7/4.
 */
$(function(){
    $("#saveButton").click(function(){
        $("#editUserForm").submit();
    });
    $("#saveMailAccountButton").click(function(){
        $("#mailAccount").submit();
    });

});
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
function addnewpeople() {
    $("#addpeople").modal("show");
    var array =new Array();
    var id = document.getElementsByName("emailId");
    var length = id.length;
    for(var i=0;i < length;i++)
    {
        if(id[i].checked ==true)
        {
            array.push(id[i]["value"]);

        }
        var str = array.join(",");

        $("#mailIds").attx("value",str);
    }

        }

window.onload =function(){
    distribute();
}


function deal(){
    $('#deal').addClass('active');
    $('#distribute').removeClass('active');
    $('#examine').removeClass('active');
    //����ͼ
    var dealData = [
        {
            value: 30,
            color:"#F7464A",
            highlight: "#FF5A5E",
            label: "123"
        },
        {
            value: 150,
            color: "#46BFBD",
            highlight: "#5AD3D1",
            label: "456"
        },


    ];
    //ֱ��ͼ
    var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

    var dealbarChartData = {
        labels : ["123","456","789","000",],
        datasets : [
            {
                fillColor : "rgba(220,220,220,0.5)",
                strokeColor : "rgba(220,220,220,0.8)",
                highlightFill: "rgba(220,220,220,0.75)",
                highlightStroke: "rgba(220,220,220,1)",
                data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
            },
            {
                fillColor : "rgba(151,187,205,0.5)",
                strokeColor : "rgba(151,187,205,0.8)",
                highlightFill : "rgba(151,187,205,0.75)",
                highlightStroke : "rgba(151,187,205,1)",
                data : [50,70,randomScalingFactor(),randomScalingFactor()]
            }
        ]

    }
    var ctx = document.getElementById("canvas").getContext("2d");
    window.myBar = new Chart(ctx).Bar(dealbarChartData, {
        responsive : true
    });
    var ctx = document.getElementById("chart-area").getContext("2d");
    window.myDoughnut = new Chart(ctx).Doughnut(dealData, {responsive : true});
}

function distribute(){
    $('#deal').removeClass('active');
    $('#distribute').addClass('active');
    $('#examine').removeClass('active');
//����ͼ
    var distributeData = [
        {
            value: 30,
            color:"#F7464A",
            highlight: "#FF5A5E",
            label: "123"
        },
        {
            value: 50,
            color: "#46BFBD",
            highlight: "#5AD3D1",
            label: "456"
        },


    ];
    //ֱ��ͼ
    var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

    var distributebarChartData = {
        labels : ["123","456","789","000",],
        datasets : [
            {
                fillColor : "rgba(220,220,220,0.5)",
                strokeColor : "rgba(220,220,220,0.8)",
                highlightFill: "rgba(220,220,220,0.75)",
                highlightStroke: "rgba(220,220,220,1)",
                data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
            },
            {
                fillColor : "rgba(151,187,205,0.5)",
                strokeColor : "rgba(151,187,205,0.8)",
                highlightFill : "rgba(151,187,205,0.75)",
                highlightStroke : "rgba(151,187,205,1)",
                data : [50,70,randomScalingFactor(),randomScalingFactor()]
            }
        ]

    }
    var ctx = document.getElementById("canvas").getContext("2d");
    window.dismyBar = new Chart(ctx).Bar(distributebarChartData, {
        responsive : true
    });
    var ctx = document.getElementById("chart-area").getContext("2d");
    window.dismyDoughnut = new Chart(ctx).Doughnut(distributeData, {responsive : true});
}

function examine(){
    $('#deal').removeClass('active');
    $('#distribute').removeClass('active');
    $('#examine').addClass('active');
    //����ͼ
    var examineData = [
        {
            value: 30,
            color:"#F7464A",
            highlight: "#FF5A5E",
            label: "123"
        },
        {
            value: 50,
            color: "#46BFBD",
            highlight: "#5AD3D1",
            label: "456"
        },
        {
            value: 100,
            color: "#FDB45C",
            highlight: "#FFC870",
            label: "789"
        },
        {
            value: 120,
            color: "#4D5360",
            highlight: "#616774",
            label: "000"
        }


    ];
    //ֱ��ͼ
    var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

    var examinebarChartData = {
        labels : ["123","456","789","000",],
        datasets : [
            {
                fillColor : "rgba(220,220,220,0.5)",
                strokeColor : "rgba(220,220,220,0.8)",
                highlightFill: "rgba(220,220,220,0.75)",
                highlightStroke: "rgba(220,220,220,1)",
                data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
            },
            {
                fillColor : "rgba(151,187,205,0.5)",
                strokeColor : "rgba(151,187,205,0.8)",
                highlightFill : "rgba(151,187,205,0.75)",
                highlightStroke : "rgba(151,187,205,1)",
                data : [50,70,randomScalingFactor(),randomScalingFactor()]
            }
        ]

    }
    var ctx = document.getElementById("canvas").getContext("2d");
    window.emyBar = new Chart(ctx).Bar(examinebarChartData, {
        responsive : true
    });
    var ctx = document.getElementById("chart-area").getContext("2d");
    window.emyDoughnut = new Chart(ctx).Doughnut(examineData, {responsive : true});

}




