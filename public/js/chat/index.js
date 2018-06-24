
var socket = new WebSocket('ws://192.168.2.137:9501/roomList');
var ChatRoomList = React.createClass({
  getInitialState: function() {
    return {
      roomList:[
        {
          roomName:'test',
          roomDescription:'.....',
          roomId:9501
        },
      ]
    };
  },
  componentDidMount: function () {
    // loaded...
  },
  render: function () {
    return (
      <ul className="list-group">
        <li className="list-group-item d-flex justify-content-between align-items-center">
          Cras justo odio
          <span className="badge badge-primary badge-pill">14</span>
        </li>
        <li className="list-group-item d-flex justify-content-between align-items-center">
          Dapibus ac facilisis in
          <span className="badge badge-primary badge-pill">2</span>
        </li>
        <li className="list-group-item d-flex justify-content-between align-items-center">
          Morbi leo risus
          <span className="badge badge-primary badge-pill">1</span>
        </li>
      </ul>
    );
  }

});
var ChatRoom = React.createClass({
  getInitialState: function() {
    return {
      chatContents:[
        {
          content:'test',
          name:'server',
          id:0
        },
        {
          content:'OK',
          name:'server',
          id:0
        }
      ]
    };
  },
  componentDidMount: function() {
    //initial Quill
    initialQuill();
    socket.onopen = function(evt) {
        // 发送一个初始化消息
        // var content = {
        //   content:''
        // };
        socket.send('I am the client and I\'m listening!');
    };

    // 监听消息
    socket.onmessage = function(event) {
        console.log('Client received a message', event);

        var content = $.parseJSON(event.data);
        window.chatObject.state.chatContents.push(content);
        window.chatObject.setState({
          chatContents: window.chatObject.state.chatContents
        });
    };

    // 监听Socket的关闭
    socket.onclose = function(event) {
        console.log('Client notified socket has closed',event);
    };

    socket.onerror = function(evt) {
        console.log('Client onerror',event);
    };
  },
  //Click send info
  handleClick: function() {
    var content = window.quill.getContents();
    var content = $('#introduce > .ql-editor').html();
    console.log(content);
    var contentObject = $('textarea[name="content"]');
    // var content = contentObject.val();
    var sendContent = {
      content:content,
      name:userName,
      id:userId
    };
    var sendContentStr = JSON.stringify(sendContent);
    // console.log(sendContentStr);
    socket.send(sendContentStr);
    // contentObject.val('');
  },
  render: function() {
    console.log(this.state.chatContents);
    window.chatObject = this;
    console.log(window.chatObject.state.chatContents);
    var fr = {
      textAlign:'right'
    };
    var userName = {
      margin:10,
      color:'red'
    };
    return(
      <div id='chatRoom'>
       {
         window.chatObject.state.chatContents.map(function( value , key ){
           if(value.id == userId){
             if(typeof value.content == 'string' || typeof value.content == 'number'){
               return (
                 <p style={fr}>
                    <span style={userName}>{value.name}:</span>
                    {value.content}
                  </p>
               );
             }else if(typeof value.content == 'object'){
               console.log(value.content);
               return <p style={fr}><span style={userName}>{value.name}:</span>{
                 value.content.ops.map(function( content_value , content_key ){
                   {return content_value.insert}
                 })
               }</p>
             }
           }else{
             return <p><span style={userName}>{value.name}:</span>{value.content}</p>
           }
         })
       }
      <div id='introduce' type='text' name='content' onPaste={this.handlePaste}></div>
      <input type='button' name='send' onClick={this.handleClick} value='send' />
      </div>
    );
  }
});
ReactDOM.render(
  <ChatRoom />,
  document.getElementById('example')
);
ReactDOM.render(
  <ChatRoomList />,
  document.getElementById('roomList')
);
