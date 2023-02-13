<?php

/*
 * UserFrosting Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2020 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\FormGeneratorExample;

use Slim\App;
use UserFrosting\Routes\RouteDefinitionInterface;

class Routes implements RouteDefinitionInterface
{
    public function register(App $app): void
    {
        /* LIST */
        $app->get('/', [Controller::class, 'main'])->setName('index');

        /* CREATE */
        $app->get('/new', [Controller::class, 'createForm'])->setName('FG.createForm');
        $app->post('/', [Controller::class, 'create'])->setName('FG.create');

        /* EDIT */
        $app->get('/{project_id:[0-9]+}/edit', [Controller::class, 'editForm'])->setName('FG.editForm');
        $app->put('/{project_id:[0-9]+}', [Controller::class, 'update'])->setName('FG.update');

        /* DELETE */
        $app->delete('/{project_id:[0-9]+}', [Controller::class, 'delete'])->setName('FG.delete');
    }
}
