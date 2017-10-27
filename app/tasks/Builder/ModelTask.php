<?php

namespace App\Tasks\Builder;

use App\Core\Template\Builder;
use App\Tasks\Builder\Validators\ModelValidator;
use App\Utils\DB;
use limx\Support\Str;
use Phalcon\Db\Column;
use Xin\Cli\Color;
use Xin\Phalcon\Cli\Traits\Input;

class ModelTask extends Task
{
    use Input;

    public $columnComments;

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
        $output = $this->argument('output');
        $hidden = [];
        if ($this->hasOption('hidden')) {
            $hidden = explode(',', $this->option('hidden'));
        }
        if ($this->hasOption('modelClass')) {
            $modelClass = $this->option('modelClass');
        }

        /** @var \Phalcon\Db\Adapter\Pdo\Mysql $db */
        $db = di('db');
        if (!$db->tableExists($table)) {
            echo Color::colorize('Table不存在', Color::FG_RED);
            return;
        }

        $fields = [];
        $res = $db->describeColumns($table);

        foreach ($res as $v) {

            if (in_array($v->getName(), $hidden)) {
                continue;
            }
            $item['name'] = $v->getName();
            $item['camelName'] = Str::camel($v->getName());
            $item['type'] = $this->getType($v->getType());
            $item['comment'] = $this->getColumnsComments($table, $v->getName());
            // $item['type'] = $v->getType();
            $fields[] = $item;
        }

        /** @var Builder $builder */
        $builder = new Builder();
        if (empty($modelClass)) {
            $modelClass = Str::studly($table);
        }
        $data = [
            'modelClass' => $modelClass,
            'fields' => $fields,
            'datetime' => date('Y-m-d H:i:s'),
            'comment' => $this->getTableComments($table) ?? $modelClass,
            'table' => $table,
        ];

        $builder->build($template, $data)->save($output);
    }

    public function helpAction()
    {
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  新建模型脚本') . PHP_EOL . PHP_EOL;
        echo Color::head('Arguments:') . PHP_EOL;
        echo Color::colorize('  table=?       表名', Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo Color::colorize('  template=?    模板名', Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo Color::colorize('  output=?      输出文件名', Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo PHP_EOL;
        echo Color::head('Options:') . PHP_EOL;
        echo Color::colorize('  --help        帮助', Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo Color::colorize('  --hidden      隐藏的参数', Color::FG_LIGHT_GREEN) . PHP_EOL;
        echo Color::colorize('  --modelClass  类名', Color::FG_LIGHT_GREEN) . PHP_EOL;
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
            case Column::TYPE_DECIMAL:
                return 'BigDecimal';
        }
    }

    /**
     * @desc   获取表注释
     * @author limx
     * @param $table
     * @return null
     */
    public function getTableComments($table)
    {
        $config = di('config')->database;
        $schema = $config->dbname;

        $sql = "SELECT TABLE_COMMENT FROM information_schema.tables 
          WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? LIMIT 1;";

        $res = DB::fetch($sql, [$schema, $table]);
        if (empty($res)) {
            return null;
        }
        return $res['TABLE_COMMENT'];
    }

    public function getColumnsComments($table, $column)
    {
        if (isset($this->columnComments)) {
            return $this->columnComments[$column];
        }
        $config = di('config')->database;
        $schema = $config->dbname;

        $sql = "SELECT COLUMN_NAME as `column`,column_comment as `comment` 
            FROM INFORMATION_SCHEMA.Columns WHERE `table_schema`=? AND `table_name`=?;";

        $res = DB::query($sql, [$schema, $table]);
        $result = [];
        foreach ($res as $item) {
            $result[$item['column']] = $item['comment'];
        }
        $this->columnComments = $result;
        return $result[$column];
    }

}

