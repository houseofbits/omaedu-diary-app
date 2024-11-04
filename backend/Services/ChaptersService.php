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
            "title" => $chapter->getTitle(),
            "period" => $chapter->getPeriod(),
            "createdAt" => $chapter->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    private function createChapterFullData(Chapter $chapter): array
    {
        return [
            "id" => $chapter->getId(),
            "title" => $chapter->getTitle(),
            "story" => $chapter->getStory(),
            "location" => $chapter->getLocation(),
            "period" => $chapter->getPeriod(),
            "layouts" => $chapter->getLayouts(),
            "createdAt" => $chapter->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
