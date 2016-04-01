<?php

namespace Infotech\YiiActivities;

use CActiveRecord;

abstract class AbstractEditActivityForm extends AbstractActivityForm
{
    /** @var CActiveRecord */
    protected $data;

    public function __construct($modelClass, $id = null, $name = 'form', $method = 'POST', $scenario = '')
    {
        if ($modelClass instanceof CActiveRecord) {
            $model = $modelClass;
            $modelClass = get_class($model);
        } else {
            $model = CActiveRecord::model($modelClass);
        }

        /** @var CActiveRecord $model */
        $this->data = $model !== CActiveRecord::model($modelClass)
            ? $model
            : ($id ? $model->findByPk($id) : new $modelClass());

        parent::__construct($name, $method, $scenario);
    }

    public function __get($name)
    {
        return $this->data->$name;
    }

    public function __set($name, $value)
    {
        $this->data->$name = $value;
    }

    /**
     * @return CActiveRecord
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Saves related model without validation.
     *
     * @return bool is save succeeded
     */
    public function save()
    {
        return $this->data->save(false);
    }

    /**
     * @return array
     */
    public function attributeNames()
    {
        return $this->data->attributeNames();
    }
    /**
     * @return array
     */
    public function attributeLabels()
    {
        return $this->data->attributeLabels();
    }

    public function rules()
    {
        return $this->data->rules();
    }
}