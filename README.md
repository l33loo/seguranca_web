# Activity Booking App

## Development Setup

Rename the `/.env/mysql.dev.env.sample` file to `/.env/mysql.dev.env`, and add in the missing MySQL secrets.

Do the same with `/.env/app.dev.env.sample`, and add an encryption key made with the 'AES-256-CBC' algorithm.

`./compose-development.sh`

## Production Setup

`./compose-production.sh`

The production setup only works on Linux machines, and required storing secrets in [password-store](https://www.passwordstore.org/). More on this below.

Inspired from [this](https://github.com/mashiox/dotfiles/blob/master/docker/secrets.md).

### Pass

You will need to have [password-store](https://www.passwordstore.org/) installed to run the project in production mode. The production secrets will need to be stored in an entry as a file (`pass insert -m {entry-name}`). You can use the content of `/.env/mysql.dev.env` as a template.

Adapt the script to use the names of your `pass` entry and directories/files.

## Troubleshooting

### Database

To refresh the database to it's original settings, do the following:

- Delete the `/mysql/data/` directory
- Run `docker compose down`
- Run the `./compose-[...].sh` script of your choice

## Design Details

We used [this tutorial](https://github.com/PatrickLouys/no-framework-tutorial) to help us implement a MVC-type design for this project.

## Image credits

[Image from Pexels](https://www.pexels.com/photo/photograph-of-a-person-surfing-1494720/).
