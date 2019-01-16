# MyOwnMusicPlayer

Simple MP3 music player via docker alpine images (_nginx, php, ftp_)

## Requirements

- Docker
- Docker-Compose

## Create HTTP auth users

```shell
# create file and add first user
$ htpasswd -c ~/Projects/MP3/.htpasswd testuser_a

# add other user
$ htpasswd ~/Projects/MP3/.htpasswd testuser_b
```
