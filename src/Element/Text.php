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
 * InputInterface
 *
 * Interface for Form elements classes
 */
class Text extends BaseInput {

    protected function applyTransformations()
    {
        $this->element = array_merge([
            "label" => "",
            "autocomplete" => "off",
            "class" => "form-control",
            "value" => $this->getValue(),
            "name" => $this->name,
            "id" => "field_" . $this->name
        ], $this->element);

        // Translate placeholder
        $this->translateArgValue('placeholder');
    }
}
