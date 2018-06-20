function initialQuill(){
  //初始化富文本..
  var quill = new Quill('#introduce', {
      theme: 'snow',
      modules: {
          toolbar: [
              [{header: [1, 2, 3, false]}],
              ['bold', 'italic', 'underline'],
              [{'list': 'ordered'}, {'list': 'bullet'}],
              [{'align': []}],
              [{'font': []}],
              [{'color': []}, {'background': []}],
              ['image', 'video' , 'code-block']
          ]
      }
  });
  window.quill = quill;
  //重写编辑器的图片预览方法
  var toolbar = quill.getModule('toolbar');
  toolbar.addHandler('image', function () {
      var fileInput = this.container.querySelector('input.ql-image[type=file]');
      if (fileInput == null) {
          fileInput = document.createElement('input');
          fileInput.setAttribute('type', 'file');
          fileInput.setAttribute('accept', 'image/png, image/gif, image/jpeg, image/bmp, image/x-icon');
          fileInput.classList.add('ql-image');
          fileInput.addEventListener('change', function () {
              if (fileInput.files != null && fileInput.files[0] != null) {
                  var formData = new FormData();
                  formData.append('file', fileInput.files[0]);
                  console.log(formData);
                  $.ajax({
                      url: '/home/upload/uploadFormImg',
                      type: 'POST',
                      cache: false,
                      data: formData,
                      processData: false,
                      contentType: false
                  }).done(function (res) {
                      //你的图片上传成功后的返回值...所以格式由你来定!
                      console.log(res);
                      var range = quill.getSelection(true);
                      quill.insertEmbed(range.index, 'image', res.data[0]);
                      quill.setSelection(range.index + 1);
                  }).fail(function (res) {
                  });
              }
          });
          this.container.appendChild(fileInput);
      }
      fileInput.click();
  });
  quill.on('text-change', function (delta, oldDelta, source) {
      //监听文本变化..将值赋给 vue 的shop 对象...
      /*   if (source == 'api') {
             console.log("An API call triggered this change.");
         } else if (source == 'user') {

         }*/
      // quill.shop.introduce = quill.container.firstChild.innerHTML;
  });
}
