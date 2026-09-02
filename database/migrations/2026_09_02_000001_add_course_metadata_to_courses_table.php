<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [];
        if (! Schema::hasColumn('courses', 'summary')) $columns['summary'] = fn (Blueprint $t) => $t->string('summary')->nullable();
        if (! Schema::hasColumn('courses', 'category')) $columns['category'] = fn (Blueprint $t) => $t->string('category')->nullable();
        if (! Schema::hasColumn('courses', 'level')) $columns['level'] = fn (Blueprint $t) => $t->string('level')->nullable();
        if ($columns) {
            Schema::table('courses', function (Blueprint $table) use ($columns) {
                foreach ($columns as $add) $add($table);
            });
        }
    }

    public function down(): void
    {
        $drop = collect(['summary', 'category', 'level'])->filter(fn ($c) => Schema::hasColumn('courses', $c))->values()->all();
        if ($drop) Schema::table('courses', fn (Blueprint $table) => $table->dropColumn($drop));
    }
};
