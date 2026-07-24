<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->string('sender_role')->default('user')->after('user_email');
            $table->foreignId('recipient_id')->nullable()->constrained('users')->nullOnDelete()->after('sender_role');
            $table->string('recipient_email')->nullable()->after('recipient_id');
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign(['recipient_id']);
            $table->dropColumn(['sender_role', 'recipient_id', 'recipient_email']);
        });
    }
};
