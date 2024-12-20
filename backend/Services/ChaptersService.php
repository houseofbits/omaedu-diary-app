<?php

namespace Backend\Services;

use DateTime;
use Backend\Repositories\ChaptersRepository;
use Backend\Entities\User;
use Backend\Entities\Chapter;
use Backend\Exceptions\HttpException;

class ChaptersService
{
    public function __construct(
        private ChaptersRepository $chaptersRepository,
        private ImageService $imageService,
    ) {

    }

    public function getAll(User $user)
    {
        $chapters = $user->getChapters()->toArray();

        usort($chapters, function (Chapter $a, Chapter $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });

        return array_map([$this, "createChapterListData"], $chapters);
    }

    public function get(User $user, int $id)
    {
        $chapter = $this->chaptersRepository->getChapter($id);

        if (!$chapter || $chapter->getUser()?->getId() !== $user->getId()) {
            throw new HttpException();
        }

        return $this->createChapterFullData($chapter);
    }

    public function delete(User $user, int $id)
    {
        $chapter = $this->chaptersRepository->getChapter($id);

        if (!$chapter || $chapter->getUser()?->getId() !== $user->getId()) {
            throw new HttpException();
        }

        foreach ($chapter->getImages() as $image) {
            $this->imageService->deleteImage($user, $image->getId());
        }

        $this->chaptersRepository->remove($chapter);

        return [];
    }

    public function create(User $user, array $data): array
    {
        $chapter = new Chapter();

        $chapter->setUser($user)
            ->setTitle(htmlspecialchars($data['title']))
            ->setStory(htmlspecialchars($data['story']))
            ->setLocation(htmlspecialchars($data['location']))
            ->setPeriod(htmlspecialchars($data['period']))
            ->setLayouts([]);

        $chapter = $this->chaptersRepository->save($chapter);

        return $this->createChapterFullData($chapter);
    }

    public function update(User $user, int $chapterId, array $data): array
    {
        $chapter = $this->chaptersRepository->getChapter($chapterId);

        if (!$chapter || $chapter->getUser()?->getId() !== $user->getId()) {
            throw new HttpException();
        }

        $chapter->setTitle(htmlspecialchars($data['title']))
            ->setStory(htmlspecialchars($data['story']))
            ->setLocation(htmlspecialchars($data['location']))
            ->setPeriod(htmlspecialchars($data['period']))
            ->setLayouts($data['layouts'] ?? []);

        $chapter = $this->chaptersRepository->save($chapter);

        return $this->createChapterFullData($chapter);
    }

    private function createChapterListData(Chapter $chapter): array
    {
        return [
            "id" => $chapter->getId(),
            "title" => htmlspecialchars_decode($chapter->getTitle()),
            "period" => htmlspecialchars_decode($chapter->getPeriod()),
            "createdAt" => $chapter->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    private function createChapterFullData(Chapter $chapter): array
    {
        return [
            "id" => $chapter->getId(),
            "title" => htmlspecialchars_decode($chapter->getTitle()),
            "story" => htmlspecialchars_decode($chapter->getStory()),
            "location" => htmlspecialchars_decode($chapter->getLocation()),
            "period" => htmlspecialchars_decode($chapter->getPeriod()),
            "layouts" => $chapter->getLayouts(),
            "createdAt" => $chapter->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
