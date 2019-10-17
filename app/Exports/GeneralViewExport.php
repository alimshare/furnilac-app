<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class GeneralViewExport implements FromView
{   

    public function __construct($view = "", $data = array())
    {
        $this->view  	= $view;
        $this->data     = $data;
    }

	public function view(): View
    {
        return view($this->view, $this->data);
    }
}
