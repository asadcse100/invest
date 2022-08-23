<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SystemEmail;
use App\Services\Apis\RecaptchaService;

class ContactController extends Controller
{
    /**
     * Handle a newly submitted  message in contact form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function contact(Request $request, RecaptchaService $recaptcha)
    {
        if (Auth::check()) {
            $request = $this->validate($request, [
                'subject' => ['required', 'min:3', 'max:255'],
                'message' => ['required', 'min:5', 'max:1000'],
            ], [
                'subject.required' => __("Please enter your subject line."),
                'message.required' => __("Please enter your message."),
            ]);
            $request = array_merge($request, [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => user_meta('profile_phone', '', Auth::user()),
            ]);
        }
        else {
            if (has_recaptcha()) $recaptcha->verify($request);
            $request = $this->validate($request, [
                'name' => ['required', 'min:2', 'max:50'],
                'email' => ['required', 'email', 'max:255'],
                'phone' => ['nullable', 'numeric', 'digits_between:4,20'],
                'subject' => ['required', 'min:5', 'max:255'],
                'message' => ['required', 'min:5', 'max:1000'],
            ], [
                'name.required' => __("Please enter your full name."),
                'email.required' => __("Please enter your email address."),
                'email.email' => __("Please enter a valid email address."),
                'subject.required' => __("Please enter your subject line."),
                'message.required' => __("Please enter your message."),
            ]);
        }

        $data = array_map('strip_tags_map', $request);

        if (isset($data['message'])) {
            $data['message'] = auto_p($data['message']);
        }

        $recipient = get_email_recipient();
        $success = false;

        try {
            Mail::to($recipient)->send(new SystemEmail($data, 'admin.contact'));
            $success = true;
        } catch (\Exception $e) {
            save_mailer_log($e, 'contact-form');
        }

        if ($success == true) {
            return response()->json(['title' => __("Message has been sent!"), 'msg' => __("Thanks for your sending your message! We will  get back to you shortly."), 'reload' => true]);
        } else {
            return response()->json(['type' => 'error', 'title' => __("Failed to sent!"), 'msg' => __("There was an error trying to send your message. Please try again later.")]);
        }

    }
}
