#!/bin/sh
# Health check script for PHP-FPM

# Check if PHP-FPM is responding
SCRIPT_NAME=/ping \
SCRIPT_FILENAME=/ping \
REQUEST_METHOD=GET \
cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1

exit 0
