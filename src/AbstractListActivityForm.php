<?php

namespace Infotech\YiiActivities;

abstract class AbstractListActivityForm extends AbstractActivityForm
{
    public function __construct($name = 'filter', $method = 'GET', $scenario = '')
    {
        parent::__construct($name, $method, $scenario);
    }
}
