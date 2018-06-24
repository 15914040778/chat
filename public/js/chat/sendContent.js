/**
 * image upload to the server
 * @var     $imageObject  Image Object
 * @return  string        Image uploaded data(after uploading image the images data)
*/
function uploadImages( imageObject ){
  $.ajax({
    type:'GET',
    dataType:'json',
    url:'/upload/images',
    data:{
      imageObect:imageObject
    },
    success:function( result ){
      console.log(result);
    },
    error:function(){
      console.log('upload image fail!');
    }
  })
}
