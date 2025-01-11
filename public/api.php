<?php

use Backend\Helpers\Application;
use Backend\Services\RouterService;
use Backend\Services\ChaptersService;
use Backend\Services\UserService;
use Backend\Services\ImageService;
use Backend\Services\DiariesService;

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->safeLoad();

$router = Application::get(RouterService::class);

$router->GET('user', function ($request, $response) {
    $userService = Application::get(UserService::class);
    $response->toJSON($userService->get($request->user));
});

$router->POST('user', function ($request, $response) {
    $userService = Application::get(UserService::class);
    $response->toJSON($userService->update($request->user, $request->getJSON()));
});

//All chapters
$router->GET('chapters', function ($request, $response) {
    $chaptersService = Application::get(ChaptersService::class);
    $response->toJSON($chaptersService->getAll($request->user, $request->targetId));
}, true);

//Single chapter
$router->GET('chapter', function ($request, $response) {
    $chaptersService = Application::get(ChaptersService::class);
    $response->toJSON($chaptersService->get($request->user, $request->targetId));
}, true);

//Single chapter
$router->DELETE('chapter', function ($request, $response) {
    $chaptersService = Application::get(ChaptersService::class);
    $response->toJSON($chaptersService->delete($request->user, $request->targetId));
}, true);

//Create chapter
$router->POST('chapter', function ($request, $response) {
    $chaptersService = Application::get(ChaptersService::class);
    $response->toJSON($chaptersService->create($request->user, $request->getJSON()));
}, false);

//Update chapter
$router->PUT('chapter', function ($request, $response) {
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

//All diaries
$router->GET('diaries', function ($request, $response) {
    $diariesService = Application::get(DiariesService::class);
    $response->toJSON($diariesService->getAll($request->user));
}, false);

$router->GET('diaries', function ($request, $response) {
    $diariesService = Application::get(DiariesService::class);
    $response->toJSON($diariesService->get($request->user, $request->targetId));
}, true);

$router->POST('diaries', function ($request, $response) {
    $diariesService = Application::get(DiariesService::class);
    $response->toJSON($diariesService->create($request->user, $request->getJSON()));
});

$router->PUT('diaries', function ($request, $response) {
    $diariesService = Application::get(DiariesService::class);
    $response->toJSON($diariesService->update($request->user, $request->targetId, $request->getJSON()));
}, true);

//Single diary
$router->DELETE('diaries', function ($request, $response) {
    $diariesService = Application::get(DiariesService::class);
    $response->toJSON($diariesService->delete($request->user, $request->targetId));
}, true);