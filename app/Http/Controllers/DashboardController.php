<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get authenticated user
        $user = Auth::user();

        // =====================================
        // CHECK PERMISSIONS
        // =====================================

        $canViewClients = $user->hasPermission('clients.view');

        $canViewProjects = $user->hasPermission('projects.view');

        $canViewPayments = $user->hasPermission('payments.view');

        $canViewReports = $user->hasPermission('reports.view');


        // =====================================
        // CLIENT STATISTICS
        // =====================================

        $totalClients = 0;

        if ($canViewClients) {
            $totalClients = Client::count();
        }


        // =====================================
        // PROJECT STATISTICS
        // =====================================

        $totalProjects = 0;
        $ongoingProjects = 0;
        $completedProjects = 0;
        $recentProjects = collect();

        if ($canViewProjects) {

            $totalProjects = Project::count();

            $ongoingProjects = Project::where(
                'status',
                'ongoing'
            )->count();

            $completedProjects = Project::where(
                'status',
                'completed'
            )->count();

            $recentProjects = Project::with('client')
                ->latest()
                ->take(5)
                ->get();
        }


        // =====================================
        // FINANCIAL STATISTICS
        // =====================================

        $totalAgreed = 0;
        $totalReceived = 0;
        $remainingBalance = 0;

        if ($canViewReports) {

            $totalAgreed = Project::sum('agreed_price');

            $totalReceived = Payment::sum('amount');

            $remainingBalance = $totalAgreed - $totalReceived;
        }


        // =====================================
        // RECENT PAYMENTS
        // =====================================

        $recentPayments = collect();

        if ($canViewPayments) {

            $recentPayments = Payment::with('project.client')
                ->latest()
                ->take(5)
                ->get();
        }


        // =====================================
        // RETURN DASHBOARD VIEW
        // =====================================

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