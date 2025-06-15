<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\JadwalPesanan;
use App\Models\Konsumsi;
use App\Models\Shift;
use App\Models\User;
use App\Models\Menu;
use Illuminate\Support\Facades\Hash;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;
use App\Mail\KaryawanMail;
use Illuminate\Support\Facades\Mail;
use App\Exports\KonsumsiExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class HrgaController extends Controller
{
    /*
    |------------------------------------------------------------------------------------------
    | BERISI FUNGSI UNTUK MENGATUR FITUR HRGA (Dashboard, Pesanan, Karyawan , Report ,Konsumsi)
    |------------------------------------------------------------------------------------------
    */
    
    // ================= Dashboard HRGA =================
    public function dashboard()
    {
        $today = Carbon::today();

        // Basic counts
        $data = [
            'total_karyawan' => User::where('role', 'Karyawan')->count(),
            'pesanan_hari_ini' => JadwalPesanan::whereDate('tanggalPesanan', $today)->count(),
            'konsumsi_hari_ini' => Konsumsi::whereDate('tanggalKonsumsi', $today)->count(),
        ];

        $shifts = Shift::all();

        $pesananPerShift = JadwalPesanan::whereDate('tanggalPesanan', $today)
            ->select('IdShift', DB::raw('SUM(JumlahPesanan) as total_porsi_pesanan')) // UBAH DISINI
            ->groupBy('IdShift')
            ->pluck('total_porsi_pesanan', 'IdShift');

        // Konsumsi per shift (dioptimalkan)
        $konsumsiData = Konsumsi::whereDate('tanggalKonsumsi', $today)
            ->select('IdShift', DB::raw('COUNT(*) as total'))
            ->groupBy('IdShift')
            ->get()
            ->keyBy('IdShift');

        $consumptionPerShift = $shifts->map(function ($shift) use ($konsumsiData) {
            $total = $konsumsiData[$shift->IdShift]->total ?? 0;
            return (object)[
                'IdShift' => $shift->IdShift,
                'namaShift' => $shift->namaShift,
                'total' => $total
            ];
        });

        // Unverified orders
        $unverifiedOrders = JadwalPesanan::whereDate('tanggalPesanan', '>=', $today)
            ->where('statusPesanan', 0)
            ->count();

        // Employee stats
        $activeEmployees = User::where('role', 'Karyawan')->where('statusUsers', 'aktif')->count();
        $inactiveEmployees = User::where('role', 'Karyawan')->where('statusUsers', 'tidak aktif')->count();

        // Employees per shift (dioptimalkan)
        $employeePerShift = Shift::select('tb_shift.IdShift', 'tb_shift.namaShift')
            ->leftJoin('tb_users', function ($join) {
                $join->on('tb_shift.IdShift', '=', 'tb_users.IdShift')
                    ->where('tb_users.role', '=', 'Karyawan');
            })
            ->selectRaw('COUNT(tb_users.IdUsers) as total')
            ->groupBy('tb_shift.IdShift', 'tb_shift.namaShift')
            ->get();

        // Most consumed menu today
        $mostConsumedMenu = Menu::leftJoin('tb_jadwalpesanan', 'tb_menu.IdMenu', '=', 'tb_jadwalpesanan.IdMenu')
            ->whereDate('tb_jadwalpesanan.tanggalPesanan', $today)
            ->select('tb_menu.IdMenu', 'tb_menu.namaMenu', DB::raw('COALESCE(SUM(tb_jadwalpesanan.JumlahPesanan), 0) as total'))
            ->groupBy('tb_menu.IdMenu', 'tb_menu.namaMenu')
            ->orderByDesc('total')
            ->first();

        // Recent activities (dummy, bisa dikembangkan)
        $recentActivities = [
            [
                'title' => 'Pesanan Baru',
                'description' => 'Total '.$data['pesanan_hari_ini'].' pesanan hari ini',
                'color' => 'blue',
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
            ],
            [
                'title' => 'Karyawan Baru',
                'description' => $data['total_karyawan'] . ' karyawan terdaftar',
                'color' => 'green',
                'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'
            ],
            [
                'title' => 'Konsumsi',
                'description' => $data['konsumsi_hari_ini'] . ' konsumsi hari ini',
                'color' => 'yellow',
                'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            ]
        ];

        // Upcoming orders
        $upcomingOrders = JadwalPesanan::with(['shift', 'menu'])
            ->whereDate('tanggalPesanan', '>=', $today)
            ->orderBy('tanggalPesanan')
            ->limit(5)
            ->get();

        return view('hrga.dashboard', compact(
            'data', 'shifts', 'pesananPerShift', 'unverifiedOrders',
            'activeEmployees', 'inactiveEmployees', 'employeePerShift',
            'consumptionPerShift', 'mostConsumedMenu', 'recentActivities',
            'upcomingOrders'
        ));
    }

    // ================= Karyawan =================
    // Get All Data Karyawan (API)
    public function indexApi(Request $request)
    {
        $search = $request->input('search');

        $karyawan = User::with(['profile', 'shift'])
            ->where('role', 'Karyawan')
            ->when($search, function ($query, $search) {
                $query->whereHas('profile', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->get();

        return response()->json([
            'message' => 'Data karyawan berhasil diambil',
            'data' => $karyawan
        ]);
    }

    // Get All Data Karyawan (Web)
    public function index(Request $request)
    {
        $search = $request->input('search');

        $karyawan = User::with(['profile', 'shift'])
            ->where('role', 'Karyawan')
            ->when($search, function ($query, $search) {
                $query->whereHas('profile', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->get();

        return view('hrga.karyawan.index', compact('karyawan'));
    }

    // Form Create (Web only)
    public function createKaryawan()
    {
        $shifts = Shift::all();
        $user = new User();

        return view('hrga.karyawan.create', compact('shifts', 'user'));
    }

    // Simpan Data Karyawan (API)
    public function storeKaryawanApi(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:tb_users,email',
            'password' => 'required|min:6',
            'IdShift' => 'required',
            'name' => 'required',
            'nik' => 'required|numeric',
            'noTelepon' => 'required',
            'address' => 'required',
            'tanggalLahir' => 'required|date',
            'foto' => 'required|string', // Base64 encoded image for API
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'Karyawan',
            'IdShift' => $validated['IdShift'],
            'statusUsers' => 'tidak aktif',
            'created_at' => now(),
        ]);

        // Handle base64 image
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $validated['foto']));
        $imageName = 'foto_' . time() . '.png';
        Storage::disk('public')->put('foto/' . $imageName, $image);

        Profile::create([
            'IdUsers' => $user->IdUsers,
            'name' => $validated['name'],
            'nik' => $validated['nik'],
            'noTelepon' => $validated['noTelepon'],
            'address' => $validated['address'],
            'tanggalLahir' => $validated['tanggalLahir'],
            'foto' => 'foto/' . $imageName,
        ]);

        return response()->json([
            'message' => 'Karyawan berhasil ditambahkan',
            'data' => $user->load('profile', 'shift')
        ], 201);
    }

    // Simpan Data Karyawan (Web)
    public function storeKaryawan(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:tb_users,email',
            'password' => 'required|min:6',
            'IdShift' => 'required',
            'name' => 'required',
            'nik' => 'required|numeric',
            'noTelepon' => 'required',
            'address' => 'required',
            'tanggalLahir' => 'required|date',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'Karyawan',
            'IdShift' => $validated['IdShift'],
            'statusUsers' => 'tidak aktif',
            'created_at' => now(),
        ]);

        $fotoPath = $request->file('foto')->store('foto', 'public');

        Profile::create([
            'IdUsers' => $user->IdUsers,
            'name' => $validated['name'],
            'nik' => $validated['nik'],
            'noTelepon' => $validated['noTelepon'],
            'address' => $validated['address'],
            'tanggalLahir' => $validated['tanggalLahir'],
            'foto' => $fotoPath,
        ]);

        return redirect()->route('hrga.karyawan.index')->with('success', 'Karyawan berhasil ditambahkan. Klik tombol Kirim Email jika ingin mengirimkan email.');
    }

    // Form Edit (Web only)
    public function editKaryawan(User $user)
    {
        $shifts = Shift::all();
        $user->load('profile');

        return view('hrga.karyawan.edit', compact('user', 'shifts'));
    }

    // Update Data Karyawan (API)
    public function updateKaryawanApi(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'email' => 'required|email|unique:tb_users,email,' . $user->IdUsers . ',IdUsers',
            'IdShift' => 'required',
            'name' => 'required',
            'nik' => 'required|numeric',
            'noTelepon' => 'required',
            'address' => 'required',
            'tanggalLahir' => 'required|date',
            'foto' => 'nullable|string', // Base64 encoded image for API
            'password' => 'nullable|min:6',
        ]);

        $user->update([
            'email' => $validated['email'],
            'IdShift' => $validated['IdShift'],
            'statusUsers' => 'tidak aktif',
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        $profileData = [
            'name' => $validated['name'],
            'nik' => $validated['nik'],
            'noTelepon' => $validated['noTelepon'],
            'address' => $validated['address'],
            'tanggalLahir' => $validated['tanggalLahir'],
        ];

        if ($request->has('foto')) {
            // Delete old photo if exists
            if ($user->profile->foto && Storage::disk('public')->exists($user->profile->foto)) {
                Storage::disk('public')->delete($user->profile->foto);
            }

            // Handle base64 image
            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $validated['foto']));
            $imageName = 'foto_' . time() . '.png';
            Storage::disk('public')->put('foto/' . $imageName, $image);
            
            $profileData['foto'] = 'foto/' . $imageName;
        }

        $user->profile->update($profileData);

        return response()->json([
            'message' => 'Data karyawan berhasil diperbarui',
            'data' => $user->load('profile', 'shift')
        ]);
    }

    // Update Data Karyawan (Web)
    public function updateKaryawan(Request $request, User $user)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:tb_users,email,' . $user->IdUsers . ',IdUsers',
            'IdShift' => 'required',
            'name' => 'required',
            'nik' => 'required|numeric',
            'noTelepon' => 'required',
            'address' => 'required',
            'tanggalLahir' => 'required|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|min:6',
        ]);

        $user->update([
            'email' => $validated['email'],
            'IdShift' => $validated['IdShift'],
            'statusUsers' => 'tidak aktif',
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        $profileData = [
            'name' => $validated['name'],
            'nik' => $validated['nik'],
            'noTelepon' => $validated['noTelepon'],
            'address' => $validated['address'],
            'tanggalLahir' => $validated['tanggalLahir'],
        ];

        if ($request->hasFile('foto')) {
            if ($user->profile->foto && Storage::disk('public')->exists($user->profile->foto)) {
                Storage::disk('public')->delete($user->profile->foto);
            }

            $fotoPath = $request->file('foto')->store('foto', 'public');
            $profileData['foto'] = $fotoPath;
        }

        $user->profile->update($profileData);

        return redirect()->route('hrga.karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui. Klik tombol Kirim Email jika ingin mengirimkan email.');
    }

    // Hapus Karyawan (API)
    public function destroyKaryawanApi($id)
    {
        $user = User::findOrFail($id);

        if ($user->profile && $user->profile->foto && Storage::disk('public')->exists($user->profile->foto)) {
            Storage::disk('public')->delete($user->profile->foto);
        }

        $user->profile()->delete();
        $user->delete();

        return response()->json([
            'message' => 'Karyawan berhasil dihapus'
        ]);
    }

    // Hapus Karyawan (Web)
    public function destroyKaryawan(User $user)
    {
        if ($user->profile && $user->profile->foto && Storage::disk('public')->exists($user->profile->foto)) {
            Storage::disk('public')->delete($user->profile->foto);
        }

        $user->profile()->delete();
        $user->delete();

        return redirect()->route('hrga.karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }

    // Kirim Email ke Karyawan (API)
    public function kirimEmailKaryawanApi($id)
    {
        $user = User::with('profile', 'shift')->findOrFail($id);

        Mail::to($user->email)->send(new KaryawanMail($user, true));

        $user->update([
            'statusUsers' => 'aktif',
        ]);

        return response()->json([
            'message' => 'Email aktivasi berhasil dikirim ke karyawan dan status diubah menjadi aktif',
            'data' => $user
        ]);
    }

    // Kirim Email ke Karyawan (Web)
    public function kirimEmailKaryawan($id)
    {
        $user = User::with('profile', 'shift')->findOrFail($id);

        Mail::to($user->email)->send(new KaryawanMail($user, true));

        $user->update([
            'statusUsers' => 'aktif',
        ]);

        return redirect()->route('hrga.karyawan.edit', $user->IdUsers)
                        ->with('success', 'Email aktivasi berhasil dikirim ke karyawan dan status diubah menjadi aktif.');
    }

    // Rolling Shift Karyawan (API)
    public function rotateShiftApi()
    {
        $shifts = Shift::orderBy('namaShift')->get();

        $shiftMap = [
            'Shift A' => 'Shift C',
            'Shift C' => 'Shift B',
            'Shift B' => 'Shift A',
        ];

        $shiftNameToId = $shifts->pluck('IdShift', 'namaShift');

        $karyawans = User::where('role', 'Karyawan')->get();

        foreach ($karyawans as $karyawan) {
            $currentShiftName = $karyawan->shift->namaShift ?? null;

            if (!$currentShiftName || !isset($shiftMap[$currentShiftName])) {
                continue;
            }

            $nextShiftName = $shiftMap[$currentShiftName];
            $karyawan->IdShift = $shiftNameToId[$nextShiftName];
            $karyawan->save();
        }

        return response()->json([
            'message' => 'Shift karyawan berhasil diputar',
            'data' => User::where('role', 'Karyawan')->with('shift')->get()
        ]);
    }

    // Rolling Shift Karyawan (Web)
    public function rotateShift()
    {
        $shifts = Shift::orderBy('namaShift')->get();

        $shiftMap = [
            'Shift A' => 'Shift C',
            'Shift C' => 'Shift B',
            'Shift B' => 'Shift A',
        ];

        $shiftNameToId = $shifts->pluck('IdShift', 'namaShift');

        $karyawans = User::where('role', 'Karyawan')->get();

        foreach ($karyawans as $karyawan) {
            $currentShiftName = $karyawan->shift->namaShift ?? null;

            if (!$currentShiftName || !isset($shiftMap[$currentShiftName])) {
                continue;
            }

            $nextShiftName = $shiftMap[$currentShiftName];
            $karyawan->IdShift = $shiftNameToId[$nextShiftName];
            $karyawan->save();
        }

        return redirect()->route('hrga.karyawan.index')->with('success', 'Shift karyawan berhasil diputar secara manual.');
    }

    // ================= Jadwal Pesanan =================
    // Get All Data Jadwal Pesanan (API)
    public function showPesananApi(Request $request)
    {
        $tanggal = $request->query('tanggal');

        $jadwalPesanan = JadwalPesanan::with(['Shift', 'Menu'])
            ->when($tanggal, function ($query) use ($tanggal) {
                $query->whereDate('tanggalPesanan', $tanggal);
            })
            ->orderBy('tanggalPesanan', 'desc')
            ->get();

        return response()->json([
            'message' => 'Data jadwal pesanan berhasil diambil',
            'data' => $jadwalPesanan
        ]);
    }

    // Get All Data Jadwal Pesanan (Web)
    public function showPesanan(Request $request)
    {
        $tanggal = $request->query('tanggal');

        $jadwalPesanan = JadwalPesanan::with(['Shift', 'Menu'])
            ->when($tanggal, function ($query) use ($tanggal) {
                $query->whereDate('tanggalPesanan', $tanggal);
            })
            ->orderBy('tanggalPesanan', 'desc')
            ->get();

        return view('hrga.jadwalpesanan.pesanan', compact('jadwalPesanan'));
    }

    // Form Create (Web only)
    public function createPesanan()
    {
        $shifts = Shift::all();
        $menus = Menu::all();
        return view('hrga.jadwalpesanan.pesanan-create', compact('shifts', 'menus'));
    }

    // Simpan Data Baru (API)
    public function storePesananApi(Request $request)
    {
        $validated = $request->validate([
            'IdShift' => 'required',
            'IdMenu' => 'required',
            'tanggalPesanan' => 'required|date',
            'JumlahPesanan' => 'required|integer',
            'statusPesanan' => 'required|in:0,1',
        ]);

        $validated['VerifAt'] = $validated['statusPesanan'] == 1 ? now() : null;

        $pesanan = JadwalPesanan::create($validated);

        return response()->json([
            'message' => 'Pesanan berhasil ditambahkan',
            'data' => $pesanan->load('Shift', 'Menu')
        ], 201);
    }

    // Simpan Data Baru (Web)
    public function storePesanan(Request $request)
    {
        $validated = $request->validate([
            'IdShift' => 'required',
            'IdMenu' => 'required',
            'tanggalPesanan' => 'required|date',
            'JumlahPesanan' => 'required|integer',
            'statusPesanan' => 'required|in:0,1',
        ]);

        $validated['VerifAt'] = $validated['statusPesanan'] == 1 ? now() : null;

        JadwalPesanan::create($validated);

        return redirect()->route('hrga.pesanan.index')->with('success', 'Pesanan berhasil ditambahkan.');
    }

    // Form Edit (Web only)
    public function editPesanan($id)
    {
        $pesanan = JadwalPesanan::findOrFail($id);
        $shifts = Shift::all();
        $menus = Menu::all();

        return view('hrga.jadwalpesanan.pesanan-edit', compact('pesanan', 'shifts', 'menus'));
    }

    // Update Data (API)
    public function updatePesananApi(Request $request, $id)
    {
        $validated = $request->validate([
            'IdShift' => 'required',
            'IdMenu' => 'required',
            'tanggalPesanan' => 'required|date',
            'JumlahPesanan' => 'required|integer',
            'statusPesanan' => 'required|in:0,1',
        ]);

        $pesanan = JadwalPesanan::findOrFail($id);

        if ($validated['statusPesanan'] == 1 && $pesanan->VerifAt == null) {
            $validated['VerifAt'] = now();
        } elseif ($validated['statusPesanan'] == 0) {
            $validated['VerifAt'] = null;
        }

        $pesanan->update($validated);

        return response()->json([
            'message' => 'Pesanan berhasil diperbarui',
            'data' => $pesanan->load('Shift', 'Menu')
        ]);
    }

    // Update Data (Web)
    public function updatePesanan(Request $request, $id)
    {
        $validated = $request->validate([
            'IdShift' => 'required',
            'IdMenu' => 'required',
            'tanggalPesanan' => 'required|date',
            'JumlahPesanan' => 'required|integer',
            'statusPesanan' => 'required|in:0,1',
        ]);

        $pesanan = JadwalPesanan::findOrFail($id);

        if ($validated['statusPesanan'] == 1 && $pesanan->VerifAt == null) {
            $validated['VerifAt'] = now();
        } elseif ($validated['statusPesanan'] == 0) {
            $validated['VerifAt'] = null;
        }

        $pesanan->update($validated);

        return redirect()->route('hrga.pesanan.index')->with('success', 'Pesanan berhasil diperbarui.');
    }

    // Hapus Data (API)
    public function destroyPesananApi($id)
    {
        $pesanan = JadwalPesanan::findOrFail($id);
        $pesanan->delete();

        return response()->json([
            'message' => 'Pesanan berhasil dihapus'
        ]);
    }

    // Hapus Data (Web)
    public function destroyPesanan($id)
    {
        $pesanan = JadwalPesanan::findOrFail($id);
        $pesanan->delete();

        return redirect()->route('hrga.pesanan.index')->with('success', 'Pesanan berhasil dihapus.');
    }
    
    // ================= Konsumsi =================
    // Monitoring Konsumsi Harian (API)
    public function monitoringKonsumsiHarianApi(Request $request)
    {
        $tanggal = $request->input('tanggal') ?? Carbon::today()->toDateString();

        $shifts = [1, 2, 3];
        $dataMonitoring = [];

        foreach ($shifts as $shiftId) {
            $jadwal = JadwalPesanan::where('IdShift', $shiftId)
                ->whereDate('tanggalPesanan', $tanggal)
                ->first();

            $jumlahPesanan = $jadwal ? $jadwal->JumlahPesanan : 0;

            $jumlahKonsumsi = Konsumsi::where('IdShift', $shiftId)
                ->whereDate('tanggalKonsumsi', $tanggal)
                ->count();

            $dataMonitoring[] = [
                'shift' => "Shift $shiftId",
                'pesanan' => $jumlahPesanan,
                'konsumsi' => $jumlahKonsumsi,
                'sisa' => max(0, $jumlahPesanan - $jumlahKonsumsi),
            ];
        }

        return response()->json([
            'message' => 'Data monitoring konsumsi harian',
            'data' => $dataMonitoring,
            'tanggal' => $tanggal
        ]);
    }

    // Monitoring Konsumsi Harian (Web)
    public function monitoringKonsumsiHarian(Request $request)
    {
        $tanggal = $request->input('tanggal') ?? Carbon::today()->toDateString();

        $shifts = [1, 2, 3];
        $dataMonitoring = [];

        foreach ($shifts as $shiftId) {
            $jadwal = JadwalPesanan::where('IdShift', $shiftId)
                ->whereDate('tanggalPesanan', $tanggal)
                ->first();

            $jumlahPesanan = $jadwal ? $jadwal->JumlahPesanan : 0;

            $jumlahKonsumsi = Konsumsi::where('IdShift', $shiftId)
                ->whereDate('tanggalKonsumsi', $tanggal)
                ->count();

            $dataMonitoring[] = [
                'shift' => "Shift $shiftId",
                'pesanan' => $jumlahPesanan,
                'konsumsi' => $jumlahKonsumsi,
                'sisa' => max(0, $jumlahPesanan - $jumlahKonsumsi),
            ];
        }

        return view('hrga.konsumsi', compact('dataMonitoring', 'tanggal'));
    }

    // ================= Report =================
    // Report Konsumsi (API)
    public function reportKonsumsiApi(Request $request)
    {
        $filter = $request->input('filter', 'daily');
        $tanggal = Carbon::parse($request->input('tanggal', Carbon::today()));

        $query = Konsumsi::query();

        switch ($filter) {
            case 'weekly':
                $start = $tanggal->copy()->startOfWeek();
                $end = $tanggal->copy()->endOfWeek();
                $query->whereBetween('tanggalKonsumsi', [$start, $end]);
                $groupByFormat = 'Y-m-d';
                break;
            case 'monthly':
                $query->whereMonth('tanggalKonsumsi', $tanggal->month)
                    ->whereYear('tanggalKonsumsi', $tanggal->year);
                $groupByFormat = 'Y-m-d';
                break;
            case 'yearly':
                $query->whereYear('tanggalKonsumsi', $tanggal->year);
                $groupByFormat = 'Y-m';
                break;
            default:
                $query->whereDate('tanggalKonsumsi', $tanggal);
                $groupByFormat = 'Y-m-d';
                break;
        }

        $data = [];

        $query->get()->groupBy(function ($item) use ($groupByFormat) {
            return Carbon::parse($item->tanggalKonsumsi)->format($groupByFormat);
        })->each(function ($items, $date) use (&$data) {
            foreach ([1, 2, 3] as $shift) {
                $jumlahKonsumsi = $items->where('IdShift', $shift)->count();

                $jadwal = JadwalPesanan::where('IdShift', $shift)
                    ->whereDate('tanggalPesanan', $date)
                    ->first();
                $jumlahPesanan = $jadwal ? $jadwal->JumlahPesanan : 0;
                $sisa = max(0, $jumlahPesanan - $jumlahKonsumsi);

                $data[] = [
                    'tanggal' => $date,
                    'shift' => "Shift $shift",
                    'jumlah_konsumsi' => $jumlahKonsumsi,
                    'jumlah_pesanan' => $jumlahPesanan,
                    'sisa' => $sisa
                ];
            }
        });

        return response()->json([
            'message' => 'Data report konsumsi',
            'data' => $data,
            'filter' => $filter,
            'tanggal' => $tanggal->toDateString()
        ]);
    }

    // Report Konsumsi (Web)
    public function reportKonsumsi(Request $request)
    {
        $filter = $request->input('filter', 'daily');
        $tanggal = Carbon::parse($request->input('tanggal', Carbon::today()));

        $query = Konsumsi::query();

        switch ($filter) {
            case 'weekly':
                $start = $tanggal->copy()->startOfWeek();
                $end = $tanggal->copy()->endOfWeek();
                $query->whereBetween('tanggalKonsumsi', [$start, $end]);
                $groupByFormat = 'Y-m-d';
                break;
            case 'monthly':
                $query->whereMonth('tanggalKonsumsi', $tanggal->month)
                    ->whereYear('tanggalKonsumsi', $tanggal->year);
                $groupByFormat = 'Y-m-d';
                break;
            case 'yearly':
                $query->whereYear('tanggalKonsumsi', $tanggal->year);
                $groupByFormat = 'Y-m';
                break;
            default:
                $query->whereDate('tanggalKonsumsi', $tanggal);
                $groupByFormat = 'Y-m-d';
                break;
        }

        $data = [];

        $query->get()->groupBy(function ($item) use ($groupByFormat) {
            return Carbon::parse($item->tanggalKonsumsi)->format($groupByFormat);
        })->each(function ($items, $date) use (&$data) {
            foreach ([1, 2, 3] as $shift) {
                $jumlahKonsumsi = $items->where('IdShift', $shift)->count();

                $jadwal = JadwalPesanan::where('IdShift', $shift)
                    ->whereDate('tanggalPesanan', $date)
                    ->first();
                $jumlahPesanan = $jadwal ? $jadwal->JumlahPesanan : 0;
                $sisa = max(0, $jumlahPesanan - $jumlahKonsumsi);

                $data[] = [
                    'tanggal' => $date,
                    'shift' => "Shift $shift",
                    'jumlah_konsumsi' => $jumlahKonsumsi,
                    'jumlah_pesanan' => $jumlahPesanan,
                    'sisa' => $sisa
                ];
            }
        });

        return view('hrga.report', compact('data', 'filter', 'tanggal'));
    }

    // download report konsumsi (API)
    public function downloadKonsumsiApi(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $shift = $request->input('shift');
        $format = $request->input('format', 'csv');

        $data = Konsumsi::with('User.Profile')
            ->whereDate('tanggalKonsumsi', $tanggal)
            ->where('IdShift', $shift)
            ->get();

        if ($format === 'excel') {
            return Excel::download(new KonsumsiExport($data), "konsumsi_{$tanggal}_shift{$shift}.xlsx");
        } elseif ($format === 'pdf') {
            $pdf = Pdf::loadView('hrga.report.download', ['data' => $data]);
            return $pdf->download("konsumsi_{$tanggal}_shift{$shift}.pdf");
        } else {
            $filename = "konsumsi_{$tanggal}_shift{$shift}.csv";
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Nama', 'Tanggal Konsumsi', 'Shift']);

                foreach ($data as $row) {
                    fputcsv($file, [
                        $row->IdKonsumsi,
                        $row->User->Profile->name,
                        $row->tanggalKonsumsi,
                        $row->IdShift
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
    }

    // download report konsumsi (Web)
    public function downloadKonsumsi(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $shiftStr = $request->input('shift');
        $shift = (int) filter_var($shiftStr, FILTER_SANITIZE_NUMBER_INT);
        $format = $request->input('format', 'csv');

        $data = Konsumsi::with('User.Profile')
            ->whereDate('tanggalKonsumsi', $tanggal)
            ->where('IdShift', $shift)
            ->get();

        if ($format === 'excel') {
            return Excel::download(new KonsumsiExport($data), "konsumsi_{$tanggal}_shift{$shift}.xlsx");
        } elseif ($format === 'pdf') {
            $pdf = Pdf::loadView('hrga.report.download', ['data' => $data]);
            return $pdf->download("konsumsi_{$tanggal}_shift{$shift}.pdf");
        } else {
            // CSV export
            $filename = "konsumsi_{$tanggal}_shift{$shift}.csv";
            $handle = fopen('php://temp', 'w+');
            fputcsv($handle, ['ID', 'Nama', 'Tanggal Konsumsi', 'Shift']);

            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->IdKonsumsi,
                    $row->User->Profile->name ?? '-',
                    \Carbon\Carbon::parse($row->tanggalKonsumsi)->format('d-m-Y'),
                    "Shift " . $row->IdShift
                ]);
            }

            rewind($handle);
            $csvContent = stream_get_contents($handle);
            fclose($handle);

            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename=\"$filename\"");
        }
    }

}