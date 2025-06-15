<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Laporan Konsumsi</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');
    body {
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        color: #2c3e50;
        margin: 0;
        padding: 20px 30px;
        background-color: #ffffff;
    }
    h1 {
        text-align: center;
        color: #1f618d;
        font-weight: 700;
        margin-bottom: 25px;
        letter-spacing: 1.5px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 2px 8px rgba(31, 97, 141, 0.15);
        border-radius: 6px;
        overflow: hidden;
    }
    thead {
        background-color: #1f618d;
        color: #ecf0f1;
        font-weight: 600;
    }
    thead th {
        padding: 14px 12px;
        text-align: left;
        font-size: 14px;
        user-select: none;
    }
    tbody tr {
        border-bottom: 1px solid #d6e4f0;
        transition: background-color 0.3s ease;
    }
    tbody tr:nth-child(even) {
        background-color: #f5f9fc;
    }
    tbody td {
        padding: 12px;
        font-size: 13px;
        color: #34495e;
    }
    tbody td:first-child {
        text-align: center;
        font-weight: 600;
        color: #1f618d;
        width: 5%;
    }
    tbody td:nth-child(4) {
        text-align: center;
        font-weight: 600;
        color: #2874a6;
        width: 15%;
    }
    footer {
        margin-top: 40px;
        text-align: center;
        font-size: 11px;
        color: #7f8c8d;
        border-top: 1px solid #d6e4f0;
        padding-top: 10px;
        user-select: none;
        font-style: italic;
    }
</style>
</head>
<body>
    <h1>Laporan Konsumsi Makanan</h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Tanggal Konsumsi</th>
                <th>Shift</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->User->Profile->name ?? 'Tidak Diketahui' }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggalKonsumsi)->format('d M Y') }}</td>
                <td>Shift {{ $item->IdShift }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        &copy; {{ date('Y') }} Dapur Emak. Semua hak cipta dilindungi.
    </footer>
</body>
</html>
