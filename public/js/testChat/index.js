var ChatRoomList = React.createClass({
  getInitialState: function() {
    return {
      roomList:[
        {
          roomName:'test',
          roomDescription:'.....',
          roomId:9501,
          userUnreadMessage:0
        },
      ]
    };
  },
  componentDidMount: function () {
    // loaded...
  },
  render: function () {
    window.roomList = this;
    return (
      <ul className="list-group roomList">
        {
          window.roomList.state.roomList.map(function( value , obj ){
            return (
              <li className="list-group-item d-flex justify-content-between align-items-center">
                {value.roomName}
                <span className="badge badge-primary badge-pill" data-id={value.roomId}>{value.userUnreadMessage}</span>
              </li>
            );
          })
        }
      </ul>
    );
  }
});
ReactDOM.render(
  <ChatRoomList />,
  document.getElementById('roomList')
);
