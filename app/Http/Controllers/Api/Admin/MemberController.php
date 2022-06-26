<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;

class MemberController extends Controller
{
    use ApiResponseTrait;

    /**
     * @var User
     */
    protected $memberModel;


    /**
     * @param Member $member
     */
    public function __construct(Member $member)
    {
        $this->memberModel = $member;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $users = $this->memberModel->get();

        return $this->apiResponse('successfully', $users);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
            'phone' => 'required',
            'emergency_phone' => 'required',
            'birthday' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->apiResponseValidation($validator);
        }

        $user = $this->memberModel->create([
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'birthday' => Carbon::parse($request->post('birthday')),
            'phone' => $request->post('phone'),
            'emergency_phone' => $request->post('emergency_phone'),
            'status' => 1
        ]);

        if($request->hasFile('avatar') && $request->file('avatar')->isValid()){
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        return $this->apiResponse('successfully', $user);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request){
        $validator = validator::make($request->all(), [
            'member_id' => 'required|exists:members,id',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'birthday' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->apiResponseValidation($validator);
        }

        $user = $this->memberModel->find($request->post('member_id'));

        $user->update([
           'name' => $request->post('name'),
           'email' => $request->post('email'),
           'phone' => $request->post('phone'),
           'emergency_phone' => $request->post('emergency_phone'),
           'birthday' => $request->post('birthday'),
        ]);

        return $this->apiResponse('successfully', $user);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request){
        $validator = validator::make($request->all(), [
            'member_id' => 'required|exists:members,id',
        ]);

        if ($validator->fails()) {
            return $this->apiResponseValidation($validator);
        }

        $this->memberModel->find($request->post('member_id'))->delete();

        return $this->apiResponse('successfully');
    }
}
