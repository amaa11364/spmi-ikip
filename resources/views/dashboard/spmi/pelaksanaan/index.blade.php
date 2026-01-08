@extends('layouts.main')

@section('title', 'Pelaksanaan SPMI')

@section('content')
<div class="row">
    <div class="col-12">
        <h3 class="fw-bold mb-3">
            <i class="fas fa-play-circle me-2 text-primary"></i>Pelaksanaan SPMI
        </h3>
        
        <!-- Integrasi Sistem -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-link me-2"></i>Integrasi SIAKAD
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success p-2 rounded me-3">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Status: {{ $integrations['siakad']['status'] }}</h6>
                                <small class="text-muted">
                                    Terakhir sinkron: {{ $integrations['siakad']['last_sync'] }}
                                </small>
                            </div>
                        </div>
                        <h6>Fitur Terintegrasi:</h6>
                        <ul class="list-unstyled">
                            @foreach($integrations['siakad']['features'] as $feature)
                            <li class="mb-1">
                                <i class="fas fa-check-circle text-success me-2"></i>{{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-database me-2"></i>Integrasi PDDIKTI
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success p-2 rounded me-3">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Status: {{ $integrations['pddikti']['status'] }}</h6>
                                <small class="text-muted">
                                    Terakhir sinkron: {{ $integrations['pddikti']['last_sync'] }}
                                </small>
                            </div>
                        </div>
                        <h6>Fitur Terintegrasi:</h6>
                        <ul class="list-unstyled">
                            @foreach($integrations['pddikti']['features'] as $feature)
                            <li class="mb-1">
                                <i class="fas fa-check-circle text-success me-2"></i>{{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Implementasi Standar -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-tasks me-2"></i>Implementasi Standar
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kegiatan</th>
                                <th>Status</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($implementations as $key => $impl)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $impl['kegiatan'] }}</td>
                                <td>
                                    <span class="badge bg-{{ $impl['status'] == 'Selesai' ? 'success' : 'warning' }}">
                                        {{ $impl['status'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar {{ $impl['status'] == 'Selesai' ? 'bg-success' : 'bg-warning' }}" 
                                             style="width: {{ $impl['status'] == 'Selesai' ? '100' : '60' }}%">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection