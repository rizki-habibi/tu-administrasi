@extends('peran.admin.app')

@section('konten')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Beranda</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    Anda telah berhasil masuk! Mengarahkan ke beranda...
                    <script>
                        setTimeout(function() {
                            window.location.href = '{{ auth()->check() ? route(auth()->user()->getDashboardRoute()) : route("login") }}';
                        }, 1000);
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
