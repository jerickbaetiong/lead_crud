<?php

namespace App\Http\Controllers;

use App\Lead;
use App\LeadTags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = Lead::with('tags')->orderBy('id', 'desc')->get();

        return response()->json($leads, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'notes' => 'required',
            'tags' => 'required'
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails())
			return response()->json($validator->errors(), 422);

        try{
            DB::beginTransaction();
            $validatedData = $validator->validated();

            $lead = new Lead();
            $lead->email = $validatedData['email'];
            $lead->notes = $validatedData['notes'];
            $lead->save();

            foreach ($validatedData['tags'] as $tag) {
                $lead_tag = new LeadTags();
                $lead_tag->lead_id = $lead->id;
                $lead_tag->tags_id = $tag['id'];
                $lead_tag->save();
            }

            $leads = Lead::with('tags')->where('id', $lead->id)->first();

            Mail::send('emails.newLead', ['lead'=>$leads], function ($message) use ($leads) {
                
                $message->to($leads->email);
                $message->subject('New Lead');

            });
            DB::commit();

            return response()->json($leads, 201);
        }
        catch(\Exception $e){
            DB::rollback();
            return response()->json('Server Error: '.$e, 500);
        }
        

       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'email' => 'required|email',
            'notes' => 'required',
            'tags' => 'required'
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails())
			return response()->json($validator->errors(), 422);

    
        try{
            $validatedData = $validator->validated();
            $lead = Lead::where('id', $id)->first();

            $lead->email = $validatedData['email'];
            $lead->notes = $validatedData['notes'];
            $lead->save();

            LeadTags::where('lead_id', $lead->id)->delete();

            foreach ($validatedData['tags'] as $tag) {
                $lead_tag = new LeadTags();
                $lead_tag->lead_id = $lead->id;
                $lead_tag->tags_id = $tag['id'];
                $lead_tag->save();
            }

            DB::commit();

            return response()->json('Lead information updated for id: '.$lead->id, 200);

        }
        catch(\Exception $e){
            DB::rollback();
            return response()->json('Server Error: '.$e, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Lead::where('id',$id)->delete();

        LeadTags::where('lead_id', $id)->delete();

        return response()->json('Lead with id '.$id.' successfully deleted');
    }
}
