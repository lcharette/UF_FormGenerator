<?php

namespace UserFrosting\Sprinkle\FormGenerator;

/**
 * The FormGenerator class, which is used to return the `form` part of a Fortress
 * scheme for html form generator in Twig. Extend `ClientSideValidationAdapter`
 * to have access to the protected `_schema`object.
 *
 * @package FormGenerator
 * @author Louis Charette
 * @link http://www.userfrosting.com/
 */
class RequestSchema extends \UserFrosting\Fortress\RequestSchema {

    protected $_formData = array();
    protected $_formTypehead = array();

    /**
     * __construct function.
     * Overwrites the Fortress constructor so the file becomes optional
     *
     * @access public
     * @param string $file (default: "")
     * @return void
     */
    public function __construct($file = "")
    {
        if ($file !== "") {
            $this->loadSchema($file);
        }
    }

    /**
     * setSchema function.
     * Add a way to manually set the schema
     *
     * @access public
     * @param mixed $schema
     * @return void
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;
    }

    /**
     * Generate an array contining all nececerry value to generate a form
     * with Twig.
     *
     * @param array $data To populate the values of a form element.
     * @return array Returns the array of form element
     */
    public function initForm($data) {

        //Reset the thing
        $this->_formData = array();

        foreach ($this->getSchema() as $name => $value) {
            if (isset($value['form'])) {

                // Perfom data check and set default
                $value['form']['label'] = isset($value['form']['label']) ? $value['form']['label'] : "";

                // Add the value inside the form elements
                // Send the values as `genereteForm` argument
                if (isset($data->$name)) {
                    $value['form']['data'] = $data->$name;
                } else if (isset($data[$name])) {
                    $value['form']['data'] = $data[$name];
                }

                // The name of the field is usually the key, but we also add it to form
                // in case we need to manipulate it
                $value['form']['name'] = $name;

                // Setup translation
                // N.B.:     Nothing to do here for that. Will be handled by the
                //            Twig template when displayed

                //Add the data
                $this->_formData[$name] = $value['form'];
            }
        }
    }

    /**
     * setValue function.
     * Use to define the "value" element of an input after `initForm` is called.
     *
     * @access public
     * @param mixed $inputName
     * @param mixed $value
     * @return void
     */
    public function setValue($inputName, $value) {
        $this->setInputArgument($inputName, "data", $value);
    }

    /**
     * setInputArgument function.
     * Function used to overwrite the input tag property that are hardcoded in the Twig file.
     * Use `setCustomFormData` to set any other tag.
     *
     * @access public
     * @param string $inputName The input name where the argument will be added
     * @param string $property  The argument name. Example "data-color"
     * @param string $data      The value of the argument
     * @return void
     */
    public function setInputArgument($inputName, $property, $data) {
        if (isset($this->_formData[$inputName])) {
            $this->_formData[$inputName][$property] = $data;
        }
    }

    /**
     * setCustomInputArgument function.
     * The difference between this one and 'setFormData' is that this one you can add
     * whatever you want where the other one it's only for hardcoded data in the Twig Template.
     *
     * @access public
     * @param string $inputName The input name where the argument will be added
     * @param string $property  The argument name. Example "data-color"
     * @param string $data      The value of the argument
     * @return void
     */
    public function setCustomInputArgument($inputName, $property, $data) {
        if (isset($this->_formData[$inputName])) {
            if (!isset($this->_formData[$inputName]['custom'])) $this->_formData[$inputName]['custom'] = array();
            $this->_formData[$inputName]['custom'][$property] = $data;
        }
    }

    /**
     * wrapNames function.
     * Wrap the fields name in a top level array. Useful when using multiple
     * schemas at once or if the names are using dot syntaxt.
     * See : http://stackoverflow.com/a/20365198/445757
     *
     * @access public
     * @param mixed $arrayValue
     * @return void
     */
    public function wrapNames($arrayValue) {
        foreach ($this->_formData as $key => $value) {
            $this->_formData[$key]['name'] = $arrayValue."[".$value['name']."]";
        }
    }

    /**
     * genereteForm function.
     * Return the form data array
     *
     * @access public
     * @return array
     */
    public function genereteForm() {
        return $this->_formData;
    }
}
