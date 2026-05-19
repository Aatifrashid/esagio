require('dotenv').config();

const express = require('express');
const cors = require('cors');
const crypto = require('crypto');
const { SessionManager } = require('./sessions');

const app = express();
app.use(cors());
app.use(express.json());

const API_KEY = process.env.API_KEY;
const PORT = process.env.PORT || 3001;
const sessionManager = new SessionManager();

// Auth middleware
function authenticate(req, res, next) {
    const apiKey = req.headers['x-api-key'];
    if (!apiKey || apiKey !== API_KEY) {
        return res.status(401).json({ error: 'Unauthorized' });
    }
    next();
}

app.use(authenticate);

// Create a new session
app.post('/sessions', async (req, res) => {
    try {
        const { session_id, webhook_url, status_webhook_url, session_webhook_url } = req.body;

        if (!session_id) {
            return res.status(400).json({ error: 'session_id required' });
        }

        await sessionManager.createSession(session_id, {
            webhookUrl: webhook_url,
            statusWebhookUrl: status_webhook_url,
            sessionWebhookUrl: session_webhook_url,
        });

        res.json({ status: 'ok', session_id });
    } catch (err) {
        console.error('Create session error:', err.message);
        res.status(500).json({ error: err.message });
    }
});

// Get QR code
app.get('/sessions/:id/qr', (req, res) => {
    const session = sessionManager.getSession(req.params.id);
    if (!session) {
        return res.status(404).json({ error: 'Session not found' });
    }
    res.json({ qr_code: session.qrCode || null, status: session.status });
});

// Send a message
app.post('/sessions/:id/send', async (req, res) => {
    try {
        const session = sessionManager.getSession(req.params.id);
        if (!session) {
            return res.status(404).json({ error: 'Session not found' });
        }
        if (session.status !== 'connected') {
            return res.status(400).json({ error: 'Session not connected' });
        }

        const { to, body, media_url } = req.body;
        const result = await sessionManager.sendMessage(req.params.id, to, body, media_url);

        res.json(result);
    } catch (err) {
        console.error('Send message error:', err.message);
        res.status(500).json({ error: err.message });
    }
});

// Get session status
app.get('/sessions/:id/status', (req, res) => {
    const session = sessionManager.getSession(req.params.id);
    if (!session) {
        return res.status(404).json({ error: 'Session not found' });
    }
    res.json({
        status: session.status,
        phone_number: session.phoneNumber,
    });
});

// Delete/disconnect session
app.delete('/sessions/:id', async (req, res) => {
    try {
        await sessionManager.deleteSession(req.params.id);
        res.json({ status: 'ok' });
    } catch (err) {
        res.status(500).json({ error: err.message });
    }
});

app.listen(PORT, () => {
    console.log(`WhatsApp Bridge running on port ${PORT}`);
});
