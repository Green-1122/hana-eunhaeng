# 🏦 Hana-Eunhaeng - Production-Grade Fintech Banking Platform

**A full-stack, enterprise-ready online banking system built with Pure PHP 8+, MySQL, and modern web technologies.**

[![Status](https://img.shields.io/badge/Status-Active%20Development-blue)]()
[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blueviolet)]()
[![License](https://img.shields.io/badge/License-MIT-green)]()

## 🎯 Overview

Hana-Eunhaeng is a **production-grade, full-stack fintech platform** designed to deliver a premium banking experience comparable to Chase, Capital One, Robinhood, and SoFi. Built entirely with:

- **Pure PHP 8+** (OOP architecture, no frameworks needed)
- **MySQL 8.0+** (optimized schema with 15+ tables)
- **HTML5 + CSS3** (Tailwind CSS)
- **Vanilla JavaScript** (lightweight, performant)
- **PDO Prepared Statements** (security first)

## ✨ Core Features

### 🔐 Security First
- ✅ **Password Hashing** (bcrypt/argon2)
- ✅ **Two-Factor Authentication** (SMS, Email, Authenticator)
- ✅ **Encrypted Storage** (sensitive data)
- ✅ **Rate Limiting** (prevent brute force)
- ✅ **CSRF Protection** (token-based)
- ✅ **SQL Injection Prevention** (prepared statements)
- ✅ **Session Management** (secure cookies)
- ✅ **Audit Logging** (complete activity tracking)
- ✅ **Fraud Detection** (anomaly alerts)

### 💰 Banking Features
- ✅ **Multiple Account Types** (Checking, Savings, Money Market, CD)
- ✅ **Debit/Credit Cards** (management, limits, controls)
- ✅ **Transactions** (full history, filtering, export)
- ✅ **Internal Transfers** (between own accounts)
- ✅ **External Transfers** (ACH, Wire, International)
- ✅ **Bill Pay** (recurring, one-time, scheduled)
- ✅ **Recurring Payments** (automated, flexible frequency)
- ✅ **Loan Management** (personal, mortgage, auto, student)
- ✅ **Recipients** (saved, verified, favorites)
- ✅ **Interest Calculation** (automated posting)
- ✅ **Overdraft Protection** (configurable)

### 📊 Analytics & Reporting
- ✅ **Dashboard Overview** (balance, accounts, cards)
- ✅ **Transaction Analytics** (charts, trends)
- ✅ **Spending Insights** (by category, merchant)
- ✅ **Financial Goals** (tracking, progress)
- ✅ **PDF Statements** (monthly, quarterly, annual)
- ✅ **Data Export** (CSV, Excel)

### 🎨 Premium UI/UX
- ✅ **Modern Design System** (glassmorphism, gradients)
- ✅ **Dark/Light Mode** (theme toggle)
- ✅ **Responsive Design** (mobile-first)
- ✅ **Accessibility** (WCAG 2.1 AA)
- ✅ **Micro Interactions** (smooth transitions)
- ✅ **Financial Education** (tooltips, explanations)
- ✅ **Notification Center** (in-app, email, SMS)

### ⚙️ Administration
- ✅ **User Management** (create, edit, suspend, monitor)
- ✅ **KYC Verification** (Know Your Customer)
- ✅ **AML Screening** (Anti-Money Laundering)
- ✅ **Transaction Monitoring** (fraud detection)
- ✅ **Audit Dashboard** (complete activity log)
- ✅ **System Settings** (configurable)

## 🏗️ Architecture

```
hana-eunhaeng/
├── public/                      # Web root
│   ├── index.php               # Single entry point
│   └── assets/
│       ├── css/
│       ├── js/
│       └── images/
├── app/
│   ├── config/                 # Configuration
│   ├── core/                   # Framework base classes
│   ├── middleware/             # Request middleware
│   ├── controllers/            # Business logic
│   ├── models/                 # Data models
│   ├── helpers/                # Utility functions
│   └── views/                  # Templates
├── database/
│   ├── schema.sql              # Database schema
│   └── seeds.sql               # Sample data
├── tests/                      # Unit & integration tests
├── .env.example                # Environment template
├── composer.json               # Dependencies
└── README.md
```

## 🚀 Getting Started

### Prerequisites
- PHP 8.0 or higher
- MySQL 8.0 or higher
- Composer
- Web server (Apache/Nginx)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/hana-eunhaeng.git
   cd hana-eunhaeng
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   # Edit .env with your configuration
   ```

4. **Create database**
   ```bash
   mysql -u root -p < database/schema.sql
   ```

5. **Run the application**
   ```bash
   php -S localhost:8000 -t public
   ```

   Access at `http://localhost:8000`

## 📋 Database Schema

**15+ Core Tables:**
- `he_users` - User accounts & profiles
- `he_accounts` - Bank accounts (Checking, Savings, etc)
- `he_cards` - Debit/Credit cards
- `he_transactions` - Transaction history
- `he_recipients` - Saved transfer recipients
- `he_recurring_transfers` - Automated payments
- `he_loans` - Loan products
- `he_bills` - Bill management
- `he_login_history` - Login tracking
- `he_audit_log` - Complete audit trail
- `he_fraud_alerts` - Suspicious activity
- `he_notifications` - User notifications
- `he_notification_preferences` - Notification settings
- `he_support_tickets` - Customer support
- `he_admin_users` - Admin accounts

**Key Features:**
- Full normalization (3NF)
- Comprehensive indexing
- Foreign key constraints
- Views for common queries
- JSON support for flexible data
- Soft deletes where needed

## 🔑 Key Technologies

| Component | Technology |
|-----------|------------|
| Backend | Pure PHP 8+ (OOP) |
| Database | MySQL 8.0+ |
| Frontend | HTML5, CSS3, Vanilla JS |
| Styling | Tailwind CSS |
| Charts | Chart.js |
| Email | PHPMailer |
| PDF | TCPDF/mPDF |
| Auth | bcrypt/Argon2 |
| Sessions | PHP Session with Redis support |

## 🔒 Security Features

- **HTTPS/TLS** - All data encrypted in transit
- **CSRF Tokens** - Token-based CSRF protection
- **Rate Limiting** - Per-IP request throttling
- **SQL Injection Prevention** - Prepared statements only
- **XSS Protection** - Output escaping, CSP headers
- **CORS** - Configurable cross-origin rules
- **2FA** - Multiple authentication methods
- **Session Security** - HttpOnly, Secure, SameSite flags
- **Password Policy** - Minimum complexity requirements
- **Data Encryption** - Sensitive fields encrypted

## 📱 Responsive Design

- Mobile-first approach
- Tailwind CSS breakpoints
- Touch-friendly interfaces
- Optimized for phones, tablets, desktops
- Progressive enhancement

## 🎓 Financial Education

- In-app tooltips explaining features
- "Why this matters" sections
- Educational resources
- Best practices for banking
- Fee explanations

## 📊 API Documentation

Full RESTful API with documentation:
- Authentication endpoints
- Account management
- Transaction handling
- Transfer operations
- Bill pay
- Loan management

## 🧪 Testing

```bash
# Run unit tests
phpunit

# Run code analysis
phpstan analyse

# Check code style
phpcs app/
```

## 📝 License

MIT License - see LICENSE file for details

## 🤝 Contributing

Contributions welcome! Please read CONTRIBUTING.md first.

## 📞 Support

For issues and questions:
- GitHub Issues
- Email: support@hana-eunhaeng.com
- Documentation: /docs

## 🗺️ Roadmap

- [ ] Mobile app (React Native)
- [ ] Crypto wallet integration
- [ ] AI-powered financial advisor
- [ ] Real-time notifications
- [ ] Multi-currency support
- [ ] Investment platform
- [ ] Insurance products
- [ ] API marketplace

---

**Built with ❤️ by Green-1122**
