<?php

/*
 * UserFrosting Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2020 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\FormGeneratorExample\Tests;

use UserFrosting\Alert\AlertStream;
use UserFrosting\Sprinkle\FormGeneratorExample\App;
use UserFrosting\Testing\TestCase;

/**
 * ControllerTest
 * The FormGeneratorExample unit tests for supplied controllers.
 */
class PublicControllerTest extends TestCase
{
    protected string $mainSprinkle = App::class;

    public function testMainPage(): void
    {
        // Create request with method and url and fetch response
        $request = $this->createRequest('GET', '/');
        $response = $this->handleRequest($request);

        // Assert response status & body
        $this->assertResponseStatus(200, $response);
        $this->assertNotEmpty((string) $response->getBody());
    }

    public function testCreateForm(): void
    {
        // Create request with method and url and fetch response
        $request = $this->createRequest('GET', '/new');
        $response = $this->handleRequest($request);

        // Assert response status & body
        $this->assertResponseStatus(200, $response);
        $this->assertNotEmpty((string) $response->getBody());
    }

    public function testCreate(): void
    {
        $data = [
            'name'       => 'Foo',
            'status'     => '1',
            'completion' => '50',
        ];

        // Create request with method and url and fetch response
        $request = $this->createJsonRequest('POST', '/', $data);
        $response = $this->handleRequest($request);

        // Assert response status & body
        $this->assertResponseStatus(200, $response);
        $this->assertJsonResponse([], $response);

        // Test alert message
        /** @var AlertStream */
        $ms = $this->ci->get(AlertStream::class);
        $messages = $ms->getAndClearMessages();
        $this->assertSame('info', array_reverse($messages)[0]['type']);
        $this->assertStringContainsString(print_r($data, true), array_reverse($messages)[0]['message']);
    }

    public function testCreateForFailValidation(): void
    {
        $data = [
            //'name'       => 'Foo',
            'status'     => '1',
            'completion' => '50',
        ];

        // Create request with method and url and fetch response
        $request = $this->createJsonRequest('POST', '/', $data);
        $response = $this->handleRequest($request);

        // Assert response status & body
        $this->assertResponseStatus(400, $response);
        $this->assertJsonResponse('Validation error', $response, 'title');
    }

    public function testEditForm(): void
    {
        // Create request with method and url and fetch response
        $request = $this->createJsonRequest('GET', '/1/edit');
        $response = $this->handleRequest($request);

        // Assert response status & body
        $this->assertResponseStatus(200, $response);
        $this->assertNotEmpty((string) $response->getBody());
    }

    public function testEditFormForProjectNotFound(): void
    {
        // Create request with method and url and fetch response
        $request = $this->createJsonRequest('GET', '/123/edit');
        $response = $this->handleRequest($request);

        // Assert response status & body
        $this->assertResponseStatus(404, $response);
        $this->assertJsonResponse([
            'title'       => 'Project not found',
            'description' => 'Requested project does not exist',
            'status'      => 404
        ], $response);
    }

    public function testUpdate(): void
    {
        $data = [
            'name'       => 'Foo',
            'status'     => '1',
            'completion' => '50',
        ];

        // Create request with method and url and fetch response
        $request = $this->createJsonRequest('PUT', '/1', $data);
        $response = $this->handleRequest($request);

        // Assert response status & body
        $this->assertResponseStatus(200, $response);
        $this->assertJsonResponse([], $response);

        // Test alert message
        /** @var AlertStream */
        $ms = $this->ci->get(AlertStream::class);
        $messages = $ms->getAndClearMessages();
        $this->assertSame('info', array_reverse($messages)[0]['type']);
        $this->assertStringContainsString(print_r($data, true), array_reverse($messages)[0]['message']);
    }

    public function testUpdateForFailedValidation(): void
    {
        $data = [
            //'name'       => 'Foo',
            'status'     => '1',
            'completion' => '50',
        ];

        // Create request with method and url and fetch response
        $request = $this->createJsonRequest('PUT', '/1', $data);
        $response = $this->handleRequest($request);

        // Assert response status & body
        $this->assertResponseStatus(400, $response);
        $this->assertJsonResponse('Validation error', $response, 'title');
    }

    public function testUpdateForProjectNotFound(): void
    {
        // Create request with method and url and fetch response
        $request = $this->createJsonRequest('PUT', '/123');
        $response = $this->handleRequest($request);

        // Assert response status & body
        $this->assertResponseStatus(404, $response);
        $this->assertJsonResponse([
            'title'       => 'Project not found',
            'description' => 'Requested project does not exist',
            'status'      => 404
        ], $response);
    }

    public function testDelete(): void
    {
        // Create request with method and url and fetch response
        $request = $this->createJsonRequest('DELETE', '/1');
        $response = $this->handleRequest($request);

        // Assert response status & body
        $this->assertResponseStatus(200, $response);
        $this->assertJsonResponse([], $response);

        // Test alert message
        /** @var AlertStream */
        $ms = $this->ci->get(AlertStream::class);
        $messages = $ms->getAndClearMessages();
        $this->assertSame('success', array_reverse($messages)[0]['type']);
        $this->assertSame('Project successfully deleted (or not)', array_reverse($messages)[0]['message']);
    }

    public function testDeleteForProjectNotFound(): void
    {
        // Create request with method and url and fetch response
        $request = $this->createJsonRequest('DELETE', '/123');
        $response = $this->handleRequest($request);

        // Assert response status & body
        $this->assertResponseStatus(404, $response);
        $this->assertJsonResponse([
            'title'       => 'Project not found',
            'description' => 'Requested project does not exist',
            'status'      => 404
        ], $response);
    }
}
