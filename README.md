# Sendgrid PHAR cli

Simple, single.phar SendGrid cli.

You have to create `~/.sendgrid/config.ini` with your SendGrid username and password.

```INI
username = my_username
password = my_password
```




## Commands

### Send

```BASH
./sendgrid.phar send -t developers@test.com -f /path/to/emailBody
```
