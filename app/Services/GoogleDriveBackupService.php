<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GoogleDriveBackupService
{
    protected ?GoogleClient $client = null;
    protected ?GoogleDrive $service = null;

    /**
     * Initialize the Google Drive client
     */
    public function initClient(): bool
    {
        try {
            $credentialsPath = config('backup.google_drive.credentials_path');
            $tokenPath = config('backup.google_drive.token_path');

            if (!$credentialsPath || !file_exists($credentialsPath)) {
                Log::warning('Google Drive: credentials file not found at ' . $credentialsPath);
                return false;
            }

            $this->client = new GoogleClient();
            $this->client->setApplicationName('TU Administrasi Backup');
            $this->client->setScopes([GoogleDrive::DRIVE_FILE]);
            $this->client->setAuthConfig($credentialsPath);
            $this->client->setAccessType('offline');
            $this->client->setPrompt('select_account consent');

            // Load previously stored token
            if ($tokenPath && file_exists($tokenPath)) {
                $accessToken = json_decode(file_get_contents($tokenPath), true);
                $this->client->setAccessToken($accessToken);

                // Refresh token if expired
                if ($this->client->isAccessTokenExpired()) {
                    if ($this->client->getRefreshToken()) {
                        $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                        file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
                    } else {
                        Log::warning('Google Drive: Token expired and no refresh token available');
                        return false;
                    }
                }
            } else {
                Log::warning('Google Drive: No token file found. Run php artisan backup:auth first');
                return false;
            }

            $this->service = new GoogleDrive($this->client);
            return true;
        } catch (\Exception $e) {
            Log::error('Google Drive init failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the Google Client for auth flow
     */
    public function getClient(): ?GoogleClient
    {
        if (!$this->client) {
            $credentialsPath = config('backup.google_drive.credentials_path');
            if (!$credentialsPath || !file_exists($credentialsPath)) {
                return null;
            }

            $this->client = new GoogleClient();
            $this->client->setApplicationName('TU Administrasi Backup');
            $this->client->setScopes([GoogleDrive::DRIVE_FILE]);
            $this->client->setAuthConfig($credentialsPath);
            $this->client->setAccessType('offline');
            $this->client->setPrompt('select_account consent');
        }
        return $this->client;
    }

    /**
     * Upload a file to Google Drive
     */
    public function uploadFile(string $localPath, string $driveFolderName = null): ?string
    {
        if (!$this->service) {
            if (!$this->initClient()) return null;
        }

        try {
            $folderId = $this->getOrCreateFolder($driveFolderName ?? config('backup.google_drive.folder_name', 'TU_Admin_Backup'));

            $fileMetadata = new DriveFile([
                'name' => basename($localPath),
                'parents' => [$folderId],
            ]);

            $content = file_get_contents($localPath);
            $mimeType = mime_content_type($localPath) ?: 'application/octet-stream';

            $file = $this->service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id, name, size',
            ]);

            Log::info("Google Drive: Uploaded {$file->name} (ID: {$file->id})");
            return $file->id;
        } catch (\Exception $e) {
            Log::error('Google Drive upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get or create a folder on Google Drive
     */
    protected function getOrCreateFolder(string $folderName): string
    {
        // Search for existing folder
        $response = $this->service->files->listFiles([
            'q' => "mimeType='application/vnd.google-apps.folder' and name='{$folderName}' and trashed=false",
            'spaces' => 'drive',
            'fields' => 'files(id, name)',
        ]);

        if (count($response->files) > 0) {
            return $response->files[0]->id;
        }

        // Create new folder
        $folderMetadata = new DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
        ]);

        $folder = $this->service->files->create($folderMetadata, [
            'fields' => 'id',
        ]);

        Log::info("Google Drive: Created folder '{$folderName}' (ID: {$folder->id})");
        return $folder->id;
    }

    /**
     * Cleanup old backups, keep only the latest N
     */
    public function cleanupOldBackups(int $keepCount = 5): int
    {
        if (!$this->service) {
            if (!$this->initClient()) return 0;
        }

        try {
            $folderName = config('backup.google_drive.folder_name', 'TU_Admin_Backup');
            $folderId = $this->getOrCreateFolder($folderName);

            $response = $this->service->files->listFiles([
                'q' => "'{$folderId}' in parents and trashed=false",
                'orderBy' => 'createdTime desc',
                'fields' => 'files(id, name, createdTime)',
            ]);

            $files = $response->files;
            $deleted = 0;

            if (count($files) > $keepCount) {
                $toDelete = array_slice($files, $keepCount);
                foreach ($toDelete as $file) {
                    $this->service->files->delete($file->id);
                    $deleted++;
                    Log::info("Google Drive: Deleted old backup {$file->name}");
                }
            }

            return $deleted;
        } catch (\Exception $e) {
            Log::error('Google Drive cleanup failed: ' . $e->getMessage());
            return 0;
        }
    }
}
