# Easier navigation
alias ..='cd ..'
alias cd..='cd ..'
alias ...='cd ../..'
alias ....='cd ../../..'
alias -- -='cd -'

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
alias gl='git log'
alias glo='git log --color --pretty=format:"%Cred%H%Creset -%C(yellow)%d%Creset %s %Cgreen(%cr) %C(bold blue)<%an>%Creset"'
alias glol='git log --color --graph --pretty=format:"%Cred%h%Creset -%C(yellow)%d%Creset %s %Cgreen(%cr) %C(bold blue)<%an>%Creset" --abbrev-commit --'
alias gp='git push '
alias gpsu='git push --set-upstream'
alias gpr='git pull --rebase --autostash'
alias gr='git reset'
alias gs='git status '
alias gtd='git tag --delete'
alias gtdr='git tag --delete origin'

alias vi='vim'

# ag silver searcher
alias ag='ag -f -S --hidden'
