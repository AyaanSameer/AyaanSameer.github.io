# Simple Enquiry Form

A lightweight contact form for WordPress. Put `[simple_enquiry_form]` on any page: visitors send a
message, it is saved privately under **Enquiries** in the dashboard and emailed to you. No
third-party services, no tracking, no JavaScript.

```
[simple_enquiry_form subject="Catering" button="Send message"]
```

## How it works

| Part | File | Notes |
|---|---|---|
| Form + handler | `includes/class-sef-form.php` | Posts to `admin-post.php`, then redirects back (post/redirect/get), so a refresh never sends twice and the page can be cached |
| Storage | `includes/class-sef-post-type.php` | A private custom post type: no public URL, no search, no REST route; nobody can create one from the dashboard. List columns for name, email, phone, message |
| Settings | `includes/class-sef-settings.php` | **Settings → Enquiry form** (Settings API): where emails go, the thank-you message |
| Clean-up | `uninstall.php` | Deleting the plugin removes its settings and every saved enquiry |

## Security and spam

- **Nonce** on every submission; an expired form says so instead of failing silently.
- **Sanitised in, escaped out**: `sanitize_text_field`, `sanitize_email`, `sanitize_textarea_field`
  on the way in; `esc_html`, `esc_attr`, `esc_url`, `esc_textarea` on every output.
- **Email header injection** is blocked: the visitor's name is stripped of line breaks before it goes
  into `Reply-To`.
- **Open redirects** are blocked with `wp_validate_redirect`.
- **Spam** is stopped without a CAPTCHA: a hidden honeypot field, a minimum of three seconds
  between loading and sending, and one message per visitor every 30 seconds. Bots are told they
  succeeded, so they have no reason to retry.

## Accessible errors

When a field is wrong the visitor is sent back with what they typed still in place (kept for ten
minutes in a transient). Each error is tied to its field with `aria-describedby` and
`aria-invalid`, and the summary is announced with `role="alert"`.

## Install

Copy this folder into `wp-content/plugins/` and activate **Simple Enquiry Form**. Requires
WordPress 6.5+ and PHP 7.4+; tested on 7.1.3. Make sure the site can send email (an SMTP plugin is
recommended on most hosts).
