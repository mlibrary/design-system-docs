if [ -f ".env" ]; then
  echo "🌎 .env exists. Leaving alone"
else
  echo "🌎 .env does not exist. Copying .env-example to .env"
  cp .env-example .env
fi

echo "🚢 Build docker images"
docker compose build

echo "📦 Installing node modules"
docker compose run --rm web npm install
