@extends('layouts.app')

@section('title', 'Profil - SPMI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card custom-card border-0">
                <div class="card-body p-5">
                    <h1 class="fw-bold mb-4 text-center" style="color: var(--primary-brown);">
                        <i class="fas fa-user-circle me-2"></i>Profil SPMI
                    </h1>
                    
                    <div class="row align-items-center mb-5">
                        <div class="col-md-4 text-center mb-4 mb-md-0">
                            <img src="{{ asset('images/photos/25600_Logo-IKIP-warna.png') }}" 
                                 alt="Logo IKIP" 
                                 class="img-fluid rounded-circle shadow" 
                                 style="max-height: 200px;">
                        </div>
                        <div class="col-md-8">
                            <h3 class="fw-bold mb-3">Sistem Penjaminan Mutu Internal</h3>
                            <p class="lead">
                                SPMI IKIP Siliwangi adalah sistem yang dirancang untuk menjamin dan meningkatkan 
                                mutu pendidikan secara berkelanjutan melalui mekanisme evaluasi diri, audit internal, 
                                dan perbaikan berkelanjutan.
                            </p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4 class="fw-bold mb-3" style="color: var(--primary-brown);">
                            <i class="fas fa-bullseye me-2"></i>Misi Kami
                        </h4>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Menjamin mutu penyelenggaraan pendidikan tinggi
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Meningkatkan budaya mutu di seluruh unit kerja
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Mengembangkan sistem evaluasi dan perbaikan berkelanjutan
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Memenuhi standar nasional dan internasional pendidikan
                            </li>
                        </ul>
                    </div>

                    <div class="mt-5">
                        <h4 class="fw-bold mb-3" style="color: var(--primary-brown);">
                            <i class="fas fa-history me-2"></i>Sejarah Singkat
                        </h4>
                        <p>
                            Sistem Penjaminan Mutu Internal (SPMI) IKIP Siliwangi didirikan pada tahun 2020 
                            sebagai respons terhadap tuntutan peningkatan mutu pendidikan tinggi. 
                            Sejak didirikan, SPMI telah berkembang menjadi sistem yang komprehensif 
                            yang mencakup seluruh aspek penyelenggaraan pendidikan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection