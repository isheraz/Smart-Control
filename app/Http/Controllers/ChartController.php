<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Chart;
use App\ChartData;
use App\SmartHome;
use App\Label;
use App\Appliance;
use App\HistoryMain;
use App\HistoryChild;
use DateTime;
use App\Streaming;
use App\SmartHomeMeta;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
class ChartController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($device_id, Request $request)
    {
        if (!Chart::where('device_id', $device_id)->get()->first()) {
            $chart = Chart::create([
                'title' => $request->title,
                'type' => /* $request->type or  */ 'line',
                'x_axis' =>/*  $request->x_axis or */ 'timestamp',
                'y_axis' => $request->y_axis,
                'device_id' => $device_id
            ]);
            return response()->json(['message' => 'chart for device created successfully'], 200);
        }

        return response()->json(['message' => 'chart for device already exists'], 401);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chart  $chart
     * @return \Illuminate\Http\Response
     */
    public function show(SmartHome $device)
    {
        return $device->charts();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chart  $chart
     * @return \Illuminate\Http\Response
     */
    public function showX($device)
    {
        $device = SmartHome::where('serial', $device)->get()->first();
        $chart = $device->chart()->with('chartValues')->first();
        // dd(ChartData::all());
        return response()->json($chart);
    }

    public function showLabelX($device, $label)
    {
        $device = SmartHome::where('serial', $device)->get()->first();
        $chart = $device->chart()->with(['chartValues' => function ($q) use ($label) {
            $q->where('chart_label','LIKE', "%$label%")->orderBy('x', 'DESC')->take(50);
        }])->get();
        return response()->json($chart);
    }

    public function getAllLabels($device)
    {
        $device = SmartHome::where('serial', $device)->get()->first();
        $chart = $device->chart()->with(['chartValues' => function ($q) {
           
        }])->get()->first()->chartValues->groupBy('chart_label')->orderBy('id', 'DESC')->take(50);
        // dd($chart->first()->chartValues->unique('chart_label'));
        return response()->json($chart);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chart  $chart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $device)
    {
        $device = SmartHome::where('serial', $device)->get()->first();
        $chart = $device->chart->first();
        // dd($chart->id);
        $res = ChartData::insert([
            'x' => new DateTime(now()),
            'y' => $request->y,
            'chart_label' => $request->label,
            'chart_id' => $chart->id
        ]);

        return response()->json(["value_added" => $res]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chart  $chart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chart $chart)
    {
        //
    }
    public function get_labels(Request $request)
    {	
      $labels = DB::table('labels as l')
	  			    ->join('smart_homes as d', 'd.id', '=', 'l.device_id')
	  				->where('d.serial',$request->serial)
					->select(DB::Raw('l.*'))
					->get();
      return response()->json($labels);
        
    }
	
	public function get_labelbyID(Request $request)
    {	
       $label_info = DB::table('labels as l')
                    ->whereraw('LOWER(l.label_title)=?',strtolower($request->label_title))
	  			    ->join('smart_homes as d', 'd.id', '=', 'l.device_id')
	  				->where('d.serial',$request->serial)
					->select(DB::Raw('l.*'))
					->first();
      return response()->json($label_info);
        
    }
    
     public function dashboard(Request $request)
    {
         $devices      = DB::table('smart_homes')->where('user_id',$request->user_id)->get();
		$connected    = count( SmartHome::where( 'connection', '=', '1' )->where('user_id', '=', $request->user_id)->get() );
		$disconnected = count( SmartHome::where( 'connection', '=', '0' )->where('user_id', '=', $request->user_id)->get() );
		
		 return response()->json(["connected" => $connected,"disconnected" => $disconnected,"devices" => $devices]);
    }
	
	 public function device_detail(Request $request)
    {
       $labels = DB::table('labels')->where('device_id',$request->device_id)->get();
	   $attributes    = DB::table('smart_home_metas')->where('smart_home_id',$request->device_id)->get();
	   $appliances    = DB::table('appliances')->where('smart_home_id',$request->device_id)->get();		
	   return response()->json(["labels" => $labels,"attributes" => $attributes,"appliances" => $appliances]);
    }
	public function update_appliances( Request $request ) {
//		dd($request);
		$Appliance = Appliance::where( 'id', '=', $request->id )->get()->first();
		$Appliance->state = $request->state;
		$Appliance->save();
		return response()->json(['Status'=>$request->state]);
	}
	public function alert_status_on( Request $request ) {
	    
//		dd($request);
		$user_infos    = DB::table('smart_homes as s')
						->join('user_apps as p', 's.user_id', '=', 'p.user_id')
						->join('smart_home_metas as m', 's.id', '=', 'm.smart_home_id')
						->select(DB::Raw('p.user_id,p.app_id,s.location_name,m.key as attribute_name,s.id,m.id as attribute_id'))
						->where('s.serial',$request->serial)
						->whereraw('LOWER(m.key)=?',strtolower($request->key))
						->get();
		if(count($user_infos)>0)	
		{			
		foreach($user_infos as $user_info)
		{
		$notificationBody = array(
    'title' => 'Alert '.$user_info->attribute_name,
    'body' => $user_info->location_name.' has alert on '.$user_info->attribute_name.' please check.',
    'status' => 2,
    'click_action' => 'com.semicolons.smartcontrol.uiActivities.DevicesTabActivity'
);

	$device_id=$user_info->id;
		$url = "https://fcm.googleapis.com/fcm/send";
       $requestBody = array("to" => $user_info->app_id, "notification" => $notificationBody, "data" =>['device_id'=>$device_id] );
        $authKey = "key=AAAAt-pYooQ:APA91bHbtWQg7Incyo71ddpzVxb7MAYNOnjtNojbsPmj1oROc-nPWPcLzVSB2zIQtFQ9kjFUv2aNvWSurcmA2FWDpegp4FftYPTWRcw6gV37V2Nm9XT_88AJ__3AXg_9ma-obkcq7Og1";
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json',
                'Authorization' => $authKey
            ]
        ]);
		$response = $client->post($url, ['body' => json_encode($requestBody)]);
		}
		///////////////////////update status
		$label= SmartHomeMeta::where('id', $user_info->attribute_id)->get()->first();
		$label->alert_status = 1;
		$label->save();
		
		return response()->json(["status"=>"Status updated","user_id"=>$user_info->user_id,"app_id"=>$user_info->app_id,'device_id'=>$device_id]);	
		}
		else
		{
		return response()->json(["status"=>"No Record Found"]);
		}
	}
   public function alert_status_off( Request $request ) {
//		dd($request);
$user_info    = DB::table('smart_home_metas as m')
						->join('smart_homes as s', 's.id', '=', 'm.smart_home_id')
						->where('s.serial',$request->serial)
						->whereraw('LOWER(m.key)=?',strtolower($request->key))
							->select(DB::Raw('m.*'))
						->first();
		$label= SmartHomeMeta::where( 'id', '=', $user_info->id )->get()->first();
		$label->alert_status = 0;
		$label->save();
		return response()->json('Status updated');	
		}
		
		public function update_label( Request $request ) {
//		dd($request);
		
		
		$Label = Label::where( 'id', '=', $request->id )->get()->first();
		$Label->label_value = $request->label_value;
		$Label->save();
		
		///////////////////update history//////////////////////
		$history = DB::table('history_mains')
		 			->where('history_id',$request->id)
					->where('history_type','label')
					->first();
		 if($history)
		 {
		 $history_id=$history->id;
		 }
		 else
		 {
		 $Label = Label::where( 'id', '=', $request->id )->get()->first();
		 $stask = new HistoryMain;
            $stask->history_id = $request->id;
			$stask->history_type = 'Label';
            $stask->history_text = $Label->label_title;
			$stask->view_limit = '10000';
            $stask->save();	
		$history_id = $stask->id;
		 }
		 $stask = new HistoryChild;
            $stask->history_id = $history_id;
			$stask->history_value = $request->label_value;
         $stask->save();
		return response()->json(['Status'=>'Label Updated']);
	}
	
	public function update_streaming( Request $request ) {
//		dd($request);
		$streaming_info = DB::table('smart_homes')->where( 'serial', '=', $request->serial )->get()->first();
		if($streaming_info)
		{
		$Label = Streaming::where( 'device_id', '=', $streaming_info->id)->where( 's_name', '=', $request->name )->get()->first();
		$Label->s_link = $request->link;
		$Label->save();
		return response()->json(['Status'=>'Streaming Updated']);
		}
		else
		{
		return response()->json(['Status'=>'No Record Found']);
		}
	}
	
	public function get_streaming(Request $request)
    {
       $streaming = DB::table('streamings')->where( 'device_id', '=', $request->device_id )->get();
	   return response()->json($streaming);
	}
	
	public function get_history(Request $request){    
				
		 $history = DB::table('history_mains as m')
		 			->where('m.history_id',$request->id)
					->where('m.history_type',$request->type)
					->join('history_children as c', 'c.history_id', '=', 'm.id')
					->orderBy('c.id', 'DESC')
					->select(DB::Raw('c.*'))					
					->get();
	return response()->json($history);
	}
	
	public function clear_history(Request $request)
  	{
	 $history = DB::table('history_mains as m')
		 			->where('m.history_id',$request->id)
					->where('m.history_type',$request->type)
					->join('history_children as c', 'c.history_id', '=', 'm.id')
					->orderBy('c.id', 'DESC')
					->select(DB::Raw('c.id,m.id as main_id'))	
					->first();
	$sql='delete from history_children where history_id='.$history->main_id.' and id<>'.$history->id;
	$deleted = DB::delete($sql);
	return response()->json(['status'=>'History Clear Successfully']);
	}
	
}
