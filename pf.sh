#!/bin/bash
# Helper to run phpunit with an optional filter
./vendor/bin/phpunit --filter ${1:-.}
