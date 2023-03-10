<?php

namespace App\Http\Controllers;

use App\Models\ChildTests\ChildRosterUpdated;
use App\Models\ChildTests\EnglishTest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class WhatsappController extends Controller
{
    //
    public function ChildTest(){
        $message='';
        $current_date=Carbon::now('Asia/Karachi')->format('Y-m-d');
        $total_schools_lhr=ChildRosterUpdated::where('lab','=','LHR')->groupBy('school_code')->count();
        $total_schools_Bwp=ChildRosterUpdated::where('lab','=','BWP')->groupBy('school_code')->count();
        $total_schools=$total_schools_Bwp+$total_schools_lhr;
        $today_schools_lhr=ChildRosterUpdated::where('lab','=','LHR')->where("str_to_date(entry_start_time, '%d/%m/%Y')","=","$current_date")->groupBy('school_code')->count();
        $today_schools_Bwp=ChildRosterUpdated::where('lab','=','BWP')->where("str_to_date(entry_start_time, '%d/%m/%Y')","=","$current_date")->groupBy('school_code')->count();
        $today_schools=$today_schools_lhr+$today_schools_Bwp;
        $total_schools_lhr_score=EnglishTest::where('lab','=','LHR')->groupBy('school_code')->count();
        $total_schools_Bwp_score=EnglishTest::where('lab','=','BWP')->groupBy('school_code')->count();
        $total_schools_score=$total_schools_Bwp_score+$total_schools_lhr_score;
        $total_childs_lhr_score=EnglishTest::where('lab','=','LHR')->groupBy('school_code','class_code','section','child_id')->count();
        $total_childs_Bwp_score=EnglishTest::where('lab','=','BWP')->groupBy('school_code','class_code','section','child_id')->count();
        $total_childs_score=$total_childs_lhr_score+$total_childs_Bwp_score;
        $today_childs_lhr_score=EnglishTest::where('lab','=','LHR')->where("str_to_date(entry_start_time, '%d/%m/%Y')","=","$current_date")->groupBy('school_code','class_code','section','child_id')->count();
        $today_childs_Bwp_score=EnglishTest::where('lab','=','BWP')->where("str_to_date(entry_start_time, '%d/%m/%Y')","=","$current_date")->groupBy('school_code','class_code','section','child_id')->count();
        $today_childs_score=$today_childs_lhr_score+$today_childs_Bwp_score;
        $message="--- RCONS --- Date = ".$current_date."\n\nApplication name = Child Test\n\n*ROSTER*\nTotal Schools = *$total_schools = ".$total_schools_lhr."L + ".$total_schools_Bwp."B*\nToday Schools = *$today_schools = ".$today_schools_lhr."L + ".$today_schools_Bwp."B*\n\n*SCORE SHEET*\nTotal Schools = *$total_schools_score = ".$total_schools_lhr_score."L + ".$total_schools_Bwp_score."B*\nTotal Childs = *$total_childs_score = ".$total_childs_lhr_score."L + ".$total_childs_Bwp_score."B*\nToday Childs = *$today_childs_score = ".$today_childs_lhr_score."L + ".$today_childs_Bwp_score."B*";
       $result= $this->whatsappNotification("+923038391109",$message);
    }
    private function whatsappNotification(string $recipient,string $message)
    {
        $sid    = getenv("TWILIO_AUTH_SID");
        $token  = getenv("TWILIO_AUTH_TOKEN");
        $wa_from= getenv("TWILIO_WHATSAPP_FROM");
        $twilio = new Client($sid, $token);
        
        $body = $message;

        return $twilio->messages->create("whatsapp:$recipient",["from" => "whatsapp:$wa_from", "body" => $body]);
    }
}
