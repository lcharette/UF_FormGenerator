<?php

declare(strict_types=1);

/*
 * UserFrosting Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2020 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\FormGenerator\Element;

/**
 * Checkbox input type class.
 * Manage the default attributes required to display a checkbox input.
 */
class Checkbox extends Input
{
    /**
     * {@inheritdoc}
     */
    protected function applyTransformations(): void
    {
        $this->element = array_merge([
            'class'  => 'js-icheck',
            'name'   => $this->name,
            'id'     => 'field_' . $this->name,
            'binary' => '1',
        ], $this->element);

        // We add the check status instead of the value
        // @phpstan-ingore-next-line - $this->element['binary'] could be a bool because of merge
        if ($this->element['binary'] !== false && $this->getValue() == 1) {
            $this->element['checked'] = 'checked';
        }

        // We add the value if non-binary
        // @phpstan-ingore-next-line - $this->element['binary'] could be a bool because of merge
        if ($this->element['binary'] === false) {
            $this->element['value'] = $this->getValue();
        }
    }
}
