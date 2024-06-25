<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.profile');
    }
    // public function profile(Request $request): View
    // {
    //     $data = Auth::user();
    //     return view('admin.profile', [
    //         'user' => $request->user(),
    //     ], compact('data'));
    // }

    public function profile()
    {
        $data = Auth::user();
        return view('admin.profile', compact('data'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = User::find(auth()->user()->id);
        $data = $request->validate([
            'name' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
        ]);
        if ($request->file('foto')) {
            if ($request->oldFoto) {
                Storage::delete($request->oldFoto);
            }
            $data['foto'] = $request->file('foto')->store('admin');
        }
        $user->update($data);
        //return Redirect::route('profile')->with('message', 'Profil Anda Berhasil Diperbarui');
        //toastr()->success('Data Admin Berhasil Ditambahkan', 'Sukses', ['positionClass' => 'toast-top-full-width', 'closeButton' => true]);
        toastr()->success('Data Profil Berhasil Diperbarui');
        return back();
    }

    public function change_password(Request $request)
    {
        $data = $request->validate([
            'oldPassword' => 'required',
            'password' => 'required|confirmed'
        ]);

        if (Hash::check($request->oldPassword, auth()->user()->password)) {
            $user = User::find(auth()->user()->id);
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            toastr()->success('Password Anda Berhasil Diubah');
        } else {
            toastr()->error('Password Lama Salah');
        }
        return back();
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
