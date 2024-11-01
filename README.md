# Activity Booking App

## Development Setup

Rename the `/dev.env.sample` file to `/dev.env`, and add in the missing MySQL secrets.

`docker compose up`

## Production Setup

`./compose-production.sh`

The production setup only works on Linux machines.

Inspired from [this](https://github.com/mashiox/dotfiles/blob/master/docker/secrets.md).

### Pass

You will need to have [password-store](https://www.passwordstore.org/) installed to run the project in production mode. The production secrets will need to be stored in an entry as a file (`pass insert -m {entry-name}`). You can use the content of `/dev.env` as a template.

Adapt the script to use the names of your `pass` entry and directories/files.

## Design Details

We used [this tutorial](https://github.com/PatrickLouys/no-framework-tutorial) to help us implement a MVC-type design for this project.
