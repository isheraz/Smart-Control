@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('bt-reset-devices') }}" class="btn btn-danger float-right">Reset Everything</a>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-{{ session('alert-type') }} alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{ session('message') }}
                            </div>
                        @endif

                        <div class="row">
                            @foreach( $bluetooth as $group)
                                <div class="col-md-4 mb-2 px-1">
                                    <div class="border card-body">
                                        Group Master : {{ $group->master_mac_address }}<br>
                                        Group Devices : {{ $group->devices }}<br>
                                        <?php $dev = \App\BluetoothDevice::where('id',json_decode($group->devices)[0] )->get() ?>
                                        Group UUID : {{ json_decode($dev, true)[0]['uuid'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr>

                        <div class="row">
                            @foreach( $devices as $device)
                                <div class="col-3 px-1 mb-2">
                                    <div class="card-body border">
                                        Device ID : {{ $device->id }}<br>
                                        Mac Address : {{ $device->mac_address }}<br>
                                        Mode : {{ $device->mode }}<br>
                                        {{--UUID : {{ $device->uuid }}<br>--}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
