composer_setup() {
  composer "${2:-install}" \
    --working-dir="${1:-.}" \
    --optimize-autoloader \
    --no-cache \
    --no-interaction

  composer check-platform-reqs \
    --working-dir="${1:-.}" \
    --no-cache \
    --no-interaction
}

function php-ci-setup() {
  if [ -d "${1}/../vendor" ] && [ -z "$CI" ]
  then
    return
  fi

  "${1}/setup"

  add_to_summary "$?"
}

function check-conflicts() {
  easy-ci check-conflicts --quiet .

  add_to_summary "$?"

  add_to_try "easy-ci check-conflicts $PWD"
}

function commented-code() {
  easy-ci check-commented-code --quiet "$1"

  add_to_summary "$?"

  add_to_try "easy-ci check-commented-code "$1""
}

function file-class-name() {
  easy-ci check-file-class-name --quiet "$1"

  add_to_summary "$?"

  add_to_try "easy-ci check-file-class-name $1"
}

function multi-classes() {
  easy-ci find-multi-classes "$1"

  add_to_summary "$?"

  add_to_try "easy-ci find-multi-classes $1"

}

function coding-standars() {
  ecs --no-error-table --no-progress-bar --quiet

  add_to_summary "$?"

  add_to_try "ecs --clear-cache"
}

function unused-packages() {
  composer-unused --no-progress --quiet $@

  add_to_summary "$?"

  add_to_try "composer-unused $@"
}

function find-transitive-depndencies() {

  composer-require-checker --quiet $@

  add_to_summary "$?"

  add_to_try "composer-require-checker $@"
}

function find-magic-numbers() {
  phpmnd \
    --hint \
    --strings \
    --include-numeric-string \
    --quiet \
    $@

  add_to_summary "$?"

  add_to_try "phpmnd --hint --strings --include-numeric-string --progress $@"
}

function find-copy-pasted-code() {
  phpcpd "$1"

  add_to_summary "$?"

  add_to_try "phpcpd $1"
}

function static-analysis() {
  psalm --no-progress --no-suggestions

  add_to_summary "$?"

  add_to_try "psalm --no-cache"
}

function check-var-dump() {
  var-dump-check --laravel --exclude vendor/ .

  add_to_summary "$?"

  add_to_try "var-dump-check --laravel --exclude vendor/ $PWD"
}

function unit-tests() {
  paratest --passthru="'--no-coverage' '--no-logging' '--no-interaction'"

  add_to_summary "$?"

  add_to_try "paratest"
}

function validate-doctrine-schema() {
  if [ -z "$CI" ]
  then
    ./vendor/bin/doctrine orm:validate-schema

    add_to_summary "$?"

    add_to_try "./vendor/bin/doctrine orm:validate-schema"
  else
    ./vendor/bin/doctrine orm:validate-schema --skip-sync

    add_to_summary "$?"

    add_to_try "./vendor/bin/doctrine orm:validate-schema --skip-sync"
  fi
}

function remake-autoloader() {
  composer dump-autoload \
    --optimize \
    --no-cache \
    --no-interaction \
    --quiet
}
