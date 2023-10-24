<?php

namespace App\Domain\User\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class UserWebController extends Controller
{
    /**
     * Creating a proxy function for solve CORS error
     * @return JsonResponse
     */
    public function getRemoteData(): \Illuminate\Http\JsonResponse
    {
        $data = file_get_contents('https://account.publishdrive.com/Books/users.json');
        return response()->json(json_decode($data));
    }
}
