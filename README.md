# Hana-Eunhaeng — Production-Grade Fintech (Scaffold)

Hana-Eunhaeng is a production-oriented scaffold for a fintech online banking platform built with Pure PHP 8+, MySQL, HTML5, CSS3, and vanilla JavaScript. It uses an MVC-inspired layout and includes security best practices and developer tooling to get started quickly.

This branch contains an initial scaffold with core classes, routing, basic auth, SQL schema, and frontend assets (Tailwind + Chart.js). Follow the INSTALL steps to set up locally.

Highlights
- MVC-like folder structure
- PDO using prepared statements
- Auth example with password hashing
- CSRF helper
- PHPMailer and dotenv sample integration (composer)
- Tailwind CSS (CDN for quick start) and Chart.js example
- SQL schema for phpMyAdmin import

Quick install
1. Clone and switch to the scaffold branch:
   git clone https://github.com/Green-1122/hana-eunhaeng.git
   cd hana-eunhaeng
   git checkout init/scaffold

2. Copy .env.example -> .env and set values (DB, MAIL, APP_URL).

3. Install composer deps:
   composer install

4. (Optional) For Tailwind build (if you want a proper build pipeline):
   npm install
   npm run build

5. Import `sql/schema.sql` into MySQL (phpMyAdmin or CLI).

6. Configure your webserver to use the `public/` directory as the document root.

What's next
- Implement full controllers/views (accounts, transfers, transactions)
- Add rate limiting, logging, and background workers
- Add automated tests and CI pipeline

License: MIT
