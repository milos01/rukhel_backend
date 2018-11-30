<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Http\Requests\ProfilePictureRequest;
use App\Model\Task;
use App\Services\UploadService;
use App\Util\HttpResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UploadController extends Controller
{
    private $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function uploadProfilePicture(ProfilePictureRequest $request){
        try{
            $path = Storage::putFile('avatars', $request->file('photo'));
            $hash_name = basename($path);

            $this->uploadService->addProfilePicture($hash_name, $request->user()->id);

            return response("", 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }

    }

    public function uploadFile(FileRequest $request, $id){
        try{
            $path = Storage::putFile('files', $request->file('file'));
            $hash_name = basename($path);

            $this->uploadService->addTaskFile($hash_name, $id);
            return response("", 200);
        }catch(HttpException $exception){
            return response(HttpResponse::handleResponse($exception->getMessage()), $exception->getStatusCode());
        }
    }
}
