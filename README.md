# [WIP] OpenEmr module - Todo list

[![CD/CI](https://github.com/MedicalMundi/oe-module-todo-list/actions/workflows/cd-ci.yaml/badge.svg)](https://github.com/MedicalMundi/oe-module-todo-list/actions/workflows/cd-ci.yaml)

## Getting started

## Development


### Test

Run all test
```bash
> ./vendor/bin/phpunit
```
Run only unit test
```bash
> ./vendor/bin/phpunit --testsuite=unit
// shortcut
> composer tu
```


### Static analysis tool

Check static analysis
```bash
> ./vendor/bin/psalm
// shortcut
> composer sa
```

### Code style

Check coding style
```bash
> ./vendor/bin/ecs
// shortcut
> composer cs
```

Fix coding style issues
```bash
> ./vendor/bin/ecs --fix
// shortcut
> composer cs:fix
```

