#!/bin/bash

# Create a new directory and enter it
function md() {
	mkdir -p "$@" && cd "$@"
}

# ignores "permission denied" outputs
function ignore_permision_denied() {
    grep -v "$PERMISSION_DENIED_MESSAGE"
}

# find shorthand
function f() {
	find . -name "$1" 2>&1 | ignore_permision_denied
}

function csvpreview() {
	sed 's/,,/, ,/g;s/,,/, ,/g' "$@" | column -s, -t | less -#2 -N -S
}

function get_repository_path() {
	echo "$( git rev-parse --show-toplevel )"
}

function php_deploy() {
	if [ -f "$( get_repository_path )/composer.json" ]
	then
		composer install
	fi
}

function node_deploy() {
	if [ -f "$( get_repository_path )/package.json" ]
	then
		npm ci
	fi
}

function project_deploy() {
	php_deploy
	node_deploy
}

function gcr() {
	REMOTE='origin'

    if [ -z "$1" ]
    then
        echo "gcr creates a new branch based on an ${REMOTE} branch. If the branch already exists it is reseted.
Usage:
    gcr <${REMOTE} base branch> <name-for-new-branch>
Example:
    Executing \"gcr development new-feature\" will result in the creation of the branch development_new-feature tracking ${REMOTE}/development
"
        return
    fi

	git fetch -t -P ${REMOTE} &&

	if [ -z "$2" ]
	then
		BRANCH_NAME="${1}"
	else
		BRANCH_NAME="${1}_${2}"
	fi &&

	git checkout -t ${REMOTE}/${1} -B ${BRANCH_NAME} &&

	project_deploy
}

# gcpr creates a new branch named "pr-<pull request id>"" based on an github pull request and changes the branch.
# Usage: gcpr <pull request id>
function gcpr() {
	REMOTE='origin'

	git fetch -f ${REMOTE} pull/${1}/head:pr-${1} && git checkout pr-${1} &&

	project_deploy
}

# history | grep
# Use together with !<number of history line> to re-execute a command
# The use case is similar to the usage of the shortcut "Ctrl+r"
function hg() {
    history | grep --color=auto $@
}

# du -sh (Disk Usage Summarizing and Human readable)
# Arguemnts:
# 1. Extra arguments (flags and path). If none is provided path * is used
function dush() {
    if [ -z "$1" ]
    then
        du -sh * 2>&1 | ignore_permision_denied
    else
        du -sh "$@" 2>&1 | ignore_permision_denied
    fi
}

# dush | sort -hr | head -n<listed-rows> (Disk Usage Summarizing and Human readable), sorted reverse by human readable size and filter first <listed-rows>
# Arguemnts:
# 1. Number of rows to list. Default: 10
function dush-sort() {
    if [ -z "$1" ]
    then
        dush | sort -hr | head -n10
    else
        dush | sort -hr | head -n${1}
    fi
}


