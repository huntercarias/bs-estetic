<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('platform'); // facebook | instagram | whatsapp | tiktok
            $table->string('account_name')->nullable();
            $table->string('page_id')->nullable();      // Facebook Page ID / Instagram Page ID
            $table->string('ig_user_id')->nullable();   // Instagram Business User ID
            $table->text('access_token')->nullable();   // Encrypted
            $table->boolean('active')->default(false);
            $table->timestamps();

            $table->unique(['tenant_id', 'platform']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};
