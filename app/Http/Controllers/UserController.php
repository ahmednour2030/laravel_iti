<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @var User
     */
    protected $userModel;

    /**
     * @var Phone
     */
    protected $phoneModel;

    /**
     * @param User $user
     * @param Phone $phone
     */
    public function __construct(User $user, Phone $phone)
    {
        $this->userModel = $user;
        $this->phoneModel = $phone;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
//        $phones = Phone::whereUserId(Auth::id())->get();
//
        $phones = Auth::user()->phones;

        return view('index', ['phones' => $phones]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return
     */
    public function create()
    {
        return view('create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|unique:phones|digits:11|regex:/(01)[0-9]{9}/',
        ]);

//        $user = new Phone();
//
//        $user->phone = $request->post('phone');
//
//        $user->user_id = Auth::id();
//
//        $user->save();

        Auth::user()->phones()->create($request->all());

        $this->authorize('update', Phone::class);


        return back()->with('success','Phone has been added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(int $id)
    {
        $phone = Phone::find($id);

        $this->authorize('update', $phone);

        return view('edit', ['phone' => $phone]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(int $id)
    {
        $phone = $this->phoneModel->find($id);

        $this->authorize('update', $phone);

        return view('edit', ['phone' => $phone]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, int $id)
    {
//        $phone = Phone::find($id);
//
//        $phone->phone = $request->post('phone');
//
//        $phone->save();

        $phone= $this->phoneModel->find($id);

        $this->authorize('update', $phone);

        $phone->update([
            'phone' => $request->post('phone')
        ]);

        return \redirect('/users');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
       $phone = $this->phoneModel->find($id);

       $this->authorize('delete', $phone);

       $phone->delete();

        return redirect()->back();
    }
}
