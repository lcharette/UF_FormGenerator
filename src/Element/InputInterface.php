<?php

/*
 * UserFrosting Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2020 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\FormGenerator\Element;

/**
 * InputInterface.
 *
 * Common Interface for Form elements
 */
interface InputInterface
{
    /**
     * Return the parsed input attributes.
     * This is passed to the Twig template to generate the actual HTML elements.
     *
     * @return array<string,string>
     */
    public function parse(): array;

    /**
     * Get the value of the current input element.
     *
     * @return string The input current value
     */
    public function getValue(): string;

    /**
     * Set the input value.
     *
     * @param string|int|null $value The input value.
     *
     * @return self
     */
    public function setValue($value);

    /**
     * Get the input schema data.
     *
     * @return array<string,string>
     */
    public function getElement(): array;

    /**
     * Set the input schema data.
     *
     * @param array<string,string> $element The input schema data.
     *
     * @return self
     */
    public function setElement(array $element);

    /**
     * Get the name of the input (eg. text, select, textarea, etc.)
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set the name of the input (eg. text, select, textarea, etc.)
     *
     * @param string $name The name of the input (eg. text, select, textarea, etc.)
     *
     * @return self
     */
    public function setName(string $name);
}
