<?php

/*
 * UserFrosting Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2020 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\FormGenerator\Tests\Unit;

use UserFrosting\Sprinkle\Core\Tests\withController;
use UserFrosting\Sprinkle\FormGeneratorExample\Controller\Controller;
use UserFrosting\Support\Exception\NotFoundException;
use UserFrosting\Tests\TestCase;

/**
 * ControllerTest
 * The FormGeneratorExample unit tests for supplied controllers.
 */
class ControllerTest extends TestCase
{
    use withController;

    /**
     * @return Controller
     */
    public function testConstructor(): Controller
    {
        $controller = new Controller($this->ci);
        $this->assertInstanceOf(Controller::class, $controller);

        return $controller;
    }

    /**
     * @depends testConstructor
     * @param Controller $controller
     */
    public function testMain(Controller $controller): void
    {
        $request = $this->getRequest();

        $result = $controller->main($request, $this->getResponse(), []);

        // Perform asertions
        $body = (string) $result->getBody();
        $this->assertSame($result->getStatusCode(), 200);
        $this->assertNotSame('', $body);
    }

    /**
     * @depends testConstructor
     * @param Controller $controller
     */
    public function testCreateForm(Controller $controller): void
    {
        $request = $this->getRequest()->withQueryParams([
            'box_id' => 'formGeneratorModal',
        ]);

        $result = $controller->createForm($request, $this->getResponse(), []);

        // Perform asertions
        $body = (string) $result->getBody();
        $this->assertSame($result->getStatusCode(), 200);
        $this->assertNotSame('', $body);
    }

    /**
     * @depends testConstructor
     * @param Controller $controller
     */
    public function testCreate(Controller $controller): void
    {
        $data = [
            'name'       => 'Foo',
            'status'     => '1',
            'completion' => '50',
        ];

        $request = $this->getRequest()->withParsedBody($data);

        $result = $controller->create($request, $this->getResponse(), []);

        // Perform asertions
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $result);
        $this->assertSame($result->getStatusCode(), 200);
        $this->assertJson((string) $result->getBody());
        $this->assertSame('[]', (string) $result->getBody());

        // Test alert message
        $ms = $this->ci->alerts;
        $messages = $ms->getAndClearMessages();
        $this->assertSame('info', end($messages)['type']);
        $this->assertStringContainsString(print_r($data, true), end($messages)['message']);
    }

    /**
     * @depends testConstructor
     * @param Controller $controller
     */
    public function testCreateForFailValidation(Controller $controller): void
    {
        $request = $this->getRequest()->withParsedBody([
            //'name'       => 'Foo',
            'status'     => '1',
            'completion' => '50',
        ]);

        $result = $controller->create($request, $this->getResponse(), []);

        // Perform asertions
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $result);
        $this->assertSame($result->getStatusCode(), 400);
        $this->assertSame('', (string) $result->getBody());

        // Test alert message
        $ms = $this->ci->alerts;
        $messages = $ms->getAndClearMessages();
        $this->assertSame('danger', end($messages)['type']);
        $this->assertSame('Please specify a value for <strong>Project name</strong>.', end($messages)['message']);
    }

    /**
     * @depends testConstructor
     * @param Controller $controller
     */
    public function testEditForm(Controller $controller): void
    {
        $request = $this->getRequest()->withQueryParams([
            'box_id' => 'formGeneratorModal',
        ]);

        $result = $controller->editForm($request, $this->getResponse(), ['project_id' => 1]);

        // Perform asertions
        $body = (string) $result->getBody();
        $this->assertSame($result->getStatusCode(), 200);
        $this->assertNotSame('', $body);
    }

    /**
     * @depends testConstructor
     * @param Controller $controller
     */
    public function testEditFormForProjectNotFound(Controller $controller): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Project not found');
        $controller->editForm($this->getRequest(), $this->getResponse(), ['project_id' => 123]);
    }

    /**
     * @depends testConstructor
     * @param Controller $controller
     */
    public function testUpdate(Controller $controller): void
    {
        $data = [
            'name'       => 'Foo',
            'status'     => '1',
            'completion' => '50',
        ];

        $request = $this->getRequest()->withParsedBody($data);

        $result = $controller->update($request, $this->getResponse(), ['project_id' => 1]);

        // Perform asertions
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $result);
        $this->assertSame($result->getStatusCode(), 200);
        $this->assertJson((string) $result->getBody());
        $this->assertSame('[]', (string) $result->getBody());

        // Test alert message
        $ms = $this->ci->alerts;
        $messages = $ms->getAndClearMessages();
        $this->assertSame('info', end($messages)['type']);
        $this->assertStringContainsString(print_r($data, true), end($messages)['message']);
    }

    /**
     * @depends testConstructor
     * @param Controller $controller
     */
    public function testUpdateForFailedValidation(Controller $controller): void
    {
        $data = [
            //'name'       => 'Foo',
            'status'     => '1',
            'completion' => '50',
        ];

        $request = $this->getRequest()->withParsedBody($data);

        $result = $controller->update($request, $this->getResponse(), ['project_id' => 1]);

        // Perform asertions
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $result);
        $this->assertSame($result->getStatusCode(), 400);
        $this->assertSame('', (string) $result->getBody());

        // Test alert message
        $ms = $this->ci->alerts;
        $messages = $ms->getAndClearMessages();
        $this->assertSame('danger', end($messages)['type']);
        $this->assertSame('Please specify a value for <strong>Project name</strong>.', end($messages)['message']);
    }

    /**
     * @depends testConstructor
     * @param Controller $controller
     */
    public function testUdateForProjectNotFound(Controller $controller): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Project not found');
        $controller->update($this->getRequest(), $this->getResponse(), ['project_id' => 123]);
    }

    /**
     * @depends testConstructor
     * @param Controller $controller
     */
    public function testDelete(Controller $controller): void
    {
        $result = $controller->delete($this->getRequest(), $this->getResponse(), ['project_id' => 1]);

        // Perform asertions
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $result);
        $this->assertSame($result->getStatusCode(), 200);
        $this->assertJson((string) $result->getBody());
        $this->assertSame('[]', (string) $result->getBody());

        // Test alert message
        $ms = $this->ci->alerts;
        $messages = $ms->getAndClearMessages();
        $this->assertSame('success', end($messages)['type']);
        $this->assertSame('Project successfully deleted (or not)', end($messages)['message']);
    }

    /**
     * @depends testConstructor
     * @param Controller $controller
     */
    public function testDeleteForProjectNotFound(Controller $controller): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Project not found');
        $controller->delete($this->getRequest(), $this->getResponse(), ['project_id' => 123]);
    }
}
