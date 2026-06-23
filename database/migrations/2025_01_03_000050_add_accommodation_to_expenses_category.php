<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * SQLite does not support ALTER COLUMN, so we rebuild the expenses table
 * to add 'accommodation' to the category CHECK constraint.
 *
 * In MySQL / PostgreSQL production:
 *   ALTER TABLE expenses MODIFY category ENUM(
 *     'transport','permits','equipment','refreshments',
 *     'marketing','accommodation','other'
 *   );
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            // MySQL / PostgreSQL — modify the enum directly
            DB::statement("ALTER TABLE expenses MODIFY COLUMN category ENUM(
                'transport','permits','equipment','refreshments','marketing','accommodation','other'
            ) NOT NULL DEFAULT 'other'");
            return;
        }

        // SQLite: rebuild the table with updated CHECK constraint
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement("CREATE TABLE expenses_new (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            hike_id INTEGER REFERENCES hikes(id) ON DELETE SET NULL,
            description VARCHAR NOT NULL,
            amount NUMERIC(10,2) NOT NULL,
            category VARCHAR CHECK(category IN (
                'transport','permits','equipment','refreshments',
                'marketing','accommodation','other'
            )) NOT NULL DEFAULT 'other',
            expense_date DATE NOT NULL,
            receipt_path VARCHAR,
            recorded_by INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            created_at DATETIME,
            updated_at DATETIME
        )");

        DB::statement('INSERT INTO expenses_new SELECT * FROM expenses');
        DB::statement('DROP TABLE expenses');
        DB::statement('ALTER TABLE expenses_new RENAME TO expenses');

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE expenses MODIFY COLUMN category ENUM(
                'transport','permits','equipment','refreshments','marketing','other'
            ) NOT NULL DEFAULT 'other'");
            return;
        }

        // Revert: remove 'accommodation' from CHECK (rows with that value will fail — drop them first)
        DB::statement('PRAGMA foreign_keys = OFF');
        DB::statement("DELETE FROM expenses WHERE category = 'accommodation'");
        DB::statement("CREATE TABLE expenses_new (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            hike_id INTEGER REFERENCES hikes(id) ON DELETE SET NULL,
            description VARCHAR NOT NULL,
            amount NUMERIC(10,2) NOT NULL,
            category VARCHAR CHECK(category IN (
                'transport','permits','equipment','refreshments','marketing','other'
            )) NOT NULL DEFAULT 'other',
            expense_date DATE NOT NULL,
            receipt_path VARCHAR,
            recorded_by INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            created_at DATETIME,
            updated_at DATETIME
        )");
        DB::statement('INSERT INTO expenses_new SELECT * FROM expenses');
        DB::statement('DROP TABLE expenses');
        DB::statement('ALTER TABLE expenses_new RENAME TO expenses');
        DB::statement('PRAGMA foreign_keys = ON');
    }
};
