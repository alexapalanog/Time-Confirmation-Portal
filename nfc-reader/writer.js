const { NFC } = require('nfc-pcsc');
const nfc = new NFC();

const EMPLOYEE_ID = '098765'; // Replace with dynamic input if needed
const BLOCK = 4; // Block to write to (MIFARE Classic 1K has 16 bytes per block)

// Convert employeeID to a 16-byte buffer (padded or trimmed)
const dataToWrite = Buffer.alloc(16, 0); // Initialize a 16-byte buffer with zeroes
dataToWrite.write(EMPLOYEE_ID, 0, 'utf-8'); // Write employeeID to the buffer

nfc.on('reader', reader => {
    console.log(`Reader connected: ${reader.reader.name}`);

    reader.on('card', async card => {
        console.log(`Card detected: UID=${card.uid}`);

        try {
    // Authenticate the block (MIFARE Classic cards require authentication)
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

    // Write data to the block
    const writeCommand = Buffer.from([
        0xff, // Class
        0xd6, // INS: Write command
        0x00, // P1: Block number
        BLOCK, // Block to write
        16,   // Length of data
        ...dataToWrite, // Data to write
    ]);

    const writeResponse = await reader.transmit(writeCommand, 64);
    if (writeResponse.equals(Buffer.from([0x90, 0x00]))) {
        console.log(`Data written successfully: ${EMPLOYEE_ID}`);
    } else {
        console.error(`Failed to write data. Response: ${writeResponse.toString('hex')}`);
    }
} catch (err) {
    console.error('Error:', err);
}

    });

    reader.on('card.off', () => {
        console.log('Card removed.');
    });

    reader.on('error', err => {
        console.error(`Error: ${err}`);
    });

    reader.on('end', () => {
        console.log(`Reader disconnected: ${reader.reader.name}`);
    });
});

nfc.on('error', err => {
    console.error(`NFC error: ${err}`);
});
