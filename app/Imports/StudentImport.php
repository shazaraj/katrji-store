<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
            //
            'name' => $row[0],
            'univ_id' => $row[1],
            'national_id' => $row[2],
            'mobile' => $row[3],
        ]);
    }
}
