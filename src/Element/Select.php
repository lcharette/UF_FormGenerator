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
class Select extends Text {

    protected function applyTransformations()
    {
        $this->element = array_merge([
            "label" => "",
            "class" => "form-control js-select2",
            "value" => $this->getValue(),
            "name" => $this->name,
            "id" => "field_" . $this->name
        ], $this->element);

        // Placeholder is required to be in `data-*` for select 2
        // Plus we translate the placeholder
        if (isset($this->element["placeholder"])) {
            $this->element["data-placeholder"] = $this->element["placeholder"];
            unset($this->element["placeholder"]);
            $this->translateArgValue('data-placeholder');
        }
    }
}
