# Email Configuration Guide for ShopWithCarl

This guide will help you configure email functionality for your ShopWithCarl application.

## Current Issue
The application was configured to use the `log` mail driver, which only logs emails instead of sending them. This has been changed to `smtp` but requires proper SMTP credentials to function.

## Email Services Supported

### Gmail SMTP (Recommended for testing)
Update your `.env` file with these settings:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="ShopWithCarl"
```

**Note for Gmail:** You need to use an "App Password" instead of your regular Gmail password:
1. Enable 2-factor authentication on your Gmail account
2. Go to Google Account settings > Security > App passwords
3. Generate an app password for "Mail"
4. Use this app password in the `MAIL_PASSWORD` field

### Mailgun (Recommended for production)
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-key
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="ShopWithCarl"
```

### SendGrid
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="ShopWithCarl"
```

### Amazon SES
```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="ShopWithCarl"
```

### Using your cPanel mailbox (info@shopwithcarl.ug)
The provided settings are compatible. For sending emails from the app, you only need SMTP (outgoing) — IMAP/POP are for reading email and are not required by the application.

Enter these values in Admin → Settings → Email Settings:

- Mail Driver (mail_driver): smtp
- SMTP Host (mail_host): mail.shopwithcarl.ug
- SMTP Port (mail_port): 465
- Encryption (mail_encryption): ssl
- SMTP Username (mail_username): info@shopwithcarl.ug
- SMTP Password (mail_password): Your mailbox password (from cPanel)
- From Email Address (mail_from_address): info@shopwithcarl.ug
- From Name (mail_from_name): "ShopWithCarl" or your preferred sender name

Alternative (non-SSL, not recommended unless 465/SSL is blocked by your host/network):
- SMTP Port: 587
- Encryption: tls

Notes specific to cPanel/hosting:
- Ensure the certificate on mail.shopwithcarl.ug is valid. If your host uses a different hostname for SSL (e.g., server123.yourhost.com), use that exact hostname as MAIL_HOST.
- If you see certificate/SSL errors on 465, switch to 587 with tls.
- Authentication is required; anonymous SMTP is not supported.
- IMAP/POP settings (993/995 or 143/110) are only for email clients and are not used by this app.

After saving these settings, use the "Send Test Email" button on the Email Settings page to verify delivery.

## Email Use Cases in the Application

Below is a complete list of where and why emails are sent in this project, including the key classes/views involved and the expected recipients.

### 1) Contact form submissions (to Admin)
- Purpose: Notify the site admin whenever a visitor submits the contact form.
- Recipient(s): Admin email set in `config('mail.admin_email')` (defaults to `admin@example.com`).
- Key files:
  - Controller: `App\Http\Controllers\Guest\PagesController::contactSubmit`
  - Mailable: `App\Mail\ContactFormMail`
  - View: `resources/views/emails/contact-form.blade.php` (referenced as `emails.contact-form`)

### 2) Admin “Send Test Email” action
- Purpose: Let admins verify mailer configuration from the dashboard.
- Recipient(s): Any address entered in the Email Settings screen.
- Key files:
  - Controller action: `App\Http\Controllers\Admin\SettingsController::testEmail`
  - View/UI: `resources/views/admin/settings/email.blade.php`

### 3) Console test email command
- Purpose: Quick CLI sanity check of email delivery.
- Recipient(s): Address passed via CLI argument.
- Key files:
  - Command: `App\Console\Commands\SendTestEmail.php`

### 4) Customer payment notifications
- Purpose: Transactional updates for payment lifecycle events.
- Recipient(s): The customer (User) associated with the order/payment.
- When email is sent: For important events such as `payment_confirmed`, `payment_failed`, `refund_processed`.
- Key files:
  - Notification: `App\Notifications\PaymentNotification` (implements `ShouldQueue`)

### 5) Customer order status notifications
- Purpose: Inform customers about order progress.
- Recipient(s): The customer (User) associated with the order.
- When email is sent: On status changes like `confirmed`/`processing`, `shipped` (with optional tracking), `delivered`, `cancelled`.
- Key files:
  - Notification: `App\Notifications\OrderStatusNotification` (implements `ShouldQueue`)

### 6) Admin payment notifications (critical events)
- Purpose: Escalate critical payment events to admins via email (and optionally Slack).
- Recipient(s): Notifiable admin users (as determined by where this notification is dispatched in your admin/payment flows).
- When email is sent: For types such as `manual_payment_pending`, `high_value_payment`, `payment_fraud_alert`.
- Key files:
  - Notification: `App\Notifications\AdminPaymentNotification` (implements `ShouldQueue`)

### 7) User email verification
- Purpose: Ensure users own the email address they registered with.
- Recipient(s): Newly registered / unverified users.
- Key files:
  - Controller/UI/tests involved in the standard Laravel verification flow, e.g. `App\Http\Controllers\Auth\VerifyEmailController`, `resources/views/livewire/auth/verify-email.blade.php`, and tests in `tests/Feature/Auth/EmailVerificationTest.php`.

Notes
- All the above notification classes that implement `ShouldQueue` will send email via the queue if a worker is running. See the Queue Configuration section below.
- Many notifications also persist to the database in parallel to email.

## Testing Email Configuration

### Method 1: Use the built-in test function
1. Log into the admin panel
2. Go to Settings > Email Settings
3. Enter a test email address
4. Click "Send Test Email"

### Method 2: Use the test script
```bash
php test_email_functionality.php
```

### Method 3: Use Artisan command
```bash
php artisan email:test your-email@example.com
```

## Queue Configuration

The application uses database queues for email sending. Make sure to run the queue worker in production:

```bash
# For development
php artisan queue:work

# For production (use a process manager like Supervisor)
php artisan queue:work --daemon
```

## Troubleshooting

### Common Issues:

1. **Emails not being sent**
   - Check that `MAIL_MAILER` is not set to `log`
   - Verify SMTP credentials are correct
   - Ensure the queue worker is running

2. **Gmail authentication errors**
   - Use an app password instead of your regular password
   - Make sure 2FA is enabled on your Google account

3. **SSL/TLS errors**
   - For Gmail, use `MAIL_ENCRYPTION=tls` and `MAIL_PORT=587`
   - For other services, check their documentation for correct settings

4. **Emails going to spam**
   - Configure SPF, DKIM, and DMARC records for your domain
   - Use a reputable email service provider
   - Avoid sending from generic domains like Gmail in production

### Log Files
Check these log files for email-related errors:
- `storage/logs/laravel.log` - General application logs
- Queue logs if using queue workers

## Production Recommendations

1. **Use a professional email service** (Mailgun, SendGrid, or Amazon SES)
2. **Set up proper DNS records** (SPF, DKIM, DMARC)
3. **Use your own domain** for the from address
4. **Set up monitoring** for email delivery rates
5. **Configure queue workers** with a process manager like Supervisor
6. **Test thoroughly** before going live

## Security Notes

- Never commit email credentials to version control
- Use environment variables for all sensitive configuration
- Consider using encrypted environment files in production
- Regularly rotate API keys and passwords
- Monitor for suspicious email activity

## Next Steps

1. Choose an email service provider
2. Update your `.env` file with the correct credentials
3. Test email functionality using one of the methods above
4. Configure DNS records if using your own domain
5. Set up queue workers for production
6. Monitor email delivery and bounce rates
