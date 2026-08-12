#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

echo "🔒 Setting up local SSL certificates..."

# Check if mkcert is installed
if ! command -v mkcert &> /dev/null; then
    echo "❌ Error: mkcert is not installed."
    echo "Please install it first (e.g., 'sudo apt install libnss3-tools mkcert' or 'brew install mkcert')"
    exit 1
fi

# Ensure the certs directory exists
mkdir -p docker/certs

# Install the local CA and generate the certificates
mkcert -install
mkcert -key-file docker/certs/my-app.local.key -cert-file docker/certs/my-app.local.crt "my-app.local"

echo "✅ Local SSL certificates generated successfully in docker/certs/"
