<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UploadImages extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //1、使用laravel 自带的request类来获取一下文件
        $images = $request::file('imageObect');
        //2、定义图片上传路径
        $filedir = "upload/article-img/";
        //3、获取上传图片的文件名
        $imagesName=$images->getClientOriginalName();
        //4、获取上传图片的后缀名
        $extension = $images -> getClientOriginalExtension();
        //5、重新命名上传文件名字
        $newImagesName = md5(time()).random_int(5,5).".".$extension;
        //6、使用move方法移动文件.
        $images->move($filedir,$newImagesName);
        return [
          imageUrl:$filedir.$newImagesName,
          imageName:$newImagesName,
        ];
        // return parent::toArray($request);
    }
}
