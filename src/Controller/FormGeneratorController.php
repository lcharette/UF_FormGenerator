<?php

namespace UserFrosting\Sprinkle\FormGenerator\Controller;

use UserFrosting\Sprinkle\Core\Controller\SimpleController;

/**
 * FormGeneratorController Class
 *
 * Controller class for /forms/confirm/* URLs.  Handles rendering the confirm dialog
 *
 * @package FormGenerator
 * @author Louis Charette
 * @link https://github.com/lcharette/UF_FormGenerator
 */
class FormGeneratorController extends SimpleController {

    /**
     * confirm function.
     *
     * @access public
     * @param mixed $request
     * @param mixed $response
     * @param mixed $args
     * @return void
     */
    public function confirm($request, $response, $args) {
		$this->ci->view->render($response, 'FormGenerator/confirm.html.twig', $request->getQueryParams());
    }
}
