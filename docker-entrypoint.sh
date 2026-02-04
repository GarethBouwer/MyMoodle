#!/bin/bash
set -e

# Safety: ensure only prefork is enabled before Apache starts
a2dismod mpm_event mpm_worker 2>/dev/null || true
a2enmod mpm_prefork rewrite >/dev/null

# Now start Apache in the foreground (same as php:apache default)
exec apache2-foreground
