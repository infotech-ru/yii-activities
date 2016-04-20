<?php

namespace Infotech\YiiActivities;

use CFormModel;
use CUploadedFile;
use CHtml;
use CFileValidator;

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

    /**
     * Handle request if form is submitted
     *
     * @return bool form is submitted
     */
    public function handleRequest()
    {
        $submitted = $this->isSubmitted();

        if ($submitted) {
            CHtml::setModelNameConverter(function () { return $this->getFormName(); });

            $formData = $GLOBALS['_' . $this->formMethod][$this->formName];
            foreach ($this->getFileAttributes() as $fileAttribute) {
                $formData[$fileAttribute] = CUploadedFile::getInstance($this, $fileAttribute);
            }

            CHtml::setModelNameConverter(null);

            $this->submit($formData);
        }

        return $submitted;
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

    public function getFileAttributes()
    {
        $fileAttributes = array();

        foreach ($this->getValidators() as $validator) {
            if ($validator instanceof CFileValidator) {
                $fileAttributes = array_merge($fileAttributes, $validator->attributes);
            }
        }

        return $fileAttributes;
    }

    public function hasFileAttributes()
    {
        return (boolean)$this->getFileAttributes();
    }
}
