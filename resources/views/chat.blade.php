<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Chat Room</title>
    <script type="text/javascript" src='https://code.jquery.com/jquery-3.3.1.min.js'></script>
    <script src="https://cdn.bootcss.com/react/15.4.2/react.min.js"></script>
  	<script src="https://cdn.bootcss.com/react/15.4.2/react-dom.min.js"></script>
  	<script src="https://cdn.bootcss.com/babel-standalone/6.22.1/babel.min.js"></script>
    <!-- editor -->
    <link href="http://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet" />
    <script src="http://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <!-- end editor -->
    <!-- roomList -->
    <link href='/bootstrap/css/bootstrap.min.css' rel='stylesheet' type='text/css' />
    <!-- end roomList -->
    <!-- 处理发送内容的JavaScript文件 -->
    <script src='/js/chat/sendContent.js' type='text/babel'></script>
    <link href='/css/chat/index.css' rel='stylesheet' type='text/css' />
    <script src='/js/chat/quill.js' type='text/javascript'></script>
  </head>

  <body>
    <div id="example"></div>
    <div id='roomList' class='bd-example'></div>
    <script type='text/babel'>
      var userName = '{{ $userInfo->name }}';
      var userId = {{ $userInfo->id }};
    </script>
    <script type="text/babel" src='/js/chat/index.js'></script>
  </body>
</html>
