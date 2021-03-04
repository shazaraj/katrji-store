<?php

namespace App\Imports;

use App\Models\StudentMark;
use Maatwebsite\Excel\Concerns\ToModel;

class ExcelImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new StudentMark([
            //
            'student_id' => $row[0],
            'student_name' => $row[1],
            'study_year_id' => $row[2],
            'season_id' => $row[3],
            'subject_id' => $row[4],
            'mark' => $row[5],
        ]);
    }
}
