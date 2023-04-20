<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Chatgpt</title>
<!--<script src="https://cdn.jsdelivr.net/npm/markdown-it@12.0.4/dist/markdown-it.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/markdown-it/13.0.1/markdown-it.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-markdown-css/4.0.0/github-markdown.min.css">
<!-- 引入 layui.js -->
<script src="//unpkg.com/layui@2.7.6/dist/layui.js"></script>  
<!-- 引入 layui.css -->
<link href="main.css?v1.2" rel="stylesheet">
<script src="jquery.js" language="JavaScript" type="text/javascript"></script>
<script src="main.js" language="JavaScript" type="text/javascript"></script>
</head>
<body>
<div style="margin: 0 auto; ">
    <div class="layui-row">
        <div class="layui-col-xs6 layui-col-md12">
        <div id="chat-container">
          <ul id="chat-messages"></ul>
          <div id="input-container">
            <input type="text" id="user-input" placeholder="输入消息..." />
            <button id="send-btn">发送</button>
          </div>
          <div class="select-container">选择版本：
              <select id="model" class="select-box">
                <option value="gpt-3.5-turbo" selected="selected">Chatgpt3.5</option>
                <option value="gpt-4">Chatgpt4</option>
              </select>
              <div class="slider-container">
                  <label for="temperature">Temperature:</label>
                  <input type="range" min="0.1" max="1" step="0.1" value="0.1" class="slider" id="temperature">
                  <input type="number" min="0.1" max="1" step="0.1" value="0.1" class="slider-input" id="temperature-input">
                  <!--<span class="slider-value"><span id="temperature-value">0.1</span></span>-->
              </div>
              <label class="switch-container">
                  <span class="switch-name">连续对话:</span>
                  <input type="checkbox" id="switch" class="switch">
                  <label for="switch" class="switch-label"></label>
              </label>
          <span class="clear-btn" onclick="clearSelection()">清空</span>
          </div>
        </div>
    </div>
</div> 
</body>
</html>
