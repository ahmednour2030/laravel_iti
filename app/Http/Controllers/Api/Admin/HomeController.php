<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Member;
use App\Models\MemberShip;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ApiResponseTrait;

    /**
     * @var User
     */
    protected $userModel;

    /**
     * @var Member
     */
    protected $memberModel;

    /**
     * @var MemberShip
     */
    protected $memberShipModel;

    /**
     * @param User $user
     * @param Member $member
     * @param MemberShip $memberShip
     */
    public function __construct(user $user, Member $member, MemberShip $memberShip)
    {
        $this->userModel = $user;
        $this->memberModel = $member;
        $this->memberShipModel = $memberShip;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function home(): \Illuminate\Http\JsonResponse
    {
        $admins = $this->userModel->whereRoleId(1)->count();
        $accountant = $this->userModel->whereRoleId(2)->count();
        $reviewer = $this->userModel->whereRoleId(3)->count();
        $members = $this->memberModel->count();
        $memberShips = $this->memberShipModel->count();

        $array = [
            'admins' => $admins,
            'accountant' => $accountant,
            'reviewer' => $reviewer,
            'members' => $members,
            'memberShips' => $memberShips,
        ];

        return $this->apiResponse('done', $array);
    }
}
