<?php

namespace App\Exports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TeachersExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, withTitle
{
    /**
     * Return a collection of users.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Teacher::all();
    }

    /**
     * Return the headings for the sheet.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'id', 
            'is_active',
            'user_id', 
            'unique_id',
            'name',
            'nik',
            'religion',
            'gender',
            'place_birth',
            'nationality',
            'date_birth',
            'home_address',
            'temporary_address',
            'handhphone',
            'email',
            'last_education',
            'major',
            'Created At', 
            'Updated At'
        ];
    }

    /**
     * Apply styles to the sheet.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:S1')->getFont()->setBold(true);
        $sheet->getStyle('A1:S' . $sheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        return [];
    }

    public function title() : string 
    {
        return 'Teachers';
    }
}
