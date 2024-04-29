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
        Schema::create('avatars', function (Blueprint $table) {
            $table->id();
            $table->longText('image_url');
            $table->timestamps(false);
        });

        $data = [
            [
                'image_url' => 'https://static.vecteezy.com/system/resources/previews/001/993/889/large_2x/beautiful-latin-woman-avatar-character-icon-free-vector.jpg',
            ],
            [
                'image_url' => 'https://static.vecteezy.com/system/resources/previews/002/002/332/large_2x/ablack-man-avatar-character-isolated-icon-free-vector.jpg',
            ],
            [
                'image_url' => 'https://static.vecteezy.com/system/resources/previews/002/002/253/large_2x/beautiful-woman-wearing-sunglasses-avatar-character-icon-free-vector.jpg',
            ],
            [
                'image_url' => 'https://static.vecteezy.com/system/resources/previews/002/002/427/large_2x/man-avatar-character-isolated-icon-free-vector.jpg',
            ],
            [
                'image_url' => 'https://static.vecteezy.com/system/resources/previews/002/002/300/large_2x/beautiful-woman-avatar-character-icon-free-vector.jpg',
            ],
            [
                'image_url' => 'https://static.vecteezy.com/system/resources/previews/002/002/341/large_2x/man-wearing-sunglasses-avatar-character-isolated-icon-free-vector.jpg',
            ],

        ];
        
        DB::table('avatars')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avatars');
    }
};
