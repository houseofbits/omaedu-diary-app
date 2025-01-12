<?php

namespace Backend\Services;

use Backend\Repositories\HealthRecordRepository;
use Backend\Repositories\DiariesRepository;
use Backend\Entities\User;
use Backend\Entities\Diary;
use Backend\Entities\HealthRecord;
use Backend\Exceptions\HttpException;

class HealthRecordService
{
    public function __construct(
        private HealthRecordRepository $healthRecordRepository,
        private DiariesRepository $diariesRepository,
    ) {

    }

    public function getAll(User $user, int $diaryId): array
    {
        $records = $this->healthRecordRepository->findBy(['diary' => $diaryId]);

        usort($records, function (HealthRecord $a, HealthRecord $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });


        return array_map([$this, "createListData"], $records);
    }

    public function delete(User $user, int $id)
    {
        $record = $this->healthRecordRepository->getHealthRecord($id);

        if (!$record || $record->getDiary()?->getUser()->getId() !== $user->getId()) {
            throw new HttpException();
        }

        $this->healthRecordRepository->remove($record);

        return [];
    }

    public function create(User $user, array $data): array
    {
        $diary = $this->diariesRepository->findOneBy(['id' => $data['diaryId']]);

        if (!$diary) {
            throw new HttpException(404, "Diary not found");
        }

        $record = new HealthRecord();

        $record->setDiary($diary)
            ->setData($data['data']);

        $record = $this->healthRecordRepository->save($record);

        return $this->createListData($record);
    }

    public function update(User $user, int $id, array $data): array
    {
        $record = $this->healthRecordRepository->getHealthRecord($id);

        if (!$record || $record->getDiary()?->getUser()->getId() !== $user->getId()) {
            throw new HttpException();
        }

        $record->setData($data);

        $record = $this->healthRecordRepository->save($record);

        return $this->createListData($record);
    }

    private function createListData(HealthRecord $record): array
    {
        return [
            "id" => $record->getId(),
            "data" => $record->getData(),
            "createdAt" => $record->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}