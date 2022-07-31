EXIT_STATUS=0

function check_exit_status() {
  if [ $EXIT_STATUS == 0 ]
  then
    EXIT_STATUS=$1
  fi
}

function print_separator() {
  echo '

*---------------------*-**--***--**-*---------------------*

'
}
