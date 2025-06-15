@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    {{ __('Tambah Obat Baru') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('obat.store') }}">
                        @include('obat._form', ['tombolSubmit' => 'Tambah Obat'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection