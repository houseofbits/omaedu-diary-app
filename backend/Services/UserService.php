<?php

namespace Backend\Services;

use Backend\Repositories\UsersRepository;
use Backend\Entities\User;
use Backend\Structures\SettingsStructure;

class UserService
{
    public function __construct(
        private UsersRepository $usersRepository,
    ) {

    }

    public function get(User $user): array
    {
        return [
            'settings' => $user->getSettings(),
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
                ->setSettings(new SettingsStructure());

            $this->usersRepository->persist($user, true);
        }

        return $user;
    }

    public function update(User $user, array $userData): array
    {
        $settings = new SettingsStructure();
        $settings->pageTheme = $userData['settings']['pageTheme'];
        $settings->diaryTheme = $userData['settings']['diaryTheme'];
        $settings->isPageNumberingEnabled = $userData['settings']['isPageNumberingEnabled'];
        $settings->isTextJustifyEnabled = $userData['settings']['isTextJustifyEnabled'];

        $user->setSettings($settings);

        $this->usersRepository->persist($user, true);

        return $this->get($user);
    }
}
;