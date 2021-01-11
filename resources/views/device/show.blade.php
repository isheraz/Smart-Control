@extends('layouts.app')

@section('content')
@if($device->manual_alert==1)
<script type="text/javascript">
setTimeout(function () { 
location.reload();
}, 60 * 1000);
</script>
@endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <h5>Device Serial: {{ $device->serial }}</h5>
                        <a href="{{route('create-appliance', $device->id)}}" class="btn btn-info" style="width:140px">Create
                            Appliance</a>
                        <!-- Trigger the modal with a button -->
                        <button type="button" class="btn btn-info" data-toggle="modal"
                                data-target="#new-attribute"  style="width:140px">Create Attribute
                        </button>

                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#new-chart" style="width:140px">
                            Create Chart
                        </button>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#new-label" style="width:140px">
                            Create Label
                        </button>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#new-streaming" style="width:140px">
                            Create Streaming
                        </button>
                         <div class="checkbox pull-right">
                        <?php
                        if($device->manual_alert==1)
						{
						$showtext="Auto Update";
						}
						else
						{
						$showtext="Manual Update";
						}
						?>
                       
						<a  class="btn btn-info" style="cursor:pointer;color:#FFFFFF; background-color: #2959A1;width:140px" onclick="update_alert('{{ $device->id }}','{{ $device->manual_alert }}')" id="autoButton">{{$showtext}}</a>
                                                    </div>
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
                

                    {{-- New label Toggle--}}
                        <div id="new-label" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <p class="mb-0 text-center"><h2>Label</h2></p>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('store_label', $device->id) }}" method="post" id="graph-type">                                           
                                            <div class="form-group">
                                                <label for="label-title">Label Title</label>
                                                <input type="text" name="label_title" id="label-title" class="form-control" required>
                                            </div>
                                            @csrf
                                            <input type="hidden" name="device_id" value="{{$device->id}}">

                                            <div class="form-group">
                                                <label for="value">Label Value</label>
                                            <input type="text" name="label_value" id="label-value" class="form-control" required>    
                                            </div>

                                            
                                            
                                            <button type="submit" class="btn btn-outline-success">Save</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        {{-- edit label Toggle--}}
                        <div id="edit_label_model" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <p class="mb-0 text-center"><h2>Update Label</h2></p>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('update_label', $device->id) }}" method="post" id="graph-type">                                           
                                            <div class="form-group">
                                                <label for="label-title">Label Title</label>
                                                <input type="text" name="edit_label_title" id="edit_label_title" class="form-control" required>
                                            </div>
                                            @csrf
                                            <input type="hidden" name="device_id" value="{{$device->id}}">
                                            <input type="hidden" name="label_id" id="label_id" value="">

                                            <div class="form-group">
                                                <label for="value">Label Value</label>
                                            <input type="text" name="edit_label_value" id="edit_label_value" class="form-control" required>    
                                            </div>

                                            
                                            
                                            <button type="submit" class="btn btn-outline-success">Update</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>

                            </div>
                        </div> {{--end edit label--}}
                        
                        {{-- New streaming Toggle--}}
                        <div id="new-streaming" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <p class="mb-0 text-center"><h2>Streaming</h2></p>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('store_streaming', $device->id) }}" method="post" id="graph-type">                                           
                                            <div class="form-group">
                                                <label for="label-title">Name</label>
                                                <input type="text" name="txtName" id="txtName" class="form-control" required>
                                            </div>
                                            @csrf
                                            <input type="hidden" name="device_id" value="{{$device->id}}">

                                            <div class="form-group">
                                                <label for="value">Link</label>
                                            <input type="text" name="txtLink" id="txtLink" class="form-control" required>    
                                            </div>

                                            
                                            
                                            <button type="submit" class="btn btn-outline-success">Save</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        {{-- edit streaming Toggle--}}
                        <div id="edit_streaming_model" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <p class="mb-0 text-center"><h2>Update Streaming</h2></p>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('update_streaming', $device->id) }}" method="post" id="graph-type">                                           
                                            <div class="form-group">
                                                <label for="label-title">Name</label>
                                                <input type="text" name="edit_name" id="edit_name" class="form-control" required>
                                            </div>
                                            @csrf
                                            <input type="hidden" name="device_id" value="{{$device->id}}">
                                            <input type="hidden" name="streaming_id" id="streaming_id" value="">

                                            <div class="form-group">
                                                <label for="value">Link</label>
                                            <input type="text" name="edit_link" id="edit_link" class="form-control" required>    
                                            </div>

                                            
                                            
                                            <button type="submit" class="btn btn-outline-success">Update</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>

                            </div>
                        </div> {{--end edit label--}}
                        
                        <div id="view_video" class="modal fade" role="dialog" >
                            <div class="modal-dialog" >

                                <!-- Modal content-->
                                <div class="modal-content" style="width:600px;">
                                    <div class="modal-header">
                                        <p class="mb-0 text-center"><h2>View Video</h2></p>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body" id="video_body">
                                        
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        <div id="view_history" class="modal fade" role="dialog" >
                            <div class="modal-dialog" >

                                <!-- Modal content-->
                                <div class="modal-content" style="width:600px;">
                                    <div class="modal-header">
                                        <p class="mb-0 text-center"><h2>View History</h2>
                                        
                                        </p>
                                        
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body" id="history_body">
                                        
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
                        <div class="col-md-12">    
                            @if($streaming)
                        <h5>Streaming</h5>
                        <table class=" table-bordered table-stripped table">
                        <thead class="table-dark">
                        <tr>
                        @foreach($streaming as $srow)

                                        <th>{{ $srow->s_name }}
                                            
                                            <a onClick="if (confirm(&quot;Are you sure you want to delete this record ?&quot;)) { window.location.replace('{{url('/streaming_delete/'.$srow->id)}}'); } event.returnValue = false; return false;" class="delete_node text-danger" style="font-size: 14px; cursor:pointer"><i class="fa fa-trash-o"></i></a> 
                                            <a onclick="edit_streaming('{{$srow->id}}','{{$srow->s_name}}','{{$srow->s_link}}')" style="font-size: 14px; cursor:pointer" class="delete_node"> <i class="fa fa-edit"></i>&nbsp;&nbsp;
                                            </a>
                                        
                                        </th>
                                    @endforeach
                        </tr>
                         </thead>
                                <tbody>
                                <tr>
                         @foreach($streaming as $srow)
                                <td>
                                            <a onclick="show_video('{{$srow->s_link}}')" style="cursor:pointer">{{ $srow->s_link }}</a><br>
                                            <!--<small class="small">updated :<br>--> {{ $srow->created_at}}</small>
                                        </td>
                                    @endforeach
                         </tr>
                         </tbody>
                         </table>
                         @endif
                         </div>
                         </div>
                         
                        <div class="row">
                        <div class="col-md-12">    
                            @if($labels)
                        <h5>Label</h5>
                        <table class=" table-bordered table-stripped table">
                        <thead class="table-dark">
                        <tr>
                        @foreach($labels as $lrow)

                                        <th>{{ $lrow->label_title }}
                                            
                                            <a onClick="if (confirm(&quot;Are you sure you want to delete this label info ?&quot;)) { window.location.replace('{{url('/label_delete/'.$lrow->id)}}'); } event.returnValue = false; return false;" class="delete_node text-danger" style="font-size: 14px; cursor:pointer">
                                                <i class="fa fa-trash-o"></i>
                                            </a> 
                                            <a onclick="edit_label('{{$lrow->id}}','{{$lrow->label_title}}','{{$lrow->label_value}}')" style="font-size: 14px; cursor:pointer" class="delete_node"> <i class="fa fa-edit"></i>&nbsp;&nbsp;
                                            </a>
                                          <a onclick="view_history('{{$lrow->id}}','label')" style="font-size: 14px; cursor:pointer" class="delete_node" title="View History of {{ $lrow->label_title }}"> <i class="fa fa-history"></i>&nbsp;&nbsp;  </a>
                                        </th>
                                    @endforeach
                        </tr>
                         </thead>
                                <tbody>
                                <tr>
                         @foreach($labels as $lrow)
                                <td>
                                            {{ $lrow->label_value }}<br>
                                            <!--<small class="small">updated :<br>--> {{ $lrow->created_at}}</small>
                                        </td>
                                    @endforeach
                         </tr>
                         </tbody>
                         </table>
                         @endif
                         </div>
                         </div>
                         <div class="row">
                        <div class="col-md-12"> 
                          <h5>Attribute</h5>
                            <table class=" table-bordered table-stripped table">
                                <thead class="table-dark">
                                <tr>

                                    @foreach($device->device_metas as $meta)

                                        <th @if($meta->alert_status==1) style="background-color:#D02B2C" @endif>{{ $meta->key }}
                                            <a href="{{url('/meta/delete/'.$meta->id)}}" class="delete_node @if($meta->alert_status==0) text-danger @endif" style="font-size: 14px;@if($meta->alert_status==1) color:#fff @endif ">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                            <a onclick="view_history('{{$meta->id}}','attributes')" style="font-size: 14px; cursor:pointer" class="delete_node" title="View History of {{ $meta->key }}"> <i class="fa fa-history"></i>&nbsp;&nbsp;  </a>
                                        </th>
                                    @endforeach


                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    @foreach($device->device_metas as $meta)

                                        <td @if($meta->alert_status==1) style="border-color:#D02B2C" @endif>
                                            @if($meta->alert_status==1)
                                            <span style="color:#D02B2C">
                                            {{ $meta->value }}
                                            </span>
                                            @else
                                            {{ $meta->value }}
                                            @endif
                                            <br>
                                            <small class="small">updated :<br> {{ $meta->created_at->diffForHumans()
                                            }}</small>
                                        </td>
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        </div>
                        <form action="{{ route('update_node', $device->id) }}" method="post"
                              id="device_node">

                            <div class="row">


                                @foreach(  $device->nodes as $node)
                                    <div class="col-md-4" style="padding-right:0">
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
                                    <td>{{  sizeof($device->chart) > 0  ? ( isset($device->chart->title) ? $device->chart->title : 'No Chart Data') : 'no chart exists' }}</td>
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
    <script>
	function getId(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);

    return (match && match[2].length === 11)
      ? match[2]
      : null;
}
	function show_video(video_link)
	{
	const videoId = getId(video_link);
	
	const iframeMarkup = '<iframe width="560" height="315" src="//www.youtube.com/embed/' 
    + videoId + '" frameborder="0" allowfullscreen></iframe>';
	$('#video_body').html(iframeMarkup);
	$('#view_video').modal('show');
	}
	
	function view_history(id,type)
	{
	$.ajax({	
    url:"{{ route('view_history') }}",
   	type: 'post', //this is your post type
    data: {id:id,type:type, _token: '{{csrf_token()}}' },
    success: function (data) {
	//alert(data['Status']);	
	$('#history_body').html(data);
	$('#view_history').modal('show');
	
    },
    
});
	}
    	function update_alert(attribute_id,manual_alert)
	{
	//alert(attribute_id);
	//alert(manual_alert);
	$.ajax({	
    url:"{{ route('update_manual_alert') }}",
   	type: 'post', //this is your post type
    data: {attribute_id:attribute_id,manual_alert:manual_alert, _token: '{{csrf_token()}}' },
    success: function (data) {	
	//alert(data['Status']);
	setTimeout(function () { 
      location.reload();
    }, 1 * 1);
	if(data['Status']==1)
	{
	jQuery('#autoButton').text('Auto Update');
	/*setTimeout(function () { 
      location.reload();
    }, 60 * 1000);*/
	}
	else
	{
	jQuery('#autoButton').text('Manual Update');
	}
    },
    
});
	}
	function edit_label(id,text,value)
	{
	$('#label_id').val(id);
	$('#edit_label_title').val(text);
	$('#edit_label_value').val(value);
	
	$('#edit_label_model').modal('show');
	}
	
	function edit_streaming(id,text,value)
	{
	$('#streaming_id').val(id);
	$('#edit_name').val(text);
	$('#edit_link').val(value);
	
	$('#edit_streaming_model').modal('show');
	}
	function update_limit(id,val)
	{
	$.ajax({	
    url:"{{ route('update_limit') }}",
   	type: 'post', //this is your post type
    data: {id:id,val:val, _token: '{{csrf_token()}}' },
    success: function (data) {	
	alert('View Limit updated successfully');
	
	
	
    },
    
});
	}
	</script>
@endsection