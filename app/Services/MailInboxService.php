<?php

namespace App\Services;

use Webklex\IMAP\Facades\Client;

class MailInboxService
{
    public static function checkIfEmailExist(string $email): bool
    {
        $client = Client::account('test');
        $client->connect();

        $folders = $client->getFolders();

        foreach ($folders as $folder) {

            $messages = $folder->messages()->recent()->get();

            foreach ($messages as $message) {
                if (str_contains($message->getTextBody(), $email)) {
                    return false;
                }
            }
        }

        return true;
    }
}
