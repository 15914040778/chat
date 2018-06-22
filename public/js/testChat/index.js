var roomListSocket = new WebSocket('ws://192.168.2.137:9501/roomList');

var ChatRoomList = React.createClass({
  getInitialState: function() {
    return {
      roomList:[
        {
          name:'test',
          description:'.....',
          id:9501,
          userUnreadMessage:0
        },
      ]
    };
  },
  componentDidMount: function () {
    // loaded...
    roomListSocket.onopen = function(evt) {
        // 发送一个初始化消息
        // var content = {
        //   content:''
        // };
        var sendMessage = {
          'action':'open',
          'uid':userId,
          'uname':userName,
          'content':'ok'
        };
        sendMessage = JSON.stringify(sendMessage);
        roomListSocket.send(sendMessage);
    };

    // 监听消息
    roomListSocket.onmessage = function(event) {
        console.log('Client received a message', event);

        var content = $.parseJSON(event.data);
        console.log(content);
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
    // window.chatRoomMessage.state.chatMessageList
    // debugger;
    if(window.chatRoomMessage != undefined){
      delete window.chatRoomMessage;
      // window.chatRoomMessage.setState({
      //   chatMessageList:[]
      // });
    }
    ReactDOM.render(
      <ChatMessage />,
      document.getElementById('chatRoomContents')
    );
  },
  render: function () {
    window.roomList = this;
    return (
      <ul className="list-group roomList">
        {
          window.roomList.state.roomList.map(function( value , obj ){
            return (
              <li className="list-group-item d-flex justify-content-between align-items-center" onClick={window.roomList.handleClick.bind(window.roomList , value.id)}>
                {value.name}
                <span className="badge badge-primary badge-pill">{value.userUnreadMessage}</span>
              </li>
            );
          })
        }
      </ul>
    );
  }
});
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
    var chatMessageSocket = new WebSocket('ws://192.168.2.137:9502/chatMessage');
    console.log('-----');
    // loaded...
    chatMessageSocket.onopen = function(evt) {
      console.log('Client received a message', event);
        // 发送一个初始化消息
        // var content = {
        //   content:''
        // };
      console.log(window.currentRoomID);
      console.log('------');
      var sendMessage = {
        uid:userId,
        uname:userName,
        content:'ok',
        room_id:window.currentRoomID,
        action:'open'
      };
      sendMessage = JSON.stringify(sendMessage);
      chatMessageSocket.send(sendMessage);
    };

    // 监听消息
    chatMessageSocket.onmessage = function(event) {
        console.log('Client received a message', event);

        var content = $.parseJSON(event.data);
        console.log(content);
        // window.chatRoomMessage.state.chatMessageList = [];
        this.state.chatMessageList.push(content);
        this.setState({
          chatMessageList: this.state.chatMessageList
        });
    };

    // 监听Socket的关闭
    chatMessageSocket.onclose = function(event) {
        console.log('Client notified socket has closed',event);
    };

    chatMessageSocket.onerror = function(evt) {
        console.log('Client onerror',event);
    };
  },
  render: function () {
    window.chatRoomMessage = this;
    console.log(window.chatRoomMessage.state.chatMessageList);
    // console.log(window.chatRoomMessage.state.chatMessageList);
    return (
        <div>
        {
          this.state.chatMessageList.map(function( value , key ){
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
ReactDOM.render(
  <ChatRoomList />,
  document.getElementById('roomList')
);
