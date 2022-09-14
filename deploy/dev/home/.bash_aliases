# shellcheck shell=bash

# Easier navigation
alias ..='cd ..'
alias cd..='cd ..'
alias ...='cd ../..'
alias ....='cd ../../..'
alias -- -='cd -'

# Files manipulation
alias mv='mv -v'
alias rm='rm -I -v'
alias cp='cp -v'

# clear - shortcut: Ctrl + l
alias cl='clear'

# ls
alias l='ls -l -h '
alias ll='ls -l -A -h '

# git
alias ga='git add '
alias gb='git branch '
alias gc='git commit -m '
alias gca='git commit --amend --no-edit'
alias gco='git checkout '
alias gd='git diff '
alias gl='git log --show-signature'
alias glo='git log --color --pretty=format:"%Cred%H%Creset - %C(blue)(%G? %GT)%Creset%C(yellow)%d%Creset %s %Cgreen(%cr) %C(bold blue)<%an>%Creset"'
alias glol='git log --color --graph --pretty=format:"%Cred%h%Creset - %C(blue)(%G? %GT)%Creset%C(yellow)%d%Creset %s %Cgreen(%cr) %C(bold blue)<%an>%Creset" --abbrev-commit --'
alias gp='git push '
alias gpsu='git push --set-upstream '
alias gpr='git pull --rebase --autostash '
alias gr='git reset '
alias gs='git status '
alias gtd='git tag --delete '
alias gtdr='git tag --delete origin '

# Development utilities
alias fix='rector && ecs --fix'

# bat cat clone with syntax highlighting - https://github.com/sharkdp/bat
alias bat='batcat'

alias vi='vim'

# Print each PATH entry on a separate line
alias path='echo -e ${PATH//:/\\n}'
