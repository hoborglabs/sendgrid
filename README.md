# Sendgrid PHAR cli

Simple, single.phar [SendGrid](https://sendgrid.com/) cli.

You have to create `~/.sendgrid/config.ini` with your SendGrid username and password.

```INI
username = my_username
password = my_password
```




## Commands

### Send

```BASH
./sendgrid.phar send \
  --to developers@test.com \
  --body @/path/to/emailBody \
  --subject "test email"
```

Combined with some git magic

```BASH
# Generate list of committers
git log origin/master..origin/my-branch \
  --format=%ae \
  | sort | uniq > committers-emails

# Generate simple change log
echo "Changes: \
" `git log --no-merges origin/master..origin/my-branch --format=" o  %s (%an)"` > changes

./sendgrid.phar send --to '@committers-emails' --body '@changes --subject "Release Notes"'
```
