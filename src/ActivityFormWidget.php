<?php

namespace Infotech\YiiActivities;

use CWidget;
use CActiveForm;
use CHtml;

/**
 * Activity Form Widget
 *
 * This widget helps to render activity form in CActiveForm widget style but without
 * explicit giving the $model into calls of rendering methods.
 *
 * The "form" option expects activity form instance.
 *
 * The "activeFormWidgetClass" option can be used to setup a rendering widget class 
 * (subclass of CActiveForm). Default value is "CActiveForm".
 *
 * Other options is proxied to related rendering widget.
 *
 * @method error($attribute, $htmlOptions = array(), $enableAjaxValidation = true, $enableClientValidation = true) string
 * @method errorSummary($header = null, $footer = null, $htmlOptions = array()) string
 * @method label($attribute, $htmlOptions = array()) string
 * @method labelEx($attribute, $htmlOptions = array()) string
 * @method urlField($attribute, $htmlOptions = array()) string
 * @method emailField($attribute, $htmlOptions = array()) string
 * @method numberField($attribute, $htmlOptions = array()) string
 * @method rangeField($attribute, $htmlOptions = array()) string
 * @method dateField($attribute, $htmlOptions = array()) string
 * @method timeField($attribute, $htmlOptions = array()) string
 * @method dateTimeField($attribute, $htmlOptions = array()) string
 * @method dateTimeLocalField($attribute, $htmlOptions = array()) string
 * @method weekField($attribute, $htmlOptions = array()) string
 * @method colorField($attribute, $htmlOptions = array()) string
 * @method telField($attribute, $htmlOptions = array()) string
 * @method textField($attribute, $htmlOptions = array()) string
 * @method searchField($attribute, $htmlOptions = array()) string
 * @method hiddenField($attribute, $htmlOptions = array()) string
 * @method passwordField($attribute, $htmlOptions = array()) string
 * @method textArea($attribute, $htmlOptions = array()) string
 * @method fileField($attribute, $htmlOptions = array()) string
 * @method radioButton($attribute, $htmlOptions = array()) string
 * @method checkBox($attribute, $htmlOptions = array()) string
 * @method dropDownList($attribute, $data, $htmlOptions = array()) string
 * @method listBox($attribute, $data, $htmlOptions = array()) string
 * @method checkBoxList($attribute, $data, $htmlOptions = array()) string
 * @method radioButtonList($attribute, $data, $htmlOptions = array()) string
 */
class ActivityFormWidget extends CWidget
{
    /**
     * @var string
     */
    public $activeFormWidgetClass = 'CActiveForm';

    /**
     * @var CActiveForm
     */
    private $activeFormWidget;

    /** @var AbstractActivityForm */
    private $form;

    public function init()
    {
        $this->getActiveFormWidget()->init();
    }

    public function setForm(AbstractActivityForm $form)
    {
        $this->form = $form;
        CHtml::setModelNameConverter(function () { return $this->form->getFormName(); });
        $this->getActiveFormWidget()->method = strtolower($this->form->getFormMethod());
    }

    public function run()
    {
        CHtml::setModelNameConverter(null);

        return $this->activeFormWidget->run();
    }

    public function __call($name, $parameters)
    {
        array_unshift($parameters, $this->form);

        return call_user_func_array(array($this->activeFormWidget, $name), $parameters);
    }

    public function __get($name)
    {
        if (method_exists($this, 'get' . $name)) {
            return $this->{'get' . $name}();
        }

        return $this->getActiveFormWidget()->$name;
    }

    public function __set($name, $value)
    {
        if (method_exists($this, 'set' . $name)) {
            $this->{'set' . $name}($value);
        } else {
            $this->getActiveFormWidget()->$name = $value;
        }
    }


    protected function getActiveFormWidget()
    {
        if (!isset($this->activeFormWidget)) {
            $this->activeFormWidget = new $this->activeFormWidgetClass($this->getOwner());
        }

        return $this->activeFormWidget;
    }
}
