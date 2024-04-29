<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('girl_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('youtube_handle', 100);
            $table->string('spotify_id', 100);
            $table->bigInteger('youtube_fans')->nullable();
            $table->timestamps();
        });

        $data = [
            [
                'name' => 'BLACKPINK',
                'youtube_handle' => 'BLACKPINK',
                'spotify_id' => '41MozSoPIsD1dJM0CLPjZF',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'IVE',
                'youtube_handle' => 'IVEstarship',
                'spotify_id' => '6RHTUrRF63xao58xh9FXYJ',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'AESPA',
                'youtube_handle' => 'aespa',
                'spotify_id' => '6YVMFz59CuY7ngCxTxjpxE',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ITZY',
                'youtube_handle' => 'ITZY',
                'spotify_id' => '2KC9Qb60EaY0kW4eH68vr3',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'NEW JEANS',
                'youtube_handle' => 'NewJeans_official',
                'spotify_id' => '6HvZYsbFfjnjFrWF950C9d',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'NMIXX',
                'youtube_handle' => 'NMIXXOfficial',
                'spotify_id' => '28ot3wh4oNmoFOdVajibBl',
                'created_at' => now(),
                'updated_at' => now()
            ],

        ];
        
        DB::table('girl_groups')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('girl_groups');
    }
};
