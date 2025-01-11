<?php

namespace Backend\Services;

use Backend\Entities\User;
use Backend\Entities\Diary;
use Backend\Structures\DiarySettingsStructure;
use Backend\Structures\HealthRecordSettingsStructure;
use Backend\Repositories\DiariesRepository;
use Backend\Exceptions\HttpException;

class DiariesService
{
    public function __construct(
        private DiariesRepository $diariesRepository,
    ) {

    }

    public function getAll(User $user)
    {
        $diaries = $user->getDiaries()->toArray();

        return array_map([$this, "createDiaryListData"], $diaries);
    }

    public function get(User $user, int $diaryId): array
    {
        $diary = $this->diariesRepository->findOneBy(['id' => $diaryId]);

        if (!$diary) {
            throw new HttpException(404, "Diary not found");
        }

        return $diary->toJson();
    }

    public function create(User $user, array $data): array
    {
        $diary = new Diary();

        $settings = [];
        if ($data['type'] == "diary") {
            $settings = (array) $this->createDiarySettingsStructure($data);
        } else {
            $settings = (array) $this->createHealthRecordSettingsStructure($data);
        }

        $diary->setUser($user)
            ->setType($data['type'])
            ->setDiaryTitle(htmlspecialchars($data['title']))
            ->setDiaryDescription(htmlspecialchars($data['description']))
            ->setSettings($settings);

        $diary = $this->diariesRepository->save($diary);

        return ['diaryId' => $diary->getId()];
    }

    public function update(User $user, int $id, array $data): array
    {
        $diary = $this->diariesRepository->findOneBy(['id' => $id]);

        if (!$diary) {
            throw new HttpException(404, "Diary not found");
        }

        $settings = [];
        if ($data['type'] == "diary") {
            $settings = (array) $this->createDiarySettingsStructure($data);
        } else {
            $settings = (array) $this->createHealthRecordSettingsStructure($data);
        }

        $diary->setDiaryTitle(htmlspecialchars($data['title']))
            ->setDiaryDescription(htmlspecialchars($data['description']))
            ->setSettings($settings);

        $diary = $this->diariesRepository->save($diary);
        
        return $diary->toJson();
    }

    public function delete(User $user, int $id)
    {
        $diary = $this->diariesRepository->findOneBy(['id' => $id]);

        if (!$diary || $diary->getUser()->getId() !== $user->getId()) {
            throw new HttpException(404, "Diary not found");
        }

        if (count($diary->getChapters()) > 0) {
            throw new HttpException(404, "Diary not empty");
        }

        $this->diariesRepository->remove($diary);

        return [];
    }    

    private function createDiarySettingsStructure(array $data): DiarySettingsStructure
    {
        $settings = new DiarySettingsStructure();
        $settings->diaryTheme = $data['settings']['diaryTheme'];
        $settings->isPageNumberingEnabled = $data['settings']['isPageNumberingEnabled'];
        $settings->isTextJustifyEnabled = $data['settings']['isTextJustifyEnabled'];

        return $settings;
    }

    private function createHealthRecordSettingsStructure(array $data): HealthRecordSettingsStructure
    {
        $settings = new HealthRecordSettingsStructure();
        $settings->columns = $data['settings']['columns'];

        return $settings;
    }

    private function createDiaryListData(Diary $diary): array
    {
        return [
            "id" => $diary->getId(),
            "type" => $diary->getType(),
            "title" => htmlspecialchars_decode($diary->getDiaryTitle()),
            "description" => htmlspecialchars_decode($diary->getDiaryDescription()),
        ];
    }
}
