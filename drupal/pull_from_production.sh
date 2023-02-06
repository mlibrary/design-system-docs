if [ -f .env ]; then
  export $(echo $(cat .env | sed 's/#.*//g'| xargs) | envsubst)
fi

db_pod=$(kubectl get pod --context $PRODUCTION_CLUSTER_CONTEXT \
  -n $NAMESPACE -l app=$MARIADB_APP -o name)

cms_pod=$(kubectl get pod --context $PRODUCTION_CLUSTER_CONTEXT \
  -n $NAMESPACE -l app=$DRUPAL_APP -o name)

echo $cms_pod

if [[ ! ${db_pod} =~ ^pod ]]; then
  echo "❌ ERROR: Couldn't fetch pod name. Check 'PRODUCTION_CLUSTER_CONTEXT' in .env"
  exit 1
fi

echo "📥 pulling latest version of db  from production"
kubectl exec -ti -n $NAMESPACE --context $PRODUCTION_CLUSTER_CONTEXT $db_pod --\
  sh -c 'mysqldump -u$MARIADB_USER -p$MARIADB_PASSWORD $MARIADB_DATABASE 2>/dev/null' \
  > db/synced_from_production.sql

for dir in ./files/*; do
  ([[ ${dir} =~ "files/js" ]] || [[ ${dir} =~ "files/css" ]] || 
    [[ ${dir} =~ "files/php" ]] || [[ ${dir} =~ "files/README.md" ]] ) && continue
        set -- "$@" "$dir"
done

if [[ ${@} =~ ./files ]]; then
  echo "🧹 Removing non-cache files and directories from ./files"
  sudo rm -rf "$@"
fi

get_files_command='cd /var/www/html/sites/default/files && \
  tar --exclude="./js" --exclude="./php" --exclude="./css" \
  -cf - . 2>/dev/null'

echo "📥 Fetch production non-cache files and directories to ./files"
kubectl exec -n $NAMESPACE --context $PRODUCTION_CLUSTER_CONTEXT $cms_pod --\
   sh -c "$get_files_command" | tar xf - -C files
