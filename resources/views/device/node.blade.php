@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <header class="card-header">
                        <h3>Device Serial: {{ $device->serial }} - Node-{{ $node->id }} {{ $node->name }}</h3>
                    </header>

                    <section class="card-body">
                        @if (session('status'))
                            <div class="alert alert-{{ session('alert-type') }} alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{ session('message') }}
                            </div>
                        @endif

                        <form action="{{ route('update_node', $device->id) }}" method="POST" id="node_update">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="node_name">Device Node Name</label>
                                    <input type="text" class="form-control" id="node_name" name="node_name"
                                           placeholder="Bulb" value="{{ $node->name }}">
                                    <small id="location_name_help" class="form-text text-muted">
                                        Name the selected node of this device
                                    </small>
                                </div>

                                <input type="hidden" id="nodeid" value="{{ $node->id }}">
                                <input type="hidden" id="nodevalue" value="{{ $node->value }}">

                                <div class="form-group col-md-6">
                                    <label for="node_icon">Node Icon</label>
                                    <select class="form-control" id="node_icon" name="node_icon">
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
                                        Select Icon for the selected node.
                                    </small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                </div>
                </form>
                </section>
            </div>
        </div>
    </div>
    </div>
@endsection