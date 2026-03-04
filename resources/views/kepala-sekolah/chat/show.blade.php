@extends('peran.kepala-sekolah.app')
@section('judul', 'Chat - ' . $percakapan->getNamaUntuk(auth()->id()))
@section('konten')
    @include('komponen.chat.show')
@endsection
