<?php
// +----------------------------------------------------------------------
// | Builder.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------

namespace App\Core\Template;

/**
 * Class Builder
 * @package App\Core\Template
 * @property \Phalcon\Mvc\View $builder
 */
class Builder
{
    protected $builder;

    protected $content;

    public function __construct()
    {
        $this->builder = di('builder');
    }

    /**
     * @desc   文件生成
     * @author limx
     * @param $template 模板文件
     * @param $data     数据
     */
    public function build($template, $data)
    {
        $this->builder->start();
        $this->builder->setVars($data);
        $this->builder->render('templates', $template);
        $this->builder->finish();
        $content = $this->builder->getContent();
        $this->content = $content;
        return $this;
    }

    /**
     * @desc   保存文件
     * @author limx
     * @param $file
     * @return bool|int
     */
    public function save($file, $isTelativeDir = true)
    {
        if ($isTelativeDir) {
            $config = di('config');
            $file = $config->application->builderDir . $file;
        }
        return file_put_contents($file, $this->content);
    }

}