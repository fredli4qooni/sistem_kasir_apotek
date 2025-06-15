@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    {{ __('Edit Obat: ') . $obat->nama_obat }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('obat.update', $obat->id) }}">
                        @method('PUT') {{-- Method spoofing untuk HTTP PUT --}}
                        @include('obat._form', ['tombolSubmit' => 'Update Obat'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection