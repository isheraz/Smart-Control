@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <header class="card-header">
                        <h3>Create Device</h3>
                    </header>

                    <section class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{route('device-create')}}" method="POST">
                            @csrf
                            <div class="row">


                                <div class="form-group col-md-6">
                                    <label for="location_name">Device Location Name</label>
                                    <input type="text" class="form-control" id="location_name" name="location_name"
                                           placeholder="My Bed Room" value="">
                                    <small id="location_name_help" class="form-text text-muted">
                                        Name the Location of this device
                                    </small>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="location_icon">Device Location Icon</label>
                                    <select class="form-control" id="location_icon" name="location_icon">
                                        <option >Please Select an Icon</option>
                                        <option value="fa-bed">&#xf236; BED</option>
                                        <option value="fa-cutlery">&#xf0f5;
                                            CUTLERY
                                        </option>
                                        <option value="fa-shower" >&#xf2cc; SHOWER</option>
                                        <option value="fa-desktop" >&#xf108;
                                            DESKTOP
                                        </option>
                                        <option value="fa-laptop" >&#xf109; LAPTOP</option>
                                        <option value="fa-plug" >&#xf1e6; PLUG</option>

                                        <option value="fa-soccer-ball-o" >&#xf1e3;
                                            FOOTBALL</option>
                                        <option value="fa-home" >&#xf015; HOME</option>
                                        <option value="fa-book" >&#xf02d; BOOK</option>
                                        <option value="fa-briefcase">&#xf0b1;
                                            BRIEFCASE</option>

                                    </select>

                                    <small id="location_icon_help" class="form-text text-muted">
                                        Select an Icon for this device.
                                    </small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-sm">Create</button>
                        </form>

                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection