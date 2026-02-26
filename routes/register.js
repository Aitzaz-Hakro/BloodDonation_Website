const express = require('express');
const router = express.Router();

// POST /api/register - Register a new donor
router.post('/', (req, res) => {
    const db = req.app.locals.db;
    const { donor_name, donor_number, donor_mail, donor_age, donor_gender, donor_blood, donor_address } = req.body;

    // Validate required fields
    if (!donor_name || !donor_blood) {
        return res.status(400).json({ 
            success: false, 
            message: 'Name and blood type are required' 
        });
    }

    const sql = `INSERT INTO donor_details 
                 (donor_name, donor_number, donor_mail, donor_age, donor_gender, donor_blood, donor_address) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)`;

    const values = [donor_name, donor_number, donor_mail, donor_age, donor_gender, donor_blood, donor_address];

    db.query(sql, values, (err, result) => {
        if (err) {
            console.error('Error inserting donor:', err);
            return res.status(500).json({ 
                success: false, 
                message: 'Database error occurred' 
            });
        }

        res.status(201).json({ 
            success: true, 
            message: 'Donor registered successfully',
            donorId: result.insertId 
        });
    });
});

// GET /api/register - Get all donors
router.get('/', (req, res) => {
    const db = req.app.locals.db;

    db.query('SELECT * FROM donor_details', (err, results) => {
        if (err) {
            console.error('Error fetching donors:', err);
            return res.status(500).json({ 
                success: false, 
                message: 'Database error occurred' 
            });
        }

        res.json({ 
            success: true, 
            donors: results 
        });
    });
});

module.exports = router;
