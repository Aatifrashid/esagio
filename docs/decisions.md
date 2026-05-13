# Architectural Decisions

## Phase 0

### Build environment
- **Decision**: Build locally (macOS), deploy to Krystal via Git pull
- **Reason**: Krystal cPanel SSH shell access not enabled; cPanel Git Version Control will pull from GitHub
- **Local tooling**: PHP 8.5.5, Composer 2.9.7, Node 24.11.0, Git 2.30.1

### Laravel version
- **Decision**: Laravel 12.x (not 13.x)
- **Reason**: Filament 3.x requires Laravel 12 or below

### CSV import library
- **Decision**: OpenSpout instead of maatwebsite/excel
- **Reason**: maatwebsite/excel incompatible with current dependency tree; OpenSpout is lighter and handles CSV/XLSX well

### cPanel document root
- **Decision**: Root .htaccess rewrites to public/ directory
- **Reason**: Simpler than reconfiguring subdomain document root; works on both dev and production

### Browser tests
- **Decision**: Skip Dusk/browser tests
- **Reason**: cPanel shared hosting cannot run headless Chrome reliably; rely on Pest unit/feature tests
