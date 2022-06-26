<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Member;
use App\Models\MemberShip;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MemberShipController extends Controller
{
    use ApiResponseTrait;

    /**
     * @var Member
     */
    protected $memberShipModel;

    /**
     * @param MemberShip $memberShip
     */
    public function __construct(MemberShip $memberShip)
    {
        $this->memberShipModel = $memberShip;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = $this->memberShipModel->get();

        return $this->apiResponse('successfully', $users);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|string',
            'days' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->apiResponseValidation($validator);
        }

        $style = $this->memberShipModel->create([
            'name' => $request->post('name'),
            'price' => $request->post('price'),
            'days' => $request->post('days')
        ]);

        return $this->apiResponse('successfully', $style);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function update(Request $request)
    {
        $validator = validator::make($request->all(), [
            'member_ship_id' => 'required|exists:member_ships,id',
            'name' => 'required|string',
            'price' => 'required|string',
            'days' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->apiResponseValidation($validator);
        }

        $style = $this->memberShipModel->find($request->post('member_ship_id'))->update([
            'name' => $request->post('name'),
            'price' => $request->post('price'),
            'days' => $request->post('days')
        ]);

        return $this->apiResponse('successfully', $style);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request){
        $validator = validator::make($request->all(), [
            'member_ship_id' => 'required|exists:member_ships,id',
        ]);

        if ($validator->fails()) {
            return $this->apiResponseValidation($validator);
        }

        $this->memberShipModel->find($request->post('member_ship_id'))->delete();

        return $this->apiResponse('successfully');
    }
}
