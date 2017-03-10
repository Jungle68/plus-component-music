<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

$component_table_name = 'music_comments';

if (!Schema::hasColumn($component_table_name, 'title')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->string('title')->comment('专辑标题');
    });
}

if (!Schema::hasColumn($component_table_name, 'content')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('storage')->comment('歌曲封面id');
    });
}

if (!Schema::hasColumn($component_table_name, 'taste_count')) {
    Schema::table($component_table_name, function (Blueprint $table) {
        $table->integer('taste_count')->default(0)->comment('播放数量');
    });
}