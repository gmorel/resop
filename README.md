# crf-resop

[![pipeline status](https://gitlab.com/mRoca/resop/badges/master/pipeline.svg)](https://gitlab.com/mRoca/resop/commits/master)
[![coverage report](https://gitlab.com/mRoca/resop/badges/master/coverage.svg)](https://gitlab.com/mRoca/resop/commits/master)

## Install

### Requirements

* git
* make
* docker >= 18.06
* docker-compose >= 1.23

### Commands

#### Install

```bash
git clone git@github.com:crf-devs/resop.git && cd resop

# If you are on linux, just run:
make

# On MacOS, run:
make pre-configure
make configure
# Now, update the docker-compose.override.yml file to match with your host
make all
```

Then, go to [http://resop.vcap.me:7500/](http://resop.vcap.me:7500/).

If you want to run a symfony or a php command: `bin/tools <command>`, example: `bin/tools bin/console`

#### Run : after a first install

```bash
make start
```

### Access

The project is using a Traefik proxy in order to allow access to all the HTTP services of the project. This service is listening on the 7500 port of the host.
The `*.vcap.me` domain names are binded on localhost. In order to use them offline, you only have to add a
`127.0.0.1 adminer.vcap.me resop.vcap.me traefik.vcap.me` line on your `/etc/hosts` file.

#### API stack
- [http://resop.vcap.me:7500](http://resop.vcap.me:7500)
- [http://adminer.vcap.me:7500](http://adminer.vcap.me:7500)
- [http://traefik.vcap.me:7500](http://traefik.vcap.me:7500)

Caution: the traefik proxy will only serve healthy containers. The api container can be unaccessible before the first healthcheck (5s).

### HTTPS

The nginx container is available over HTTPS. This url must be used in order to use Facebook, Gmaps, camera...

- [https://resop.vcap.me:7543](https://resop.vcap.me:7543) ou [https://resop.vcap.me:7583](https://resop.vcap.me:7583)

## PHP

### Tools & commands

As the php-fpm docker container doesn't contain any dev tool as composer, all dev commands must be run on the `tools` container. For example:

```bash
bin/tools composer
bin/tools bin/console cache:clear
bin/tools # to open a shell on the tools container
```

### Blackfire

In order to profile the php app with [Blackfire](https://blackfire.io/), you need to have a Blackfire account, then:
- Add your [credentials](https://blackfire.io/my/settings/credentials) in the `.env` file
- Uncomment the `blackfire` service in the `docker-compose.override.yml` file
- Uncomment the blackfire env var for the `backend_php` service in the `docker-compose.override.yml` file
- `docker-compose up -d --force-recreate backend_php blackfire`
- That's it, you can [profile](https://blackfire.io/docs/cookbooks/profiling-http) the app!

### Tests

```bash
make test
make test-cs
make test-advanced
make test-unit
make test-unit-coverage
```

## Node

A node container is available in order to run `yarn` commands for `webpack encore`:

```bash
bin/node-tools yarn encore dev
```
