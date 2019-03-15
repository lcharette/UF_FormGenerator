<?php

/*
 * UF Form Generator.
 *
 * @link https://github.com/lcharette/UF_FormGenerator
 *
 * @copyright Copyright (c) 2017 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Tests\Unit;

use UserFrosting\Fortress\RequestSchema\RequestSchemaRepository;
use UserFrosting\Sprinkle\FormGenerator\Element\InputInterface;
use UserFrosting\Sprinkle\FormGenerator\Form;
use UserFrosting\Support\Repository\Loader\YamlFileLoader;
use UserFrosting\Tests\TestCase;

/**
 * FormGeneratorTest
 * The FormGenerator unit tests.
 */
class FormGeneratorTest extends TestCase
{
    public $basePath;

    public function setUp()
    {
        parent::setUp();
        $this->basePath = __DIR__ . '/data';
    }

    /**
     * Test the base `Test` element class works on it's own.
     */
    public function testTextFormElement()
    {
        // Get Schema
        $loader = new YamlFileLoader($this->basePath . '/good.json');
        $schema = new RequestSchemaRepository($loader->load());

        // Get TextInput from the `name` element of the schema
        $inputSchema = $schema['name']['form'];
        $textInput = new \UserFrosting\Sprinkle\FormGenerator\Element\Text('name', $inputSchema);

        // Test instanceof $textInput
        $this->assertInstanceof(InputInterface::class, $textInput);

        // Parse the input
        $text = $textInput->parse();

        // Test the parsing
        $expected = [
            'type' => 'text',
            'label' => 'Project Name',
            'icon' => 'fa-flag',
            'autocomplete' => 'off',
            'class' => 'form-control',
            'placeholder' => 'Project Name',
            'name' => 'name',
            'id' => 'field_name',
            'value' => '',
            'data-source' => 'name',
        ];

        // We test the generated result
        $this->assertEquals($expected, $text);
    }

    /**
     * This test make sure the `Text` element works correctly when a current
     * value is passed to the constructor. Should return the same as the
     * previous test, but with the `value` setup instead of empty.
     */
    public function testTextFormElementWithData()
    {
        // Get Schema
        $loader = new YamlFileLoader($this->basePath . '/good.json');
        $schema = new RequestSchemaRepository($loader->load());

        // Get TextInput from the `name` element of the schema
        $inputSchema = $schema['name']['form'];
        $textInput = new \UserFrosting\Sprinkle\FormGenerator\Element\Text('name', $inputSchema, 'The Bar project');

        // Test instanceof $textInput
        $this->assertInstanceof(InputInterface::class, $textInput);

        // Parse the input
        $text = $textInput->parse();

        // Test the parsing
        $expected = [
            'type' => 'text',
            'label' => 'Project Name',
            'icon' => 'fa-flag',
            'autocomplete' => 'off',
            'class' => 'form-control',
            'placeholder' => 'Project Name',
            'name' => 'name',
            'id' => 'field_name',
            'value' => 'The Bar project',
            'data-source' => 'name',
        ];

        // We test the generated result
        $this->assertEquals($expected, $text);
    }

    /**
     * This test is the same as the one before, but we test the `owener` field with some data
     * This make sure the `default` schema field will work correctly when empty data is passed.
     */
    public function testTextFormElementWithEmptyData()
    {
        // Get Schema
        $loader = new YamlFileLoader($this->basePath . '/good.json');
        $schema = new RequestSchemaRepository($loader->load());

        // Get TextInput from the `name` element of the schema
        $inputSchema = $schema['owner']['form'];
        $textInput = new \UserFrosting\Sprinkle\FormGenerator\Element\Text('owner', $inputSchema, '');

        // Test instanceof $textInput
        $this->assertInstanceof(InputInterface::class, $textInput);

        // Parse the input
        $text = $textInput->parse();

        // Test the parsing
        $expected = [
            'label' => 'Project Owner',
            'autocomplete' => 'off',
            'class' => 'form-control',
            'value' => '', //Shoudn't be a value here ! "" is overwritting "Foo"
            'name' => 'owner',
            'id' => 'owner',
            'type' => 'text',
            'icon' => 'fa-user',
            'placeholder' => 'Project Owner',
            'data-source' => 'owner',
            'default' => 'Foo',
        ];

        // We test the generated result
        $this->assertEquals($expected, $text);
    }

    /**
     * Test the Form Class.
     * Run the test with no current values (empty form).
     */
    public function testForm()
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
        $this->assertInternalType('array', $generatedForm);

        // Test one of the form input
        $expected = [
            'number' => [
                'label' => 'Project Number',
                'autocomplete' => 'off',
                'class' => 'form-control',
                'value' => '',
                'name' => 'number',
                'id' => 'field_number',
                'type' => 'number',
                'icon' => 'fa-edit',
                'placeholder' => 'Project Number',
                'data-source' => 'number',
            ],
            'owner' => [
                'label' => 'Project Owner',
                'autocomplete' => 'off',
                'class' => 'form-control',
                'value' => 'Foo',
                'name' => 'owner',
                'id' => 'owner',
                'type' => 'text',
                'icon' => 'fa-user',
                'placeholder' => 'Project Owner',
                'default' => 'Foo',
                'data-source' => 'owner'
            ],
            'name' => [
                'label' => 'Project Name',
                'autocomplete' => 'off',
                'class' => 'form-control',
                'value' => '',
                'name' => 'name',
                'id' => 'field_name',
                'type' => 'text',
                'icon' => 'fa-flag',
                'placeholder' => 'Project Name',
                'data-source' => 'name',
            ],
            'description' => [
                'label' => 'Project Description',
                'autocomplete' => 'off',
                'class' => 'form-control',
                'value' => '',
                'name' => 'description',
                'rows' => 5,
                'id' => 'field_description',
                'type' => 'textarea',
                'icon' => 'fa-pencil',
                'placeholder' => 'Project Description',
                'data-source' => 'description',
            ],
            'status' => [
                'label' => 'Project Status',
                'class' => 'form-control js-select2',
                'value' => '',
                'name' => 'status',
                'id' => 'field_status',
                'type' => 'select',
                'options' => [
                    0 => 'Closed',
                    1 => 'Open',
                ],
                'placeholder' => 'Status',
                'data-source' => 'status',
            ],
            'active' => [
                'label' => 'Active',
                'class' => 'js-icheck',
                'name' => 'active',
                'id' => 'field_active',
                'type' => 'checkbox',
                'binary' => true,
                'placeholder' => 'Active',
                'data-source' => 'active',
            ],
            'hidden' => [
                'label' => 'Hidden',
                'value' => 'Something',
                'name' => 'hidden',
                'id' => 'field_hidden',
                'type' => 'hidden',
                'placeholder' => 'Hidden',
                'data-source' => 'hidden',
            ],
            'alert' => [
                'label' => 'Alert',
                'class' => 'alert-success',
                'icon' => 'fa-check',
                'value' => 'You\'re awesome!',
                'name' => 'alert',
                'type' => 'alert',
                'placeholder' => 'Alert',
                'data-source' => 'alert',
            ],
        ];

        // We test the generated result
        $this->assertEquals($expected, $generatedForm);
    }

    /**
     * Test the Form Clas with values to make sure filled form works correctly.
     */
    public function testFormWithData()
    {
        // Get Schema
        $loader = new YamlFileLoader($this->basePath . '/good.json');
        $schema = new RequestSchemaRepository($loader->load());

        // The data
        $data = [
            'name' => 'Bar project',
            'owner' => '',
            'description' => "The bar project is less awesome, but at least it's open.",
            'status' => 1,
            'hiddenString' => 'The Bar secret code is...',
            'completion' => 12,
            'active' => true,
        ];

        // Generate the form
        $form = new Form($schema, $data);

        // Test to make sure the class creation is fine
        $this->assertInstanceof(Form::class, $form);

        // Test the form generation
        $generatedForm = $form->generate();
        $this->assertInternalType('array', $generatedForm);

        // Test one of the form input
        $expected = [
            'number' => [
                'label' => 'Project Number',
                'autocomplete' => 'off',
                'class' => 'form-control',
                'value' => '',
                'name' => 'number',
                'id' => 'field_number',
                'type' => 'number',
                'icon' => 'fa-edit',
                'placeholder' => 'Project Number',
                'data-source' => 'number',
            ],
            'name' => [
                'label' => 'Project Name',
                'autocomplete' => 'off',
                'class' => 'form-control',
                'value' => 'Bar project', //Value here !
                'name' => 'name',
                'id' => 'field_name',
                'type' => 'text',
                'icon' => 'fa-flag',
                'placeholder' => 'Project Name',
                'data-source' => 'name',
            ],
            'owner' => [
                'label' => 'Project Owner',
                'autocomplete' => 'off',
                'class' => 'form-control',
                'value' => '', //Shoudn't be a value here ! "" is overwritting "Foo"
                'name' => 'owner',
                'id' => 'owner',
                'type' => 'text',
                'icon' => 'fa-user',
                'placeholder' => 'Project Owner',
                'default' => 'Foo',
                'data-source' => 'owner',
            ],
            'description' => [
                'label' => 'Project Description',
                'autocomplete' => 'off',
                'class' => 'form-control',
                'value' => 'The bar project is less awesome, but at least it\'s open.', //Value here !
                'name' => 'description',
                'rows' => 5,
                'id' => 'field_description',
                'type' => 'textarea',
                'icon' => 'fa-pencil',
                'placeholder' => 'Project Description',
                'data-source' => 'description',
            ],
            'status' => [
                'label' => 'Project Status',
                'class' => 'form-control js-select2',
                'value' => 1, //Value here !
                'name' => 'status',
                'id' => 'field_status',
                'type' => 'select',
                'options' => [
                    0 => 'Closed',
                    1 => 'Open',
                ],
                'data-source' => 'status',
            ],
            'active' => [
                'label' => 'Active',
                'class' => 'js-icheck',
                'name' => 'active',
                'id' => 'field_active',
                'type' => 'checkbox',
                'checked' => 'checked', //Value here !
                'binary' => true,
                'data-source' => 'active',
            ],
            'hidden' => [
                'value' => 'Something',
                'name' => 'hidden',
                'id' => 'field_hidden',
                'type' => 'hidden',
                'data-source' => 'hidden',
            ],
            'alert' => [
                'class' => 'alert-success',
                'icon' => 'fa-check',
                'value' => 'You\'re awesome!',
                'name' => 'alert',
                'type' => 'alert',
                'data-source' => 'alert',
            ],
        ];

        // We test the generated result
        $this->assertEquals($expected, $generatedForm);
    }

    /**
     * Test a non existant input type. It's supposed to not find the class and
     * default back to the `Text` element class.
     */
    public function testUndefinedFormElement()
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
        $this->assertInternalType('array', $generatedForm);

        // Test one of the form input
        $expected = [
            'type' => 'foo',
            'autocomplete' => 'off',
            'class' => 'form-control',
            'name' => 'myField',
            'id' => 'field_myField',
            'value' => '',
            'data-source' => 'myField',
        ];

        // We test the generated result
        $this->assertEquals($expected, $generatedForm['myField']);
    }
}
