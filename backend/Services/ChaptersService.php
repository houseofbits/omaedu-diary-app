<?php

namespace Backend\Services;

use DateTime;
use Backend\Repositories\ChaptersRepository;
use Backend\Repositories\DiariesRepository;

use Backend\Entities\User;
use Backend\Entities\Diary;
use Backend\Entities\Chapter;
use Backend\Exceptions\HttpException;

class ChaptersService
{
    public function __construct(
        private ChaptersRepository $chaptersRepository,
        private DiariesRepository $diariesRepository,
        private ImageService $imageService,
    ) {

    }

    public function getAll(User $user, int $diaryId)
    {
        $diary = $this->diariesRepository->findOneBy(['id' => $diaryId]);

        if (!$diary) {
            throw new HttpException(404, "Diary not found");
        }

        $chapters = $diary->getChapters()->toArray();    

        usort($chapters, function (Chapter $a, Chapter $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });

        return array_map([$this, "createChapterListData"], $chapters);
    }

    public function get(User $user, int $id)
    {
        $chapter = $this->chaptersRepository->findOneBy(['id' => $id]);

        if (!$chapter) {
            throw new HttpException();
        }

        return $this->createChapterFullData($chapter);
    }

    public function delete(User $user, int $id)
    {
        $chapter = $this->chaptersRepository->getChapter($id);

        if (!$chapter || $chapter->getDiary()?->getUser()->getId() !== $user->getId()) {
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
        $diary = $this->diariesRepository->findOneBy(['id' => $data['diaryId']]);

        if (!$diary) {
            throw new HttpException(404, "Diary not found");
        }

        $chapter = new Chapter();

        $chapter->setDiary($diary)
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

        if (!$chapter || $chapter->getDiary()?->getUser()->getId() !== $user->getId()) {
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
            "diaryId" => $chapter->getDiary()->getId(),
            "createdAt" => $chapter->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
