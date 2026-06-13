<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::query()->with('user')->latest();

        if ($request->filled('module')) {
            $query->where('module', $request->query('module'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->query('action'));
        }

        return view('admin.audit-logs.index', [
            'logs' => $query->paginate(20)->withQueryString(),
            'modules' => AuditLog::query()->select('module')->distinct()->orderBy('module')->pluck('module'),
            'actions' => ['created', 'updated', 'deleted'],
        ]);
    }
}
