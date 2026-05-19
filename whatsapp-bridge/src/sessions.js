const { default: makeWASocket, useMultiFileAuthState, DisconnectReason, makeCacheableSignalKeyStore } = require('@whiskeysockets/baileys');
const pino = require('pino');
const QRCode = require('qrcode');
const crypto = require('crypto');
const axios = require('axios');
const path = require('path');
const fs = require('fs');

const API_KEY = process.env.API_KEY;
const AUTH_DIR = path.join(__dirname, '..', 'auth_sessions');

class SessionManager {
    constructor() {
        this.sessions = new Map();
    }

    async createSession(sessionId, webhooks) {
        if (this.sessions.has(sessionId)) {
            throw new Error('Session already exists');
        }

        const sessionDir = path.join(AUTH_DIR, sessionId);
        if (!fs.existsSync(sessionDir)) {
            fs.mkdirSync(sessionDir, { recursive: true });
        }

        const sessionData = {
            id: sessionId,
            status: 'connecting',
            qrCode: null,
            phoneNumber: null,
            socket: null,
            webhooks,
        };

        this.sessions.set(sessionId, sessionData);

        await this._connectSocket(sessionId);

        return sessionData;
    }

    async _connectSocket(sessionId) {
        const session = this.sessions.get(sessionId);
        if (!session) return;

        const sessionDir = path.join(AUTH_DIR, sessionId);
        const { state, saveCreds } = await useMultiFileAuthState(sessionDir);

        const logger = pino({ level: 'silent' });

        const socket = makeWASocket({
            auth: {
                creds: state.creds,
                keys: makeCacheableSignalKeyStore(state.keys, logger),
            },
            printQRInTerminal: false,
            logger,
            browser: ['Esagio CRM', 'Chrome', '120.0'],
        });

        session.socket = socket;

        // Handle connection updates
        socket.ev.on('connection.update', async (update) => {
            const { connection, lastDisconnect, qr } = update;

            if (qr) {
                // Generate QR code as base64 PNG
                try {
                    const qrBase64 = await QRCode.toDataURL(qr);
                    // Strip the data URL prefix to get raw base64
                    session.qrCode = qrBase64.replace(/^data:image\/png;base64,/, '');
                    session.status = 'qr_pending';

                    // Notify Laravel
                    await this._notifySessionStatus(session, {
                        session_id: sessionId,
                        status: 'qr_pending',
                        qr_code: session.qrCode,
                    });
                } catch (err) {
                    console.error('QR generation error:', err.message);
                }
            }

            if (connection === 'close') {
                const statusCode = lastDisconnect?.error?.output?.statusCode;
                const shouldReconnect = statusCode !== DisconnectReason.loggedOut;

                console.log(`[${sessionId}] Connection closed. Status: ${statusCode}. Reconnect: ${shouldReconnect}`);

                if (shouldReconnect) {
                    // Reconnect after a brief delay
                    setTimeout(() => this._connectSocket(sessionId), 3000);
                } else {
                    session.status = 'disconnected';
                    await this._notifySessionStatus(session, {
                        session_id: sessionId,
                        status: 'disconnected',
                    });
                }
            }

            if (connection === 'open') {
                session.status = 'connected';
                session.qrCode = null;

                // Extract phone number from auth state
                const phoneNumber = socket.user?.id?.split(':')[0] || socket.user?.id?.split('@')[0];
                session.phoneNumber = phoneNumber;

                console.log(`[${sessionId}] Connected as ${phoneNumber}`);

                await this._notifySessionStatus(session, {
                    session_id: sessionId,
                    status: 'connected',
                    phone_number: phoneNumber,
                });
            }
        });

        // Save auth credentials when updated
        socket.ev.on('creds.update', saveCreds);

        // Handle incoming messages
        socket.ev.on('messages.upsert', async ({ messages, type }) => {
            if (type !== 'notify') return;

            for (const msg of messages) {
                // Skip status broadcasts and our own messages
                if (msg.key.remoteJid === 'status@broadcast') continue;
                if (msg.key.fromMe) continue;

                const from = msg.key.remoteJid.replace('@s.whatsapp.net', '');
                const pushName = msg.pushName || '';

                let body = '';
                let msgType = 'text';
                let mediaUrl = null;
                let mediaMimeType = null;

                if (msg.message?.conversation) {
                    body = msg.message.conversation;
                } else if (msg.message?.extendedTextMessage?.text) {
                    body = msg.message.extendedTextMessage.text;
                } else if (msg.message?.imageMessage) {
                    msgType = 'image';
                    body = msg.message.imageMessage.caption || '';
                    mediaMimeType = msg.message.imageMessage.mimetype;
                } else if (msg.message?.documentMessage) {
                    msgType = 'document';
                    body = msg.message.documentMessage.fileName || '';
                    mediaMimeType = msg.message.documentMessage.mimetype;
                } else if (msg.message?.audioMessage) {
                    msgType = 'audio';
                    mediaMimeType = msg.message.audioMessage.mimetype;
                } else if (msg.message?.videoMessage) {
                    msgType = 'video';
                    body = msg.message.videoMessage.caption || '';
                    mediaMimeType = msg.message.videoMessage.mimetype;
                }

                // Send to Laravel webhook
                await this._notifyMessage(session, {
                    session_id: sessionId,
                    from,
                    body,
                    type: msgType,
                    media_url: mediaUrl,
                    media_mime_type: mediaMimeType,
                    message_id: msg.key.id,
                    push_name: pushName,
                });
            }
        });

        // Handle message status updates (sent, delivered, read)
        socket.ev.on('messages.update', async (updates) => {
            for (const update of updates) {
                if (update.update?.status) {
                    const statusMap = {
                        2: 'sent',
                        3: 'delivered',
                        4: 'read',
                    };
                    const status = statusMap[update.update.status];
                    if (status) {
                        await this._notifyStatusUpdate(session, {
                            message_id: update.key.id,
                            status,
                        });
                    }
                }
            }
        });
    }

    async sendMessage(sessionId, to, body, mediaUrl) {
        const session = this.sessions.get(sessionId);
        if (!session || !session.socket) {
            throw new Error('Session not available');
        }

        // Format the JID
        const jid = to.includes('@') ? to : `${to}@s.whatsapp.net`;

        let sentMsg;
        if (mediaUrl) {
            // Send media message (image for now)
            sentMsg = await session.socket.sendMessage(jid, {
                image: { url: mediaUrl },
                caption: body || '',
            });
        } else {
            sentMsg = await session.socket.sendMessage(jid, { text: body });
        }

        return {
            message_id: sentMsg.key.id,
            status: 'sent',
        };
    }

    getSession(sessionId) {
        return this.sessions.get(sessionId) || null;
    }

    async deleteSession(sessionId) {
        const session = this.sessions.get(sessionId);
        if (session?.socket) {
            try {
                await session.socket.logout();
            } catch (e) {
                // Ignore logout errors
            }
            session.socket.end();
        }
        this.sessions.delete(sessionId);

        // Clean up auth directory
        const sessionDir = path.join(AUTH_DIR, sessionId);
        if (fs.existsSync(sessionDir)) {
            fs.rmSync(sessionDir, { recursive: true, force: true });
        }
    }

    // Webhook helpers
    async _notifyMessage(session, payload) {
        await this._sendWebhook(session.webhooks.webhookUrl, payload);
    }

    async _notifyStatusUpdate(session, payload) {
        await this._sendWebhook(session.webhooks.statusWebhookUrl, payload);
    }

    async _notifySessionStatus(session, payload) {
        await this._sendWebhook(session.webhooks.sessionWebhookUrl, payload);
    }

    async _sendWebhook(url, payload) {
        if (!url) return;

        try {
            const body = JSON.stringify(payload);
            const signature = crypto
                .createHmac('sha256', API_KEY)
                .update(body)
                .digest('hex');

            await axios.post(url, payload, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-Bridge-Signature': signature,
                },
                timeout: 10000,
            });
        } catch (err) {
            console.error(`Webhook failed (${url}):`, err.message);
        }
    }
}

module.exports = { SessionManager };
