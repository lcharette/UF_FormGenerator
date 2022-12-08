<?php

declare(strict_types=1);

/*
 * UserFrosting Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2020 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\FormGenerator\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

/**
 * Controller class for /forms/confirm/* URLs.  Handles rendering the confirm dialog.
 */
class FormGeneratorController
{
    /**
     * Display the confirmation dialog.
     * Request type: GET.
     *
     * @param Request  $request
     * @param Response $response
     * @param Twig     $view
     */
    public function __invoke(Request $request, Response $response, Twig $view): Response
    {
        return $view->render($response, 'FormGenerator/confirm.html.twig', $request->getQueryParams());
    }
}
