<?php

namespace UserFrosting\Sprinkle\FormGenerator\Controller;

use Interop\Container\ContainerInterface;

/**
 * CompagnieController Class
 *
 * Controller class for /compagnie/* URLs.  Handles compagnie-related activities, including listing compagnies, CRUD for compagnies, etc.
 *
 * @package Gaston
 * @author Louis Charette
 * @link https://github.com/lcharette/GASTON
 */
class FormGeneratorController {

    protected $_types = [];

    /**
     * @var ContainerInterface The global container object, which holds all your services.
     */
    protected $ci;

    /**
     * __construct function.
     * Create a new CompagnieController object.
     *
     * @access public
     * @param UserFrosting $app The main UserFrosting app.
     * @return void
     */
    public function __construct(ContainerInterface $ci){
        $this->ci = $ci;
    }

    /**
     * bordereau function.
     *
     * @access public
     * @param mixed $project_id
     * @param mixed $view_name
     * @return void
     */
    public function confirm($request, $response, $args) {
		$this->ci->view->render($response, 'FormGenerator/confirm.twig', $request->getQueryParams());
    }
}
