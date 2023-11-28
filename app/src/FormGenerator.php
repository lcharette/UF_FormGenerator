<?php

declare(strict_types=1);

/*
 * UserFrosting Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2020 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\FormGenerator;

use UserFrosting\Sprinkle\Core\Core;
use UserFrosting\Sprinkle\SprinkleRecipe;

class FormGenerator implements SprinkleRecipe
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'Form Generator';
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return __DIR__ . '/../';
    }

    /**
     * {@inheritdoc}
     */
    public function getSprinkles(): array
    {
        return [
            Core::class,
        ];
    }

    /**
     * Returns a list of routes definition in PHP files.
     *
     * @return string[]
     */
    public function getRoutes(): array
    {
        return [
            Routes::class,
        ];
    }

    /**
     * Returns a list of all PHP-DI services/container definitions files.
     *
     * @return string[]
     */
    public function getServices(): array
    {
        return [];
    }
}
