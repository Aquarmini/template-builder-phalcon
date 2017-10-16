<?php

namespace App\Tasks\Template;

use App\Core\Template\Builder;
use App\Tasks\Task;
use Xin\Cli\Color;

class BuilderTask extends Task
{

    public function mainAction()
    {
        $builder = new Builder();
        // $builder->build('demos/demo', ['field' => 'name']);
        // dd($builder);
        $builder->build('demos/demo', ['field' => 'name'])->save('result.php');
    }

    public function helpAction()
    {

        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  模板文件新建脚本') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run template:builder@[action] ', Color::FG_LIGHT_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Argument:') . PHP_EOL;
        // echo Color::colorize('  table   表名', Color::FG_LIGHT_GREEN) . PHP_EOL;
        // echo Color::colorize('  view    清理视图缓存', Color::FG_LIGHT_GREEN) . PHP_EOL;
        // echo Color::colorize('  meta    清理模型元数据缓存', Color::FG_LIGHT_GREEN) . PHP_EOL;
    }

}

