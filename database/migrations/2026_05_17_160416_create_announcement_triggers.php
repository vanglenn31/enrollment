<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ══════════════════════════════════════════════════════════════════════
        // TRIGGER 1: After a Payment row is INSERTED
        // → Inserts a personal announcement for the student (via user_id chain:
        //   payments → student_enrollments → students → profiles → user_id)
        // ══════════════════════════════════════════════════════════════════════
        DB::unprepared("
            CREATE TRIGGER trg_after_payment_insert
            AFTER INSERT ON payments
            FOR EACH ROW
            BEGIN
                DECLARE v_user_id BIGINT UNSIGNED;
                DECLARE v_title   VARCHAR(255);
                DECLARE v_body    TEXT;

                -- Resolve user_id from the enrollment → student → profile chain
                SELECT p.user_id
                INTO   v_user_id
                FROM   student_enrollments se
                JOIN   students            st ON st.id = se.student_id
                JOIN   profiles            p  ON p.id  = st.profile_id
                WHERE  se.id = NEW.student_enrollment_id
                LIMIT  1;

                IF v_user_id IS NOT NULL THEN
                    SET v_title = CONCAT('💳 Payment Received — ₱', FORMAT(NEW.amount, 2));
                    SET v_body  = CONCAT(
                        'A ', NEW.payment_type, ' payment of ₱', FORMAT(NEW.amount, 2),
                        ' has been recorded in the system with status: ', NEW.payment_status, '. ',
                        'Reference: ', IFNULL(NEW.reference_number, 'N/A'), '.'
                    );

                    INSERT INTO announcements (title, body, type, user_id, is_read, created_at, updated_at)
                    VALUES (v_title, v_body, 'payment', v_user_id, 0, NOW(), NOW());
                END IF;
            END
        ");

        // ══════════════════════════════════════════════════════════════════════
        // TRIGGER 2: After a Payment row is UPDATED
        // → Fires when payment_status changes (e.g. pending → paid / partial /
        //   cancelled). Notifies the student of the new status.
        // ══════════════════════════════════════════════════════════════════════
        DB::unprepared("
            CREATE TRIGGER trg_after_payment_update
            AFTER UPDATE ON payments
            FOR EACH ROW
            BEGIN
                DECLARE v_user_id BIGINT UNSIGNED;
                DECLARE v_title   VARCHAR(255);
                DECLARE v_body    TEXT;

                -- Only fire when status actually changed
                IF OLD.payment_status <> NEW.payment_status THEN

                    SELECT p.user_id
                    INTO   v_user_id
                    FROM   student_enrollments se
                    JOIN   students            st ON st.id = se.student_id
                    JOIN   profiles            p  ON p.id  = st.profile_id
                    WHERE  se.id = NEW.student_enrollment_id
                    LIMIT  1;

                    IF v_user_id IS NOT NULL THEN

                        CASE NEW.payment_status
                            WHEN 'paid' THEN
                                SET v_title = '✅ Payment Fully Confirmed';
                                SET v_body  = CONCAT(
                                    'Your payment of ₱', FORMAT(NEW.amount, 2),
                                    ' has been approved. Your tuition is now fully paid. 🎉'
                                );
                            WHEN 'partial' THEN
                                SET v_title = '✅ Partial Payment Confirmed';
                                SET v_body  = CONCAT(
                                    'Your payment of ₱', FORMAT(NEW.amount, 2),
                                    ' has been confirmed. You still have a remaining balance. ',
                                    'Please submit another payment when ready.'
                                );
                            WHEN 'cancelled' THEN
                                SET v_title = '❌ Payment Cancelled';
                                SET v_body  = CONCAT(
                                    'Your payment request of ₱', FORMAT(NEW.amount, 2),
                                    ' was rejected and the record has been cancelled. ',
                                    'Please submit a new request with the correct details.'
                                );
                            ELSE
                                SET v_title = '🔔 Payment Status Updated';
                                SET v_body  = CONCAT(
                                    'Your payment of ₱', FORMAT(NEW.amount, 2),
                                    ' status has changed to: ', NEW.payment_status, '.'
                                );
                        END CASE;

                        INSERT INTO announcements (title, body, type, user_id, is_read, created_at, updated_at)
                        VALUES (v_title, v_body, 'payment', v_user_id, 0, NOW(), NOW());
                    END IF;
                END IF;
            END
        ");

        // ══════════════════════════════════════════════════════════════════════
        // TRIGGER 3: After a Term row is UPDATED
        // → Fires on status change (upcoming→active, active→ended)
        //   and on is_enrollment_open toggle.
        //   Broadcasts to ALL users (user_id = NULL).
        // ══════════════════════════════════════════════════════════════════════
        DB::unprepared("
            CREATE TRIGGER trg_after_term_update
            AFTER UPDATE ON terms
            FOR EACH ROW
            BEGIN
                DECLARE v_label VARCHAR(100);
                DECLARE v_title VARCHAR(255);
                DECLARE v_body  TEXT;
                DECLARE v_type  VARCHAR(50);

                SET v_label = CONCAT(
                    CASE NEW.semester
                        WHEN '1st'    THEN '1st Semester'
                        WHEN '2nd'    THEN '2nd Semester'
                        WHEN 'summer' THEN 'Summer Term'
                        ELSE NEW.semester
                    END,
                    ' ', NEW.school_year
                );

                -- ── Status changed ────────────────────────────────────────────
                IF OLD.status <> NEW.status THEN
                    SET v_type = 'term';

                    IF NEW.status = 'active' THEN
                        SET v_title = CONCAT('📢 New Term Now Active: ', v_label);
                        SET v_body  = CONCAT(
                            'The ', v_label, ' term has officially started. ',
                            'Please check the enrollment portal for available courses and schedules.'
                        );

                    ELSEIF NEW.status = 'ended' THEN
                        SET v_title = CONCAT('🏁 Term Ended: ', v_label);
                        SET v_body  = CONCAT(
                            'The ', v_label, ' term has officially concluded. ',
                            'All course enrollments have been dissolved. ',
                            'Please watch for announcements about the next enrollment period.'
                        );
                    END IF;

                    IF v_title IS NOT NULL THEN
                        -- user_id NULL = broadcast to everyone
                        INSERT INTO announcements (title, body, type, user_id, is_read, created_at, updated_at)
                        VALUES (v_title, v_body, v_type, NULL, 0, NOW(), NOW());
                    END IF;
                END IF;

                -- ── Enrollment open/close toggled ─────────────────────────────
                IF OLD.is_enrollment_open <> NEW.is_enrollment_open THEN
                    SET v_type = 'term';

                    IF NEW.is_enrollment_open = 1 THEN
                        SET v_title = CONCAT('✅ Enrollment is Now Open — ', v_label);
                        SET v_body  = CONCAT(
                            'Enrollment for ', v_label, ' is now open! ',
                            'Log in to the portal and secure your courses before slots fill up.'
                        );
                    ELSE
                        SET v_title = CONCAT('🔒 Enrollment Closed — ', v_label);
                        SET v_body  = CONCAT(
                            'Enrollment for ', v_label, ' has been closed by the administration. ',
                            'Contact the registrar\'s office if you have concerns.'
                        );
                    END IF;

                    INSERT INTO announcements (title, body, type, user_id, is_read, created_at, updated_at)
                    VALUES (v_title, v_body, v_type, NULL, 0, NOW(), NOW());
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_after_payment_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_after_payment_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_after_term_update');
    }
};