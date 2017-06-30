<?php
/**
 * UF Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2017 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */
namespace UserFrosting\Sprinkle\FormGenerator;

use UserFrosting\Fortress\RequestSchema\RequestSchemaInterface;
use UserFrosting\Sprinkle\Core\Facades\Debug;

/**
 * Form Class
 *
 * The FormGenerator class, which is used to return the `form` part from a Fortress
 * schema for html form generator in Twig.
 */
class Form {

    /**
     * @var RequestSchemaInterface The form fields definition
     */
    protected $schema;

    /**
     * @var array|object The form values
     */
    protected $data = [];

    /**
     * @var string Use this to wrap form fields in top-level array
     */
    protected $formNamespace = "";

    /**
     * Constructor
     *
     * @access public
     * @param RequestSchemaInterface $schema
     * @param array|object $data (default: [])
     * @return void
     */
    public function __construct(RequestSchemaInterface $schema, $data = [])
    {
        $this->setSchema($schema);
        $this->setData($data);
    }

    /**
     * Set the form current values
     *
     * @param array|object $data The form values
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Set the schema for this validator.
     *
     * @param RequestSchemaInterface $schema A RequestSchemaInterface object, containing the form definition.
     */
    public function setSchema(RequestSchemaInterface $schema)
    {
        $this->schema = $schema;
    }

    /**
     * Use to define the value of a form input when `setData` is already set
     *
     * @access public
     * @param mixed $inputName
     * @param mixed $value
     * @return void
     */
    public function setValue($inputName, $value) {
        $this->data[$inputName] = $value;
    }

    /**
     * Function used to overwrite the input argument from a schema file
     * Can also be used to overwrite an argument hardcoded in the Twig file.
     * Use `setCustomFormData` to set any other tag.
     *
     * @access public
     * @param string $inputName The input name where the argument will be added
     * @param string $property  The argument name. Example "data-color"
     * @param string $data      The value of the argument
     * @return void
     */
    public function setInputArgument($inputName, $property, $data) {
        if ($this->schema->has($inputName)) {
            // Get the element and force set the property
            $element = $this->schema->get($inputName);
            $element['form'][$property] = $data;

            // Push back the modifyed element in the schema
            $this->schema->set($inputName, $element);
        }
    }

    /**
     * Function to set the form namespace.
     * Use the form namespace to wrap the fields name in a top level array.
     * Useful when using multiple schemas at once or if the names are using dot syntaxt.
     * See : http://stackoverflow.com/a/20365198/445757
     *
     * @access public
     * @param string $namespace
     * @return void
     */
    public function setFormNamespace($namespace)
    {
	    $this->formNamespace = $namespace;
    }

    /**
     * Generate an array contining all nececerry value to generate a form
     * with Twig.
     *
     * @access public
     * @return array The form fields data
     */
    public function generate() {

        $form = [];

        foreach ($this->schema->all() as $name => $input) {
            if (isset($input['form'])) {

	            /*foreach ($input['form'] as $argument => $value) {
	            	Debug::debug("$argument => $value");
	            	$input['form'][$argument] = $this->transformInput($argument, $value);
				}*/

                // Perfom data check and set default
                $input['form']['label'] = isset($input['form']['label']) ? $input['form']['label'] : "";

                // Add the value inside the form elements
                if (isset($this->data->$name)) {
                    $input['form']['value'] = $this->data->$name;
                } else if (isset($this->data[$name])) {
                    $input['form']['value'] = $this->data[$name];
                }

                // The name of the field is usually the key, but we also add it to form
                // in case we need to manipulate it
                $input['form']['name'] = ($this->formNamespace != "") ? $this->formNamespace."[".$name."]" : $name;

                // Also need an id for that field
                $input['form']['id'] = isset($input['form']['id']) ? $input['form']['id'] : "field_" . $input['form']['name'];

                //Add the data
                $form[$name] = $input['form'];
            }
        }

        return $form;
    }
}
