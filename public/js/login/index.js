$(function () {
  $('button[name="login"]').click(function () {
    // User name
    var userName = $('input[name="user_name"]').val();
    console.log(userName);
    //PassWord
    var password = $('input[name="password"]').val();
    console.log(password);
    //if it is empty
    if(userName == '' || password == ''){
      alert('user name and password cannot empty!');
      return false;
    }
    $.ajax({
      type:'post',
      dataType:'json',
      data:{
        'userName':userName,
        'password':password
      },
      url:'/loginServer/'+userName+'/'+password,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      },
      success:function( result ){
        console.log(result);
      },
      error:function(){
        console.log('error');
      }
    })
  })
})
