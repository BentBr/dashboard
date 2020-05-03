<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class DashboardController extends Controller
{
    public function renderBrashboard()
    {

        $data['clients'] = Client::getAllClientsWithLoginVisitEventsCount();


        return view('BRashboard', $data);
    }
}
