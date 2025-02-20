<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Mail\SendMailable;
use App\Email;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function dummyEmail()
    {

     return view('email.dummy', []);

    }

    public function sendDummyEmail(Request $req)
    {

        $content= $req->email_content;
        $subject= $req->email_subject;
        $receiver= $req->email_to;
      //  Mail::to(['nuramirah.adnan@tm.com.my','zatiaqmar.zahari@tm.com.my','afdzal@tm.com.my','mimi.maisara@tm.com.my'])->send(new SendMailable($name));

      //  return 'Email was sent';

    //  Mail::to($receiver)->subject($subject)->send(new SendMailable($content));
 $data = array('content'=> $content);
      Mail::send('mail', $data, function($message) {
         $message->to($receiver)->subject($subject);
      });

     //return view('dummy.email.dummy', []);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function show(Email $email)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function edit(Email $email)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Email $email)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function destroy(Email $email)
    {
        //
    }
}
