<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $all = Document::count();
        $news = Document::where('status', 'yangi')->count();
        $reject = Document::where('status', 'reject')->count();
        $users = User::where('role', 'user2')->orderByDesc('created_at')->paginate(20);
        return view('users.index', compact('users', 'all', 'news', 'reject'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $all = Document::count();
        $news = Document::where('status', 'yangi')->count();
        $reject = Document::where('status', 'reject')->count();
        $offers = Offer::where('is_active', 1)->where('user_id', null)->get();
        return view('users.create', compact('offers', 'all', 'news', 'reject'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->route('admin.users.index')->with('message', 'Yaratildi!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $all = Document::count();
        $news = Document::where('status', 'yangi')->count();
        $reject = Document::where('status', 'reject')->count();
        $user = User::with('offer')->findOrFail($id);
        return view('users.show', compact('user', 'all', 'news', 'reject'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $all = Document::count();
        $news = Document::where('status', 'yangi')->count();
        $reject = Document::where('status', 'reject')->count();
        $user = User::findOrFail($id);
        $offers = Offer::where('is_active', 1)->where('user_id', null)->OrWhere('user_id', $id)->get();
        return view('users.edit', compact('user', 'offers', 'all', 'news', 'reject'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        dd($request->all());
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);
        $offers = Offer::where('is_active', 1)->where('user_id', $user->id)->get();
        foreach ($offers as $offer) {
            $offer->update([
                'user_id' => null,
                'status' => 'tender'
            ]);
        }
        if (isset($request->offer_id)) {
            foreach ($request->offer_id as $offer_id) {
                Offer::findOrFail($offer_id)->update([
                    'user_id' => $user->id,
                    'status' => 'shartnoma'
                ]);
            }
        }
        $user->update($data);
        return redirect()->route('admin.users.index')->with('message', 'Tahrirlandi!');
    }

    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $offers = Offer::where('user_id', $user->id)->get();
        $documents = Document::where('user_id', $user->id)->get();
        foreach ($documents as $document){
            $document->update([
                'status' => 'off'
            ]);
        }
        foreach ($offers as $offer) {
            $offer->update([
                'user_id' => null,
                'status' => 'tender'
            ]);
        }
        $user->update([
            'role' => 'deleted'
        ]);
        return redirect()->route('admin.users.index')->with('message', 'O`chirildi!');
    }

    public function status($id)
    {
        $user = User::findOrFail($id);
//        dd($user->is_active);
        if ($user->is_active == 1) {
            $user->is_active = 0;
            session()->flash('inactive', 'Faol emas!');
        } else {
            $user->is_active = 1;
            session()->flash('message', 'Faol!');
        }
        $user->save();
        return redirect()->route('admin.users.index');
    }

    public function role($id)
    {
        $user = User::findOrFail($id);
        if ($user->role == 'off') {
            $user->role = 'user2';
            session()->flash('message', 'Ruxsat berildi!');
            $user->save();
        } else {
            session()->flash('inactive', 'Oops something went wrong!');
        }
        return redirect()->route('admin.users.index')->with('message', 'Ruxsat berildi!');
    }

    public function password(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user->update([
            'password' => Hash::make($data['password'])
        ]);
        return redirect()->route('admin.users.index')->with('message', 'Parol o`zgardi !');
    }

    public function trash()
    {
        $all = Document::count();
        $news = Document::where('status', 'news')->count();
        $reject = Document::where('status', 'reject')->count();
        $users = User::where('role', 'deleted')->get();
        return view('trash.trash', compact('users', 'all', 'news', 'reject'));
    }

    public function trashshow($id)
    {
        $all = Document::count();
        $news = Document::where('status', 'yangi')->count();
        $reject = Document::where('status', 'reject')->count();
        $user = User::findOrFail($id);
        $documents = Document::with('offer')->where('user_id', $id)->get();
        return view('trash.view', compact('user', 'all', 'news', 'reject', 'documents'));
    }

    public function restore($id)
    {
        User::findOrFail($id)->update([
            'role' => 'user2'
        ]);
        return back()->with('message', 'Tiklandi!');
    }
}
