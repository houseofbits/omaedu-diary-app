<?php

namespace Backend\Services;

use Backend\Repositories\HealthRecordRepository;

use Backend\Entities\User;
use Backend\Entities\Diary;
use Backend\Entities\Chapter;
use Backend\Exceptions\HttpException;

class HealthRecordService
{
    public function __construct(
        private HealthRecordRepository $healthRecordRepository,
    ) {

    }

}