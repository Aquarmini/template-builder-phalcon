<?php
// +----------------------------------------------------------------------
// | ModelValidator.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Tasks\Builder\Validators;

use App\Core\Validation\Validator;

class ModelValidator extends Validator
{
    public function initialize()
    {
        $this->add(
            [
                'table',
                'template',
                'output',
            ],
            new \Phalcon\Validation\Validator\PresenceOf([
                'message' => 'The :field is required',
            ])
        );
    }

}