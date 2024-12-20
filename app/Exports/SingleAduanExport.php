<?php

namespace App\Exports;

use App\Models\TambahAduan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SingleAduanExport implements FromCollection, WithHeadings
{
    protected $aduanId;

    /**
     * Constructor untuk menerima ID data yang akan diekspor.
     */
    public function __construct($aduanId)
    {
        $this->aduanId = $aduanId;
    }

    /**
     * Mengambil data untuk diekspor.
     */
    public function collection()
    {
        return TambahAduan::where('id', $this->aduanId)
            ->select([
                'id', 'description', 'province', 'regency', 'district', 
                'village', 'type', 'voting', 'status', 'created_at'
            ])
            ->get();
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
