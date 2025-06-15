<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\QrToken;
use App\Models\User;


class KaryawanController extends Controller
{
    // ========== Web: Dashboard ==========
    public function dashboard()
    {
        $user = Auth::user()->load('profile');
        return view('karyawan.dashboard', compact('user'));
    }

    // ========== Web: Generate QR ==========
    public function generateQr()
    {
        $user = Auth::user();

        if ($user->role !== 'Karyawan') {
            abort(403, 'Unauthorized');
        }

        $token = Str::random(32);
        $createTime = Carbon::now();
        $expiredTime = $createTime->copy()->addSeconds(15);

        QrToken::create([
            'IdUsers' => $user->IdUsers,
            'token' => $token,
            'create' => $createTime,
            'expired' => $expiredTime,
        ]);

        return redirect()->route('karyawan.dashboard')->with([
            'token' => $token,
            'expired' => $expiredTime,
        ]);
    }

    // ========== Web: Generate QR via AJAX ==========
    public function generateQrAjax()
    {
        $user = Auth::user();

        if ($user->role !== 'Karyawan') {
            abort(403); // Jika bukan karyawan, tolak akses
        }

        $token = Str::random(32);
        $createTime = Carbon::now();
        $expiredTime = $createTime->copy()->addSeconds(15);

        QrToken::create([
            'IdUsers' => $user->IdUsers,
            'token' => $token,
            'create' => $createTime,
            'expired' => $expiredTime,
        ]);

        return response()->json([
            'token' => $token,
            'expired' => $expiredTime->toDateTimeString()
        ]);
    }


    // ========== API: Generate QR ==========
    public function generateQrApi()
    {
        $user = auth('api')->user();

        if (!$user || $user->role !== 'Karyawan') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $token = Str::random(32);
        $createTime = Carbon::now();
        $expiredTime = $createTime->copy()->addSeconds(15);

        QrToken::create([
            'IdUsers' => $user->IdUsers,
            'token' => $token,
            'create' => $createTime,
            'expired' => $expiredTime,
        ]);

        return response()->json([
            'token' => $token,
            'expired' => $expiredTime->toDateTimeString()
        ]);
    }
}
