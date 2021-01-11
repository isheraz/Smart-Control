<?php



namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Label;
use App\Streaming;
use App\ChartData;
use App\HistoryMain;
use App\HistoryChild;
use App\SmartHome;
use App\Appliance;
use DateTime;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class LabelController extends Controller

{
    /**

     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */ 

    public function __construct() {

		$this->middleware( 'auth',

			[
				'except' => [

					'alive',

					'disconnect',

					'devices',

					'update_device',

					'update_node',

					'kill',

					'units'

				]

			]

		);

	}
	 public function store_label(Request $request){    

			$stask = new Label;
            $stask->label_title = $request['label_title'];
			$stask->user_id = Auth::user()->id;
            $stask->label_value = $request['label_value'];
            $stask->device_id = $request['device_id'];
            $stask->save();			

			return redirect('device/'.$request['device_id'])->with([
			'status'     => true,

			'alert-type' => 'success',

			'message'    => 'label created Successfully'

		]);

    }

	public function update_label( Request $request ) {
		
		 $history = DB::table('history_mains')
		 			->where('history_id',$request->label_id)
					->where('history_type','label')
					->first();
		 if($history)
		 {
		 $history_id=$history->id;
		 }
		 else
		 {
		 $stask = new HistoryMain;
            $stask->history_id = $request->label_id;
			$stask->history_type = 'Label';
            $stask->history_text = $request->edit_label_title;
			$stask->view_limit = '10000';
            $stask->save();	
		$history_id = $stask->id;
		 }
		 $stask = new HistoryChild;
            $stask->history_id = $history_id;
			$stask->history_value = $request->edit_label_value;
         $stask->save();	
		$label = Label::where( 'id', '=', $request->label_id )->get()->first();
		$label->label_value      = $request->edit_label_value;	
		$label->save();	

		return redirect()->back()->with( [
			'status'     => true,
			'alert-type' => 'success',
			'message'    => 'label Update Successfully'

		] );
	}

     public function label_delete($label_id)
  	{
	$deleted = DB::delete('delete from history_mains where history_type="Label" AND history_id="'.$label_id.'"');
	$deleted = DB::delete('delete from labels where id="'.$label_id.'"');
	return redirect()->back()->with( [

			'status'     => true,
			'alert-type' => 'danger',
			'message'    => 'label Deleted Successfully'
		] );

	}

   public function update_manual_alert( Request $request ) {

	//	dd($request);
		if($request->manual_alert==1)
		{
		$value=0;
		}
		else
		{
		$value=1;
		}
		$label= SmartHome::where( 'id', '=', $request->attribute_id )->get()->first();
		$label->manual_alert = $value;
		$label->save();
		 return response()->json(["Status"=>$value]);
   }

	////////////////////start streaming ////////////
 public function store_streaming(Request $request){    

			$stask = new Streaming;	
            $stask->s_name = $request['txtName'];
			$stask->s_link = $request['txtLink'];
            $stask->device_id = $request['device_id'];
            $stask->save();
			return redirect('device/'.$request['device_id'])->with([
			'status'     => true,
			'alert-type' => 'success',
			'message'    => 'Streaming created Successfully'

		]);

    }
	public function view_history(Request $request){    
		$creatorData='';
		
		 $history = DB::table('history_mains as m')
		 			->where('m.history_id',$request->id)
					->where('m.history_type',$request->type)
					->join('history_children as c', 'c.history_id', '=', 'm.id')
					->orderBy('c.id', 'DESC')
					->select(DB::Raw('c.*,m.history_text,m.view_limit'))					
					->get();
		$creatorData.='<table class="table table-bordered">';			
		if(count($history)>0)
		{
		
		$creatorData.='<thead>';
		$creatorData.='<tr>';
		$creatorData.='<th><p>View History of '.$history[0]->history_text.' '. $request->type.'</p></th>';
		$creatorData.='<th ><a href="'.url('/clear_history/'.$history[0]->history_id).'" class="btn btn-info">Clear History</a></th>';
		$creatorData.='<th >Update Limit<input type="text" name="txtLimit" class="form-control" value="'.$history[0]->view_limit.'" onblur="update_limit('.$request->id.',this.value)"></th>';
		$creatorData.='</tr>';
		$creatorData.='<tr>';
		$creatorData.='<th></th><th>Value</th><th>Time</th></tr>';
		$creatorData.='</thead>';
		$creatorData.='<tbody>';
		$i=1;
		foreach($history as $row)
		{
		$old_date = $row->created_at;              // returns Saturday, January 30 10 02:06:34
		$old_date_timestamp = strtotime($old_date);
		$new_date = date('d M Y H:i', $old_date_timestamp); 
		$creatorData.='<tr>';
		$creatorData.='<th>'.$i.'</th>';
		$creatorData.='<th>'.$row->history_value.'</th>';
		$creatorData.='<th>'.$new_date.'</th>';
		$creatorData.='</tr>';
		$i++;
		}
		$creatorData.='</tbody>';
		}
		else
		{
		$creatorData.='<tbody>';
		$creatorData.='<tr>';
		$creatorData.='<td colspan=2>No History Availabe</td>';
		$creatorData.='</tr>';
		$creatorData.='</tbody>';
		}
		$creatorData.='</table>';
		echo $creatorData;
		exit;
    }
	public function update_streaming( Request $request ) {

		$label = Streaming::where( 'id', '=', $request->streaming_id )->get()->first();
		$label->s_name = $request->edit_name;
		$label->s_link      = $request->edit_link;	
		$label->save();	
		return redirect()->back()->with( [
			'status'     => true,
			'alert-type' => 'success',
			'message'    => 'Streaming Update Successfully'
		] );
	}

     public function streaming_delete($label_id)
  	{
	$deleted = DB::delete('delete from streamings where id="'.$label_id.'"');
	return redirect()->back()->with( [

			'status'     => true,

			'alert-type' => 'danger',

			'message'    => 'Streamings Deleted Successfully'

		] );

	}
	
	public function clear_history($id)
  	{
	$history = DB::table('history_children')
		 			->where('history_id',$id)
					->orderBy('id', 'DESC')
					->first();
	$deleted = DB::delete('delete from history_children where history_id='.$id.' and id<>'.$history->id.'');
	return redirect()->back()->with( [

			'status'     => true,

			'alert-type' => 'success',

			'message'    => 'History Clear Successfully'

		] );

	}
	public function update_limit( Request $request ) {

		$label = HistoryMain::where( 'id', '=', $request->id )->get()->first();
		$label->view_limit = $request->val;
		$label->save();	
		
	}
}

