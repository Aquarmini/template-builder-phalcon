<?php
// +----------------------------------------------------------------------
// | Demo [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2016/11/13 Time: 15:48
// +----------------------------------------------------------------------

if (file_exists(__DIR__ . '/system/session.php')) {
    /**
     * 注入SESSION 服务
     */
    include __DIR__ . '/system/session.php';
}

if (file_exists(__DIR__ . '/system/cache.php')) {
    include __DIR__ . '/system/cache.php';
}

if (file_exists(__DIR__ . '/system/log.php')) {
    include __DIR__ . '/system/log.php';
}
