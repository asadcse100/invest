<?php


namespace App\Http\Controllers\Admin;


use App\Enums\UserRoles;
use App\Enums\EmailTemplateStatus;
use App\Enums\EmailRecipientType;
use App\Mail\SendEmail;
use App\Mail\SendTestEmail;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class ManageEmailTemplatesController extends Controller
{
    public function index()
    {

        $templates = EmailTemplate::paginate(20);
        $iRecipient = (object)get_enums(EmailRecipientType::class, false);
        $iStatus = (object)get_enums(EmailTemplateStatus::class, false);

        return view('admin.manage-content.email-templates.list', compact('templates', 'iRecipient', 'iStatus'));
    }


    public function edit($slug)
    {
        $templateList = optional(EmailTemplate::select('name', 'slug', 'group', 'recipient')->get())->groupBy('group');
        $templateDetails = EmailTemplate::where('slug', $slug)->first();
        $adminUsers = User::select('id', 'name', 'email')->whereIn('role', [UserRoles::SUPER_ADMIN, UserRoles::ADMIN])->get();

        return view('admin.manage-content.email-templates.form', compact('templateList', 'templateDetails', 'adminUsers'));
    }

    public function save(Request $request)
    {
        $input = $request->validate([
            'slug' => 'required',
            'subject' => 'required|string|max:190',
            'greeting' => 'required|string|max:190',
            'content' => 'required|string',
            'addresses' => 'nullable|array',
            'params' => 'nullable|array',
            'status' => 'required',
        ], [
            'slug.required' => 'Invalid Action',
        ]);
        $input = array_map('strip_tags_map', Arr::only($input, ['slug', 'subject', 'greeting', 'content'])) + $input;

        EmailTemplate::updateOrCreate(['slug' => $request->get('slug')], $input);

        return response()->json(['msg' => __('Email template updated successfully.')]);
    }

    public function sendTestEmail(Request $request)
    {
        $input = $request->validate([
            'slug' => 'required|string',
            'send_to' => 'nullable|email'
        ], [
            'slug.*' => __("Please select correct email template."),
            'send_to.email' => __("Please provide a valid email address.")
        ]);

        $slug = Arr::get($input, 'slug');

        try {
            $user = Auth::user();
            $sendTo = $input['send_to'] ?? $user->email;

            Mail::to($sendTo)->send(new SendTestEmail($user, $slug));
            return response()->json([ 'title' => 'Test Email Sent', 'msg' => __("Email (:address) sent to address, please check your email.", ['address' => $sendTo]) ]);

        } catch (\Exception $e) {
            save_mailer_log($e, $slug.'-test');
            throw ValidationException::withMessages(['invalid' => __('Unable to send test email. Please check your email configuration.'), 'trace' => $e->getMessage()]);
        }
    }
}
