<?php

/*
 * UF Form Generator.
 *
 * @link https://github.com/lcharette/UF_FormGenerator
 *
 * @copyright Copyright (c) 2017 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\FormGenerator;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use UserFrosting\Fortress\RequestSchema\RequestSchemaInterface;
use UserFrosting\Sprinkle\Core\Facades\Debug;

/**
 * Form Class.
 *
 * The FormGenerator class, which is used to return the `form` part from a Fortress
 * schema for html form generator in Twig.
 */
class Form
{
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
    protected $formNamespace = '';

    /**
     * Constructor.
     *
     * @param RequestSchemaInterface $schema
     * @param array|object           $data   (default: [])
     *
     * @return void
     */
    public function __construct(RequestSchemaInterface $schema, $data = [])
    {
        $this->setSchema($schema);
        $this->setData($data);
    }

    /**
     * Set the form current values.
     *
     * @param array|object $data The form values
     */
    public function setData($data)
    {
        if ($data instanceof Collection || $data instanceof Model) {
            $this->data = $data->toArray();
        } elseif (is_array($data) || $data instanceof Repository) {
            $this->data = $data;
        } else {
            throw new \InvalidArgumentException('Data must be an array, a Collection, a Model or a Repository');
        }
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
     * Use to define the value of a form input when `setData` is already set.
     *
     * @param mixed $inputName
     * @param mixed $value
     *
     * @return void
     */
    public function setValue($inputName, $value)
    {
        $this->data[$inputName] = $value;
    }

    /**
     * Function used to overwrite the input argument from a schema file
     * Can also be used to overwrite an argument hardcoded in the Twig file.
     * Use `setCustomFormData` to set any other tag.
     *
     * @param string $inputName The input name where the argument will be added
     * @param string $property  The argument name. Example "data-color"
     * @param string $data      The value of the argument
     *
     * @return void
     */
    public function setInputArgument($inputName, $property, $data)
    {
        if ($this->schema->has($inputName)) {
            // Get the element and force set the property
            $element = $this->schema->get($inputName);
            $element['form'][$property] = $data;

            // Push back the modifyed element in the schema
            $this->schema->set($inputName, $element);
        }
    }

    /**
     * Function used to set options of a select element. Shortcut for using
     * `setInputArgument` and `setValue`.
     *
     * @param string $inputName The select name to add options to
     * @param array  $data      An array of `value => label` options
     * @param string $selected  The selected key
     *
     * @return void
     */
    public function setOptions($inputName, $data = [], $selected = null)
    {
        // Set opdations
        $this->setInputArgument($inputName, 'options', $data);

        // Set the value
        if (!is_null($selected)) {
            $this->setValue($inputName, $selected);
        }
    }

    /**
     * Function to set the form namespace.
     * Use the form namespace to wrap the fields name in a top level array.
     * Useful when using multiple schemas at once or if the names are using dot syntaxt.
     * See : http://stackoverflow.com/a/20365198/445757.
     *
     * @param string $namespace
     *
     * @return void
     */
    public function setFormNamespace($namespace)
    {
        $this->formNamespace = $namespace;
    }

    /**
     * getFieldName function
     *
     * This will check the form array prefix and also the field name to see if it belongs to a sub array
     * and based on this it returns a proper html name for the form element
     * e.g. formNamespace='thisform', $name='meta.shirt_size' will return thisform[meta][shirt_size]
     * @param [string] $name
     * @return fiendname
     */

    public function getFieldName($name)
    {
        $suffix = $prefix = '';
        if (($this->formNamespace != '')) {
            $prefix = $this->formNamespace . "[";
            $suffix = "]";
        }
        if (strpos($name, '.') !== false) {
            $name = str_replace('.', '][', $name);
            $suffix =  "]";
        }
        $fiendname = $prefix . $name . $suffix;
        return $fiendname;
        //        $name = ($this->formNamespace != '') ? ($this->formNamespace . '[' . $name . ']') : $name;
    }

    /**
     * getFieldValue function
     *
     * Will traverse thru the data array and return the field value, if the field name consists of a subarray element
     * then it will fetch the subarray for
     * e.g. $name='meta.shirt_size' will return $data['meta']['shirt_size']
     * or it will just return $data[$name]
     * null if no value exists
     * @param [type] $name
     * @param [type] $data
     * @return value
     */
    public function getFieldValue($name, $data)
    {
        if (strpos($name, '.') !== false) {
            $fldarr = explode('.', $name);
            //Debug::debug("Line 197 the fldarr is ", $fldarr);
            $thisdata = $data;
            foreach ($fldarr as $field) {
                $thisdata = isset($thisdata[$field]) ? $thisdata[$field] : null;
                if ($thisdata == null) {
                    return null;
                }
            }
            $value = $thisdata;
        } else {
            $value = isset($data[$name]) ? $data[$name] : null;
        }
        return $value;
    }
    /**
     * Generate an array contining all nececerry value to generate a form
     * with Twig.
     *
     * @return array The form fields data
     */
    public function generate()
    {
        $form = collect([]);
        // Loop all the the fields in the schema
        foreach ($this->schema->all() as $name => $input) {
            // Get the value from the data
            //$value = $this->getFieldValue($name, $this->data);
            $value = isset($this->data[$name]) ? $this->data[$name] : null;

            // Add the namespace to the name if it's defined
            if ($this->formNamespace != '') {
                $hasarray = strpos($this->formNamespace, '[');
                if ($hasarray !== false) {
                    $domain = substr($this->formNamespace, 0, ($hasarray));
                } else {
                    $domain = $this->formNamespace;
                }
                $input['form']['data-source'] = $domain . '.' . $name;
            } else {
                $input['form']['data-source'] = $name;
            }
            //$name = $this->getFieldName($name);
            $name = ($this->formNamespace != '') ? ($this->formNamespace . '[' . $name . ']') : $name;
            // Get the element class and make sure it exist
            // If the YAML does not have FORM section then use the, so use the UF 
            // Validation Schama to figure this out
            if (isset($input['form']['type'])) {
                $type = $input['form']['type'];
            } else if (isset($input['validators']['member_of'])) {
                // figure out if this is a select field if this is not specified
                $type = $input['form']['type'] = 'select';
                foreach ($input['validators']['member_of']['values'] as $optvalue) {
                    $input['form']['options'][$optvalue] = $optvalue;
                }
            } else {
                // default all fields to text
                $type = 'text';
            }
            $type = 'UserFrosting\\Sprinkle\\FormGenerator\\Element\\' . Str::studly($type);

            if (!isset($input['form']['label'])) {
                if ((isset($input['validators']['required']['label']))) {
                    // see if label name is spefified in the validators
                    $input['form']['label'] = str_replace('&', '', $input['validators']['required']['label']);
                } else {
                    // Clean up the label values
                    $input['form']['label'] = ucwords(strtolower(str_replace(['[', '_', ']'], [' ', ''], $name)));
                }
            }

            if (!isset($input['form']['placeholder'])) {
                $input['form']['placeholder'] = $input['form']['label'];
            }
            // If class doesn't esist, default to Text element
            if (!class_exists($type)) {
                $type = 'UserFrosting\\Sprinkle\\FormGenerator\\Element\\Text';
            }

            // Create a new instance
            $element = new $type($name, $input['form'], $value);
            // Push data to `$form`
            $form->put($name, $element->parse());
        }

        return $form->toArray();
    }
}
