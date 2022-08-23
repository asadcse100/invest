<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserActivity;
use App\Models\UserMeta;
use Carbon\Carbon;

class ActivityController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @version 1.0.0
     * @since 1.0
     */
    public function view()
    {
        $activities = UserActivity::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->take(20)->get();
        return view('user.account.activity', compact('activities'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @version 1.0.0
     * @since 1.0
     */
    public function destroy($id)
    {
        UserActivity::where('id', $id)->where('user_id', auth()->user()->id)->delete();
        return redirect()->route('account.activity');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @version 1.0.0
     * @since 1.0
     */
    public function clearActivity()
    {
        UserActivity::where('user_id', auth()->user()->id)->delete();

        UserMeta::updateOrCreate([
            'user_id' => auth()->user()->id,
            'meta_key' => 'last_clear_activity',
        ], ['meta_value' => Carbon::now()->timestamp]);

        return redirect()->route('account.activity');
    }
}
