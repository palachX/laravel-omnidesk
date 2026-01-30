# Contributing

Contributions are **welcome** and will be fully **credited**.

Please read and understand the contribution guide before creating an issue or pull request.

## Etiquette

This project is open source, and as such, the maintainers give their free time to build and maintain the source code
held within. They make the code freely available in the hope that it will be of use to other developers. It would be
extremely unfair for them to suffer abuse or anger for their hard work.

Please be considerate towards maintainers when raising issues or presenting pull requests. Let's show the
world that developers are civilized and selfless people.

It's the duty of the maintainer to ensure that all submissions to the project are of sufficient
quality to benefit the project. Many developers have different skillsets, strengths, and weaknesses. Respect the maintainer's decision, and do not be upset or abusive if your submission is not used.

## Viability

When requesting or submitting new features, first consider whether it might be useful to others. Open
source projects are used by many developers, who may have entirely different needs to your own. Think about
whether or not your feature is likely to be used by other users of the project.

## Procedure

Before filing an issue:

- Attempt to replicate the problem, to ensure that it wasn't a coincidental incident.
- Check to make sure your feature suggestion isn't already present within the project.
- Check the pull requests tab to ensure that the bug doesn't have a fix in progress.
- Check the pull requests tab to ensure that the feature isn't already in progress.

Before submitting a pull request:

- Check the codebase to ensure that your feature doesn't already exist.
- Check the pull requests to ensure that another person hasn't already submitted the feature or fix.


## For quick startup and testing when a task is available:

#### Task documentation:
https://taskfile.dev

```shell
task init
```

```shell
task prep
```

### Teams

View available commands
```shell
task -a
```

### To launch docker quickly:

```shell
docker compose -f compose.local.yaml build --build-arg UID=${BUILD_ARG_UID:-$(id -u)} --build-arg GID=${BUILD_ARG_GID:-$(id -g)}
```


### Known issues
* On a MacBook, the php image build may fail with the error `addgroup: gid '20' in use`
  To solve the problem, you need to specify a different user group id through the BUILD_ARG_GID variable, on the command line, or in the root .env file.
```shell
BUILD_ARG_GID=1000 task init
```
```dotenv
#.env

BUILD_ARG_GID=1000
```
