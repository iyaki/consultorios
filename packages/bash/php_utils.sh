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
