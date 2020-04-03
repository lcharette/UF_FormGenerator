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
     * @var string|int|null The input value.
     */
    public $value;

    /**
     * Constructor.
     *
     * @param string               $name
     * @param array<string,string> $element (default: [])
     * @param string|int|null      $value   (default: '')
     */
    public function __construct(string $name, array $element = [], $value = '')
    {
        $this->setName($name)->setElement($element)->setValue($value);
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
     * {@inheritdoc}
     * If not value is set in `$this->value`, return the default value (from the schema data), if any.
     */
    public function getValue(): string
    {
        if (isset($this->value) && !is_null($this->value)) {
            return (string) $this->value;
        } elseif (isset($this->element['default'])) {
            return (string) $this->element['default'];
        } else {
            return '';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getElement(): array
    {
        return $this->element;
    }

    /**
     * {@inheritdoc}
     */
    public function setElement(array $element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Translate the value of passed argument using the Translator Facade.
     *
     * @param string $argument
     */
    protected function translateArgValue(string $argument): void
    {
        if (isset($this->element[$argument])) {
            $this->element[$argument] = Translator::translate($this->element[$argument]);
        }
    }

    /**
     * Add defaut attributes to the current input element.
     * Also transform attributes values passed from the schema.
     */
    abstract protected function applyTransformations(): void;
}
