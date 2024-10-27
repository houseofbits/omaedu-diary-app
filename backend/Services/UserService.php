<?php

namespace Backend\Services;

use Backend\Repositories\UsersRepository;
use Backend\Entities\User;
use Backend\Structures\SettingsStructure;

class UserService {

    public function __construct(
            private UsersRepository $usersRepository,
        ) {

    }

    private function decryptUserName(string $credentials): ?string {

        //TODO openssl_decrypt

        return "user1";
    }

    public function getUser(string $credentials): ?User {
        $userName = $this->decryptUserName($credentials);
        if ($userName === null) {
            return null;
        }

        $userModel = $this->usersRepository->getUser($userName);
        if ($userModel === null) {
            $userModel = new User();
            $userModel
                ->setUserName($userName)
                ->setDiaryTitle("Diary title")
                ->setSettings(new SettingsStructure());

            $this->usersRepository->persist($userModel, true);
        }

        return $userModel;
    }
};