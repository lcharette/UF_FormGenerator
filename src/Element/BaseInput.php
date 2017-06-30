<?php
/**
 * UF Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2017 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */
namespace UserFrosting\Sprinkle\FormGenerator\Element;

use UserFrosting\Sprinkle\FormGenerator\Element\InputInterface;
use UserFrosting\Sprinkle\Core\Facades\Translator;
use UserFrosting\Sprinkle\Core\Facades\Debug;

/**
 * InputInterface
 *
 * Interface for Form elements classes
 */
abstract class BaseInput implements InputInterface {

    var $name;
    var $element;
    var $value;

    public function __construct($name, $element, $value = null)
    {
        $this->name = $name;
        $this->element = $element;
        $this->value = $value;
    }

    public function parse()
    {
        $this->applyTransformations();
        return $this->element;
    }

    public function translateArgValue($argument) {
        if (isset($this->element[$argument])) {
            $this->element[$argument] = Translator::translate($this->element[$argument]);
        }
    }

    public function getValue() {
        if (isset($this->value) && $this->value !== null) {
            return $this->value;
        } else if (isset($this->element['default'])) {
            return $this->element['default'];
        } else {
            return "";
        }
    }

    abstract protected function applyTransformations();
}
