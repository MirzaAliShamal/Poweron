<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SubscriberCountImport implements ToCollection
{
    public $rowCount = 0;

    public function collection(Collection $rows)
    {
        $this->rowCount = $rows->count();
    }
}
