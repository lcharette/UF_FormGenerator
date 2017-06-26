<?php
/**
 * UF Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2017 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */
namespace UserFrosting\Sprinkle\FormGenerator\Controller;

use UserFrosting\Sprinkle\Core\Controller\SimpleController;

/**
 * FormGeneratorController Class
 *
 * Controller class for /forms/confirm/* URLs.  Handles rendering the confirm dialog
 */
class FormGeneratorController extends SimpleController {

    /**
     * Display the confirmation dialog
     */
    public function confirm($request, $response, $args) {
		$this->ci->view->render($response, 'FormGenerator/confirm.html.twig', $request->getQueryParams());
    }
}
