<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Constant;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function showPage(Request $request)
    {
        $slug = $request->slug;;
        $page = DB::table('pages')->where('slug', $slug)->where('is_actived', 'Y')->first();
        return response()->json($page);
    }

    public function sendContactMessage(Request $request)
    {
        $this->validate($request, [
            'full_name'     => 'required',
            'email'         => 'required|email',
            'subject'       => 'required|max:255',
            'message'       => 'required|max:255'
        ]);

        try {
            $data = Helpers::requestExcept($request);
            DB::table('contacts')->insert([
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                ...$data
            ]);
            $emailForCust = [
                'type'      => 'auto_reply',
                ...$data
            ];
            $emailForCust['message'] = Constant::AUTO_REPLY;
            $emailForAdmin = [
                'type'      => 'send',
                ...$data
            ];
            Mail::to($request->email)->queue(new ContactMail($emailForCust));
            Mail::to(Helpers::generalSetting()->company_email)->queue(new ContactMail($emailForAdmin));
            return response()->json([
                'success'   => true,
                'message'   => 'Pesan Berhasil Dikirim'
            ]);
        } catch(Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage()
            ], 400);
            DB::rollBack();
        }
    }
}
