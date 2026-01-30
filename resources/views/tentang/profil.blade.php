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
                                Berdasarkan Undang-Undang Nomor 49 Tahun 2014 tentang Sistem Nasional Pendidikan Tinggi, 
                                setiap perguruan tinggi wajib memenuhi standar mutu pendidikan. 
                                IKIP Siliwangi menyusun sistem penjaminan mutu sesuai dengan standar nasional pendidikan 
                                dengan tujuan menjadi perguruan tinggi yang bermutu dan berdaya saing.
                            </p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4 class="fw-bold mb-3" style="color: var(--primary-brown);">
                            <i class="fas fa-bullseye me-2"></i>Visi & Misi IKIP Siliwangi
                        </h4>
                        <div class="mb-4 p-3 rounded" style="background-color: #f8f9fa; border-left: 4px solid var(--primary-brown);">
                            <h5 class="fw-bold mb-2" style="color: var(--dark-brown);">Visi:</h5>
                            <p class="mb-0">
                                Terwujudnya IKIP Siliwangi sebagai perguruan tinggi yang unggul dalam penyelenggaraan 
                                pendidikan, penelitian, dan pengabdian kepada masyarakat pada tahun 2030.
                            </p>
                        </div>
                        <div class="p-3 rounded" style="background-color: #f8f9fa; border-left: 4px solid var(--primary-brown);">
                            <h5 class="fw-bold mb-2" style="color: var(--dark-brown);">Misi:</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Menyelenggarakan pendidikan yang bermutu dan relevan dengan kebutuhan masyarakat
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Mengembangkan penelitian yang inovatif dan bermanfaat bagi pengembangan ilmu pengetahuan
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Meningkatkan pengabdian kepada masyarakat untuk memecahkan masalah sosial
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h4 class="fw-bold mb-3" style="color: var(--primary-brown);">
                            <i class="fas fa-cogs me-2"></i>Tujuan SPMI IKIP Siliwangi
                        </h4>
                        <p class="mb-4">
                            IKIP Siliwangi dalam menjalankan sistem penjaminan mutu internal bertujuan menjaga <em>sustainability</em> 
                            terciptanya Pelaksanaan, Evaluasi, Pengendalian, Peningkatan (PPEPP) secara konsisten.
                        </p>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-4 rounded h-100" style="background: linear-gradient(135deg, #fff9e6 0%, #fff5d6 100%);">
                                    <h5 class="fw-bold mb-3" style="color: var(--primary-brown);">
                                        <i class="fas fa-tasks me-2"></i>Fungsi LPMI
                                    </h5>
                                    <p>
                                        Lembaga Penjaminan Mutu Internal (LPMI) sebagai unit yang mampu melaksanakan, 
                                        memonitoring, dan mengevaluasi secara kredibel, terukur, dan profesional 
                                        demi terciptanya visi IKIP Siliwangi.
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 rounded h-100" style="background: linear-gradient(135deg, #fff9e6 0%, #fff5d6 100%);">
                                    <h5 class="fw-bold mb-3" style="color: var(--primary-brown);">
                                        <i class="fas fa-chart-line me-2"></i>Monitoring & Evaluasi
                                    </h5>
                                    <p>
                                        Melakukan monitoring dan evaluasi kegiatan akademik dan non-akademik secara 
                                        berkala terhadap pelaksanaan seluruh kegiatan sebagai bentuk penjaminan mutu 
                                        akademik secara berkelanjutan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h4 class="fw-bold mb-3" style="color: var(--primary-brown);">
                            <i class="fas fa-history me-2"></i>Sejarah Singkat
                        </h4>
                        <p>
                            Sistem Penjaminan Mutu Internal (SPMI) IKIP Siliwangi didirikan pada tahun 2020 
                            sebagai respons terhadap tuntutan Undang-Undang Nomor 49 Tahun 2014 tentang Sistem Nasional Pendidikan Tinggi. 
                            Sejak didirikan, SPMI telah berkembang menjadi sistem yang komprehensif 
                            yang mencakup seluruh aspek penyelenggaraan pendidikan sesuai dengan standar nasional.
                        </p>
                    </div>

                    <div class="mt-5">
                        <h4 class="fw-bold mb-3" style="color: var(--primary-brown);">
                            <i class="fas fa-handshake me-2"></i>Komitmen Kami
                        </h4>
                        <div class="alert alert-warning" style="background-color: #fff9e6; border-color: var(--primary-brown);">
                            <div class="d-flex">
                                <i class="fas fa-lightbulb fa-2x me-3" style="color: var(--primary-brown);"></i>
                                <div>
                                    <h5 class="fw-bold mb-2">Membangun Budaya Mutu</h5>
                                    <p class="mb-0">
                                        Membangun kesadaran dan komitmen seluruh sivitas akademika 
                                        untuk melaksanakan tugas dan fungsinya sesuai dengan standar yang ditetapkan, 
                                        serta melakukan perbaikan secara terus-menerus.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection