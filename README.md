# Simple Contact Form

Plugin example for WordPressⓌ Aix-en-Provence - Meetup.
The purpose of this plugin is to show off how to create a contact form, submit it and send mail.
The contact form also offers the possibility to register email/name from a subscriber (e.g.: newsletter).
To be RPGD compliant, subscribers data can be managed thanks to WordPress privacy exporter/eraser.

This plugin also provides a dynamic block for Gutenberg editor to easily add the contact form.
Shortcode method is provided as fallback.

# Requirements

```
WordPress 4.7
Gutenberg 4.0
PHP 5.4
Node 8+
```

# Setup

Clone or download this repository in your plugins directory.
Then run the following scripts:

```
npm install
npm run dev
```

To generate `.pot` file, you must globally install [wp-i18n CLI tool](https://www.npmjs.com/package/node-wp-i18n).
Then run the following script:
```
npm run build
```
