<?php

namespace App\Exports;

use App\Models\TambahAduan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AduanExport implements FromCollection, WithHeadings
{
    protected $filterDate;

    /**
     * Constructor untuk menerima filter tanggal (opsional).
     */
    public function __construct($filterDate = null)
    {
        $this->filterDate = $filterDate;
    }

    /**
     * Mengambil data untuk diekspor.
     */
    public function collection()
    {
        $query = TambahAduan::query();

        // Filter berdasarkan tanggal jika diberikan
        if ($this->filterDate) {
            $query->whereDate('created_at', $this->filterDate);
        }

        // Pilih kolom yang diperlukan
        return $query->select([
            'id', 'description', 'province', 'regency', 'district', 
            'village', 'type', 'voting', 'status', 'created_at'
        ])->get();
    }

    /**
     * Header untuk file Excel.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Deskripsi',
            'Provinsi',
            'Kabupaten',
            'Kecamatan',
            'Desa',
            'Tipe',
            'Vote',
            'Status',
            'Tanggal Pengaduan',
        ];
    }
}
