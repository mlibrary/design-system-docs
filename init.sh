echo "🚢 Build docker images"
docker-compose build

echo "📦 Installing node modules"
docker-compose run --rm web npm install
