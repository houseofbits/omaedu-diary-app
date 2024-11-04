<?php

namespace Backend\Services;

use DateTime;
use Backend\Repositories\UsersRepository;
use Backend\Entities\User;
use Backend\Entities\Chapter;
use Backend\Structures\SettingsStructure;

class UserService
{
    public function __construct(
        private UsersRepository $usersRepository,
    ) {

    }

    public function get(User $user): array
    {
        $initialDate = new DateTime("now");
        $minDate = array_reduce($user->getChapters()->toArray(), function (DateTime $carry, Chapter $chapter) {
            return ($chapter->getCreatedAt() < $carry) ? $chapter->getCreatedAt() : $carry;
        }, $initialDate);

        $initialDate = new DateTime("now");
        $maxDate = array_reduce($user->getChapters()->toArray(), function (DateTime $carry, Chapter $chapter) {
            return ($chapter->getCreatedAt() > $carry) ? $chapter->getCreatedAt() : $carry;
        }, $initialDate);        

        return [
            'diaryTitle' => $user->getDiaryTitle(),
            'settings' => $user->getSettings(),
            'datePeriodFrom' => $minDate->format('Y-m-d'),
            'datePeriodTo' => $maxDate->format('Y-m-d'),
        ];
    }

    private function decryptUserName(string $credentials): ?string
    {
        // $credentials = "8yP5aDBBnj1kS9Xxtn6akjhmUkhUTjVWWDczZUtYWkVNTDlSWVE9PQ";  //user1

        $key = $_ENV['SECRET_KEY'];
        $method = "AES-256-CBC";

        //Encryption
        // $userName = "user1"; //EXAMPLE 
        // $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
        // $encrypted = openssl_encrypt($userName, $method, $key, 0, $iv);
        // $encrypted = base64_encode($iv . $encrypted);
        // var_dump($encrypted);

        $encrypted = base64_decode($credentials);
        $iv = substr($encrypted, 0, openssl_cipher_iv_length($method));
        $encrypted = substr($encrypted, openssl_cipher_iv_length($method));
        $decrypted = @openssl_decrypt($encrypted, $method, $key, 0, $iv);

        if ($decrypted != false) {
            return $decrypted;
        }

        return null;
    }

    public function getUser(string $credentials): ?User
    {
        $userName = $this->decryptUserName($credentials);
        if ($userName === null) {
            return null;
        }

        $user = $this->usersRepository->getUser($userName);
        if ($user === null) {
            $user = new User();
            $user
                ->setUserName($userName)
                ->setDiaryTitle("Diary title")
                ->setSettings(new SettingsStructure());

            $this->usersRepository->persist($user, true);
        }

        return $user;
    }

    public function update(User $user, array $userData): array
    {
        $settings = new SettingsStructure();
        $settings->theme = $userData['settings']['theme'];

        $user->setSettings($settings);
        $user->setDiaryTitle($userData['diaryTitle']);

        $this->usersRepository->persist($user, true);

        return $this->get($user);
    }
}
;