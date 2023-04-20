$(document).ready(function() {
  // 发送按钮点击事件
  $("#send-btn").click(function() {
    var userInput = $("#user-input").val(); 
    if (userInput !== "") {
      addMessage("user", userInput); // 添加用户消息
      // 此处可以调用API，生成回复消息并添加到聊天框
      $("#user-input").val(""); // 清空输入框
      $("#user-input").focus(); 
      scrollToBottom(); // 滚动到底部
    }
  });

  // 回车键事件
  $("#user-input").keypress(function(event) {
    if (event.which === 13) {
      $("#send-btn").trigger("click"); // 触发发送按钮点击事件
    }
  });
  //滑块值处理
  var slider = $('#temperature');
  var sliderInput = $('#temperature-input');
  // 当滑块的值发生变化时，更新滑块值和输入框的值
  slider.bind('input', function() {
    var value = slider.val();
    $('#temperature-value').text(value);
    sliderInput.val(value);
  });
  
  // 当输入框的值发生变化时，更新滑块值和输入框的值
  sliderInput.bind('input', function() {
    var value = sliderInput.val();
    $('#temperature-value').text(value);
    slider.val(value);
  });
  // 添加消息到聊天框
  function addMessage(sender, message) {
    var chatMessages = $("#chat-messages");
    var timestamp = new Date().toLocaleTimeString();
    var chatMessage = $('<li class="chat-message user-message"><div class="avatar"><img src="avatar.png"></div><div class="content"><div class="user-time"><div class="user">You </div><div class="time">' + timestamp + '</div></div><div class="message">' + message + '</div></div></li>');
    chatMessages.append(chatMessage); // 将消息添加到聊天框中
    getcontent();
  }

    // 滚动到聊天框底部
  function scrollToBottom() {
    var chatContainer = $("#chat-container");
    var chatMessages = $("#chat-messages");
    chatContainer.scrollTop(chatMessages.height());
  }
});
//清空文本内容
function clearSelection(){
    $("#chat-messages").html('');
}
//获取chatgpt数据
function getcontent(){
   var allarr = [];
   var temperature = $('#temperature-input').val();
   var isChecked=document.getElementById('switch').checked;
   $("#chat-messages li .content .message").each(function(){
        allarr.push($(this).text());
   });
   layui.use('layer', function(){
      var layer = layui.layer;
        var loading = layer.msg('数据请求中...', {
            icon: 16,
            shade: 0.4,
            time: false //取消自动关闭
        });
   });
   if(isChecked){
      var switchison = 1; 
   } else {
      var switchison = 2;
   }
   prompt = $("#user-input").val(); 
   model = $("#model").val(); // 获取选中项的值
   allcontent = JSON.stringify(allarr);
   $.post("chatgpt.php",{prompt:prompt,model:model,allcontent:allcontent,temperature:temperature,switchison:switchison},function (data){
       if(data==''){
           layer.close(layer.index);
           $("#showanswer").html('系统错误，稍后再试！');
       }
        var dataObj=eval("("+data+")")
        if(dataObj.status=='2'){
           layer.close(layer.index);
           $("#showanswer").html(dataObj.answer); 
        }else{
          layer.close(layer.index);
          printcontent(dataObj.answer)
        }
    }); 
}
// 初始化 Markdown 解析器
var md = new markdownit({
  html:         false,        // 在源码中启用 HTML 标签
  xhtmlOut:     false,        // 使用 '/' 来闭合单标签 （比如 <br />）。
                              // 这个选项只对完全的 CommonMark 模式兼容。
  breaks:       false,        // 转换段落里的 '\n' 到 <br>。
  langPrefix:   'language-',  // 给围栏代码块的 CSS 语言前缀。对于额外的高亮代码非常有用。
  linkify:      false,        // 将类似 URL 的文本自动转换为链接。

  // 启用一些语言中立的替换 + 引号美化
  typographer:  false,

  // 双 + 单引号替换对，当 typographer 启用时。
  // 或者智能引号等，可以是 String 或 Array。
  //
  // 比方说，你可以支持 '«»„“' 给俄罗斯人使用， '„“‚‘'  给德国人使用。
  // 还有 ['«\xA0', '\xA0»', '‹\xA0', '\xA0›'] 给法国人使用（包括 nbsp）。
  quotes: '“”‘’',

  // 高亮函数，会返回转义的HTML。
  // 或 '' 如果源字符串未更改，则应在外部进行转义。
  // 如果结果以 <pre ... 开头，内部包装器则会跳过。
  highlight: function (str, lang) {
    var hljs = window.hljs;
    if (lang && hljs.getLanguage(lang)) {
      try {
        return '<pre class="hljs"><code>' +
               hljs.highlight(lang, str, true).value +
               '</code></pre>';
      } catch (__) {}
    }

    return '<pre class="hljs"><code>' + md.utils.escapeHtml(str) + '</code></pre>';
  }
});
//输出信息
function printcontent(content){ 
    const chatMessagesEl = $('#chat-messages');
    var timestamp = new Date().toLocaleTimeString();
 
    const listItem = $('<li class="chat-message"></li>');
    const avatarImg = $('<div class="avatar"><img src="chatgpt.png"></div>');
    const messageContent = $('<div class="content"></div>');
    const messageTime = $('<div class="user-time"><div class="user">Chatgpt</div> <div class="time">' +timestamp+ '</div></div>');
    const messagemessage = $('<div class="message markdown-body"></div>');
    listItem.append(avatarImg).append(messageContent);
    messageContent.append(messageTime).append(messagemessage);
    chatMessagesEl.append(listItem);
    // 模拟打字机效果
    let textstr = '';
    let i = 0;
    const typingInterval = setInterval(function() {
      let alltext = content;
      if (textstr.length < alltext.length) {
        //i++;
        textstr += alltext[i++];
        nowcontent = textstr + "";
        if ((textstr.split("```").length % 2) == 0) nowcontent += "\n```\n";
      } else {
        clearInterval(typingInterval);
      }
      
      var allcontent = md.render(nowcontent);
       messagemessage.html(allcontent);
    }, 50);
}    
