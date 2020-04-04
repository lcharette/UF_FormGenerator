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
     *
     * @param string $file
     * @param array  $data
     * @param array  $expected
     * @dataProvider formProvider
     */
    public function testForm(string $file, array $data, array $expected): void
    {
        // Get Schema
        $loader = new YamlFileLoader($this->basePath . $file);
        $schema = new RequestSchemaRepository($loader->load());

        // Generate the form
        $form = new Form($schema, $data);

        // Test to make sure the class creation is fine
        $this->assertInstanceof(Form::class, $form);

        // Test the form generation
        $generatedForm = $form->generate();
        $this->assertIsArray($generatedForm);

        // We test the generated result
        $this->assertSame($expected, $generatedForm);
    }

    /**
     * Data provider for tests
     */
    public function formProvider(): array
    {
        return [
            // WITH NO DATA
            [
                '/good.json',
                [],
                [
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
                ],
            ],
            // WITH DATA
            [
                '/good.json',
                [
                    'name' => 'Bar project',
                ],
                [
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
                    ],
                ],
            ],
            // WITH BAD DATA
            [
                '/bad.json',
                [],
                [
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
                ],
            ],
        ];
    }
}
