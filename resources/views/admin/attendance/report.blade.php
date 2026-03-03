@extends('layouts.admin')
@section('title', 'Rekap Kehadiran')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between">
        <h6 class="mb-0">Rekap Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th class="text-center">Hadir</th>
                    <th class="text-center">Terlambat</th>
                    <th class="text-center">Izin</th>
                    <th class="text-center">Sakit</th>
                    <th class="text-center">Alpha</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceData as $i => $data)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $data['staff']->name }}</td>
                    <td class="text-center"><span class="badge bg-success">{{ $data['hadir'] }}</span></td>
                    <td class="text-center"><span class="badge bg-warning">{{ $data['terlambat'] }}</span></td>
                    <td class="text-center"><span class="badge bg-info">{{ $data['izin'] }}</span></td>
                    <td class="text-center"><span class="badge bg-primary">{{ $data['sakit'] }}</span></td>
                    <td class="text-center"><span class="badge bg-danger">{{ $data['alpha'] }}</span></td>
                    <td class="text-center"><strong>{{ $data['total'] }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
