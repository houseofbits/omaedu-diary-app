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

        $this->chaptersRepository->delete($chapter);

        return [];
    }    

    public function create(User $user, array $data): array
    {
        $chapter = new Chapter();

        $chapter->setUser($user);
        $chapter->setTitle($data['title']);
        $chapter->setStory($data['story']);
        $chapter->setLocation($data['location']);
        $chapter->setCreatedAt(new DateTime($data['date']));

        $chapter = $this->chaptersRepository->create($chapter);

        return $this->createChapterFullData($chapter);
    }

    public function update(User $user, int $chapterId, array $data): array
    {
        $chapter = $this->chaptersRepository->getChapter($chapterId);

        if (!$chapter || $chapter->getUser()?->getId() !== $user->getId()) {
            throw new HttpException();
        }

        $chapter->setTitle($data['title']);
        $chapter->setStory($data['story']);
        $chapter->setLocation($data['location']);

        $chapter = $this->chaptersRepository->update($chapter);

        return $this->createChapterFullData($chapter);
    }

    private function createChapterListData(Chapter $chapter): array
    {
        return [
            "id" => $chapter->getId(),
            "title" => $chapter->getTitle(),
            "createdAt" => $chapter->getCreatedAt()->format("Y-m-d"),
        ];
    }

    private function createChapterFullData(Chapter $chapter): array
    {
        return [
            "id" => $chapter->getId(),
            "title" => $chapter->getTitle(),
            "story" => $chapter->getStory(),
            "location" => $chapter->getLocation(),
            "createdAt" => $chapter->getCreatedAt()->format("Y-m-d"),
            // templates
            // images
        ];
    }
}
