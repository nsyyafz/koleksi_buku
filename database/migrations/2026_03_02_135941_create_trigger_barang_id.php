<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Buat function untuk generate ID
        DB::unprepared("
            CREATE OR REPLACE FUNCTION generate_id_barang()
            RETURNS TRIGGER AS $$
            DECLARE
                next_id INTEGER;
                new_id VARCHAR(20);
            BEGIN
                -- Ambil ID terakhir
                SELECT COALESCE(
                    MAX(CAST(SUBSTRING(id_barang FROM 4) AS INTEGER)), 
                    0
                ) + 1 INTO next_id
                FROM barang
                WHERE id_barang LIKE 'BRG%';
                
                -- Format: BRG0001, BRG0002, dst
                new_id := 'BRG' || LPAD(next_id::TEXT, 4, '0');
                
                -- Set ID barang
                NEW.id_barang := new_id;
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Buat trigger
        DB::unprepared("
            CREATE TRIGGER trigger_id_barang
            BEFORE INSERT ON barang
            FOR EACH ROW
            WHEN (NEW.id_barang IS NULL OR NEW.id_barang = '')
            EXECUTE FUNCTION generate_id_barang();
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trigger_id_barang ON barang");
        DB::unprepared("DROP FUNCTION IF EXISTS generate_id_barang()");
    }
};