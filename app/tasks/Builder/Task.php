<?php

namespace App\Tasks\Builder;

use App\Tasks\Task as DefaultTask;
use Xin\Cli\Color;
use Xin\Phalcon\Cli\Traits\Input;

abstract class Task extends DefaultTask
{
    use Input;

    public function onConstruct()
    {
        $help = $this->hasOption('help');
        if ($help && $this->dispatcher->getActionName() !== 'help') {
            $forward = [
                'task' => $this->dispatcher->getTaskName(),
                'action' => 'help',
            ];
            $this->dispatcher->forward($forward);
        }
    }
}

