@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <header class="card-header">
                        <h3>Device Serial: {{ $device->serial }}</h3>
                    </header>

                    <section class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('update_device', $device->id) }}" method="POST">
                            @csrf
                            <div class="row">


                                <div class="form-group col-md-6">
                                    <label for="location_name">Device Location Name</label>
                                    <input type="text" class="form-control" id="location_name" name="location_name"
                                           placeholder="My Bed Room" value="{{ $device->location_name }}">
                                    <small id="location_name_help" class="form-text text-muted">
                                        Name the Location of this device
                                    </small>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="location_icon">Device Location Icon</label>
                                    <select class="form-control" id="location_icon" name="location_icon">
                                        <option {{ $selection['default'] }} >Please Select an Icon</option>
                                        <option value="fa-bed" {{ $selection['bed'] }} >&#xf236; BED</option>
                                        <option value="fa-cutlery" {{ $selection['cutlery'] }} >&#xf0f5;
                                            CUTLERY
                                        </option>
                                        <option value="fa-shower" {{ $selection['shower'] }} >&#xf2cc; SHOWER</option>
                                        <option value="fa-desktop" {{ $selection['desktop'] }} >&#xf108;
                                            DESKTOP
                                        </option>
                                        <option value="fa-laptop" {{ $selection['laptop'] }} >&#xf109; LAPTOP</option>
                                        <option value="fa-plug" {{ $selection['plug'] }} >&#xf1e6; PLUG</option>

                                        <option value="fa-soccer-ball-o" {{ $selection['football'] }}>&#xf1e3;
                                            FOOTBALL</option>
                                        <option value="fa-home" {{ $selection['home'] }}>&#xf015; HOME</option>
                                        <option value="fa-book" {{ $selection['book'] }}>&#xf02d; BOOK</option>
                                        <option value="fa-briefcase" {{ $selection['briefcase'] }}>&#xf0b1;
                                            BRIEFCASE</option>

                                    </select>

                                    <small id="location_icon_help" class="form-text text-muted">
                                        Select an Icon for this device.
                                    </small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        </form>

                    </section>

                    <footer class="card-footer">
                        <small class="text-muted">
                            @if($device->connection)
                                <i class="fa fa-globe text-success"></i>&nbsp;Connected
                            @else
                                <i class="fa fa-times text-dark-50"></i>&nbsp;Disconnected
                            @endif
                            :&nbsp;{{ $device->updated_at->diffForHumans() }}
                        </small>
                    </footer>
                </div>
            </div>
        </div>
    </div>
@endsection