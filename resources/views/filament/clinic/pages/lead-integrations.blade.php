<x-filament-panels::page>

    <style>
        .li-wrap { font-family: 'Lexend Deca', -apple-system, sans-serif; }
        .li-wrap * { box-sizing: border-box; }

        .li-creds {
            background: #1a1a2e;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            color: #fff;
        }
        .li-creds h2 { font-size: 16px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
        .li-cred-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }
        .li-cred-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af;
            min-width: 100px;
        }
        .li-cred-val {
            flex: 1;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            font-family: 'JetBrains Mono', monospace;
            color: #e5e7eb;
            overflow-x: auto;
            white-space: nowrap;
        }
        .li-copy-btn {
            background: #E8663D;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: opacity 0.15s;
        }
        .li-copy-btn:hover { opacity: 0.85; }
        .li-regen {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.2);
            color: #9ca3af;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .li-regen:hover { border-color: #dc2626; color: #dc2626; }

        .li-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 20px;
        }
        .li-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            transition: box-shadow 0.2s;
        }
        .li-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        .li-card-head {
            padding: 20px 24px 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 1px solid #f3f4f6;
        }
        .li-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .li-card-head h3 { font-size: 15px; font-weight: 700; color: #1a1a1a; margin: 0; }
        .li-card-head p { font-size: 12px; color: #6b7280; margin: 4px 0 0; line-height: 1.4; }

        .li-card-body { padding: 20px 24px; }
        .li-step {
            display: flex;
            gap: 12px;
            margin-bottom: 14px;
        }
        .li-step:last-child { margin-bottom: 0; }
        .li-step-num {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #f3f4f6;
            color: #6b7280;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .li-step-text {
            font-size: 13px;
            color: #374151;
            line-height: 1.5;
        }
        .li-step-text strong { color: #1a1a1a; }
        .li-step-text code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
            font-family: 'JetBrains Mono', monospace;
            color: #E8663D;
        }

        .li-code-block {
            background: #1a1a2e;
            border-radius: 8px;
            padding: 14px 16px;
            margin: 10px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: #e5e7eb;
            overflow-x: auto;
            line-height: 1.6;
            white-space: pre;
            position: relative;
        }
        .li-code-copy {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(255,255,255,0.1);
            border: none;
            color: #9ca3af;
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 10px;
            cursor: pointer;
        }
        .li-code-copy:hover { background: rgba(255,255,255,0.2); color: #fff; }

        .li-difficulty {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 10px;
            margin-top: 4px;
        }
    </style>

    <div class="li-wrap">

        {{-- Credentials bar --}}
        <div class="li-creds">
            <h2>
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                Your API Credentials
            </h2>

            <div class="li-cred-row">
                <span class="li-cred-label">Webhook URL</span>
                <div class="li-cred-val">{{ $webhookUrl }}</div>
                <button class="li-copy-btn" onclick="copyText('{{ $webhookUrl }}')">Copy</button>
            </div>

            <div class="li-cred-row">
                <span class="li-cred-label">API Key</span>
                <div class="li-cred-val">{{ $apiKey }}</div>
                <button class="li-copy-btn" onclick="copyText('{{ $apiKey }}')">Copy</button>
                <button class="li-regen" wire:click="regenerateKey" wire:confirm="This will invalidate your current key. Any active integrations will stop working. Continue?">Regenerate</button>
            </div>

            <div style="margin-top: 12px; font-size: 11px; color: #6b7280; display: flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Every lead sent to this URL with your API key will appear as a new contact in your CRM automatically.
            </div>
        </div>

        {{-- Integration cards --}}
        <div class="li-grid">

            {{-- Website Form --}}
            <div class="li-card">
                <div class="li-card-head">
                    <div class="li-card-icon" style="background: #dbeafe; color: #2563eb;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg>
                    </div>
                    <div>
                        <h3>Website Contact Form</h3>
                        <p>Capture leads from your clinic website directly</p>
                        <span class="li-difficulty" style="background: #d1fae5; color: #065f46;">Easy</span>
                    </div>
                </div>
                <div class="li-card-body">
                    <div class="li-step">
                        <span class="li-step-num">1</span>
                        <div class="li-step-text">Add this JavaScript to your website's contact form. It sends the form data to your CRM when submitted.</div>
                    </div>
                    <div class="li-code-block" id="website-code">
<button class="li-code-copy" onclick="copyEl('website-code')">Copy</button>
// Add to your form's submit handler
fetch('{{ $webhookUrl }}', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    api_key: '{{ $apiKey }}',
    first_name: document.getElementById('name').value,
    email: document.getElementById('email').value,
    phone: document.getElementById('phone').value,
    lead_channel: 'website_form',
    source: 'website',
    landing_page: window.location.href,
    referrer_url: document.referrer,
    utm_source: new URLSearchParams(location.search).get('utm_source'),
    utm_medium: new URLSearchParams(location.search).get('utm_medium'),
    utm_campaign: new URLSearchParams(location.search).get('utm_campaign')
  })
})</div>
                    <div class="li-step">
                        <span class="li-step-num">2</span>
                        <div class="li-step-text">UTM parameters are captured automatically from the visitor's URL, so you'll know exactly which campaign brought them in.</div>
                    </div>
                </div>
            </div>

            {{-- Facebook Lead Ads --}}
            <div class="li-card">
                <div class="li-card-head">
                    <div class="li-card-icon" style="background: #e0e7ff; color: #4338ca;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </div>
                    <div>
                        <h3>Facebook Lead Ads</h3>
                        <p>Auto-import leads from your Facebook ad campaigns</p>
                        <span class="li-difficulty" style="background: #fef3c7; color: #92400e;">Medium</span>
                    </div>
                </div>
                <div class="li-card-body">
                    <div class="li-step">
                        <span class="li-step-num">1</span>
                        <div class="li-step-text">Go to <strong>Zapier.com</strong> and create a free account (or use Make.com)</div>
                    </div>
                    <div class="li-step">
                        <span class="li-step-num">2</span>
                        <div class="li-step-text">Create a new Zap: <strong>Trigger</strong> = "Facebook Lead Ads &rarr; New Lead"</div>
                    </div>
                    <div class="li-step">
                        <span class="li-step-num">3</span>
                        <div class="li-step-text">Add <strong>Action</strong> = "Webhooks by Zapier &rarr; POST" and paste this config:</div>
                    </div>
                    <div class="li-code-block" id="fb-code">
<button class="li-code-copy" onclick="copyEl('fb-code')">Copy</button>
URL: {{ $webhookUrl }}
Method: POST
Content-Type: application/json

Body:
{
  "api_key": "{{ $apiKey }}",
  "first_name": "@{{ First Name }}",
  "last_name": "@{{ Last Name }}",
  "email": "@{{ Email }}",
  "phone": "@{{ Phone Number }}",
  "source": "facebook_ads",
  "lead_channel": "facebook_ads",
  "utm_source": "facebook",
  "utm_medium": "cpc",
  "utm_campaign": "@{{ Campaign Name }}"
}</div>
                    <div class="li-step">
                        <span class="li-step-num">4</span>
                        <div class="li-step-text">Test the Zap and turn it on. New Facebook leads will appear in your CRM within seconds.</div>
                    </div>
                </div>
            </div>

            {{-- Instagram Ads --}}
            <div class="li-card">
                <div class="li-card-head">
                    <div class="li-card-icon" style="background: linear-gradient(135deg, #fce4ec, #f3e5f5); color: #c2185b;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </div>
                    <div>
                        <h3>Instagram Ads</h3>
                        <p>Import leads from Instagram ad campaigns via Zapier</p>
                        <span class="li-difficulty" style="background: #fef3c7; color: #92400e;">Medium</span>
                    </div>
                </div>
                <div class="li-card-body">
                    <div class="li-step">
                        <span class="li-step-num">1</span>
                        <div class="li-step-text">Instagram Lead Ads use the <strong>same Facebook Lead Ads connector</strong> in Zapier (Meta manages both).</div>
                    </div>
                    <div class="li-step">
                        <span class="li-step-num">2</span>
                        <div class="li-step-text">Follow the Facebook Lead Ads steps above, but change the body values:</div>
                    </div>
                    <div class="li-code-block" id="ig-code">
<button class="li-code-copy" onclick="copyEl('ig-code')">Copy</button>
"source": "instagram_ads",
"lead_channel": "instagram_ads",
"utm_source": "instagram",
"utm_medium": "cpc"</div>
                    <div class="li-step">
                        <span class="li-step-num">3</span>
                        <div class="li-step-text">Your Instagram leads will appear with a distinct <strong>Instagram Ads</strong> badge in your patient list.</div>
                    </div>
                </div>
            </div>

            {{-- Google Ads --}}
            <div class="li-card">
                <div class="li-card-head">
                    <div class="li-card-icon" style="background: #fef3c7; color: #d97706;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/></svg>
                    </div>
                    <div>
                        <h3>Google Ads</h3>
                        <p>Capture leads from Google search and display campaigns</p>
                        <span class="li-difficulty" style="background: #d1fae5; color: #065f46;">Easy</span>
                    </div>
                </div>
                <div class="li-card-body">
                    <div class="li-step">
                        <span class="li-step-num">1</span>
                        <div class="li-step-text"><strong>Option A (Recommended):</strong> Add UTM parameters to your Google Ads destination URLs. Your website form (set up above) will capture them automatically.</div>
                    </div>
                    <div class="li-code-block" id="gads-code">
<button class="li-code-copy" onclick="copyEl('gads-code')">Copy</button>
?utm_source=google&utm_medium=cpc&utm_campaign={campaignname}&utm_term={keyword}</div>
                    <div class="li-step">
                        <span class="li-step-num">2</span>
                        <div class="li-step-text"><strong>Option B (Google Lead Forms):</strong> If using Google's native lead form extensions, use Zapier: <strong>Trigger</strong> = "Google Ads &rarr; New Lead Form Entry" &rarr; <strong>Action</strong> = Webhook POST to your URL with <code>source: "google_ads"</code></div>
                    </div>
                    <div class="li-step">
                        <span class="li-step-num">3</span>
                        <div class="li-step-text">Leads from Google will show with a blue <strong>Google Ads</strong> badge and you'll see which keywords brought them in.</div>
                    </div>
                </div>
            </div>

            {{-- TikTok Ads --}}
            <div class="li-card">
                <div class="li-card-head">
                    <div class="li-card-icon" style="background: #111; color: #fff;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.34-6.34V8.75a8.18 8.18 0 004.76 1.52V6.84a4.86 4.86 0 01-1-.15z"/></svg>
                    </div>
                    <div>
                        <h3>TikTok Ads</h3>
                        <p>Import leads from TikTok lead generation campaigns</p>
                        <span class="li-difficulty" style="background: #fef3c7; color: #92400e;">Medium</span>
                    </div>
                </div>
                <div class="li-card-body">
                    <div class="li-step">
                        <span class="li-step-num">1</span>
                        <div class="li-step-text">In <strong>Zapier</strong>, create a Zap: <strong>Trigger</strong> = "TikTok Lead Generation &rarr; New Lead"</div>
                    </div>
                    <div class="li-step">
                        <span class="li-step-num">2</span>
                        <div class="li-step-text">Add <strong>Action</strong> = "Webhooks by Zapier &rarr; POST" with your webhook URL and this body:</div>
                    </div>
                    <div class="li-code-block" id="tt-code">
<button class="li-code-copy" onclick="copyEl('tt-code')">Copy</button>
{
  "api_key": "{{ $apiKey }}",
  "first_name": "@{{ Name }}",
  "email": "@{{ Email }}",
  "phone": "@{{ Phone }}",
  "source": "tiktok_ads",
  "lead_channel": "tiktok_ads",
  "utm_source": "tiktok",
  "utm_medium": "cpc"
}</div>
                </div>
            </div>

            {{-- Zapier / Make --}}
            <div class="li-card">
                <div class="li-card-head">
                    <div class="li-card-icon" style="background: #fff3e0; color: #ff6d00;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3>Zapier / Make / Any Webhook</h3>
                        <p>Connect any app that supports webhooks</p>
                        <span class="li-difficulty" style="background: #d1fae5; color: #065f46;">Easy</span>
                    </div>
                </div>
                <div class="li-card-body">
                    <div class="li-step">
                        <span class="li-step-num">1</span>
                        <div class="li-step-text">Send a <code>POST</code> request to your webhook URL with the JSON body below. Only <code>api_key</code> and <code>first_name</code> are required.</div>
                    </div>
                    <div class="li-code-block" id="api-code">
<button class="li-code-copy" onclick="copyEl('api-code')">Copy</button>
POST {{ $webhookUrl }}
Content-Type: application/json

{
  "api_key": "{{ $apiKey }}",
  "first_name": "Jane",
  "last_name": "Doe",
  "email": "jane@example.com",
  "phone": "+447123456789",
  "source": "website",
  "lead_channel": "website_form",
  "utm_source": "google",
  "utm_medium": "cpc",
  "utm_campaign": "summer-promo",
  "notes": "Interested in veneers",
  "tags": ["veneers", "high-value"]
}</div>
                    <div class="li-step">
                        <span class="li-step-num">2</span>
                        <div class="li-step-text"><strong>Duplicate detection:</strong> If a lead with the same email or phone already exists, we update their UTM data instead of creating a duplicate.</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                new FilamentNotification()
                    .title('Copied to clipboard')
                    .success()
                    .send();
            });
        }
        function copyEl(id) {
            const el = document.getElementById(id);
            const text = el.innerText.replace('Copy\n', '').trim();
            navigator.clipboard.writeText(text).then(() => {
                new FilamentNotification()
                    .title('Copied to clipboard')
                    .success()
                    .send();
            });
        }
    </script>

</x-filament-panels::page>
