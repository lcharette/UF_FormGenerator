<?php

/*
 * UserFrosting Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2020 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\FormGenerator;

use Slim\App;
use UserFrosting\Routes\RouteDefinitionInterface;
use UserFrosting\Sprinkle\FormGenerator\Controller\FormGeneratorController;

class Routes implements RouteDefinitionInterface
{
    public function register(App $app): void
    {
        $app->get('/forms/confirm', [FormGeneratorController::class, 'confirm']);
    }
}
