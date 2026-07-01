# Security Guide - Hana-Eunhaeng

## Security Overview

Hana-Eunhaeng implements enterprise-grade security to protect user data and financial transactions.

## Authentication & Authorization

### Password Security

- **Hashing Algorithm**: bcrypt with configurable rounds (default: 12)
- **Minimum Length**: 8 characters
- **Complexity**: Uppercase, lowercase, numbers, and symbols recommended
- **Expiration**: Optional password expiration policies
- **History**: Optional prevent reuse of previous passwords

```php
// Password hashing example
$hash = SecurityHelper::hashPassword($password);
$verified = SecurityHelper::verifyPassword($password, $hash);
```

### Two-Factor Authentication (2FA)

- **Methods**: Email, SMS, authenticator apps
- **Token Expiration**: 10 minutes
- **Rate Limiting**: 3 attempts per 5 minutes

### Session Management

- **Session Timeout**: Configurable (default: 24 hours)
- **HTTPOnly Cookies**: Prevents JavaScript access
- **Secure Flag**: HTTPS only in production
- **SameSite Policy**: Strict/Lax/None configuration

```env
SESSION_SECURE=true
SESSION_SAME_SITE=Strict
SESSION_LIFETIME=1440
```

## Encryption & Data Protection

### Data Encryption

- **Algorithm**: AES-256-CBC
- **Key Management**: Secure environment variables
- **Sensitive Fields**: SSN, card numbers, addresses

```php
// Encryption example
$encrypted = SecurityHelper::encrypt($data, $key);
$decrypted = SecurityHelper::decrypt($encrypted, $key);
```

### Data in Transit

- **HTTPS/TLS 1.2+**: All connections encrypted
- **Certificate**: SHA-256 signed
- **HSTS**: HTTP Strict Transport Security enabled

### Data at Rest

- **Database Encryption**: MariaDB/MySQL built-in encryption
- **File Permissions**: 600 for sensitive files
- **Backup Encryption**: AES-256 encrypted backups

## Input Validation & Sanitization

### Input Validation

```php
$validator = new ValidationHelper($_POST);
$validator->required('email')
          ->email('email')
          ->minLength('password', 8)
          ->unique('email', 'users');

if (!$validator->passes()) {
    $errors = $validator->errors();
}
```

### Input Sanitization

- **XSS Prevention**: HTML escaping on output
- **SQL Injection Prevention**: Prepared statements only
- **CSRF Protection**: Token-based validation

```php
// Sanitize user input
$email = SecurityHelper::sanitize($_POST['email']);

// Escape output
echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8');
```

## CSRF Protection

- **Token Generation**: Cryptographically secure random tokens
- **Validation**: Hash comparison with timing attack protection
- **Duration**: Per-session tokens

```php
// Generate token in forms
<input type="hidden" name="csrf_token" value="<?php echo SecurityHelper::generateCsrfToken(); ?>">

// Verify on submission
if (!SecurityHelper::verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    exit;
}
```

## Rate Limiting

```php
// Example: Login rate limiting
$userId = $_SESSION['user_id'];
$attemptKey = "login_attempt_{$userId}";
$attempts = $_SESSION[$attemptKey] ?? 0;

if ($attempts >= 3) {
    // Lock account for 15 minutes
    $lockedUntil = time() + (15 * 60);
    $_SESSION['locked_until'] = $lockedUntil;
}
```

## API Security

### Authentication

- **Session-Based**: Standard PHP sessions for web
- **API Keys**: For third-party integrations
- **OAuth 2.0**: Future support planned

### Rate Limiting

- **Per User**: 1000 requests/hour
- **Per IP**: 10,000 requests/hour
- **Burst**: 100 requests/minute

### CORS Policy

```php
header('Access-Control-Allow-Origin: ' . $_ENV['APP_URL']);
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

## Audit Logging

### Logged Events

- User login/logout
- Account creation/modification
- Transaction processing
- Card operations
- Permission changes
- Security events

```php
// Log security event
AuditLog::create([
    'user_id' => $userId,
    'action' => 'transfer_initiated',
    'resource_type' => 'transaction',
    'resource_id' => $transactionId,
    'old_values' => ['balance' => 1000],
    'new_values' => ['balance' => 900],
    'ip_address' => $_SERVER['REMOTE_ADDR'],
]);
```

## Security Headers

```php
// Implemented in config.php
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Content-Security-Policy: default-src \'self\'; ...');
```

## Vulnerability Management

### Dependency Updates

```bash
# Check for vulnerabilities
composer audit

# Update dependencies
composer update
```

### Patching

- **Critical**: Within 24 hours
- **High**: Within 7 days
- **Medium**: Within 30 days
- **Low**: Next release

## Compliance

### Standards

- **PCI DSS 3.2.1**: Payment card industry standards
- **OWASP**: Application security standards
- **GDPR**: Data privacy regulations
- **SOC 2**: Security and availability standards

### Data Retention

- **Transactions**: 7 years
- **Logs**: 1 year
- **Backups**: 3 years (encrypted)
- **User Data**: Until account deletion

## Security Best Practices

### For Users

1. Use strong, unique passwords
2. Enable two-factor authentication
3. Don't share account credentials
4. Verify URLs before entering data
5. Log out on shared computers
6. Report suspicious activity

### For Administrators

1. Keep software updated
2. Monitor security logs
3. Implement backups
4. Restrict admin access
5. Use strong authentication
6. Regular security audits
7. Employee training

## Incident Response

### Response Plan

1. **Detect**: Monitor logs and alerts
2. **Isolate**: Prevent further damage
3. **Investigate**: Determine scope
4. **Remediate**: Fix vulnerabilities
5. **Communicate**: Notify affected users
6. **Review**: Learn and improve

### Contact

- **Security Issues**: security@hana-eunhaeng.local
- **Urgent**: +1-XXX-XXX-XXXX
- **Response Time**: Within 1 hour

## Additional Resources

- [OWASP Top 10](https://owasp.org/Top10/)
- [PHP Security](https://www.php.net/manual/en/security.php)
- [MySQL Security](https://dev.mysql.com/doc/refman/8.0/en/security.html)
