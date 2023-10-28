<?php

namespace App\Http\Controllers;

use App\Exports\ChronoExport;
use App\Models\Trial;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function chrono(string $id)
    {
        $trial = Trial::findOrFail($id);
        $date = $trial->updated_at->format('Y-m-d');
        return Excel::download(new ChronoExport($trial), "chrono-{$trial->slot}-{$date}.xlsx");
    }
}
