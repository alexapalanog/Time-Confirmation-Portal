const { NFC } = require('nfc-pcsc');
const express = require('express');
const cors = require('cors');
const app = express();
const nfc = new NFC();

app.use(cors()); // Enable CORS for all routes

// Store the last read card data
let lastCardData = null;

nfc.on('reader', reader => {
    console.log(`Device attached: ${reader.reader.name}`);

    reader.on('card', async card => {
        console.log(`Card detected: UID=${card.uid}`);
        const cardData = [];

        try {
            for (let sector = 0; sector < 16; sector++) { // Adjust the sector range based on the card type
                for (let block = 0; block < 4; block++) { // 4 blocks per sector
                    const blockNumber = sector * 4 + block;

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
                            blockNumber, // Block to authenticate
                            0x60, // Key type A
                            0x00, // Key number
                        ]);

                        const authResponse = await reader.transmit(authenticateCommand, 64);
                        if (!authResponse.equals(Buffer.from([0x90, 0x00]))) {
                            throw new Error(`Authentication failed for block ${blockNumber}`);
                        }

                        // Read data from the block
                        const readCommand = Buffer.from([
                            0xff, // Class
                            0xb0, // INS: Read command
                            0x00, // P1: Block number
                            blockNumber, // Block to read
                            16,   // Number of bytes to read
                        ]);

                        const readResponse = await reader.transmit(readCommand, 64);
                        if (readResponse.length > 2 && readResponse.slice(-2).equals(Buffer.from([0x90, 0x00]))) {
                            const data = readResponse.slice(0, -2); // Remove status bytes
                            cardData.push({
                                blockNumber,
                                data: data.toString('utf-8').replace(/\0/g, ''), // Remove padding
                                rawData: data.toString('hex'), // Keep raw data as well
                            });
                        } else {
                            console.error(`Failed to read block ${blockNumber}. Response: ${readResponse.toString('hex')}`);
                        }
                    } catch (err) {
                        console.error(`Error reading block ${blockNumber}:`, err.message);
                    }
                }
            }

            lastCardData = {
                uid: card.uid,
                data: cardData,
                timestamp: new Date().toISOString(),
            };

            console.log(`Card data successfully read:`, JSON.stringify(lastCardData, null, 2));
        } catch (err) {
            console.error('Error reading card:', err);
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

// API endpoint to get the last read card data
app.get('/api/lastcard', (req, res) => {
    res.json(lastCardData || { uid: null, data: [] });

    lastCardData = null; // Reset the last card data
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
