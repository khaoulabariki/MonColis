<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditLogController extends Controller
{
    /**
     * Afficher les journaux d'audit du système.
     */
    public function index()
    {
        
        $auditLogs = DB::table('audit_logs')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        
        return view('admin.AuditLog.index', compact('auditLogs'));
    }
}