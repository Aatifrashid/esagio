# Krystal cPanel Deployment

## Server details
- Host: rosina.uksrv.co.uk
- cPanel user: esagio
- Domain: esagio.com
- Staging: dev.esagio.com
- IP: 77.72.4.16
- SSH port: 722 (shell access needs enabling via Krystal support)

## Deployment method
1. Push to GitHub (Aatifrashid/esagio, main branch)
2. Use cPanel Git Version Control to pull from GitHub
3. Or manually upload via cPanel File Manager

## Production cutover
1. Run `php artisan optimize` (config, route, view cache)
2. Run `php artisan migrate --force`
3. Set up cron: `* * * * * cd ~/public_html && php artisan schedule:run >> /dev/null 2>&1`
4. Set up queue cron: `* * * * * cd ~/public_html && php artisan queue:work --once --queue=default --max-time=55`
5. Configure Stripe webhook to https://esagio.com/stripe/webhook
6. Verify SSL via Krystal AutoSSL
7. Switch document root or DNS from dev to production
8. Tag v1.0.0
