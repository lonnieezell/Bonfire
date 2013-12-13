# Site Settings

**Sections**

- [Main Settings](#main-settings)
- [Security Settings](#security-settings)
- [Developer Settings](#developer-settings)
- [Extended Settings](#extended-settings)


## Main Settings {#main-settings}

### Site Name

Displayed as part of the title for every page in the default/admin/docs themes

### Site Email

The default email that system-generated emails are sent from

### Site Status

Allows you to take the site offline (or bring it back online).

### Items per page

Allows you to set the default number of items displayed by the pager.

### Language

Allows you to choose which languages are available for selection by the user.

## Security Settings {#security-settings}

### Allow User Registrations?

### Activation Method

Allow you to choose the activation method for the site: None/Email/Admin

### Login Type

Email Only/Username Only/Email or Username

### User display across bonfire

Username/Email

### Allow 'Remember Me'?

Determines whether the 'Remember Me' checkbox is displayed on the login page

### Password Strength Settings

Set the minimum length of passwords allowed by the system

### Password Options

#### Should password force numbers?

If checked, passwords must include numbers.

#### Should password force symbols?

If checked, passwords must include symbols (characters other than numbers and letters).

#### Should password force mixed case?

If checked, passwords must include lowercase and uppercase letters.

#### Display password validation labels

If checked, password validation labels will be displayed.

### Password Stretching

2/4/8/16/31
The number of iterations used in hashing the password. Since this information is stored with the hashed password, this may be changed at any time, but will not change the stretching used on existing passwords. To force a change in this value, you would also have to force all users to reset their passwords.
See [article on password management with phpass](http://www.openwall.com/articles/PHP-Users-Passwords)

### Force Password Resets

Using the 'Reset Now' button will force all users to reset their passwords on their next login.
It will also force you to reset your password on next login, log you out, and exit the page without saving any other settings on this page.

## Developer Settings {#developer-settings}

### Show Admin Profiler?

If checked, the profiler will be shown on the admin pages.

### Show Front End Profiler?

If checked, the profiler will also be shown on the front-end pages.

## Extended Settings {#extended-settings}

Administrators and/or developers can add additional settings by modifying the <tt>/application/config/extended_settings.php</tt> file.
See [Extended Settings](settings/extended_settings) documentation for more information.