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
use UserFrosting\Sprinkle\FormGenerator\Element\Text;

/**
 * InputInterface
 *
 * Interface for Form elements classes
 */
class Textarea extends Text {

    protected function applyTransformations()
    {
        $this->element = array_merge([
            "label" => "",
            "autocomplete" => "off",
            "class" => "form-control",
            "value" => $this->getValue(),
            "name" => $this->name,
            "rows" => 3,
            "id" => "field_" . $this->name
        ], $this->element);

        // Translate placeholder
        $this->translateArgValue('placeholder');
    }
}
