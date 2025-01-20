const { NFC } = require('nfc-pcsc');
const express = require('express');
const cors = require('cors');
const app = express();
const nfc = new NFC();

app.use(cors()); // Enable CORS for all routes

// Store the last read card data
let lastCard = null;

// Block to read from (same as the block used for writing in writer.js)
const BLOCK = 4;

nfc.on('reader', reader => {
    console.log(`Device attached: ${reader.reader.name}`);

    reader.on('card', async card => {
        console.log(`Card detected: UID=${card.uid}`);

        try {
            // Authenticate the block
            const key = Buffer.from('FFFFFFFFFFFF', 'hex'); // Default key
            const authenticateCommand = Buffer.from([
                0xff, // Class
                0x86, // INS
                0x00, // P1
                0x00, // P2
                0x05, // Lc
                0x01, // Version
                0x00, // Block number MSB
                BLOCK, // Block to authenticate
                0x60, // Key type A
                0x00, // Key number
            ]);

            const authResponse = await reader.transmit(authenticateCommand, 64);
            if (!authResponse.equals(Buffer.from([0x90, 0x00]))) {
                throw new Error('Authentication failed');
            }

            console.log('Authentication successful.');

            // Read data from the block
            const readCommand = Buffer.from([
                0xff, // Class
                0xb0, // INS: Read command
                0x00, // P1: Block number
                BLOCK, // Block to read
                16,   // Number of bytes to read
            ]);

            const readResponse = await reader.transmit(readCommand, 64);

            if (readResponse.length > 2 && readResponse.slice(-2).equals(Buffer.from([0x90, 0x00]))) {
                const data = readResponse.slice(0, -2); // Remove status bytes
                const employeeID = data.toString('utf-8').replace(/\0/g, ''); // Remove padding
                console.log(`Data read successfully: EMPLOYEE_ID=${employeeID}`);

                lastCard = {
                    uid: card.uid,
                    employeeID,
                    timestamp: new Date().toISOString(),
                };
            } else {
                console.error(`Failed to read data. Response: ${readResponse.toString('hex')}`);
            }
        } catch (err) {
            console.error('Error:', err);
        }
    });

    reader.on('card.off', () => {
        console.log(`Card removed`);
    });

    reader.on('error', err => {
        console.log(`Reader error:`, err);
    });

    reader.on('end', () => {
        console.log(`Reader disconnected`);
    });
});

// API endpoint to get the last read card
app.get('/api/lastcard', (req, res) => {
    res.json(lastCard || { uid: null, employeeID: null });

    lastCard = null; // Reset the last card
});

// Root route
app.get('/', (req, res) => {
    res.send('Welcome! This is the NFC API server.');
});

// Start the server
const PORT = 4000;
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});
