<?php

namespace Backend\Services;

use DateTime;
use Backend\Entities\Image;
use Backend\Entities\User;
use Backend\Entities\Chapter;
use Backend\Entities\Diary;
use Backend\Repositories\ImagesRepository;
use Backend\Repositories\ChaptersRepository;
use Backend\Exceptions\HttpException;

class ImageService
{
    public function __construct(
        private ImagesRepository $imagesRepository,
        private ChaptersRepository $chaptersRepository,
    ) {

    }

    public function getChapterImages(User $user, int $chapterId): array
    {
        $chapter = $this->chaptersRepository->getChapter($chapterId);
        if (!$chapter) {
            throw new HttpException(500, "Not authorized");
        }

        return array_map([$this, 'createData'], $chapter->getImages()->toArray());
    }

    public function uploadImage(User $user, int $chapterId, ?array $imageFile): array
    {
        if (!$imageFile) {
            throw new HttpException(500, "File not present");
        }

        $chapter = $this->chaptersRepository->getChapter($chapterId);
        if (!$chapter || $chapter->getDiary()->getUser()->getId() !== $user->getId()) {
            throw new HttpException(500, "Not authorized");
        }

        $check = getimagesize($imageFile["tmp_name"]);
        if (!$check) {
            throw new HttpException(500, "Not an image");
        }
        $imageFileType = strtolower(pathinfo($imageFile["name"], PATHINFO_EXTENSION));

        $image = new Image();
        $image->setFileName(htmlspecialchars(basename($imageFile["name"])))
            ->setExtension($imageFileType)
            ->setOrder(1)
            ->setChapter($chapter);

        $this->imagesRepository->save($image);

        $uploadsDir = $_ENV['UPLOADS_DIR'] ?? '../uploads';
        $targetDir = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/' . trim($uploadsDir) . '/';
        $targetFile = $targetDir . $image->getId() . '.' . $imageFileType;

        if (!move_uploaded_file($imageFile["tmp_name"], $targetFile)) {
            $this->imagesRepository->remove($image);

            throw new HttpException(500, "Failed to move");
        }

        return $this->createData($image);
    }

    public function getImagePath(User $user, int $imageId): string
    {
        $image = $this->imagesRepository->find($imageId);
        if (!$image) {
            throw new HttpException(500, "Not found");
        }

        if ($image->getChapter()->getDiary()->getUser()->getId() !== $user->getId()) {
            throw new HttpException(500, "Not authorized");
        }

        $uploadsDir = $_ENV['UPLOADS_DIR'] ?? '../uploads';
        $targetDir = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/' . trim($uploadsDir) . '/';
        $targetFile = $targetDir . $image->getId() . '.' . $image->getExtension();

        return $targetFile;
    }

    public function deleteImage(User $user, int $imageId): void
    {
        $image = $this->imagesRepository->find($imageId);
        if (!$image) {
            throw new HttpException(500, "Not found");
        }

        if ($image->getChapter()->getDiary()->getUser()->getId() !== $user->getId()) {
            throw new HttpException(500, "Not authorized");
        }

        $imagePath = $this->getImagePath($user, $imageId);

        $this->imagesRepository->remove($image);

        unlink($imagePath);
    }

    private function createData(Image $image): array
    {
        return [
            "id" => $image->getId(),
            "fileName" => $image->getFileName(),
            "order" => $image->getOrder(),
        ];
    }
}