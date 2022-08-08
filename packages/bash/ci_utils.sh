#!/usr/bin/env bash

EXIT_STATUS=0

function check_exit_status() {
  if [ $EXIT_STATUS == 0 ]
  then
    EXIT_STATUS=$1
  fi
}

function print_separator() {
  local TITLE="***"

  if [ ! -z "${1}" ]
  then
    TITLE="* ${1} *"
  fi

  echo "

*---------------------*-**--${TITLE}--**-*---------------------*

"
}

function ci-setup() {
  print_separator "${FUNCNAME[0]}"

  "${1}/setup"

  check_exit_status $?
}

function check-conflicts() {
  print_separator "${FUNCNAME[0]}"

  easy-ci check-conflicts .

  check_exit_status $?
}

function commented-code() {
  print_separator "${FUNCNAME[0]}"

  easy-ci check-commented-code "$1"

  check_exit_status $?
}

function file-class-name() {
  print_separator "${FUNCNAME[0]}"

  easy-ci check-file-class-name "$1"

  check_exit_status $?
}

function multi-classes() {
  print_separator "${FUNCNAME[0]}"

  easy-ci find-multi-classes "$1"

  check_exit_status $?
}

function coding-standars() {
  print_separator "${FUNCNAME[0]}"

  ecs --clear-cache

  check_exit_status $?
}

function unused-packages() {
  print_separator "${FUNCNAME[0]}"

  composer-unused $@

  check_exit_status $?
}

function find-transitive-depndencies() {
  print_separator "${FUNCNAME[0]}"

  if [ -z "$1" ]
  then
    composer-require-checker
  else
    composer-require-checker --config-file="$1"
  fi


  check_exit_status $?
}

function find-magic-numbers() {
  print_separator "${FUNCNAME[0]}"

  phpmnd \
    --hint \
    --strings \
    --include-numeric-string \
    $@

  check_exit_status $?
}

function find-copy-pasted-code() {
  print_separator "${FUNCNAME[0]}"

  phpcpd "$1"

  check_exit_status $?
}

function static-analysis() {
  print_separator "${FUNCNAME[0]}"

  psalm \
    --threads=4 \
    --no-cache

  check_exit_status $?
}

function remake-autoloader() {
  composer dump-autoload \
    --optimize \
    --no-cache \
    --no-interaction
}
