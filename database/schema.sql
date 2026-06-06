-- ============================================================
-- CHNMS — Full Production Schema
-- Run this on Hostinger hPanel → phpMyAdmin
-- ============================================================

-- -------- ROLES --------
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);
INSERT IGNORE INTO roles (name) VALUES ('super_admin'), ('hotel_admin'), ('guest');

-- -------- USERS (upgraded) --------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    hotel_id INT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    avatar VARCHAR(255),
    status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
    login_attempts INT DEFAULT 0,
    locked_until TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Default super admin: email=admin@chnms.com / password=password
INSERT IGNORE INTO users (role_id, name, email, password_hash) VALUES 
(1, 'Super Admin', 'admin@chnms.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- -------- HOTELS (upgraded) --------
CREATE TABLE IF NOT EXISTS hotels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_user_id INT NOT NULL,
    hotel_code VARCHAR(20) UNIQUE,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    owner_name VARCHAR(100),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    zip VARCHAR(20),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    star_rating TINYINT DEFAULT 3,
    gst_number VARCHAR(20),
    pan_number VARCHAR(20),
    bank_name VARCHAR(100),
    bank_account VARCHAR(30),
    bank_ifsc VARCHAR(20),
    commission_rate DECIMAL(5,2) DEFAULT 10.00,
    cover_image VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected', 'suspended') DEFAULT 'pending',
    suspended_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_user_id) REFERENCES users(id)
);

-- Auto-generate hotel code trigger
DELIMITER $$
CREATE TRIGGER IF NOT EXISTS before_hotel_insert
BEFORE INSERT ON hotels
FOR EACH ROW
BEGIN
    IF NEW.hotel_code IS NULL OR NEW.hotel_code = '' THEN
        SET NEW.hotel_code = CONCAT('HTL', LPAD(FLOOR(RAND() * 99999), 5, '0'));
    END IF;
END$$
DELIMITER ;

-- -------- ROOM TYPES --------
CREATE TABLE IF NOT EXISTS room_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    base_price DECIMAL(10,2) NOT NULL,
    capacity INT DEFAULT 2,
    bed_type ENUM('single','double','twin','queen','king','sofa') DEFAULT 'double',
    amenities JSON,
    description TEXT,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
);

-- -------- ROOMS (upgraded) --------
CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hotel_id INT NOT NULL,
    room_type_id INT NOT NULL,
    room_number VARCHAR(20) NOT NULL,
    floor_number INT DEFAULT 1,
    max_guests INT DEFAULT 2,
    price_override DECIMAL(10,2) NULL,
    amenities JSON,
    images JSON,
    status ENUM('available','occupied','reserved','cleaning','maintenance','blocked') DEFAULT 'available',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE,
    FOREIGN KEY (room_type_id) REFERENCES room_types(id),
    UNIQUE KEY unique_room (hotel_id, room_number)
);

-- -------- GUESTS --------
CREATE TABLE IF NOT EXISTS guests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(150),
    phone VARCHAR(20),
    id_proof_type ENUM('aadhaar','passport','pan','driving_license','voter_id'),
    id_proof_number VARCHAR(100),
    id_proof_image VARCHAR(255),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- -------- BOOKINGS (upgraded) --------
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_ref VARCHAR(20) NOT NULL UNIQUE,
    hotel_id INT NOT NULL,
    room_id INT NOT NULL,
    guest_id INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    num_guests INT DEFAULT 1,
    num_nights INT GENERATED ALWAYS AS (DATEDIFF(check_out_date, check_in_date)) STORED,
    room_rate DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    platform_fee DECIMAL(10,2) DEFAULT 0,
    paid_amount DECIMAL(10,2) DEFAULT 0,
    extra_charges DECIMAL(10,2) DEFAULT 0,
    damage_charges DECIMAL(10,2) DEFAULT 0,
    discount_amount DECIMAL(10,2) DEFAULT 0,
    payment_status ENUM('pending','partial','paid','refunded') DEFAULT 'pending',
    razorpay_order_id VARCHAR(100),
    razorpay_payment_id VARCHAR(100),
    status ENUM('confirmed','checked_in','checked_out','cancelled','transferred','no_show') DEFAULT 'confirmed',
    transferred_to INT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (guest_id) REFERENCES guests(id),
    FOREIGN KEY (transferred_to) REFERENCES hotels(id) ON DELETE SET NULL
);

-- Auto-generate booking ref
DELIMITER $$
CREATE TRIGGER IF NOT EXISTS before_booking_insert
BEFORE INSERT ON bookings
FOR EACH ROW
BEGIN
    IF NEW.booking_ref IS NULL OR NEW.booking_ref = '' THEN
        SET NEW.booking_ref = CONCAT('BKG', YEAR(NOW()), LPAD(FLOOR(RAND() * 999999), 6, '0'));
    END IF;
END$$
DELIMITER ;

-- -------- PAYMENTS --------
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

-- -------- BOOKING TRANSFERS --------
CREATE TABLE IF NOT EXISTS booking_transfers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    from_hotel_id INT NOT NULL,
    to_hotel_id INT NOT NULL,
    reason TEXT,
    requested_by INT,
    admin_notes TEXT,
    transfer_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending','accepted','rejected') DEFAULT 'pending',
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (from_hotel_id) REFERENCES hotels(id),
    FOREIGN KEY (to_hotel_id) REFERENCES hotels(id),
    FOREIGN KEY (requested_by) REFERENCES users(id) ON DELETE SET NULL
);

-- -------- NOTIFICATIONS --------
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

-- -------- AUDIT LOGS --------
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

-- -------- INDEXES FOR PERFORMANCE --------
CREATE INDEX IF NOT EXISTS idx_bookings_hotel ON bookings(hotel_id);
CREATE INDEX IF NOT EXISTS idx_bookings_dates ON bookings(check_in_date, check_out_date);
CREATE INDEX IF NOT EXISTS idx_rooms_hotel ON rooms(hotel_id);
CREATE INDEX IF NOT EXISTS idx_rooms_status ON rooms(status);
CREATE INDEX IF NOT EXISTS idx_notifications_user ON notifications(user_id, is_read);
CREATE INDEX IF NOT EXISTS idx_audit_user ON audit_logs(user_id);
CREATE INDEX IF NOT EXISTS idx_hotels_status ON hotels(status);
