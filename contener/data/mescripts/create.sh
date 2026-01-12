#!/usr/bin/env bash
set -euo pipefail

podman login docker.io && podman compose up -d --build

echo "OK: containers up. Next: ./push.sh"
