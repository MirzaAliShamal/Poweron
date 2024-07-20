<?php

namespace App\Http\Controllers;

use App\Models\EmailList;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Models\EmailCampaign;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $campaignsCount = EmailCampaign::count();
        $emailListsCount = EmailList::count();
        $subscribersCount = Subscriber::count();
        $emailTemplatesCount = EmailTemplate::count();

        $topCampaigns = EmailCampaign::orderBy('total_opens', 'DESC')->take(5)->get();
        $topEmailLists = EmailList::withCount('activeSubscribers')->orderBy('active_subscribers_count', 'DESC')->take(5)->get();

        return view('dashboard.index', get_defined_vars());
    }

    public function profile()
    {
        return view('dashboard.profile', get_defined_vars());
    }

    public function generalUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.auth()->user()->id,
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('success', 'Account details updated successfully');
    }

    public function passUpdate(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Account password updated successfully');
    }
}
