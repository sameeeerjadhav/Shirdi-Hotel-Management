-- ============================================================
-- CHNMS — SAFE MIGRATION (Run this on existing database)
-- Run via Hostinger hPanel → phpMyAdmin → SQL tab
-- This uses ALTER TABLE safely (won't fail if columns exist)
-- ============================================================

-- -------- Upgrade hotels table --------
ALTER TABLE hotels
    ADD COLUMN IF NOT EXISTS hotel_code VARCHAR(20) UNIQUE,
    ADD COLUMN IF NOT EXISTS description TEXT,
    ADD COLUMN IF NOT EXISTS owner_name VARCHAR(100),
    ADD COLUMN IF NOT EXISTS star_rating TINYINT DEFAULT 3,
    ADD COLUMN IF NOT EXISTS gst_number VARCHAR(20),
    ADD COLUMN IF NOT EXISTS pan_number VARCHAR(20),
    ADD COLUMN IF NOT EXISTS bank_name VARCHAR(100),
    ADD COLUMN IF NOT EXISTS bank_account VARCHAR(30),
    ADD COLUMN IF NOT EXISTS bank_ifsc VARCHAR(20),
    ADD COLUMN IF NOT EXISTS cover_image VARCHAR(255),
    ADD COLUMN IF NOT EXISTS latitude DECIMAL(10,8),
    ADD COLUMN IF NOT EXISTS longitude DECIMAL(11,8),
    ADD COLUMN IF NOT EXISTS suspended_at TIMESTAMP NULL,
    ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    MODIFY COLUMN status ENUM('pending','approved','rejected','suspended') DEFAULT 'pending';

-- -------- Upgrade users table --------
ALTER TABLE users
    ADD COLUMN IF NOT EXISTS hotel_id INT NULL,
    ADD COLUMN IF NOT EXISTS avatar VARCHAR(255),
    ADD COLUMN IF NOT EXISTS login_attempts INT DEFAULT 0,
    ADD COLUMN IF NOT EXISTS locked_until TIMESTAMP NULL;

-- -------- Upgrade rooms table --------
ALTER TABLE rooms
    ADD COLUMN IF NOT EXISTS floor_number INT DEFAULT 1,
    ADD COLUMN IF NOT EXISTS max_guests INT DEFAULT 2,
    ADD COLUMN IF NOT EXISTS price_override DECIMAL(10,2) NULL,
    ADD COLUMN IF NOT EXISTS amenities JSON,
    ADD COLUMN IF NOT EXISTS images JSON,
    ADD COLUMN IF NOT EXISTS notes TEXT,
    ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    MODIFY COLUMN status ENUM('available','occupied','reserved','cleaning','maintenance','blocked') DEFAULT 'available';

-- -------- Upgrade room_types table --------
ALTER TABLE room_types
    ADD COLUMN IF NOT EXISTS bed_type ENUM('single','double','twin','queen','king','sofa') DEFAULT 'double',
    ADD COLUMN IF NOT EXISTS amenities JSON,
    ADD COLUMN IF NOT EXISTS description TEXT;

-- -------- Upgrade bookings table --------
ALTER TABLE bookings
    ADD COLUMN IF NOT EXISTS booking_ref VARCHAR(20) UNIQUE,
    ADD COLUMN IF NOT EXISTS num_guests INT DEFAULT 1,
    ADD COLUMN IF NOT EXISTS room_rate DECIMAL(10,2) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS platform_fee DECIMAL(10,2) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS paid_amount DECIMAL(10,2) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS extra_charges DECIMAL(10,2) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS damage_charges DECIMAL(10,2) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS discount_amount DECIMAL(10,2) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS payment_status ENUM('pending','partial','paid','refunded') DEFAULT 'pending',
    ADD COLUMN IF NOT EXISTS razorpay_order_id VARCHAR(100),
    ADD COLUMN IF NOT EXISTS razorpay_payment_id VARCHAR(100),
    ADD COLUMN IF NOT EXISTS transferred_to INT NULL,
    ADD COLUMN IF NOT EXISTS notes TEXT,
    ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    MODIFY COLUMN status ENUM('confirmed','checked_in','checked_out','cancelled','transferred','no_show') DEFAULT 'confirmed';

-- -------- NEW: payments table --------
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('razorpay','cash','card','upi','bank_transfer') DEFAULT 'razorpay',
    razorpay_payment_id VARCHAR(100),
    razorpay_order_id VARCHAR(100),
    status ENUM('pending','captured','failed','refunded') DEFAULT 'pending',
    notes TEXT,
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

-- -------- NEW: notifications table --------
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT,
    link VARCHAR(255),
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- -------- NEW: audit_logs table --------
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    model VARCHAR(50),
    model_id INT NULL,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- -------- Upgrade booking_transfers table --------
ALTER TABLE booking_transfers
    ADD COLUMN IF NOT EXISTS requested_by INT,
    ADD COLUMN IF NOT EXISTS admin_notes TEXT;

-- -------- Upgrade guests table --------
ALTER TABLE guests
    ADD COLUMN IF NOT EXISTS id_proof_image VARCHAR(255),
    ADD COLUMN IF NOT EXISTS address TEXT,
    ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    MODIFY COLUMN id_proof_type ENUM('aadhaar','passport','pan','driving_license','voter_id');

-- -------- Performance indexes --------
CREATE INDEX IF NOT EXISTS idx_bookings_hotel ON bookings(hotel_id);
CREATE INDEX IF NOT EXISTS idx_bookings_dates ON bookings(check_in_date, check_out_date);
CREATE INDEX IF NOT EXISTS idx_rooms_hotel ON rooms(hotel_id);
CREATE INDEX IF NOT EXISTS idx_rooms_status ON rooms(status);
CREATE INDEX IF NOT EXISTS idx_notifications_user ON notifications(user_id, is_read);
CREATE INDEX IF NOT EXISTS idx_hotels_status ON hotels(status);

-- -------- Generate booking refs for existing records --------
UPDATE bookings SET booking_ref = CONCAT('BKG2026', LPAD(id, 6, '0')) WHERE booking_ref IS NULL OR booking_ref = '';
