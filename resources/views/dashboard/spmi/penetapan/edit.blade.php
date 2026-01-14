{{-- edit.blade.php --}}
@extends('layouts.main')

@section('title', 'Edit Penetapan SPMI')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-warning">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Data Penetapan
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('spmi.penetapan.update', $penetapan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Form fields sama seperti create.blade.php -->
                        <!-- Gunakan $penetapan->field untuk value -->
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('spmi.penetapan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection