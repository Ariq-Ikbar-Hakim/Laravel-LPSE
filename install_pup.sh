#!/bin/bash
export PATH=/home/ariq/.nvm/versions/node/v20.20.2/bin:$PATH
export PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=false
export PUPPETEER_CACHE_DIR=/home/ariq/.cache/puppeteer
cd /home/ariq/web_lpse
npx puppeteer browsers install chrome-headless-shell
