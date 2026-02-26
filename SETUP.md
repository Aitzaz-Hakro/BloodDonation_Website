# Blood Donation Website - Setup Guide

## Prerequisites
- **XAMPP** (includes Apache, MySQL, PHP) - Download from https://www.apachefriends.org/
- **Node.js** (v18 or higher) - Download from https://nodejs.org/

---

## Quick Setup (5 Steps)

### Step 1: Install & Start XAMPP
1. Download and install XAMPP
2. Open **XAMPP Control Panel**
3. Start **Apache** and **MySQL** services

### Step 2: Create Database
1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click **"New"** on the left sidebar
3. Enter database name: `blood_donation`
4. Click **"Create"**
5. With `blood_donation` selected, click **"Import"** tab
6. Click **"Choose File"** and select: `db/schema.sql` from this project
7. Click **"Go"** to import the tables

### Step 3: Copy Files to XAMPP
Copy the entire project folder to XAMPP's htdocs:
```
From: D:\Github Repos\BloodDonation_Website
To:   C:\xampp\htdocs\BloodDonation_Website
```

Or run this PowerShell command:
```powershell
Copy-Item -Path "D:\Github Repos\BloodDonation_Website" -Destination "C:\xampp\htdocs\BloodDonation_Website" -Recurse -Force
```

### Step 4: Install Node.js Dependencies
```powershell
cd "D:\Github Repos\BloodDonation_Website"
npm install
```

### Step 5: Start Servers
```powershell
npm start
```

---

## Access Points

| Page | URL |
|------|-----|
| Homepage (Node.js) | http://localhost:3000 |
| Homepage (XAMPP) | http://localhost/BloodDonation_Website/public/index.html |
| Admin Panel | http://localhost/BloodDonation_Website/public/adminPanel.php |
| Donor Registration | http://localhost/BloodDonation_Website/public/registration.html |
| Blood Requests | http://localhost/BloodDonation_Website/public/bloodRequest.html |

---

## Database Tables

The `schema.sql` creates these tables:

| Table | Purpose |
|-------|---------|
| `donor_details` | Stores registered blood donors |
| `blood_requests` | Blood request records |
| `blood_inventory` | Blood stock by type |
| `admin_info` | Admin credentials (for future use) |

---

## Testing the Registration

1. Go to: `http://localhost/BloodDonation_Website/public/registration.html`
2. Fill in the donor registration form
3. Click "Register as Donor"
4. Check the Admin Panel to see the new donor

---

## Troubleshooting

### "Connection failed" error
- Make sure MySQL is running in XAMPP Control Panel
- Verify database `blood_donation` exists in phpMyAdmin

### PHP files not working
- Make sure Apache is running in XAMPP Control Panel
- Files must be in `C:\xampp\htdocs\` folder

### Form submission fails
- Check that all required fields are filled
- Age must be between 18-65

---

## File Structure

```
BloodDonation_Website/
├── app.js              # Node.js server
├── package.json        # Node dependencies
├── db/
│   ├── conn.js         # DB connection (Node)
│   └── schema.sql      # Database schema
├── public/
│   ├── index.html      # Homepage
│   ├── registration.html   # Donor registration
│   ├── bloodRequest.html   # Blood requests page
│   ├── adminPanel.php      # Admin dashboard
│   ├── submit_blood_req.php # Form handler
│   ├── style.css       # Main styles
│   └── media/          # Images & assets
└── routes/
    └── register.js     # API routes
```

---

## Default Credentials

**User Login** (signIn.html):
- Email: `aitzazhakro123@gmail.com`
- Password: `hello123`

**Admin** (for future authentication):
- Username: `admin`
- Password: `admin123`
