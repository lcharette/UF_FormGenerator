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
 * Textarea input type class.
 * Manage the default attributes required to display a textarea input.
 *
 * @extends BaseInput
 */
class Textarea extends BaseInput
{
    /**
     * {@inheritdoc}
     */
    protected function applyTransformations()
    {
        $this->element = array_merge([
            'autocomplete' => 'off',
            'class' => 'form-control',
            'value' => $this->getValue(),
            'name' => $this->name,
            'rows' => 3,
            'id' => 'field_' . str_replace(['[', ']'], ['_', ''], $this->name),
        ], $this->element);

        // Translate placeholder
        $this->translateArgValue('placeholder');
    }
}
