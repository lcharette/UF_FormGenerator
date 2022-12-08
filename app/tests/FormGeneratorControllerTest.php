<?php

/*
 * UserFrosting Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2020 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\FormGenerator\Tests;

use UserFrosting\Sprinkle\FormGenerator\FormGenerator;
use UserFrosting\Testing\TestCase;

/**
 * FormGeneratorControllerTest
 * The FormGenerator unit tests for supplied controllers.
 */
class FormGeneratorControllerTest extends TestCase
{
    protected string $mainSprinkle = FormGenerator::class;

    public function testConfirm(): void
    {
        // Create request with method and url and fetch response
        $request = $this->createRequest('GET', '/forms/confirm');
        $response = $this->handleRequest($request);

        // Asserts
        $this->assertNotSame('', (string) $response->getBody());
        $this->assertResponseStatus(200, $response);
    }
}
