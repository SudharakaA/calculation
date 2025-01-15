const express = require('express');
const fs = require('fs');
const path = require('path');

const app = express();
const port = 3000;

app.get('/logs/app', (req, res) => {
    const logFilePath = path.join(__dirname, 'app.log');
    fs.readFile(logFilePath, 'utf8', (err, data) => {
        if (err) {
            res.status(500).send('Error reading log file');
            return;
        }
        res.send(`<pre>${data}</pre>`);
    });
});

app.get('/logs/folder/app', (req, res) => {
    const logFilePath = path.join(__dirname, 'logs', 'app.log');
    fs.readFile(logFilePath, 'utf8', (err, data) => {
        if (err) {
            res.status(500).send('Error reading log file');
            return;
        }
        res.send(`<pre>${data}</pre>`);
    });
});

app.listen(port, () => {
    console.log(`Server is running at http://localhost:${port}`);
});
