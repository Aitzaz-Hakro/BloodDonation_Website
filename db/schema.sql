-- Blood Donation Website Database Schema
-- Run this script in phpMyAdmin or MySQL CLI to set up the database

-- Create database
CREATE DATABASE IF NOT EXISTS blood_donation;
USE blood_donation;

-- Admin info table (for admin panel authentication)
CREATE TABLE IF NOT EXISTS admin_info (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    admin_username VARCHAR(100) NOT NULL UNIQUE,
    admin_password VARCHAR(255) NOT NULL,
    admin_email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (username: admin, password: admin123)
INSERT INTO admin_info (admin_username, admin_password, admin_email) 
VALUES ('admin', 'admin123', 'admin@bloodbank.com')
ON DUPLICATE KEY UPDATE admin_username = admin_username;

-- Donors table (basic info)
CREATE TABLE IF NOT EXISTS donors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    blood_type VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Donor details table (extended donor info)
CREATE TABLE IF NOT EXISTS donor_details (
    donor_id INT AUTO_INCREMENT PRIMARY KEY,
    donor_name VARCHAR(255) NOT NULL,
    donor_number VARCHAR(50),
    donor_mail VARCHAR(255),
    donor_age INT,
    donor_gender VARCHAR(20),
    donor_blood VARCHAR(10) NOT NULL,
    donor_address TEXT,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blood requests table
CREATE TABLE IF NOT EXISTS blood_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    recipient_name VARCHAR(255) NOT NULL,
    recipient_blood_group VARCHAR(10) NOT NULL,
    units_required INT NOT NULL DEFAULT 1,
    patient_condition VARCHAR(255),
    hospital_name VARCHAR(255),
    contact_number VARCHAR(50),
    request_status ENUM('Pending', 'Approved', 'Rejected', 'Completed') DEFAULT 'Pending',
    request_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blood inventory table
CREATE TABLE IF NOT EXISTS blood_inventory (
    inventory_id INT AUTO_INCREMENT PRIMARY KEY,
    blood_group VARCHAR(10) NOT NULL,
    units_available INT NOT NULL DEFAULT 0,
    minimum_threshold INT DEFAULT 5,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    expiry_date DATE
);

-- Insert initial blood inventory (all blood types)
INSERT INTO blood_inventory (blood_group, units_available, minimum_threshold) VALUES
('A+', 10, 5),
('A-', 5, 3),
('B+', 8, 5),
('B-', 4, 3),
('AB+', 6, 3),
('AB-', 3, 2),
('O+', 15, 8),
('O-', 7, 5)
ON DUPLICATE KEY UPDATE blood_group = blood_group;

-- Sample data for testing (optional - can be removed in production)
INSERT INTO donor_details (donor_name, donor_number, donor_mail, donor_age, donor_gender, donor_blood, donor_address) VALUES
('John Doe', '0312-1234567', 'john@example.com', 28, 'Male', 'O+', 'Karachi, Pakistan'),
('Jane Smith', '0321-7654321', 'jane@example.com', 25, 'Female', 'A+', 'Lahore, Pakistan')
ON DUPLICATE KEY UPDATE donor_name = donor_name;

INSERT INTO blood_requests (recipient_name, recipient_blood_group, units_required, patient_condition, hospital_name, contact_number, request_status, request_date) VALUES
('Ali Shah', 'O+', 2, 'Surgery', 'City Hospital', '0312-4567890', 'Pending', CURDATE()),
('Sara Khan', 'B-', 1, 'Accident', 'Red Cross Hospital', '0301-1234567', 'Approved', CURDATE())
ON DUPLICATE KEY UPDATE recipient_name = recipient_name;

-- Show tables created
SHOW TABLES;
