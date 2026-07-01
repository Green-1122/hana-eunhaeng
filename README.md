# Hana-Eunhaeng Banking Platform

## Production-Grade Fintech Online Banking Platform

Hana-Eunhaeng is a **premium, full-stack fintech banking platform** built with **Pure PHP 8+, MySQL, HTML5, CSS3, and Vanilla JavaScript**. It delivers enterprise-ready features with a modern, intuitive interface inspired by leading digital banking platforms.

### ✨ Features

#### Core Banking
- 🏦 **Multiple Account Types** - Checking, Savings, Money Market, CDs
- 💳 **Digital Cards** - Debit, Credit, and Prepaid with full management
- 💸 **Transfers & Payments** - Instant internal and external transfers
- 📊 **Transaction History** - Complete audit trail with filtering and export
- 📈 **Account Analytics** - Real-time balance tracking and insights

#### Security & Compliance
- 🔐 **Bank-Level Encryption** - AES-256 for sensitive data
- 🔑 **Two-Factor Authentication** - Email and SMS support
- ✅ **PCI DSS Compliance** - Card data security standards
- 📝 **Audit Logging** - Complete activity tracking
- 🛡️ **CSRF Protection** - Token-based request validation

#### User Management
- 👤 **User Profiles** - Comprehensive profile management
- 🔒 **Password Security** - bcrypt hashing with configurable rounds
- 📧 **Email Verification** - Secure email validation
- ⏱️ **Session Management** - Configurable session timeouts
- 🚪 **Login History** - Track user access patterns

#### Admin & Analytics
- 📊 **Dashboard Analytics** - Real-time financial insights
- 📋 **Transaction Reports** - Detailed transaction analysis
- 👥 **User Management** - Admin controls for user accounts
- 🔍 **Audit Trail** - Security event logging
- 💹 **Financial Reporting** - Balance sheets and statements

### 🏗️ Architecture

```
hana-eunhaeng/
├── app/
│   ├── config/           # Configuration files
│   ├── controllers/      # Request handlers
│   ├── models/          # Data models
│   ├── views/           # Template files
│   ├── middleware/      # Request middleware
│   ├── helpers/         # Utility functions
│   └── core/            # Framework core
├── public/              # Web root
│   ├── index.php        # Entry point
│   ├── css/             # Stylesheets
│   ├── js/              # JavaScript files
│   └── assets/          # Images and fonts
├── database/            # Database schema
├── storage/             # Logs and uploads
└── docs/                # Documentation
```

### 🚀 Quick Start

#### Requirements
- PHP 8.0+
- MySQL 5.7+
- Apache with mod_rewrite
- Composer (optional)

#### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Green-1122/hana-eunhaeng.git
   cd hana-eunhaeng
   ```

2. **Copy environment configuration**
   ```bash
   cp app/config/.env.example app/config/.env
   ```

3. **Update .env with your database credentials**
   ```
   DB_HOST=localhost
   DB_NAME=hana_eunhaeng
   DB_USER=root
   DB_PASS=your_password
   ```

4. **Create database**
   ```bash
   mysql -u root -p < database/schema.sql
   ```

5. **Set file permissions**
   ```bash
   chmod -R 755 storage/
   chmod 644 public/.htaccess
   ```

6. **Start development server**
   ```bash
   php -S localhost:8000 -t public/
   ```

7. **Access the application**
   - Navigate to `http://localhost:8000`
   - Register a new account
   - Login and explore

### 📚 API Endpoints

#### Authentication
- `POST /auth/login` - User login
- `POST /auth/register` - User registration
- `GET /auth/logout` - User logout
- `POST /auth/forgot-password` - Password reset

#### Dashboard
- `GET /dashboard` - Main dashboard
- `GET /dashboard/profile` - User profile
- `POST /dashboard/profile` - Update profile
- `GET /dashboard/settings` - Account settings

#### Accounts
- `GET /accounts/list` - List all accounts
- `GET /accounts/view?id={id}` - View account details
- `GET /accounts/create` - Account creation form
- `POST /accounts/store` - Create new account

#### Transactions
- `GET /transactions/history` - Transaction history
- `GET /transactions/view?id={id}` - Transaction details
- `GET /transactions/transfer` - Transfer form
- `POST /transactions/process-transfer` - Process transfer

### 🎨 UI/UX Features

#### Design System
- **Color Palette** - Professional blues, greens, and neutrals
- **Typography** - Clear hierarchy with modern fonts
- **Spacing** - Consistent 8px grid system
- **Shadows** - Depth through subtle shadows
- **Animations** - Smooth transitions and interactions

#### Responsive Design
- 📱 Mobile-first approach
- 💻 Desktop optimization
- 🎯 Touch-friendly interactions
- 📏 Flexible layouts

#### Accessibility
- ♿ WCAG 2.1 AA compliance
- ⌨️ Keyboard navigation
- 👁️ High contrast support
- 🔊 Screen reader friendly

### 🔒 Security Best Practices

- **HTTPS Only** - All connections encrypted
- **CSRF Tokens** - Request validation
- **SQL Injection Protection** - Prepared statements
- **XSS Prevention** - Input sanitization
- **Rate Limiting** - Brute force protection
- **Session Security** - HTTPOnly cookies
- **Password Hashing** - bcrypt with salt
- **Audit Logging** - Security event tracking

### 📊 Database Schema

Key tables:
- `users` - User accounts and profiles
- `accounts` - Bank accounts
- `cards` - Debit/credit cards
- `transactions` - Transaction history
- `transfers` - Money transfers
- `beneficiaries` - Saved recipients
- `loans` - Loan accounts
- `bills` - Bill payments
- `notifications` - User notifications
- `audit_logs` - Activity logging
- `security_events` - Security tracking

### 🧪 Testing

```bash
# Test credentials
Email: demo@hana-eunhaeng.local
Password: DemoPassword123!
```

### 📖 Documentation

- [Installation Guide](docs/INSTALL.md)
- [API Documentation](docs/API.md)
- [Database Schema](docs/DATABASE.md)
- [Security Guide](docs/SECURITY.md)
- [Contributing](CONTRIBUTING.md)

### 📝 License

MIT License - see LICENSE.md

### 👥 Contributing

Contributions welcome! Please read [CONTRIBUTING.md](CONTRIBUTING.md) first.

### 📞 Support

For issues, questions, or suggestions:
- Open an issue on GitHub
- Email: support@hana-eunhaeng.local
- Documentation: https://docs.hana-eunhaeng.local

### 🙏 Acknowledgments

Inspired by leading fintech platforms:
- Chase Bank
- Capital One
- Robinhood
- SoFi

---

**Build with ❤️ for modern banking**
