<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Traits\ApiResponseTrait;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
     * @param LoginRequest $request
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

//            if(is_null($admin['email_verified_at'])){
//                return $this->apiResponse('please verified email', '',403,  'email not verified');
//            }

            $token = $user->createToken('token')->plainTextToken;

            return $this->apiResponse('successfully', $user, 200 , null, $token);
        }
        return $this->apiResponse('not found user', '',403,  'not found user');
    }

    public function index()
    {
        $admins = $this->userModel->with('roles')->get();

        return $this->apiResponse('successfully', $admins);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'role_id'=>'required',
            'password'=>'required',
        ]);

        if($validator->fails()){
            return $this->apiResponseValidation($validator);
        }

        $admin = $this->userModel::create([
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'phone' => $request->post('phone'),
            'role_id' => $request->post('role_id'),
            'password' => Hash::make($request->post('password'))
        ]);

        return $this->apiResponse('successfully', $admin);
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'admin_id'=>'required|exists:users,id',
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
        ]);

        if($validator->fails()){
            return $this->apiResponseValidation($validator);
        }

        $admin = $this->userModel::find($request->post('admin_id'));

        if($admin){
            $admin = $admin->update([
                'name' => $request->post('name'),
                'email' => $request->post('email'),
                'phone' => $request->post('phone'),
                'role_id' => $request->post('role_id'),
            ]);
            return $this->apiResponse('successfully', $admin);
        }

        return $this->apiResponse('failed', null, 422);
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'admin_id'=>'required|exists:users,id',
        ]);

        if($validator->fails()){
            return $this->apiResponseValidation($validator);
        }

        $admin = $this->userModel::find($request->post('admin_id'));

        if($admin){
            $admin->delete();
        }

        return $this->apiResponse('successfully');
    }

    /**
     * @return JsonResponse
     */
    public function getRoles(): JsonResponse
    {
        $roles = DB::table('roles')->get();

        return $this->apiResponse('successfully', $roles);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = request()->user();
        // Revoke current user token
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return $this->apiResponse("You have been successfully logged out!");
    }
}
