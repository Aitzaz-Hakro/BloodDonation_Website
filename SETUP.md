# Blood Donation Website - Setup Guide

## Prerequisites
- **XAMPP** (includes Apache, MySQL, PHP) - Download from https://www.apachefriends.org/

---

## Quick Setup (3 Steps)

### Step 1: Install & Start XAMPP
1. Download and install XAMPP
2. Open **XAMPP Control Panel**
3. Start **Apache** and **MySQL** services

### Step 2: Clone/Copy Project to XAMPP htdocs
Copy the project folder to XAMPP's htdocs directory:
```
C:\xampp\htdocs\BloodDonation_Website
```

### Step 3: Setup Database
**Option A: Automatic Setup (Recommended)**
1. Open browser and go to: `http://localhost/BloodDonation_Website/public/setup_database.php`
2. The script will automatically create the database and all tables
3. Click "Go to Homepage" when done

**Option B: Manual Setup via phpMyAdmin**
1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click **"New"** on the left sidebar
3. Enter database name: `blood_donation`
4. Click **"Create"**
5. With `blood_donation` selected, click **"Import"** tab
6. Click **"Choose File"** and select: `db/schema.sql` from this project
7. Click **"Go"** to import the tables

---

## Access the Website

Main website: `http://localhost/BloodDonation_Website/public/index.php`

### All Pages:
| Page | URL |
|------|-----|
| **Homepage** | `http://localhost/BloodDonation_Website/public/index.php` |
| **Sign In / Sign Up** | `http://localhost/BloodDonation_Website/public/signIn.php` |
| **Donor Registration** | `http://localhost/BloodDonation_Website/public/registration.php` |
| **Blood Requests** | `http://localhost/BloodDonation_Website/public/bloodRequest.php` |
| **Request Blood** | `http://localhost/BloodDonation_Website/public/request_blood.php` |
| **Admin Panel** | `http://localhost/BloodDonation_Website/public/adminPanel.php` |

---

## Features

### User Features
- **Sign Up/Sign In**: Create account and login (stored in MySQL database)
- **Donor Registration**: Register as a blood donor
- **Request Blood**: Submit a blood request for a patient
- **View Blood Requests**: See all blood request records

### Admin Features
- View all registered donors (click to see details)
- View all blood requests (click to manage status)
- Update request status (Pending, Approved, Rejected, Completed)
- Manage blood inventory (add/remove units)
- View registered users
- Delete donors or requests
- **Blood expiry**: Blood units expire 3 days after being added

---

## Database Configuration

The database connection is configured in `public/config.php`:

```php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "blood_donation";
```

Modify these values if your MySQL setup is different.

---

## Project Structure

```
BloodDonation_Website/
├── public/                    # All PHP pages
│   ├── admin/                 # Admin management pages
│   │   ├── view_donor.php     # View donor details
│   │   ├── view_request.php   # View/update request status
│   │   └── inventory.php      # Manage blood inventory
│   ├── media/                 # Images and assets
│   ├── config.php             # Database configuration
│   ├── header.php             # Common header (menu)
│   ├── index.php              # Homepage
│   ├── signIn.php             # Sign In / Sign Up
│   ├── logout.php             # Logout handler
│   ├── registration.php       # Donor registration
│   ├── bloodRequest.php       # View blood requests
│   ├── request_blood.php      # Submit blood request
│   ├── adminPanel.php         # Admin dashboard
│   ├── setup_database.php     # Database setup script
│   └── style.css              # Main stylesheet
├── db/
│   └── schema.sql             # Database schema
└── SETUP.md                   # This file
```

---

## Database Tables

| Table | Purpose |
|-------|---------|
| `users` | User accounts (sign in/sign up) |
| `admin_info` | Admin credentials |
| `donor_details` | Donor registration data |
| `donors` | Basic donor info |
| `blood_requests` | Blood request records |
| `blood_inventory` | Blood stock levels |

---

## Default Admin Credentials

- **Username**: admin
- **Password**: admin123

---

## Troubleshooting

### "Connection failed" error
- Make sure MySQL is running in XAMPP
- Check that database `blood_donation` exists
- Run `setup_database.php` to create tables

### Pages not loading / 404 error
- Make sure Apache is running in XAMPP
- Check the URL path is correct
- Ensure files are in `C:\xampp\htdocs\BloodDonation_Website\public\`

### Sign In not working
- Run `setup_database.php` first to create the users table
- Make sure you've signed up before trying to sign in
