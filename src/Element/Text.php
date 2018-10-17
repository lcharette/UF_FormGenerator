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
 * Text input type class.
 * Manage the default attributes required to display a text and other html5 input.
 *
 * @extends BaseInput
 */
class Text extends BaseInput
{
    /**
     * {@inheritdoc}
     */
    protected function applyTransformations()
    {
        $this->element = array_merge([
            'autocomplete' => 'off',
            'class'        => 'form-control',
            'value'        => $this->getValue(),
            'name'         => $this->name,
            'id'           => 'field_'.$this->name,
        ], $this->element);

        // Translate placeholder
        $this->translateArgValue('placeholder');
    }
}
