<?php

/*
 * UserFrosting Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2020 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\FormGenerator\Tests\Unit;

use UserFrosting\Fortress\RequestSchema\RequestSchemaRepository;
use UserFrosting\Sprinkle\FormGenerator\Form;
use UserFrosting\Support\Repository\Loader\YamlFileLoader;
use UserFrosting\Tests\TestCase;

/**
 * FormGeneratorTest
 * The FormGenerator unit tests.
 */
class FormTest extends TestCase
{
    /** @var string */
    public $basePath;

    public function setUp(): void
    {
        parent::setUp();
        $this->basePath = __DIR__ . '/data';
    }

    /**
     * Test the Form Class.
     * Run the test with no current values (empty form).
     */
    public function testForm(): void
    {
        // Get Schema
        $loader = new YamlFileLoader($this->basePath . '/good.json');
        $schema = new RequestSchemaRepository($loader->load());

        // Generate the form
        $form = new Form($schema);

        // Test to make sure the class creation is fine
        $this->assertInstanceof(Form::class, $form);

        // Test the form generation
        $generatedForm = $form->generate();
        $this->assertIsArray($generatedForm);

        // Test one of the form input
        $expected = [
            'name' => [
                'autocomplete' => 'off',
                'class'        => 'form-control',
                'value'        => '',
                'name'         => 'name',
                'id'           => 'field_name',
                'type'         => 'text',
                'label'        => 'Project Name',
                'icon'         => 'fa-flag',
                'placeholder'  => 'Project Name',
            ],
        ];

        // We test the generated result
        $this->assertSame($expected, $generatedForm);
    }

    /**
     * Test the Form Clas with values to make sure filled form works correctly.
     */
    public function testFormWithData(): void
    {
        // Get Schema
        $loader = new YamlFileLoader($this->basePath.'/good.json');
        $schema = new RequestSchemaRepository($loader->load());

        // The data
        $data = [
            'name' => 'Bar project',
        ];

        // Generate the form
        $form = new Form($schema, $data);

        // Test to make sure the class creation is fine
        $this->assertInstanceof(Form::class, $form);

        // Test the form generation
        $generatedForm = $form->generate();
        $this->assertIsArray($generatedForm);

        // Test one of the form input
        $expected = [
            'name' => [
                'autocomplete' => 'off',
                'class'        => 'form-control',
                'value'        => 'Bar project', //Value's here !
                'name'         => 'name',
                'id'           => 'field_name',
                'type'         => 'text',
                'label'        => 'Project Name',
                'icon'         => 'fa-flag',
                'placeholder'  => 'Project Name',
            ]
        ];

        // We test the generated result
        $this->assertEquals($expected, $generatedForm);
    }

    /**
     * Test a non existant input type. It's supposed to not find the class and
     * default back to the `Text` element class.
     */
    public function testUndefinedFormElement(): void
    {
        // Get Schema
        $loader = new YamlFileLoader($this->basePath . '/bad.json');
        $schema = new RequestSchemaRepository($loader->load());

        // Generate the form
        $form = new Form($schema);

        // Test to make sure the class creation is fine
        $this->assertInstanceof(Form::class, $form);

        // Test the form generation
        $generatedForm = $form->generate();
        $this->assertIsArray($generatedForm);

        // Test one of the form input
        $expected = [
            'myField' => [
                'autocomplete' => 'off',
                'class'        => 'form-control',
                'value'        => '',
                'name'         => 'myField',
                'id'           => 'field_myField',
                'type'         => 'foo', // Will still be foo, but the whole element is parsed using the text parser
            ],
            'myOtherField' => [
                'autocomplete' => 'off',
                'class'        => 'form-control',
                'value'        => 'Bar',
                'name'         => 'myOtherField',
                'id'           => 'field_myOtherField',
                'type'         => 'text', // Will be added by the FORM class
            ],
        ];

        // We test the generated result
        $this->assertSame($expected, $generatedForm);
    }
}
