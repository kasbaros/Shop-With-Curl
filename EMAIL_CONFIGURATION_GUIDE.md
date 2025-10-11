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

## Email Features in the Application

### 1. User Email Verification
- Automatically sends verification emails when users register
- Users must verify their email before accessing certain features
- Handled by Laravel's built-in email verification system

### 2. Payment Notifications
- Payment confirmation emails
- Payment failure notifications
- Refund processed notifications
- Handled by `App\Notifications\PaymentNotification` class

### 3. Admin Notifications
- Payment alerts for administrators
- Handled by `App\Notifications\AdminPaymentNotification` class

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
