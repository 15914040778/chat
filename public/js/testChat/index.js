var roomListSocket = new WebSocket('ws://192.168.2.137:9501/roomList');
// Char Room List
var ChatRoomList = React.createClass({
  getInitialState: function() {
    return {
      roomList:[]
    };
  },
  componentDidMount: function () {
    // loaded...
    roomListSocket.onopen = function(evt) {
        var sendMessage = {
          'action':'open',
          'uid':userId,
          'uname':userName,
          'content':'ok',
          'room_id':0
        };
        // console.log(sendMessage);
        sendMessage = JSON.stringify(sendMessage);
        roomListSocket.send(sendMessage);
    };

    // 监听消息
    roomListSocket.onmessage = function(event) {
        var content = $.parseJSON(event.data);
        window.roomList.state.roomList.push(content);
        window.roomList.setState({
          roomList: window.roomList.state.roomList
        });
    };

    // 监听Socket的关闭
    roomListSocket.onclose = function(event) {
        console.log('Client notified socket has closed',event);
    };

    roomListSocket.onerror = function(evt) {
        console.log('Client onerror',event);
    };
  },
  handleClick: function ( room_id ) {
    window.currentRoomID = room_id;
    console.log(window.currentRoomID , '- -!!');
    if(window.chatRoomMessage != undefined){
      ReactDOM.unmountComponentAtNode(document.getElementById('chatRoomContents'));
    }
    if(window.editor != undefined){
      //remover editor example
      UE.getEditor('container').destroy();
      $('#container').remove();
      //remove react example
      ReactDOM.unmountComponentAtNode(document.getElementById('loadUeditor'));
    }
    ReactDOM.render(
      <ChatMessage />,
      document.getElementById('chatRoomContents')
    );
    ReactDOM.render(
      <Editor />,
      document.getElementById('loadUeditor')
    );
    //update current chat room the read time
    window.roomList.setState({
      roomList: []
    });
    var sendMessage = {
      'action':'updateReadTime',
      'uid':userId,
      'uname':userName,
      'content':'ok',
      'room_id':window.currentRoomID
    };
    sendMessage = JSON.stringify(sendMessage);
    roomListSocket.send(sendMessage);
  },
  render: function () {
    window.roomList = this;
    return (
      <ul className="list-group">
        {
          window.roomList.state.roomList.map(function( value , key ){
            return (
              <li className="list-group-item d-flex justify-content-between align-items-center" onClick={window.roomList.handleClick.bind(window.roomList , value.room_id)}>
                {value.name}
                {
                  value.userUnreadMessage != 0 ?
                      <span className="badge badge-primary badge-pill">{value.userUnreadMessage}</span>
                  : ''
                }
              </li>
            );
          })
        }
      </ul>
    );
  }
});

//Chat Message
var ChatMessage = React.createClass({
  getInitialState: function () {
    console.log('init');
    return {
      chatMessageList:[
        {
          content:'Hello everyone!',
          uname:'server',
          uid:0,
          room_id:window.currentRoomID
        },
      ]
    };
  },
  componentDidMount: function () {
    //handle chat room chat message the server
    window.chatMessageSocket = new WebSocket('ws://192.168.2.137:9502/chatMessage');
    // console.log('-----');
    // loaded...
    window.chatMessageSocket.onopen = function(evt) {
      console.log(evt);
      var sendMessage = {
        uid:userId,
        uname:userName,
        content:'ok',
        room_id:window.currentRoomID,
        action:'open'
      };
      console.log(sendMessage , '_!!_');
      sendMessage = JSON.stringify(sendMessage);
      window.chatMessageSocket.send(sendMessage);
    };

    // 监听消息
    window.chatMessageSocket.onmessage = function(event) {
        console.log('Client received a message', event);

        var content = $.parseJSON(event.data);
        // window.chatRoomMessage.state.chatMessageList = [];
        window.chatRoomMessage.state.chatMessageList.push(content);
        window.chatRoomMessage.setState({
          chatMessageList: window.chatRoomMessage.state.chatMessageList
        });
        console.log(window.chatRoomMessage.state.chatMessageList , '-!-');
    };

    // 监听Socket的关闭
    window.chatMessageSocket.onclose = function(event) {
        console.log('Client notified socket has closed',event);
    };

    window.chatMessageSocket.onerror = function(evt) {
        console.log('Client onerror',event);
    };
  },
  render: function () {
    window.chatRoomMessage = this;
    return (
        <div>
        {
          window.chatRoomMessage.state.chatMessageList.map(function( value , key ){
             if(value.uid == userId){
               return <p className="text-right">
                  {value.content}
                  <span data-id={value.uid}>:{value.uname}</span>
                </p>
             }else{
               return <p className="text-left">
                  <span data-id={value.uid}>{value.uname}:</span>
                  {value.content}
                </p>
             }

          })
        }
        </div>
    );
  }
});

//Editor
var Editor = React.createClass({
  getInitialState: function () {
    return {

    };
  },
  componentDidMount: function () {
    // window.ue = null;
    // window.ue.getEditor('container' , {
    //   toolbars : []
    // }).destroy();
    console.log('~~~~~~~~');
    window.ue = UE.getEditor('container');
  },
  handleClick: function () {
    console.log('Send Successes');
    var sendContent = window.ue.getContent();
    var sendMessage = {
      action:'send',
      uname:userName,
      uid:userId,
      content:sendContent,
      room_id:window.currentRoomID
    };
    sendMessage = JSON.stringify(sendMessage);
    window.chatMessageSocket.send(sendMessage);
    window.ue.setContent('');
  },
  render: function () {
    window.editor = this;
    return <div>
            <script id="container" name="content" type="text/plain">
                这里写你的初始化内容
            </script>
            <button type="button" className="btn btn-primary btn-lg btn-block" onClick={window.editor.handleClick}>Send Message</button>
          </div>;
  }
});

/*

 */

ReactDOM.render(
  <ChatRoomList />,
  document.getElementById('roomList')
);
