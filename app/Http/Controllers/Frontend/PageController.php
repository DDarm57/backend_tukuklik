<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Constant;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function showPage($slug)
    {
        $exceptPage = ['contact'];
        $page = DB::table('pages')->where('slug', $slug)->where('is_actived', 'Y');
        $data['page'] = $page->first();
        if($page->count() == 0){
            if(in_array($slug, $exceptPage)){
                return view($slug, $data);
            }
            return abort(404);
        }
        return view('pages', $data);
    }

    public function sendContactMessage(Request $request)
    {
        $this->validate($request, [
            'full_name'     => 'required',
            'email'         => 'required|email',
            'subject'       => 'required|max:255',
            'message'       => 'required|max:255'
        ]);

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
        return redirect()->back()->with('success', 'Pesan Berhasil Dikirim');
    }
}
