@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <h3>Device History</h3>
                    </div>


                    <div class="card-body">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif

                        <div class="row">
                            <table class=" table-bordered table-stripped table">
                                <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Temperature</th>
                                    <th>Normal Bill</th>
                                    <th>Peak Bill</th>
                                    <th>Total Bill</th>
                                    <th>Door Sensor</th>
                                    <th>Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($history as $event)
                                    <tr>
                                        <td>{{ $event->id }}</td>
                                        <td>{{ $event->temperature }}</td>
                                        <td>{{ $event->watts }}</td>
                                        <td>{{ $event->voltages}}</td>
                                        <td>{{ $event->current}}</td>
                                        <td>{{ $event->door_sensor}}</td>
                                        <td>{{ $event->created_at}}</td>
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