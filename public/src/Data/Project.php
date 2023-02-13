<?php

/*
 * UserFrosting Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2020 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\FormGeneratorExample\Data;

use Illuminate\Support\Collection;

/**
 * Project Class.
 *
 * Simulate a model wich would return a list of projects
 * for demo purpose. This can be substituded in FormGeneratorExampleController
 * by a real Eloquent Model
 */
class Project
{
    public static function all(): Collection
    {
        return collect([
            [
                'id'          => 1,
                'name'        => 'Foo project',
                'owner'       => 'Foo',
                'description' => 'The foo project is awesome, but not available.',
                'status'      => 0,
                'completion'  => 100,
                'active'      => false,
            ],
            [
                'id'           => 2,
                'name'         => 'Bar project',
                'owner'        => '',
                'description'  => "The bar project is less awesome, but at least it's open.",
                'status'       => 1,
                'hiddenString' => 'The Bar secret code is...',
                'completion'   => 12,
                'active'       => true,
            ],
        ]);
    }

    /**
     * @param string $id
     *
     * @return array<string,string>|null
     */
    public static function find(string $id): ?array
    {
        return self::all()->where('id', $id)->first();
    }
}
