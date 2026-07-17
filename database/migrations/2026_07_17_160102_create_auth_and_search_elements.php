<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Drop the table first if it was partially left over to avoid ORA errors
        try {
            DB::statement("DROP TABLE app_users CASCADE CONSTRAINTS");
        } catch (\Exception $e) {}

        // 1. Create App Users Table with highly unique check constraint name
        DB::statement("
            CREATE TABLE app_users (
                user_id       NUMBER PRIMARY KEY,
                username      VARCHAR2(50) NOT NULL UNIQUE,
                email         VARCHAR2(100) NOT NULL UNIQUE,
                password_hash VARCHAR2(255) NOT NULL,
                role          VARCHAR2(20) DEFAULT 'User' NOT NULL,
                created_at    DATE DEFAULT SYSDATE NOT NULL,
                CONSTRAINT chk_usr_role_final CHECK (role IN ('Admin', 'User'))
            )
        ");

        // 2. Create Sequence safely
        try { 
            DB::statement("DROP SEQUENCE seq_user_id"); 
        } catch (\Exception $e) {}
        
        DB::statement("CREATE SEQUENCE seq_user_id START WITH 1 INCREMENT BY 1 NOCACHE");

        // 3. Create Trigger safely
        DB::statement("
            CREATE OR REPLACE TRIGGER trg_bi_app_users
            BEFORE INSERT ON app_users FOR EACH ROW
            BEGIN
                IF :NEW.user_id IS NULL THEN
                    SELECT seq_user_id.NEXTVAL INTO :NEW.user_id FROM dual;
                END IF;
            END;
        ");
    }

    public function down()
    {
        DB::statement("DROP TRIGGER trg_bi_app_users");
        DB::statement("DROP SEQUENCE seq_user_id");
        DB::statement("DROP TABLE app_users CASCADE CONSTRAINTS");
    }
};