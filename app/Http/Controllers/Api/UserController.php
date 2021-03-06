<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    use ApiResponseTrait;

    /**
     * @var User
     */
    protected $userModel;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->userModel = $user;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
        ]);

        if($validator->fails()){
            return $this->apiResponseValidation($validator);
        }

        $user = $this->userModel->whereEmail($request->post('email'))->first();

        if ($user) {
            if (!Hash::check($request->post('password'), $user->password)) {
                $message = 'Wrong password';
                return $this->apiResponse($message, null,403, 'not authorized');
            }
            $token = $user->createToken('token')->plainTextToken;

            return $this->apiResponse('successfully', $user, 200 , null, $token);
        }
        return $this->apiResponse('not found user', '',403,  'not found user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function getPhones()
    {
        $phones = Auth::user()->phones;

        return $this->apiResponse('successfully', $phones, 200 );
    }
}
