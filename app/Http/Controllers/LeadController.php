<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class LeadController extends Controller
{
    public function showLeads(){
        $leads = DB::table('leads')->get();
        return view('pages.leads', ['data' => $leads]);
    }
    
    public function showAddLeadForm($id = null, $isView = false)
    {
        $leadData = null;
        if ($id) {
            $leadData = DB::table('leads')->where('id', $id)->first();
            // Handle case if lead is not found
        }
        return view('pages.add-lead', ['leadData' => $leadData, 'isViewMode' => $isView]);
    }

    public function showLead($id = null, $isView = true){
        $leadData = null;
        if ($id) {
            $leadData = DB::table('leads')->where('id', $id)->first();
        }
        return view('pages.add-lead', ['leadData' => $leadData, 'isViewMode' => $isView]);
    }

    public function getLeadData(Request $req){
        $leadData = [
            'name' => $req->input('name'),
            'mobile_number_1' => $req->input('mobile_no_1'),
            'mobile_number_2' => $req->input('mobile_no_2'),
            'landline_number_1' => $req->input('landline_no_1'),
            'landline_number_2' => $req->input('landline_no_2'),
            'email' => $req->input('email'),
            'source_of_information' => $req->input('source_of_information'),
            'details' => $req->input('details'),
        ];
        
        return $leadData;
    }

    public function addLead(Request $req){
        $leadData = $this->getLeadData($req);
        try 
        {   if(DB::table('leads')->where('email', $leadData['email'])->first())
            {
                return response()->json(['error' => 'Lead already exists']);
            }
            else
            {
                $inserted = DB::table('leads')->insert($leadData);
                return response()->json(['success' => 'Lead added successfully']);
            }
            
        } 
        catch (\Exception $e) 
        {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateLead(Request $req) {
        $leadData = $this->getLeadData($req);
        $id = $req->input('id'); // Get the user ID from the request
        try {
            $updated = DB::table('leads')
                ->where('id', $id)
                ->update($leadData);
    
            if ($updated) {
                return response()->json(['success' => 'Lead updated successfully']);
            } else {
                return response()->json(['error' => 'Lead not found or update failed'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteLead(Request $req) {
        $id = $req->input('id');
        try {
            $deleted = DB::table('leads')
                ->where('id', $id)
                ->delete();
    
            if ($deleted) {
                return new JsonResponse(['success' => 'Lead deleted successfully'], 200);
            } else {
                return new JsonResponse(['error' => 'Lead not found or could not be deleted'], 404);
            }
        } 
        catch (\Exception $e) 
        {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }  

    public function addCallLog(Request $req)
    {
        $leadId = $req->input('id');
        $validatedData = $req->validate([
            'call_date_time' => 'required',
            'customer_response' => 'required',
            'next_call_date_time' => 'required',
        ]);

        $callDateTime = Carbon::createFromFormat('Y-m-d H:i', $req->input('call_date_time'));
        $nextCallDateTime = Carbon::createFromFormat('Y-m-d H:i', $req->input('next_call_date_time'));

        $logData = [
            'lead_id' => $leadId,
            'date_of_call' => $callDateTime->toDateString(),
            'time_of_call' => $callDateTime->toTimeString(),
            'customer_response' => $req->input('customer_response'),
            'next_call_date' => $nextCallDateTime->toDateString(),
            'next_call_time' => $nextCallDateTime->toTimeString(),
        ];

        try
        {
            $inserted = DB::table('call_logs')->insert($logData);
            return response()->json(['success' => 'Call log added successfully']);
        }
        catch (\Exception $e) 
        {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }        
    }
}
