<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <style>
    	button{
    		width:20px;
    		height:20px;
    		display: inline-block;
    		margin-right: -4px;
            margin-top: -1px;
            background-color:#000000;
    	}
</style>
    </head>
    <body>
        <div id = "showStart" style="display: none">
            <div id = "show" ></div>
            <div id = "info">
                <h1><p>得分</p></h1>
                <h2 id = 'showScore'>0</h2>
                <h1><p>速度等級</p></h1>
                <h2 id = 'showSpeed'></h2>
            </div>
        </div>
        <div id="setData">
            <table align='center' valign="middle">
                <tr>
                    <td><input type="text" id = "length" name="length" value='30'/></td><td>長</td>
                </tr>
                <tr>
                    <td><input type="text" id = "width" name="width" value='30'/></td><td>寬</td>
                </tr>
            </table>
            <table align='center' valign="middle">
                <tr>
                    <td><div id = "selectSpeed" align='center' valign="middle"></div><td>
                    <td>速度等級</td>
                <!--<tr ><td id = "selectSpeed"></td>-->
                    <!--<td><input type="radio" id = "speed" name="Fruit" value="100"> 快</td>-->
                    <!--<td><input type="radio" id = "speed" name="Fruit" value="200" checked="true"> 中</td>-->
                    <!--<td><input type="radio" id = "speed" name="Fruit" value="300"> 慢</td>-->
                </tr>
                
                <tr>   
                
                    <td><input type="button" onclick="btnStart()" value="開始遊戲"/></td>
                </tr>
            </table>
        </div>
    </body>
    
        <script src="jquery.js"></script>
        <script type="text/javascript">
        var row;
        var con;
        var count=0;
        var snake = [];
        snake[0] = [2,0];
        snake[1] = [1,0];
        snake[2] = [0,0];
        var path = 40;
        var key = 40;
        var score = -1;
        var speed = 200;
        
        getSelectSpeet();
        //建立下拉式選單
        function getSelectSpeet() {
            var str;
            str = "<select id='speed'>";
            for (i = 1; i <= 30; i++) {
                if (i == 20) {
                    str += "<option value='"+i*10+"' selected='selected'>"+i+"</option>";
                } else {
                    str += "<option value='"+i*10+"'>"+i+"</option>";
                }
            }
            str += "</select>";
             $("#selectSpeed").html(str);
        }
        function btnStart() {
            $("#setData").hide();
            row = Number($("#length").val());
            con = Number($("#width").val());
            speed = Number($("#speed").val());
            $("#showStart").show();
            
            document.onkeydown=keyFunction;
            getMap();
            getSnake();
            getFood();
            getSpeed()
            meter1 = setInterval("countSecond()", speed);
        }
        //getMap
        function getMap() {
            for (i = 0; i < row; i++) {
                for (j = 0; j < con; j++) {
                    $("#show").append("<button id='"+i+"_"+j+"' value='666'></button>");
                }
                $("#show").append("<br>");
            }
        }
        //get snake
        function getSnake(){
            for (i = 0; i <snake.length; i++) {
                $("#"+snake[i][0]+"_"+snake[i][1]).css("background-color","#FF0000");
                $("#"+snake[i][0]+"_"+snake[i][1]).val(999);
            }
        }
        
        function getFood() {
            maxI = Math.round(Math.random()*10);
            maxJ = Math.round(Math.random()*10);
            //吃到食物 +分數
            if($("#"+maxI+"_"+maxJ).val() == 666) {
                $("#"+maxI+"_"+maxJ).css("background-color","#0000FF");
                $("#"+maxI+"_"+maxJ).val(555);
                //分數+100 提速
                score++;
                if(score !=0 &&score%10 == 0 ) {
                    upSpeed();
                    getSpeed();
                }
                $("#showScore").html(score*10);
            } else {
                getFood();
            }    
        }
        function getSpeed() {
            $("#showSpeed").html((speed/10));
        }

        function keyFunction() {
            if(event.keyCode == 39 || event.keyCode == 38 || event.keyCode == 37 || event.keyCode == 40) {
                    key = event.keyCode;
            } else if (event.keyCode == 13) {
                    alert("暫停");
            } else if (event.keyCode == 107) {
                clearInterval(meter1);
                upSpeed();
                meter1 = setInterval("countSecond()", speed);
                getSpeed();
            }else if (event.keyCode == 109) {
                clearInterval(meter1);
                downSpeed();
                meter1 = setInterval("countSecond()", speed);
                getSpeed();
            }
            
        }
        //提速
        function upSpeed () {
            if(speed > 10) {
                speed -= 10;
            }
        }
        //降速
        function downSpeed () {
            if(speed < 300) {
                speed += 10;
            }
        }
        
        function countSecond() {
            //確認不為反方向
            if (path == 37 && key != 39 || path == 38 && key != 40 || path == 39 && key != 37  || path == 40 && key != 38) {
            } else {
                key = path;
            }
            
            if (key == 37) {
                 snake.unshift([snake[0][0],snake[0][1]-1]);
            } else if (key == 38) {
                 snake.unshift([snake[0][0]-1,snake[0][1]]);
            } else if (key == 39) {
                 snake.unshift([snake[0][0],snake[0][1]+1]);
            } else if (key == 40) {
                snake.unshift([snake[0][0]+1,snake[0][1]]);
            }
            //上下超出邊界
            if (snake[0][0] < 0 || snake[0][0] >= row) {
                if(key == 38) {
                    snake[0][0] += row;
                } else {
                    snake[0][0] -= row;
                }
            }
            //左右超出邊界
            if (snake[0][1] < 0 || snake[0][1] >= con) {

                if(key == 39) {
                    snake[0][1] -= con;
                } else {
                    snake[0][1] += con;
                    // alert(snake[0][0]+"_"+snake[0][1]);
                }
            }
            if ($("#"+snake[0][0]+"_"+snake[0][1]).val() == 555) {
            
            getFood();
            } else if ($("#"+snake[0][0]+"_"+snake[0][1]).val() == 999) {
              stopCount();
            } else {
            $("#"+snake[snake.length - 1][0]+"_"+snake[snake.length-1][1]).css("background-color","#000000");
            $("#"+snake[snake.length - 1][0]+"_"+snake[snake.length-1][1]).val(666);
            snake.pop();
            }
            //+頭-尾    
            $("#"+snake[1][0]+"_"+snake[1][1]).css("background-color","#FF0000");
            $("#"+snake[0][0]+"_"+snake[0][1]).css("background-color","#FFFFFF");
            $("#"+snake[0][0]+"_"+snake[0][1]).val(999);
            path = key;
        }
        
        function stopCount() {   
            clearInterval(meter1);
            alert("失敗");
        }

        </script>
</html>
    