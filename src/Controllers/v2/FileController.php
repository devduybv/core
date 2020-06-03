<?php

namespace VCComponent\Laravel\Vicoders\Core\Controllers\v2;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use VCComponent\Laravel\Vicoders\Core\Contracts\FileValidatorInterface;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

class FileController extends ApiController
{
    private $validator;
    const UPLOAD_DIRECTORY  = 'public/';
    const LOCAL_UPLOAD_TYPE = 'local';
    const S3_UPLOAD_TYPE    = 's3';

    public function __construct(FileValidatorInterface $validator)
    {
        $this->validator = $validator;
        // $this->middleware('jwt.auth', ['except' => []]);
    }

    public function upload(Request $request)
    {
        $data = $request->all();
        $this->validator->isValid($data, 'RULE_CREATE');
        $upload_file_type = Config::get('filesystems.default');

        if (!$request->hasFile('file')) {
            throw new NotFoundException("File");
        } else {

            $file           = $request->file('file');
            $origin_name    = $file->getClientOriginalName();
            $file_extension = $file->getClientOriginalExtension();

            $upload_path = $request->get('upload_path');

            if (!is_null($file_extension)) {
                $file_name = Str::snake(str_replace('.' . $file_extension, '', $origin_name));
                switch ($upload_file_type) {
                    case FileController::LOCAL_UPLOAD_TYPE:
                        $path = $upload_path . "/" . time() . "_{$file_name}.{$file_extension}";
                        break;
                    case FileController::S3_UPLOAD_TYPE:
                        $path = $upload_path . "/" . time() . "_{$file_name}.{$file_extension}";
                        break;
                }
            } else {
                switch ($upload_file_type) {
                    case FileController::LOCAL_UPLOAD_TYPE:
                        $path = $upload_path . "/" . time() . "_{$file_name}";
                        break;
                    case FileController::S3_UPLOAD_TYPE:
                        $path = $upload_path . "/" . time() . "_{$file_name}";
                        break;
                }
            }
            $path = strtolower($path);

            $success = Storage::disk($upload_file_type)->put(self::UPLOAD_DIRECTORY . $path, File::get($file));

            if ($success) {

                switch ($upload_file_type) {
                    case FileController::LOCAL_UPLOAD_TYPE:
                        $result = $this->response->array(['success' => $success, 'path' => config('filesystems.disks.public.url') . '/' . $path]);
                        break;
                    case FileController::S3_UPLOAD_TYPE:
                        $result = $this->response->array(['success' => $success, 'path' => config('filesystems.disks.s3.url') . $path]);
                        break;
                }
                return $result;

            } else {
                return $this->response->error('can\'t upload file', 1009);
            }
        }
    }

    public function uploadS3(Request $request)
    {
        $data = $request->all();
        $this->validator->isValid($data, 'RULE_CREATE');

        if (!$request->hasFile('file')) {
            throw new NotFoundException("File");
        } else {

            $file           = $request->file('file');
            $origin_name    = $file->getClientOriginalName();
            $file_extension = $file->getClientOriginalExtension();

            $currency = config('filesystems.disks.s3.url');

            $upload_path = $request->get('upload_path');

            if (!is_null($file_extension)) {
                $file_name = Str::snake(str_replace('.' . $file_extension, '', $origin_name));
                $path      = $upload_path . "/" . time() . "_{$file_name}.{$file_extension}";
            } else {
                $path = $upload_path . "/" . time() . "_{$file_name}";
            }
            $path = strtolower($path);

            $success = Storage::disk('s3')->put($path, File::get($file));

            if ($success) {
                return $this->response->array(['success' => $success, 'path' => $currency . $path]);
            } else {
                return $this->response->error('can\'t upload file', 1009);
            }
        }
    }

    public function delete($path)
    {
        $upload_file_type = Config::get('filesystems.default');
        try {
            switch ($upload_file_type) {
                case FileController::LOCAL_UPLOAD_TYPE:
                    if (Storage::disk('local')->has($path)) {
                        if (is_dir($path)) {
                            Storage::disk('local')->deleteDirectory($path);
                        } else {
                            Storage::disk('local')->delete($path);
                        }
                    }
                    break;
                case FileController::S3_UPLOAD_TYPE:
                    Storage::disk('s3')->delete($path);
                    break;
            }
            return true;
        } catch (Exception $e) {
            throw new Exception($e, 1);
        }
    }
}
