<?php

use Backend\Helpers\Application;
use Backend\Services\RouterService;
use Backend\Services\ChaptersService;

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->safeLoad();

$router = Application::get(RouterService::class);

$router->GET('user', function ($request, $response) {
    $response->toJSON($request->user->toJson());
});

//All chapters
$router->GET('chapters', function ($request, $response) {
    $chaptersService = Application::get(ChaptersService::class);
    $response->toJSON($chaptersService->getAll($request->user));
}, false);

//Single chapter
$router->GET('chapters', function ($request, $response) {
    $chaptersService = Application::get(ChaptersService::class);
    $response->toJSON($chaptersService->get($request->user, $request->targetId));
}, true);

//Single chapter
$router->DELETE('chapters', function ($request, $response) {
    $chaptersService = Application::get(ChaptersService::class);
    $response->toJSON($chaptersService->delete($request->user, $request->targetId));
}, true);

//Create chapter
$router->POST('chapters', function ($request, $response) {
    $chaptersService = Application::get(ChaptersService::class);
    $response->toJSON($chaptersService->create($request->user, $request->getJSON()));
}, false);

//Update chapter
$router->PUT('chapters', function ($request, $response) {
    $chaptersService = Application::get(ChaptersService::class);
    $response->toJSON($chaptersService->update($request->user, $request->targetId, $request->getJSON()));
}, true);

