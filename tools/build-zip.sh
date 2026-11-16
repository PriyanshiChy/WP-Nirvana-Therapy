#!/usr/bin/env bash
# Builds dist/nirvana-therapy.zip for Appearance → Themes → Add New → Upload.
# Run with: npm run zip
set -euo pipefail

cd "$(dirname "$0")/.."
npm run build

slug="nirvana-therapy"
stage="$(mktemp -d)"
trap 'rm -rf "$stage"' EXIT

mkdir -p "$stage/$slug" dist
rsync -a \
  --exclude '.git' --exclude '.gitignore' --exclude '.DS_Store' --exclude '.superpowers' \
  --exclude 'node_modules' --exclude 'tools' --exclude 'docs' --exclude 'dist' \
  --exclude 'entry.css' --exclude 'package.json' --exclude 'package-lock.json' \
  ./ "$stage/$slug/"

rm -f "dist/$slug.zip"
(cd "$stage" && zip -qr -X "$OLDPWD/dist/$slug.zip" "$slug")
echo "Built dist/$slug.zip ($(du -h "dist/$slug.zip" | cut -f1))"
