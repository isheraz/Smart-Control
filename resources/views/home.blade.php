@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a class="btn btn-primary pull-right" href="{{ route('device_create') }}">{{ __('Create New Device')
                        }}</a>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-{{ session('alert-type') }} alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{ session('message') }}
                            </div>
                        @endif

                        <div class="row">
                            <section class="col-md-12">
                                <table class="table-bordered table-stripped table-light w-100">
                                    <thead>
                                    <tr class="text-center p-1">
                                        <th class="text-success">Connected</th>
                                        <th class="text-danger">Disconnected</th>
                                        <th class="text-info">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="text-center"><h4>{{ $connected }}</h4></td>
                                        <td class="text-center"><h4>{{ $disconnected }}</h4></td>
                                        <td class="text-center"><h4>{{ count($devices) }}</h4></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </section>
                        </div>

                        <hr>
                        <div class="row">
                            @foreach( $devices as $device)
                                <div class="col-md-3">
                                    <section class="sh-device" id="my-room">
                                        <a href="{{ route('configure',$device->id) }}" class="edit"><i class="fa
                                        fa-pencil"></i></a>
                                        <a href="{{ route('delete_device',$device->id) }}" class="text-danger delete"><i
                                                    class="fa
                                        fa-trash"></i></a>
                                        @if($device->connection)
                                            <small class="text-success" title="connected"><i class="fa fa-globe"></i>
                                            </small>
                                            <p class="text-dark">
                                                <a href="{{ route('device', $device->id) }}" class="device-io">
                                                    <i class="fa {{$device->location_icon}}"></i>
                                                    <span>{{ $device->location_name }}</span>
                                                </a>
                                            </p>
                                        @else
                                            <small class="text-black-50" title="disconnected"><i class="fa
                                                fa-times"></i></small>
                                            <p class="text-dark">
                                                <a href="{{ route('device', $device->id) }}" class="device-io">
                                                    <i class="fa {{$device->location_icon}}"></i>
                                                    <span>{{ $device->location_name }}</span>
                                                </a>
                                            </p>
                                        @endif

                                        <span class="text-dark-50 device-serial">{{ $device->serial }}</span>
                                        {{--<span class="text-dark-50 device-history"><a
                                                    href="{{route('history', $device->id)}}">History</a></span>--}}
                                    </section>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
