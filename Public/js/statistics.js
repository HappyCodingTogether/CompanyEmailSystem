/**
 * Created by Relly on 2015/7/9.
 */


window.onload =function(){
    examine();
}


function examine(){
    var roleId=$("#roleId").html();
    var distributionNumber=$("#distributionNumber").html();
    var notDistributionNumber=$("#notDistributionNumber").html();
    var dealNumber=$("#dealNumber").html();
    var notDealNumber=$("#notDealNumber").html();
    var notSend=$("#notSend").html();
    var send=$("#send").html();
    var waitForCheck=$("#waitForCheck").html();
    var fail=$("#fail").html();

    if(roleId==1||roleId==2){

        var examineData = [
            {
                value: distributionNumber,
                color:"#F7464A",
                highlight: "#FF5A5E",
                label: "已分发"
            },
            {
                value: notDistributionNumber,
                color: "#46BFBD",
                highlight: "#5AD3D1",
                label: "未分发"
            },
            {
                value: dealNumber,
                color: "#FDB45C",
                highlight: "#FFC870",
                label: "已处理"
            },
            {
                value: notDealNumber,
                color: "#4D5360",
                highlight: "#616774",
                label: "未处理"
            },
            {
                value: send,
                color:"#F7464A",
                highlight: "#FF5A5E",
                label: "已发送"
            },
            {
                value: notSend,
                color: "#FDB45C",
                highlight: "#FFC870",
                label: "未发送"
            },
            {
                value: waitForCheck,
                color: "#46BFBD",
                highlight: "#5AD3D1",
                label: "待审核"
            },
            {
                value: fail,
                color: "#FDB45C",
                highlight: "#FFC870",
                label: "未通过"
            }


        ];
        var examinebarChartData = {
            labels : ["分发","处理","审核","发送"],
            datasets : [
                {
                    fillColor : "rgba(220,220,220,0.5)",
                    strokeColor : "rgba(220,220,220,0.8)",
                    highlightFill: "rgba(220,220,220,0.75)",
                    highlightStroke: "rgba(220,220,220,1)",
                    data : [distributionNumber,dealNumber,waitForCheck,send]
                },
                {
                    fillColor : "rgba(151,187,205,0.5)",
                    strokeColor : "rgba(151,187,205,0.8)",
                    highlightFill : "rgba(151,187,205,0.75)",
                    highlightStroke : "rgba(151,187,205,1)",
                    data : [notDistributionNumber,notDealNumber,fail,notSend]
                }
            ]

        }
    }else if(roleId==3){
        var distributionNumber=$("#distributionNumber").html();
        var notDistributionNumber=$("#notDistributionNumber").html();
        var examineData = [

            {
                value: dealNumber,
                color: "#FDB45C",
                highlight: "#FFC870",
                label: "已处理"
            },
            {
                value: notDealNumber,
                color: "#4D5360",
                highlight: "#616774",
                label: "未处理"
            },
            {
                value: send,
                color:"#F7464A",
                highlight: "#FF5A5E",
                label: "已发送"
            },
            {
                value: notSend,
                color: "#FDB45C",
                highlight: "#FFC870",
                label: "未发送"
            }
        ];
        var examinebarChartData = {
            labels : ["处理","发送"],
            datasets : [
                {
                    fillColor : "rgba(220,220,220,0.5)",
                    strokeColor : "rgba(220,220,220,0.8)",
                    highlightFill: "rgba(220,220,220,0.75)",
                    highlightStroke: "rgba(220,220,220,1)",
                    data : [dealNumber,send]
                },
                {
                    fillColor : "rgba(151,187,205,0.5)",
                    strokeColor : "rgba(151,187,205,0.8)",
                    highlightFill : "rgba(151,187,205,0.75)",
                    highlightStroke : "rgba(151,187,205,1)",
                    data : [notDealNumber,notSend]
                }
            ]

        }
    }else if(roleId==4){
        var examineData = [

            {
                value: waitForCheck,
                color: "#46BFBD",
                highlight: "#5AD3D1",
                label: "待审核"
            },
            {
                value: fail,
                color: "#FDB45C",
                highlight: "#FFC870",
                label: "未通过"
            }
        ];
        var examinebarChartData = {
            labels : ["审核"],
            datasets : [
                {
                    fillColor : "rgba(220,220,220,0.5)",
                    strokeColor : "rgba(220,220,220,0.8)",
                    highlightFill: "rgba(220,220,220,0.75)",
                    highlightStroke: "rgba(220,220,220,1)",
                    data : [waitForCheck]
                },
                {
                    fillColor : "rgba(151,187,205,0.5)",
                    strokeColor : "rgba(151,187,205,0.8)",
                    highlightFill : "rgba(151,187,205,0.75)",
                    highlightStroke : "rgba(151,187,205,1)",
                    data : [fail]
                }
            ]

        }
    }



    var ctx = document.getElementById("canvas").getContext("2d");
    window.emyBar = new Chart(ctx).Bar(examinebarChartData, {
        responsive : true
    });
    var ctx = document.getElementById("chart-area").getContext("2d");
    window.emyDoughnut = new Chart(ctx).Doughnut(examineData, {responsive : true});

}




