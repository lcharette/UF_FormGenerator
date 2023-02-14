<?php

declare(strict_types=1);

/*
 * UserFrosting Form Generator
 *
 * @link      https://github.com/lcharette/UF_FormGenerator
 * @copyright Copyright (c) 2020 Louis Charette
 * @license   https://github.com/lcharette/UF_FormGenerator/blob/master/LICENSE (MIT License)
 */

namespace UserFrosting\Sprinkle\FormGeneratorExample;

use UserFrosting\Sprinkle\Core\Exceptions\NotFoundException;
use UserFrosting\Support\Message\UserMessage;

final class ProjectNotFoundException extends NotFoundException
{
    protected string $title = 'Project not found';
    protected string|UserMessage $description = 'Requested project does not exist';
}
