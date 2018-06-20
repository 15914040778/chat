$(function(){
  $('button[name="submits"]').click(function(){
    debugger;
    //User name
    var Username = $('input[name="user_name"]').val();
    //PassWord
    var PassWord = $('input[name="password"]').val();
    //confirm PassWord
    var confirm_password = $('input[name="confirm_password"]').val();
    if(Username == ''){
      alert('User name Can\'t be empty!');
      return false;
    }
    if(PassWord == ''){
      alert('PassWord can\'t be empty!');
      return false;
    }else if(PassWord != confirm_password){
      alert('PassWord Inequality!');
      return false;
    }
    $('form').submit();
  })
  $('form').submit(function(){
    window.console.log('success');
  })
})
