# shellcheck shell=bash

if [ -z "$(which shellcheck)" ]
then
  function shellcheck() {
    return 0
  }
fi

function validate-shell-scripts() {
  shellcheck --exclude=SC1091,SC1090 "$@"

  add_to_summary "$?"

  add_to_try "shellcheck --exclude=SC1091 $*"
}
