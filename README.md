# CheckEngine

CheckEngine was developed as part of our research on the security of integrated browsers.
This work is published as [Shiny Shells, Rusty Cores: A Crowdsourced Security Evaluation of Integrated Web Browsers](https://www.usenix.org/conference/soups2025/presentation/franken) at SOUPS 2025.
Our instance of [CheckEngine](https://checkengine.distrinet-research.be/) remains online to support the ongoing continuation of this study.

CheckEngine relies on a [modified version of BrowserAudit](https://github.com/DistriNet/browseraudit).


## 🚀 Setup

1. Clone this repository:
```bash
git clone https://github.com/DistriNet/checkengine.git ./checkengine
```


2. Clone our browseraudit fork to `./browseraudit`
```bash
git clone https://github.com/DistriNet/browseraudit.git ./checkengine/browseraudit
```
> This fork is tailored for seamless integration with CheckEngine.


3. Fill in all required environment variables:
- **Laravel:** Edit `./laravel/.env`:
    - `APP_URL` — Public URL of your CheckEngine instance
    - `DB_*` — PostgreSQL credentials
    - `TINI_URL_TOKEN` — API token for tini.fyi URL shortener

- **Let's Encrypt Certbot:** Edit `./certbot/letsencrypt/certbot.conf`:
    - `domains`: List all domains/subdomains you control that point to this server

- **BrowserAudit Go Server:** Edit ./browseraudit/server.cfg
    - `[database]` — PostgreSQL credentials (must match Laravel)
    - `[domain]` — List all domains/subdomains you control that point to this server
    - `geoip2database`— Path to your GeoLite2 database (see BrowserAudit README)

4. Build and launch the BrowserAudit server
```bash
cd ./checkengine/browseraudit
go build
./browseraudit
```
> The Go server should now be listening on the configured port.

5. Start the framework with docker compose
```bash
cd ./checkengine
docker compose up -d nginx
```
> This launches the nginx, php-fpm and memcache.


## 🤝 Credits

CheckEngine was mainly developed by **Pieter Claeys**, with additional contributions from **Gertjan Franken**.

Many thanks to the creators of [BrowserAudit](https://github.com/browseraudit/browseraudit).
