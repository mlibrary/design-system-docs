if [ -f .env ]; then
  export $(echo $(cat .env | sed 's/#.*//g'| xargs) | envsubst)
fi

kubectl exec -ti -n $NAMESPACE -s $PRODUCTION_CLUSTER \
  $(kubectl get pod -n $NAMESPACE -l app=$MARIADB_APP -o name) -- \
  sh -c 'mysqldump -u$MARIADB_USER -p$MARIADB_PASSWORD $MARIADB_DATABASE 2>/dev/null' \
  > db/synced_from_production.sql

sudo rm -r files/*

kubectl exec -n $NAMESPACE -s $PRODUCTION_CLUSTER \
  $(kubectl get pod -n $NAMESPACE -l app=$DRUPAL_APP -o name) -- sh -c 'cd /var/www/html/sites/default/files && tar --exclude="./js" --exclude="./php" --exclude="./css"  -cf - . 2>/dev/null' | tar xf - -C files

docker-compose down -v
