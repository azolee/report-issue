<?php

namespace App\Services;

use App\Report;
use Illuminate\Http\Request;

class ReportService
{
    public function create(Request $request) {
        return Report::create( $request->only(['title', 'details', 'email', 'phone', 'user_id', 'data']) );
    }

    public function update(Request $request, Report $report) {
        return tap( $report )->update( $request->only(['title', 'details', 'email', 'phone', 'user_id', 'data']) );
    }

    public function updateStatus(Request $request, Report $report, $status) {
        if( in_array($status, Report::$statusValues) ) {
            return tap( $report )->update( ['status' => $status] );
        }
        return false;
    }

    public function delete(Report $report){
        return $report->delete();
    }

}