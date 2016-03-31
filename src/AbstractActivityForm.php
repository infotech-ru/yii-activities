<?php

namespace Infotech\YiiActivities;

use CFormModel;

abstract class AbstractActivityForm extends CFormModel
{
    protected $formName;
    protected $formMethod;

    public function __construct($name = 'form', $method = 'POST', $scenario = '')
    {
        parent::__construct($scenario);
        
        $this->formName = $name;
        $this->formMethod = $method;
    }

    public function getFormName()
    {
        return $this->formName;
    }

    public function getFormMethod()
    {
        return $this->formMethod;
    }

    public function handleRequest()
    {
        if ($this->isSubmitted()) {
            $this->submit($GLOBALS['_' . $this->formMethod][$this->formName]);
        }
    }

    public function isSubmitted()
    {
        return isset($GLOBALS['_' . $this->formMethod][$this->formName])
            && is_array($GLOBALS['_' . $this->formMethod][$this->formName]);
    }

    public function submit(array $data)
    {
        $this->setAttributes($data);
        $this->validate(null, true);
    }
}
