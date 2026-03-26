<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Contratos',
            'Facturas',
            'Informes',
            'Actas',
            'Presupuestos',
            'Manuales',
            'Certificados',
            'Correspondencia',
            'Expedientes',
            'Resoluciones',
        ];

        foreach ($categories as $category) {
            DocumentCategory::firstOrCreate(['name' => $category]);
        }
    }
}
