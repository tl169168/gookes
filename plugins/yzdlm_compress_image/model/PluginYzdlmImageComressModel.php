<?php
// +----------------------------------------------------------------------
// | YzdlmCompressImage [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2019 yzdlm All rights reserved.
// +----------------------------------------------------------------------
// | Author: yzdlm <1741509527@qq.com>
// +----------------------------------------------------------------------

namespace plugins\yzdlm_compress_image\model;
use think\Model;
use plugins\yzdlm_compress_image\lib\Tinify;
use think\Db;

class PluginYzdlmImageComressModel extends Model
{
    protected $config;
	function __construct($config){
		$this->config = $config;
	}

    public function key(){
        $key = $this->config['key'];
        // if(empty($key)) return '未设置秘钥';
		if(empty($key)) return ['code'=>1,'msg'=>'未设置秘钥'];
        Tinify::setKey($key);
    }

    public function index($id=''){

        if(empty($id)) return ['code'=>-1,'msg'=>'无压缩图片'];
        //if(empty($id)) return '无压缩图片';
        $data = Db::name('asset')->where('id',$id)->find();
        // dump($data);
        // return $data;
        if($data){

            if(empty($data['more'])){
                $key = self::key();
                if($key){
                    return $key;
                }
                $resData = self::Comress($data);
                // break;
                $res = DB::name('asset')->where('id',$data['id'])->update($resData); 

                $acceptData = $res ? ['code'=>0,'msg'=>'压缩成功'] : ['code'=>1,'msg'=>'压缩失败'];    
                $msg = $res ? '压缩成功' : '压缩失败';    
            
            }else{
				$acceptData =  ['code'=>0,'msg'=>'已压缩成功'];
                $msg = '压缩成功';
            }
        }else{
            $acceptData = ['code'=>-1,'msg'=>'资源不存在'];
            $msg = '资源不存在';
        }
		
		//return $msg;
        return $acceptData;
		
    }

    //对图片进行压缩
    public function Comress($arr){
        
        // 加载key
        $image = cmf_get_image_url($arr['file_path']);
        $source = Tinify::fromFile($image);
        $size = $source->toFile(WEB_ROOT.'upload/'.$arr['file_path']);

        // dump($source);
        $more = ['comress'=>1];

        return ['file_size'=>$size,'more'=>json_encode($more)];

    }


    /*function setKey($key) {
        return Tinify::setKey($key);
    }*/

    /*function setAppIdentifier($appIdentifier) {
        return Tinify::setAppIdentifier($appIdentifier);
    }

    function setProxy($proxy) {
        return Tinify::setProxy($proxy);
    }

    function getCompressionCount() {
        return Tinify::getCompressionCount();
    }

    function compressionCount() {
        return Tinify::getCompressionCount();
    }

    

    function validate() {
        try {
            Tinify::getClient()->request("post", "/shrink");
        } catch (AccountException $err) {
            if ($err->status == 429) return true;
            throw $err;
        } catch (ClientException $err) {
            return true;
        }
    }*/
}