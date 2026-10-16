<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Payment;

class DashboardController extends Controller
{

    public function index()
    {
        $totalClients = Client::count();

        $totalProjects = Project::count();

        $ongoingProjects = Project::where('status', 'ongoing')->count();

        $completedProjects = Project::where('status', 'completed')->count();

        $totalAgreed = Project::sum('agreed_price');

        $totalReceived = Payment::sum('amount');

        $remainingBalance = $totalAgreed - $totalReceived;

        $recentProjects = Project::with('client')
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with('project.client')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalClients',
            'totalProjects',
            'ongoingProjects',
            'completedProjects',
            'totalAgreed',
            'totalReceived',
            'remainingBalance',
            'recentProjects',
            'recentPayments'
        ));
    }

}