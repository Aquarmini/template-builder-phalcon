<?php

namespace App\Tasks\Builder;

use App\Core\Template\Builder;
use App\Tasks\Builder\Validators\ModelValidator;
use limx\Support\Str;
use Phalcon\Db\Column;
use Xin\Cli\Color;
use Xin\Phalcon\Cli\Traits\Input;

class ModelTask extends Task
{
    use Input;

    public function mainAction()
    {
        $validotar = new ModelValidator();
        $invalide = $validotar->validate($this->argument());
        if ($invalide->valid()) {
            echo Color::colorize($validotar->getErrorMessage(), Color::FG_RED);
            return;
        }

        $table = $this->argument('table');
        $template = $this->argument('template');

        /** @var \Phalcon\Db\Adapter\Pdo\Mysql $db */
        $db = di('db');
        if (!$db->tableExists($table)) {
            echo Color::colorize('Table不存在', Color::FG_RED);
            return;
        }

        $modelClass = Str::studly($table);
        $fields = [];
        $res = $db->describeColumns($table);

        foreach ($res as $v) {
            $item['name'] = $v->getName();
            $item['type'] = $this->getType($v->getType());
            // $item['type'] = $v->getType();
            $fields[] = $item;
        }

        /** @var Builder $builder */
        $builder = new Builder();
        $data = [
            'modelClass' => $modelClass,
            'fields' => $fields,
        ];

        $builder->build($template, $data)->save($modelClass . '.java');
    }

    public function helpAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  新建模型脚本') . PHP_EOL . PHP_EOL;
        echo Color::head('Arguments:') . PHP_EOL;
        echo Color::colorize('  table=?       表名', Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo Color::colorize('  template=?    模板名', Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo PHP_EOL;
        echo Color::head('Options:') . PHP_EOL;
        echo Color::colorize('  --help        帮助', Color::FG_LIGHT_GREEN) . PHP_EOL;
    }

    public function getType($type)
    {
        switch ($type) {
            case Column::TYPE_INTEGER:
                return 'Integer';
            case Column::TYPE_BIGINTEGER:
                return 'Long';
            case Column::TYPE_VARCHAR:
                return 'String';
            case Column::TYPE_DATE:
            case Column::TYPE_DATETIME:
            case Column::TYPE_TIMESTAMP:
                return 'LocalDateTime';
        }
    }

}

