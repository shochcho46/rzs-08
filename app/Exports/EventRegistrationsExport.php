<?php

namespace App\Exports;

use App\Models\EventDetail;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class EventRegistrationsExport implements FromArray, WithHeadings, WithStyles, WithTitle
{
    protected $eventDetails;
    protected $eventTitle;

    public function __construct($eventDetails, $eventTitle = 'Event Registrations')
    {
        $this->eventDetails = $eventDetails;
        $this->eventTitle = $eventTitle;
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->eventDetails as $detail) {
            // Add main participant row
            $rows[] = [
                $detail->id,
                'Main',
                $detail->name,
                $detail->mobile,
                $detail->jersey_name,
                $detail->jersey_number,
                $detail->size,
                $detail->custom_width ?? 'N/A',
                $detail->custom_height ?? 'N/A',
                ucfirst(str_replace('_', ' ', $detail->sleeve_type)),
                $detail->play_status ? 'Player' : 'Only Jersey',
                $detail->created_at->format('Y-m-d H:i:s'),
            ];

            // Add guest rows if any
            foreach ($detail->guests as $guest) {
                $rows[] = [
                    $detail->id,
                    'Guest',
                    $guest->name,
                    'N/A',
                    $guest->jersey_name,
                    $guest->jersey_number,
                    $guest->size,
                    $guest->custom_width ?? 'N/A',
                    $guest->custom_height ?? 'N/A',
                    ucfirst(str_replace('_', ' ', $guest->sleeve_type)),
                    'N/A',
                    $guest->created_at->format('Y-m-d H:i:s'),
                ];
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            ['Event: ' . $this->eventTitle], // Event title in first row
            [], // Empty row
            ['ID', 'Type', 'Name', 'Mobile', 'Jersey Name', 'Jersey Number', 'Size', 'Custom Width', 'Custom Height', 'Sleeve Type', 'Play Status', 'Created At']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells for event title
        $sheet->mergeCells('A1:L1');

        // Style event title row
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '7C3AED'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Style header row
        $sheet->getStyle('A3:L3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'A855F7'],
            ],
        ]);

        // Auto-size columns
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }

    public function title(): string
    {
        return 'Registrations';
    }
}
