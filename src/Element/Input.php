<?php

/*
 * UserFrosting Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2020 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\FormGenerator\Element;

use UserFrosting\Sprinkle\Core\Facades\Translator;

/**
 * Input class.
 *
 * Parse the schema data for a form input element to add the default
 * attributes values and transform other attributes.
 */
abstract class Input implements InputInterface
{
    /**
     * @var string The name of the input (eg. text, select, textarea, etc.)
     */
    public $name;

    /**
     * @var array<string,string> The input schema data.
     */
    public $element;

    /**
     * @var string|null The input value.
     */
    public $value;

    /**
     * Constructor.
     *
     * @param string               $name
     * @param array<string,string> $element
     * @param string|null          $value   (default: null)
     */
    public function __construct(string $name, array $element, ?string $value = null)
    {
        $this->name = $name;
        $this->element = $element;
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(): array
    {
        $this->applyTransformations();

        return $this->element;
    }

    /**
     * Translate the value of passed argument using the Translator Facade.
     *
     * @param string $argument
     */
    public function translateArgValue(string $argument): void
    {
        if (isset($this->element[$argument])) {
            $this->element[$argument] = Translator::translate($this->element[$argument]);
        }
    }

    /**
     * Return the value of the current input element.
     * If not value is set in `$this->value`, return the default value (from the schema data), if any.
     *
     * @return string The input current value
     */
    public function getValue(): string
    {
        if (isset($this->value) && $this->value !== null) {
            return $this->value;
        } elseif (isset($this->element['default'])) {
            return $this->element['default'];
        } else {
            return '';
        }
    }

    /**
     * Add defaut attributes to the current input element.
     * Also transform attributes values passed from the schema
     */
    abstract protected function applyTransformations(): void;
}
