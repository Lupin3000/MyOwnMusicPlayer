# MyOwnMusicPlayer

Simple MP3 music player via docker alpine images (_nginx, php, ftp_)

## Requirements

- Docker
- Docker-Compose

## Create HTTP auth users

```shell
# create new file and add first user
$ htpasswd -c ./lib/.htpasswd testuser_a

# add other user to existing file
$ htpasswd ./lib/.htpasswd testuser_b
```
## Change server port

The port configuration is done via docker-compose.yml, here change following value:

```yaml
    web:
      ports:
        - "80:80"
```

## Change FTP configuration

The FTP configuration is done via docker-compose.yml, here change following values:

```yaml
    environment:
      - FTP_USER=user
      - FTP_PASS=test123
      - PASV_ADDRESS=5.6.7.8
      - PASV_MIN=21100
      - PASV_MAX=21110
```

## Run application

```shell
# run application
$ docker-compose run -d
```
