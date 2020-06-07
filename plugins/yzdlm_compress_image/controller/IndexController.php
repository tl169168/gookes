<?php
// +----------------------------------------------------------------------
// | YzdlmCompressImage [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2019 yzdlm All rights reserved.
// +----------------------------------------------------------------------
// | Author: yzdlm <1741509527@qq.com>
// +----------------------------------------------------------------------

namespace plugins\yzdlm_compress_image\controller;

use cmf\controller\PluginBaseController;
use plugins\yzdlm_compress_image\model\PluginYzdlmImageComressModel;

class IndexController extends PluginBaseController
{

    public function index(){ 
        $model = new PluginYzdlmImageComressModel($this->getPlugin()->getConfig());
        $id = $this->request->post('id');
        return json($model->index($id));
    }


}