<?php

namespace App\Exports;

use App\POModel;
use App\ItemModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\FromCollection;

class POExport implements FromView
{
    public function view(): View
    {
        return view('back-end/pages/po/lrp', [
            'data' => ItemModel::get()
        ]);
    }
}
