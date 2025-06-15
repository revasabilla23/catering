<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Informasi Akun Karyawan Baru</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            background-color: #f9f9f9;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            background-color: #ffffff;
            margin: 30px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        h2 {
            color: #1a73e8;
            margin-bottom: 20px;
        }
        ul {
            padding-left: 0;
            list-style: none;
        }
        li {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.9em;
            color: #777;
        }
        a {
            color: #1a73e8;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Selamat Bergabung di Perusahaan Kami</h2>
        <p>Berikut adalah informasi akun Anda yang telah berhasil didaftarkan oleh tim HRGA:</p>

        <ul>
            <li><span class="label">Nama:</span> {{ $user->profile->name ?? '-' }}</li>
            <li><span class="label">Tanggal Lahir:</span> 
                {{ $user->profile && $user->profile->tanggalLahir ? \Carbon\Carbon::parse($user->profile->tanggalLahir)->format('d-m-Y') : '-' }}
            </li>
            <li><span class="label">NIK:</span> {{ $user->profile->nik ?? '-' }}</li>
            <li><span class="label">No. Telepon:</span> {{ $user->profile->noTelepon ?? '-' }}</li>
            <li><span class="label">Alamat:</span> {{ $user->profile->address ?? '-' }}</li>
            <li><span class="label">Shift:</span> {{ $user->Shift->namaShift ?? '-' }}</li>
            <li><span class="label">Email:</span> {{ $user->email ?? '-' }}</li>
            <li><span class="label">Foto:</span>
                @if($user->profile && $user->profile->foto)
                    <a href="{{ asset('storage/' . $user->profile->foto) }}" target="_blank">Lihat Foto</a>
                @else
                    Tidak tersedia
                @endif
            </li>
            <li><span class="label">Password:</span> Hubungi HRGA untuk informasi login awal</li>
        </ul>

        <p>Silakan login ke sistem menggunakan email Anda. Jika Anda memiliki pertanyaan atau memerlukan bantuan, silakan hubungi tim HRGA.</p>

        <div class="footer">
            Hormat kami,<br>
            <strong>Tim HRGA</strong><br>
            Sistem Informasi Karyawan
        </div>
    </div>
</body>
</html>