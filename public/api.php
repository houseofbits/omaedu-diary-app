<?php

use Backend\Helpers\Application;
use Backend\Services\RouterService;
use Backend\Services\ChaptersService;
use Backend\Services\UserService;
use Backend\Services\ImageService;


require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->safeLoad();

$router = Application::get(RouterService::class);

$router->GET('user', function ($request, $response) {
    $response->toJSON($request->user->toJson());
});

$router->POST('user', function ($request, $response) {
    $userService = Application::get(UserService::class);
    $response->toJSON($userService->update($request->user, $request->getJSON()));
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

$router->GET('images', function ($request, $response) {
    $imageService = Application::get(ImageService::class);
    $response->toJSON($imageService->getChapterImages($request->user, $request->targetId));
}, true);

$router->POST('images', function ($request, $response) {
    $imageService = Application::get(ImageService::class);
    $response->toJSON($imageService->uploadImage($request->user, $request->targetId, $request->getUploadedFile()));
}, true);

$router->DELETE('images', function ($request, $response) {
    $imageService = Application::get(ImageService::class);
    $imageService->deleteImage($request->user, $request->targetId);
    $response->toJSON(['ok']);
}, true);

$router->GET('img', function ($request, $response) {
    $imageService = Application::get(ImageService::class);
    $response->filePassthru($imageService->getImagePath($request->user, $request->targetId));
}, true);