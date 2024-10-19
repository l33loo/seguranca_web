# Activity Booking App

## Development Setup

`docker compose up`

The MySQL secrets for the development environment are included in `mysql/mysql.dev.env`. (**Note**: Normally, this file would not be committed in git, but it is included to make this class project easier to run.)

Docker Compose is configured to use `mysql/mysql.dev.env` to override the production secrets. See the `env_file` property of the `mysql` service in the `compose.yaml` file for more details.

## Production Setup

`./compose-production.sh`

The production setup only works on Linux machines.

**Make sure to delete `mysql/mysql.dev.env` beforehand.**

Inspired from (this)[https://github.com/mashiox/dotfiles/blob/master/docker/secrets.md].

### Pass

You will need to have [password-manager](https://www.passwordstore.org/) installed to run the project in production mode. The MySQL production secrets will need to be stored in an entry as a file (`pass insert -m {entry-name}`). You can use the content of `mysql/mysql.dev.env` as a template.

Adapt the script to use the names of your `pass` entry and directories/files.

## Design Details

We used (this tutorial)[https://github.com/l33loo/no-framework-tutorial] to help us implement a MVC-type design for this project.
