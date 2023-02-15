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

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use UserFrosting\Alert\AlertStream;
use UserFrosting\Fortress\Adapter\JqueryValidationAdapter;
use UserFrosting\Fortress\RequestDataTransformer;
use UserFrosting\Fortress\RequestSchema;
use UserFrosting\Fortress\ServerSideValidator;
use UserFrosting\I18n\Translator;
use UserFrosting\Sprinkle\Core\Exceptions\ValidationException;
use UserFrosting\Sprinkle\FormGenerator\Form;
use UserFrosting\Sprinkle\FormGeneratorExample\Data\Project;

/**
 * FormGeneratorExampleController Class.
 *
 * Controller class for /formgenerator/* URLs.
 */
class Controller
{
    /**
     * Display a list of all the projects
     * Request type: GET.
     *
     * @param Response $response
     * @param Twig     $view
     *
     * @return Response
     */
    public function main(
        Response $response,
        Twig $view
    ): Response {
        // Get all projects
        // This can be replace by a database Model. We hardcode it here in the helper class for demo purposes.
        $projects = Project::all();

        return $view->render($response, 'pages/formgenerator.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * Renders the form for creating a new project.
     *
     * This does NOT render a complete page.  Instead, it renders the HTML for the form, which can be embedded in other pages.
     * The form is rendered in "modal" (for popup) or "panel" mode, depending on the template used
     *
     * @param Request              $request
     * @param Response             $response
     * @param RouteParserInterface $router
     * @param Translator           $translator
     * @param Twig                 $view
     *
     * @return Response
     */
    public function createForm(
        Request $request,
        Response $response,
        RouteParserInterface $router,
        Translator $translator,
        Twig $view,
    ): Response {
        $get = $request->getQueryParams();

        // Load validator rules
        $schema = new RequestSchema('schema://forms/formgenerator.json');
        $validator = new JqueryValidationAdapter($schema, $translator);

        // Generate the form
        $form = new Form($schema);

        return $view->render($response, 'FormGenerator/modal.html.twig', [
            'box_id'        => $get['box_id'] ?? 'formgenerator-create-form',
            'box_title'     => 'Create project',
            'submit_button' => 'Create',
            'form_action'   => $router->urlFor('FG.create'),
            'fields'        => $form->generate(),
            'validators'    => $validator->rules('json', true),
        ]);
    }

    /**
     * Processes the request to create a new project.
     *
     * @param Request     $request
     * @param Response    $response
     * @param AlertStream $ms
     * @param Translator  $translator
     *
     * @return Response
     */
    public function create(
        Request $request,
        Response $response,
        AlertStream $ms,
        Translator $translator,
    ): Response {
        // Request POST data
        $post = (array) $request->getParsedBody();

        // Load the request schema
        $schema = new RequestSchema('schema://forms/formgenerator.json');

        // Whitelist and set parameter defaults
        $transformer = new RequestDataTransformer($schema);
        $data = $transformer->transform($post);

        // Validate, and halt on validation errors.
        $validator = new ServerSideValidator($schema, $translator);
        if ($validator->validate($data) === false && is_array($validator->errors())) {
            $e = new ValidationException();
            $e->addErrors($validator->errors());

            throw $e;
        }

        // Create the item.
        // This is where the project would be saved to the database.
        // This can be replace by a database Model.
        // $project = new Project($data);
        // $project->save();

        // Success message
        $ms->addMessageTranslated('success', 'Project successfully created (or not)');
        $ms->addMessageTranslated('info', 'The form data: <br />' . print_r($data, true));

        $payload = json_encode([], JSON_THROW_ON_ERROR);
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Renders the form for editing an existing project.
     *
     * This does NOT render a complete page.  Instead, it renders the HTML for the form, which can be embedded in other pages.
     * The form is rendered in "modal" (for popup) or "panel" mode, depending on the template used
     *
     * @param int                  $project_id
     * @param Request              $request
     * @param Response             $response
     * @param RouteParserInterface $router
     * @param Translator           $translator
     * @param Twig                 $view
     *
     * @return Response
     */
    public function editForm(
        int $project_id,
        Request $request,
        Response $response,
        RouteParserInterface $router,
        Translator $translator,
        Twig $view,
    ): Response {
        $get = $request->getQueryParams();

        // Get the project to edit.
        // This can be replace by a database Model. We hardcode it here in the helper class for demo purposes.
        $project = Project::find($project_id);

        // Make sure a project was found.
        if ($project === null) {
            throw new ProjectNotFoundException();
        }

        // Load validator rules
        $schema = new RequestSchema('schema://forms/formgenerator.json');
        $validator = new JqueryValidationAdapter($schema, $translator);

        // Generate the form
        $form = new Form($schema, $project);

        // Render the template / form
        return $view->render($response, 'FormGenerator/modal.html.twig', [
            'box_id'        => $get['box_id'] ?? 'formgenerator-edit-form',
            'box_title'     => 'Edit project',
            'submit_button' => 'Edit',
            'form_action'   => $router->urlFor('FG.update', ['project_id' => (string) $project_id]),
            'form_method'   => 'PUT', //Send form using PUT instead of "POST"
            'fields'        => $form->generate(),
            'validators'    => $validator->rules('json', true),
        ]);
    }

    /**
     * Processes the request to update an existing project's details.
     *
     * @param int         $project_id
     * @param Request     $request
     * @param Response    $response
     * @param AlertStream $ms
     * @param Translator  $translator
     *
     * @return Response
     */
    public function update(
        int $project_id,
        Request $request,
        Response $response,
        AlertStream $ms,
        Translator $translator,
    ): Response {
        // Get the target object & make sure a project was found.
        $project = Project::find($project_id);
        if ($project === null) {
            throw new ProjectNotFoundException();
        }

        // Request POST data
        $post = (array) $request->getParsedBody();

        // Load the request schema
        $schema = new RequestSchema('schema://forms/formgenerator.json');

        // Whitelist and set parameter defaults
        $transformer = new RequestDataTransformer($schema);
        $data = $transformer->transform($post);

        // Validate, and halt on validation errors.
        $validator = new ServerSideValidator($schema, $translator);
        if ($validator->validate($data) === false && is_array($validator->errors())) {
            $e = new ValidationException();
            $e->addErrors($validator->errors());

            throw $e;
        }

        // Update the project
        // This is where you would save the changes to the database...
        // $project->fill($data)->save();

        //Success message!
        $ms->addMessageTranslated('success', 'Project successfully updated (or not)');
        $ms->addMessageTranslated('info', 'The form data: <br />' . print_r($data, true));

        $payload = json_encode([], JSON_THROW_ON_ERROR);
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Processes the request to delete an existing project.
     *
     * @param int         $project_id
     * @param Response    $response
     * @param AlertStream $ms
     *
     * @return Response
     */
    public function delete(
        int $project_id,
        Response $response,
        AlertStream $ms,
    ): Response {
        // Get the target object & make sure a project was found.
        $project = Project::find($project_id);
        if ($project === null) {
            throw new ProjectNotFoundException();
        }

        // Delete the project
        // This is where you would delete the project from the database
        // $project->delete();

        // Nice and simple message
        $ms->addMessageTranslated('success', 'Project successfully deleted (or not)');

        $payload = json_encode([], JSON_THROW_ON_ERROR);
        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
