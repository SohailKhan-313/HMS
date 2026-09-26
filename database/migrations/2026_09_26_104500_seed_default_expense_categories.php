<?php

use App\Models\ExpenseCatagory;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $categories = [
            ['name' => 'Consultation', 'description' => 'Doctor consultations and OPD visits'],
            ['name' => 'Pharmacy / Medicine', 'description' => 'Medicines and pharmaceutical sales/supplies'],
            ['name' => 'General Procedures', 'description' => 'Clinical and minor surgical procedures'],
            ['name' => 'Diagnostics / Lab', 'description' => 'Laboratory investigations, pathology, and tests'],
            ['name' => 'Emergency', 'description' => 'Emergency room and urgent clinical care'],
            ['name' => 'Medical Supplies', 'description' => 'Disposable medical items, syringes, and clinical equipment'],
            ['name' => 'Utility Bills', 'description' => 'Electricity, water, gas, and internet utilities'],
            ['name' => 'Hospital Maintenance', 'description' => 'Facility repairs and biomedical equipment maintenance'],
            ['name' => 'Staff Welfare', 'description' => 'Staff refreshments and welfare expenses'],
            ['name' => 'Other', 'description' => 'Miscellaneous hospital billing and operational expenses'],
        ];

        foreach ($categories as $cat) {
            ExpenseCatagory::firstOrCreate(
                ['name' => $cat['name']],
                ['description' => $cat['description']]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
