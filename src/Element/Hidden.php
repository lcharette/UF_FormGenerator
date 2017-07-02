<?php
/**
 * UF Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2017 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */
namespace UserFrosting\Sprinkle\FormGenerator\Element;

use UserFrosting\Sprinkle\FormGenerator\Element\BaseInput;

/**
 * Hidden input type class.
 * Manage the default attributes required to display an hidden input type
 *
 * @extends BaseInput
 */
class Hidden extends BaseInput {

    /**
     * {@inheritDoc}
     */
    protected function applyTransformations()
    {
        $this->element = array_merge([
            "value" => $this->getValue(),
            "name" => $this->name,
            "id" => "field_" . $this->name
        ], $this->element);
    }
}
