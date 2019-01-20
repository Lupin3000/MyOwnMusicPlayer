# MyOwnMusicPlayer

Simple MP3 player via docker alpine images (_nginx, php, ftp_).

## Requirements:

- Docker
- Docker-Compose

## HTTP auth users:

```shell
# create new file and add first user
$ htpasswd -c ./lib/.htpasswd testuser_a

# add other user to existing file
$ htpasswd ./lib/.htpasswd testuser_b
```
## Webserver port:

The webserver port configuration is done via file: `.env`. You need to change variables before you startup the environment.

## FTP server configuration:

The FTP configuration is done via via file: `.env`. You need to change variables before you startup the environment.

## Run application:

Do all necessary configurations and start environment via docker-compose. Add some MP3 files (_via FTP or SCP_) into folder `mp3`. Open browser and call you URL.

```shell
# validate YAML (optional)
$ docker-compose config

# run environment
$ docker-compose run -d

# open browser (example localhost with port 8080)
$ open http://localhost:8080

# stop environment
$ docker-compose down
```
