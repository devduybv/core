<?php

namespace VCComponent\Laravel\Vicoders\Core\Test\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use VCComponent\Laravel\Vicoders\Core\Test\TestCase;

class FileControllerV2Test extends TestCase
{
    /** @test */
    public function can_upload_file_to_s3_by_admin()
    {
        $this->withoutMiddleware(['jwt.auth']);

        Storage::fake('s3');

        $file = UploadedFile::fake()->create('image.jpg');

        $files = [
            'file' => $file,
            'upload_path' => 'upload'
        ];

        $response = $this->call('POST', 'api/v2/file/upload/s3', $files);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);
    }

    /** @test */
    public function should_not_upload_file_to_s3_without_upload_path()
    {
        $this->withoutMiddleware(['jwt.auth']);

        Storage::fake('s3');

        $file = UploadedFile::fake()->create('image.jpg');

        $files = [
            'file' => $file,
        ];

        $response = $this->call('POST', 'api/v2/file/upload/s3', $files);
        $response->assertStatus(422);
        $response->assertJson([
            "message" => "The given data was invalid."
        ]);
    }

    /** @test */
    public function should_not_upload_file_to_s3_without_file()
    {
        $this->withoutMiddleware(['jwt.auth']);

        Storage::fake('s3');

        $files = [
            'upload_path' => '/upload'
        ];

        $response = $this->call('POST', 'api/v2/file/upload/s3', $files);

        $response->assertStatus(422);
        $response->assertJson([
            "message" => "The given data was invalid.",
        ]);
    }

    /** @test */
    public function can_upload_file_to_configed_drive_by_admin()
    {
        $this->withoutMiddleware(['jwt.auth']);

        $upload_file_type = $this->app['config']->get('filesystems.default');

        Storage::fake($upload_file_type);

        $file = UploadedFile::fake()->create('image.jpg');

        $files = [
            'file' => $file,
            'upload_path' => 'upload'
        ];

        $response = $this->call('POST', 'api/v2/file/upload', $files);

        $response->assertStatus(200);
        $response->assertJson([
            "success" => true
        ]);
    }

    /** @test */
    public function should_not_upload_file_to_configed_storage_without_file()
    {
        $this->withoutMiddleware(['jwt.auth']);

        $upload_file_type = $this->app['config']->get('filesystems.default');

        Storage::fake($upload_file_type);

        $files = [
            'upload_path' => 'upload'
        ];

        $response = $this->call('POST', 'api/v2/file/upload', $files);

        $response->assertStatus(422);
        $response->assertJson([
            "message" => "The given data was invalid."
        ]);
    }

    /** @test */
    public function can_upload_file_to_configed_storage_without_upload_path()
    {
        $this->withoutMiddleware(['jwt.auth']);

        $upload_file_type = $this->app['config']->get('filesystems.default');

        Storage::fake($upload_file_type);

        $file = UploadedFile::fake()->create('image.jpg');

        $files = [
            'file' => $file,
        ];

        $response = $this->call('POST', 'api/v2/file/upload', $files);

        $response->assertStatus(422);
        $response->assertJson([
            "message" => "The given data was invalid."
        ]);
    }
}
