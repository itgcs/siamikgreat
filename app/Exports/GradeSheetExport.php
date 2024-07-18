<?php

namespace App\Exports;

use App\Models\Teacher_subject;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GradeSheetExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected $grade;

    public function __construct($grade)
    {
        $this->grade = $grade;
    }

    public function collection()
    {
        return Teacher_subject::leftJoin('subjects', 'teacher_subjects.subject_id', '=', 'subjects.id')
            ->leftJoin('teachers', 'teacher_subjects.teacher_id', '=', 'teachers.id')
            ->select(
                'subjects.name_subject as subject_name',
                'teachers.name as teacher_name'
            )
            ->where('teacher_subjects.grade_id', $this->grade)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Subject', 
            'Teacher'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet->getStyle('A1:B' . $sheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        return [];
    }

    public function title(): string
    {
        return $this->grade;
    }
}
