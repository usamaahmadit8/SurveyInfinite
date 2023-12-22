<?php

namespace App\Http\Controllers;

use App\Models\ChildTests\ChildRosterUpdated;
use App\Models\ChildTests\EnglishTest;
use App\Models\ChildTests\TipTest;
use App\Models\ChildTests\GetCompleteSchool;
use App\Models\ChildTests\Title;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;

class WhatsappController extends Controller
{
    //
    public function ChildTest()
    {

        $message = '';
        $current_date = Carbon::now('Asia/Karachi')->subDay()->format('Y-m-d');
        //$current_date="2023-03-23";
        $total_schools_lhr = ChildRosterUpdated::distinct('school_code')->where('lab', '=', 'LHR')->count();
        $total_schools_Bwp = ChildRosterUpdated::distinct('school_code')->where('lab', '=', 'BWP')->count();
        $total_schools = $total_schools_Bwp + $total_schools_lhr;
        $today_schools_lhr = ChildRosterUpdated::distinct('school_code')->where('lab', '=', 'LHR')->where(function ($query) use ($current_date) {
            $query->where(DB::raw("str_to_date(entry_start_time, '%d-%M-%y')"), "=", "$current_date")
                ->orWhere(DB::raw("str_to_date(entry_start_time, '%m/%d/%Y')"), "=", "$current_date")
                ->orWhere(DB::raw("str_to_date(entry_start_time, '%d/%m/%Y')"), "=", "$current_date");
        })->count();
        $today_schools_Bwp = ChildRosterUpdated::distinct('school_code')->where('lab', '=', 'BWP')->where(function ($query) use ($current_date) {
            $query->where(DB::raw("str_to_date(entry_start_time, '%d-%M-%y')"), "=", "$current_date")
                ->orWhere(DB::raw("str_to_date(entry_start_time, '%m/%d/%Y')"), "=", "$current_date")
                ->orWhere(DB::raw("str_to_date(entry_start_time, '%d/%m/%Y')"), "=", "$current_date");
        })->count();
        $today_schools = $today_schools_lhr + $today_schools_Bwp;
        $total_schools_lhr_score = EnglishTest::distinct('school_code')->where('lab', '=', 'LHR')->count();
        $total_schools_Bwp_score = EnglishTest::distinct('school_code')->where('lab', '=', 'BWP')->count();
        $total_schools_score = $total_schools_Bwp_score + $total_schools_lhr_score;
        $total_childs_lhr_score = EnglishTest::distinct('school_code', 'class_code', 'section', 'child_id')->where('lab', '=', 'LHR')->count();
        $total_childs_Bwp_score = EnglishTest::distinct('school_code', 'class_code', 'section', 'child_id')->where('lab', '=', 'BWP')->count();
        $total_childs_score = $total_childs_lhr_score + $total_childs_Bwp_score;
        $today_childs_lhr_score = EnglishTest::distinct('school_code', 'class_code', 'section', 'child_id')->where('lab', '=', 'LHR')->where(function ($query) use ($current_date) {
            $query->where(DB::raw("str_to_date(entry_start_time, '%d-%M-%y')"), "=", "$current_date")
                ->orWhere(DB::raw("str_to_date(entry_start_time, '%m/%d/%Y')"), "=", "$current_date")
                ->orWhere(DB::raw("str_to_date(entry_start_time, '%d/%m/%Y')"), "=", "$current_date");
        })->count();
        $today_childs_Bwp_score = EnglishTest::distinct('school_code', 'class_code', 'section', 'child_id')->where('lab', '=', 'BWP')->where(function ($query) use ($current_date) {
            $query->where(DB::raw("str_to_date(entry_start_time, '%d-%M-%y')"), "=", "$current_date")
                ->orWhere(DB::raw("str_to_date(entry_start_time, '%m/%d/%Y')"), "=", "$current_date")
                ->orWhere(DB::raw("str_to_date(entry_start_time, '%d/%m/%Y')"), "=", "$current_date");
        })->count();
        $today_childs_score = $today_childs_lhr_score + $today_childs_Bwp_score;
        //echo $total_childs_lhr_score;
        $total_schools_lhr_grader = TipTest::where('office', '=', 'LHR')->where('Received_Status', '=', '1')->count();
        $total_schools_Bwp_grader = TipTest::where('office', '=', 'BWP')->where('Received_Status', '=', '1')->count();
        $total_schools_grader = $total_schools_Bwp_grader + $total_schools_lhr_grader;
        $totalSum_LHR = TipTest::where('office', '=', 'LHR')->where('Received_Status', '=', '1')->sum('TotalCount');
        $totalSum_BWP = TipTest::where('office', '=', 'BWP')->where('Received_Status', '=', '1')->sum('TotalCount');
        $total_childs_grader = $totalSum_BWP + $totalSum_LHR;
        $todaySum_LHR = TipTest::where('office', '=', 'LHR')->where(DB::raw("date(Date_of_Received)"), "=", "$current_date")
            ->where('Received_Status', '=', '1')->sum('TotalCount');
        $todaySum_BWP = TipTest::where('office', '=', 'BWP')->where(DB::raw("date(Date_of_Received)"), "=", "$current_date")->where('Received_Status', '=', '1')->sum('TotalCount');
        $today_childs_grader = $todaySum_BWP + $todaySum_LHR;

        $total_teacher = Title::distinct('school_code', 'teacher_id')->count();

        $today_teacher = Title::distinct('school_code', 'teacher_id')->where(function ($query) use ($current_date) {
            $query->where(DB::raw("str_to_date(entry_start_time, '%d-%M-%y')"), "=", "$current_date")
                ->orWhere(DB::raw("str_to_date(entry_start_time, '%m/%d/%Y')"), "=", "$current_date")
                ->orWhere(DB::raw("str_to_date(entry_start_time, '%d/%m/%Y')"), "=", "$current_date");
        })->count();

        $count_of_complete_done_school = Title::distinct('school_code')->count();
        //echo $count_of_complete_done_school;
        $message = "--- RCONS --- Date = " . $current_date . "\n\nApplication name = Child Test\n\n*ROSTER*\nTotal Schools = *$total_schools = " . $total_schools_lhr . "L + " . $total_schools_Bwp . "B*\nToday Schools = *$today_schools = " . $today_schools_lhr . "L + " . $today_schools_Bwp . "B*\n\n*SCORE SHEET*\nTotal Schools = *$total_schools_score = " . $total_schools_lhr_score . "L + " . $total_schools_Bwp_score . "B*\nTotal Childs = *$total_childs_score = " . $total_childs_lhr_score . "L + " . $total_childs_Bwp_score . "B*\nToday Childs = *$today_childs_score = " . $today_childs_lhr_score . "L + " . $today_childs_Bwp_score . "B*\n\n*GRADERS*\nTotal Schools = *" . $total_schools_grader . " = " . $total_schools_lhr_grader . "L + " . $total_schools_Bwp_grader . "B*\nTotal Childs = *" . $total_childs_grader . " = " . $totalSum_LHR . "L + " . $totalSum_BWP . "B*\nToday Childs = *" . $today_childs_grader . " = " . $todaySum_LHR . "L + " . $todaySum_BWP . "B*\n\n*TEACHERS*\nTotal Schools = *" . $count_of_complete_done_school . "*\nTotal Teachers = *" . $total_teacher . "*\nToday Teachers = *" . $today_teacher . "*";
        $result = $this->whatsappNotification("+923008422788", $message);
        $result = $this->whatsappNotification("+923008167076", $message);
        $result = $this->whatsappNotification("+923217642430", $message);
        $result = $this->whatsappNotification("+923215789286", $message);
        $result = $this->whatsappNotification("+923038391109", $message);

        //           $mytime = Carbon::now();
        //   echo $mytime->toDateTimeString();

    }

    private function whatsappNotification(string $recipient, string $message)
    {
        $sid    = getenv("TWILIO_AUTH_SID");
        $token  = getenv("TWILIO_AUTH_TOKEN");
        $wa_from = getenv("TWILIO_WHATSAPP_FROM");
        $twilio = new Client($sid, $token);

        $body = $message;

        return $twilio->messages->create("whatsapp:$recipient", ["from" => "whatsapp:$wa_from", "body" => $body]);
    }
}
