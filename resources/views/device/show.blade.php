@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <h3>Device Serial: {{ $device->serial }}</h3>
                        <a href="{{route('create-appliance', $device->id)}}" class="btn btn-success">Create New
                            Appliance</a>
                        <!-- Trigger the modal with a button -->
                        <button type="button" class="btn btn-info" data-toggle="modal"
                                data-target="#new-attribute">Create New Attribute
                        </button>

                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#new-chart">
                            Create New Chart
                        </button>

                        <!-- Modal -->
                        <div id="new-attribute" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <p class="mb-0 text-center">U can access these keys with the key name you define
                                            here</p>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('device-attribute') }}" method="post">
                                            <div class="form-group">
                                                <label for="key">Enter Key</label>
                                                <input type="text" name="key" id="key" class="form-control">
                                            </div>
                                            @csrf
                                            <input type="hidden" name="device_id" value="{{$device->id}}">

                                            <div class="form-group">
                                                <label for="value">Enter Value</label>
                                                <input type="text" name="value" id="value" class="form-control">
                                            </div>
                                            <button type="submit" class="btn btn-outline-success">Save</button>

                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- New Chart Toggle --}}
                        <div id="new-chart" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <p class="mb-0 text-center">U can Push Data on the table using the <b>Title</b> you set</p>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('store-graph', $device->id) }}" method="post" id="graph-type">                                           
                                            <div class="form-group">
                                                <label for="chart-title">Title</label>
                                                <input type="text" name="title" id="chart-title" class="form-control">
                                            </div>
                                            @csrf
                                            <input type="hidden" name="device_id" value="{{$device->id}}">

                                            <div class="form-group">
                                                <label for="value">Select Chart Type</label>
                                                <select name="type" disabled class="form-control">
                                                    <option value="line" selected>Line Chart</option>
                                                    <option value="bar">Bar Chart</option>
                                                    <option value="pie">Pie Chart</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="value">Select X-Axis Label</label>
                                                <select name="x_axis" disabled class="form-control">
                                                    <option value="timestamp" selected>DateTime</option>
                                                    <option value="months">Year (Jan - Dec)</option>
                                                    <option value="100">1-100</option>
                                                    <option value="auto-numeric">1 -~ Auto Increase</option>
                                                </select>
                                            </div>
                                            <div class="form-group chart-y-axises">
                                                <label for="chart-line">Add Y-Axis Labels( numeric data only)</label>
                                                <div class="input-group">
                                                    <input type="text" name="y_axis" class="form-control">
                                                </div>


                                            </div>
                                            <button type="submit" class="btn btn-outline-success">Save</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-{{ session('alert-type') }} alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{ session('message') }}
                            </div>
                        @endif

                        <div class="row">
                            <table class=" table-bordered table-stripped table">
                                <thead class="table-dark">
                                <tr>

                                    @foreach($device->device_metas as $meta)

                                        <th>{{ $meta->key }}
                                            <a href="{{url('/meta/delete/'.$meta->id)}}" class="delete_node
                                            text-danger"
                                               style="font-size: 14px">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </th>
                                    @endforeach


                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @foreach($device->device_metas as $meta)

                                        <td>
                                            {{ $meta->value }}<br>
                                            <small class="small">updated :<br> {{ $meta->created_at->diffForHumans()
                                            }}</small>
                                        </td>
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <form action="{{ route('update_node', $device->id) }}" method="post"
                              id="device_node">

                            <div class="row">


                                @foreach(  $device->nodes as $node)
                                    <div class="col-md-4">
                                        <section class="node border-dark mb-3 p-2 pt-3">

                                            <h5 class="pull-left pt-2">
                                                <i class="fa {{$node->icon}}"></i> {{$node->name}}
                                                <input type="hidden" id="node_icon" value="{{$node->icon}}">
                                            </h5>
                                            <div class="checkbox pull-right">
                                                <label>
                                                    @if($node->state)

                                                        <input type="checkbox" data-toggle="toggle" checked
                                                               name="{{ $node->name }}" class="node-state"
                                                               id="{{ $node->id }}" value="{{ $node->value }}">

                                                    @else
                                                        <input type="checkbox" data-toggle="toggle"
                                                               name="{{ $node->name }}" class="node-state"
                                                               id="{{ $node->id }}" value="{{ $node->value }}">

                                                    @endif
                                                </label>
                                            </div>
                                            <a href="{{ route('delete_node', [$device->id, $node->id]) }}"
                                               class="delete_node text-danger">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                            <div class="clearfix"></div>

                                        </section>
                                    </div>
                                @endforeach
                            </div>
                        </form>
                    </div>

                    <div class="row">
                        <section class="col-8 m-auto">
                            <table class="table table-bordered table-stripped">
                                <thead>
                                    <tr>
                                        <th>CHART</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <td>{{ /* $device->chart->first()->chartValues or */ $device->chart ? $device->chart->title : 'no chart exists' }}</td>
                                    </tr>
                                </tbody>
                                
                            </table>

                        </section>
                    </div>

                    <footer class="card-footer">
                        <small>
                            @if($device->connection)
                                <i class="fa fa-globe text-success"></i>&nbsp;Connected
                            @else
                                <i class="fa fa-times text-dark-50"></i>&nbsp;Disconnected
                            @endif
                            :&nbsp;{{ $device->updated_at->diffForHumans() }}
                        </small>
                    </footer>

                    <script>
                        //                        setTimeout(function () {
                        //                            window.location.reload();
                        //                        }, 10000);
                    </script>

                </div>
            </div>
        </div>
    </div>
@endsection