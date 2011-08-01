README

This zip file includes three plugins.

*shibboleth*

The shibboleth directory is a drop-in replacement for the standard Moodle 
auth/shibboleth directory which uses the Shibalike library.

After the new directory is installed, visit the Moodle admin area to 
upgrade the shibboleth plugin. Then visit the Shibboleth configuration page
under Plugins/Authentication/Shibboleth and set at least six values:

Username: elggUsername
Elgg URL: URL to the root of your Elgg install - must have a closing slash)
Shibalike library directory: wherever you have the Shibalike library source 
installed.
First name: dcfFirstName
Surname: dcfLastName
Email address: dcfEmail

By default visitors to the site will need to visit

http://moodle-url/auth/shibboleth/index.php

to autologin using Shibboleth.

Moodle has a standard setting which allows redirecting the login link
to this Shibboleth page. 

*shibalike*

The shibalike plugin directory is an Elgg plugin that should be placed in
the mod directory of your Elgg install and activated using the usual Elgg
admin plugin area. Once activated, you should visit the Shibalike plugin settings
and enter the location of the Moodle configuration file on the local file system.

You also need to create a table called "elf_users" in your Elgg database with
the following columns: `dcf_id` VARCHAR(25), `email` VARCHAR(255), and
`username` VARCHAR(25).

Currently the Shibalike library is stored in mod/shibalike/myclasses
and is loaded using an autoloader in mod/shibalike/start.php.

This is the standard Shibalike library with the addition of a new ElggStore
class in 

myclasses/Shibalike/Attr/Store/ElggStore.php

If you want to move the library location, edit the first line of
mod/shibalike/start.php.

The shibalike plugin changes the Elgg registration process to add
first_name, last_name and dcf_id fields, updates the username field
in the elf_users table upon email confirmation, changes the login
process to accept email address or DCF ID, and populates the Shibalike
session with the appropriate content upon login for use by
other applications such as Moodle.

The plugin also disables the user's ability to change their Elgg
email address.

*qli_recaptcha*

I have modified this standard Elgg plugin to work with Elgg 1.8.

Install it in Elgg's mod directory and activate it using Elgg's
plugin system. You will need to enter your Google Recaptcha private
and public keys in the qli_recaptcha plugin settings to get this to 
work properly.

*Outstanding issues*

I am happy to work on any of these issues once I receive relevant
input/feedback:

The Elgg admin user creation system has not been altered yet and will need
to be if admin created accounts are to work with Moodle.

Elgg's reset-my-password feature currently only accepts username and email
address. It should probably be changed to accept DCF IDs instead of usernames.

Moodle will allow Shibalike logins but will not currently allow you to edit 
your profile without supplying your city/town and country.

It also allows you to change your email address, username, first name, last name
and other details. 

It also has Moodle specific questions on forum tracking and email digests.

Do you want some or all of the Moodle profile editing page to be disabled?