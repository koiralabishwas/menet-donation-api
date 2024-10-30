<?php

namespace App\Providers;

use Google\Exception;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_Permission;
use Illuminate\Support\ServiceProvider;

class GoogleDriveProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * @throws Exception
     */
    public static function uploadFile($file, $file_name): string
    {
        $client = new Google_Client;
        $client->setAuthConfig(config_path('google_client_secret.json'));
        $client->addScope(Google_Service_Drive::DRIVE);

        $google_drive_service = new Google_Service_Drive($client);

        $file_metadata = new Google_Service_Drive_DriveFile(['name' => $file_name]);

        $content = file_get_contents($file->getRealPath());

        $uploaded_file = $google_drive_service->files->create($file_metadata, [
            'data' => $content,
            'uploadType' => 'multipart',
            'fields' => 'id',
        ]);

        $permission = new Google_Service_Drive_Permission;
        $permission->setType('anyone');
        $permission->setRole('reader');
        $google_drive_service->permissions->create($uploaded_file->id, $permission);

        return $uploaded_file->id;
    }

    /**
     * @throws \Google\Service\Exception
     * @throws Exception
     */
    public static function delete_file($file_id): string
    {
        $client = new Google_Client;
        $client->setAuthConfig(config_path('google_client_secret.json'));
        $client->addScope(Google_Service_Drive::DRIVE);
        $client->setAccessType('offline'); // Ensure that access is granted

        $google_drive_service = new Google_Service_Drive($client);

        $file = $google_drive_service->files->get($file_id, ['fields' => 'name']);
        $file_name = $file->getName();

        $google_drive_service->files->delete($file_id);

        return $file_name;
    }
}
