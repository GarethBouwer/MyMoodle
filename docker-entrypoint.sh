#!/bin/bash
set -e

# Disable all MPM modules to avoid conflicts (Railway-specific issue)
a2dismod mpm_event 2>/dev/null || true
a2dismod mpm_worker 2>/dev/null || true
a2dismod mpm_prefork 2>/dev/null || true

# Enable only prefork (required for PHP + mod_php in Moodle)
a2enmod mpm_prefork

# Moodle needs rewrite
a2enmod rewrite

# Start Apache in the foreground (required in Docker)
exec apache2-foreground
