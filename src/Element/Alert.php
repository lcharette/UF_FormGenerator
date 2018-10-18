<?php

/*
 * UF Form Generator.
 *
 * @link https://github.com/lcharette/UF_FormGenerator
 *
 * @copyright Copyright (c) 2017 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\FormGenerator\Element;

/**
 * Alert input type class.
 * Manage the default attributes required to display an alert.
 *
 * @extends BaseInput
 */
class Alert extends BaseInput
{
    /**
     * {@inheritdoc}
     */
    protected function applyTransformations()
    {
        $this->element = array_merge([
            'class' => 'alert-danger',
            'icon'  => 'fa-ban',
            'value' => $this->value,
            'name'  => $this->name,
        ], $this->element);
    }
}
