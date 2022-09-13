# shellcheck shell=bash

EXIT_STATUS=0
function check_exit_status() {
  if [ "$EXIT_STATUS" == "0" ]
  then
    EXIT_STATUS="$1"
  fi
}

function print_separator() {
  local TITLE="***"

  if [ -n "${1}" ]
  then
    TITLE="* ${1} *"
  fi

  echo "

*---------------------*-**--${TITLE}--**-*---------------------*

"
}

function variable_keys() {
  eval "echo \"\${!${1}[@]}\""
}

function variable_values() {
  eval "echo \"\${${1}[@]}\""
}

function variable_value() {
  eval "echo \"\${${1}[\"${2}\"]}\""
}

function get_shared_variable_path() {
  local SHARED_VARIABLES_BASEPATH="/dev/shm"
  echo "${SHARED_VARIABLES_BASEPATH}/${NAMESPACE}_${1}"
}

function initialize_shared_variable() {
  SHARED_VARIABLE_PATH="$(get_shared_variable_path "${1}")"
  if [ -f "$SHARED_VARIABLE_PATH" ]
  then
    rm -f "$SHARED_VARIABLE_PATH"
  fi

  declare -A "$1"
  declare -p "$1" > "$SHARED_VARIABLE_PATH"
}

# Parameters:
# 1. Name of the array variable to save
# 2. Key to add to the array variable
# 3. Value to add to the array variable
function add_to_shared_variable() {
  SHARED_VARIABLE_PATH="$(get_shared_variable_path "${1}")"
  # shellcheck source=/dev/null
  source "$SHARED_VARIABLE_PATH"

  eval "${1}[\"${2}\"]=\"$3\""

  declare -p "$1" > "$SHARED_VARIABLE_PATH"
}

SUMMARY_VARNAME="SUMMARY"
initialize_shared_variable "$SUMMARY_VARNAME"
function add_to_summary() {
  add_to_shared_variable "$SUMMARY_VARNAME" "${FUNCNAME[1]}" "$1"
}

ON_ERROR_TRY_VARNAME="ON_ERROR_TRY"
initialize_shared_variable "$ON_ERROR_TRY_VARNAME"
function add_to_try() {
  add_to_shared_variable "$ON_ERROR_TRY_VARNAME" "${FUNCNAME[1]}" "$1"
}

function show_summary() {
  local RED
  RED="$(tput setaf 1)"
  local GREEN
  GREEN="$(tput setaf 2)"
  local NORMAL
  NORMAL="$(tput sgr0)"

  # shellcheck source=/dev/null
  source "$(get_shared_variable_path "$SUMMARY_VARNAME")"
  # shellcheck source=/dev/null
  source "$(get_shared_variable_path "$ON_ERROR_TRY_VARNAME")"

  for validation in $(variable_keys "${SUMMARY_VARNAME}")
  do
    local STATUS
    STATUS="$(variable_value "$SUMMARY_VARNAME" "$validation")";
    check_exit_status "$STATUS"
    if [ "$STATUS" == 0 ]
    then
      printf "%s - %sOK%s\n" "$validation" "$GREEN" "$NORMAL"
    else
      printf "%s - %sFAILED%s - Try: %s\n" "$validation" "$RED" "$NORMAL" "$(variable_value "$ON_ERROR_TRY_VARNAME" "$validation")"
    fi
  done
}
