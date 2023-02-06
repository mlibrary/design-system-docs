if [ -f .env ]; then
  export $(echo $(cat .env | sed 's/#.*//g'| xargs) | envsubst)
fi

./pull_from_production.sh

db_pod=$(kubectl get pod --context $WORKSHOP_CLUSTER_CONTEXT \
  -n $WORKSHOP_NAMESPACE -l app=$WORKSHOP_MARIADB_APP -o name)
cms_pod=$(kubectl get pod --context $WORKSHOP_CLUSTER_CONTEXT \
  -n $WORKSHOP_NAMESPACE -l app=$WORKSHOP_DRUPAL_APP -o name)

if [[ ! ${db_pod} =~ ^pod ]]; then
  echo "❌ ERROR: Couldn't fetch pod name. Check WORKSHOP envars in .env"
  exit 1
fi

# Get rid of the leading 'pod/' string
db_pod=${db_pod#pod/}
cms_pod=${cms_pod#pod/}

echo "📃📃 Copying production db dump to the workshop"
kubectl --context $WORKSHOP_CLUSTER_CONTEXT -n $WORKSHOP_NAMESPACE\
  cp ./db/synced_from_production.sql $db_pod:/dump.sql

echo "💾 Loading the production db in the workshop"
kubectl exec --context $WORKSHOP_CLUSTER_CONTEXT \
  -n $WORKSHOP_NAMESPACE $db_pod --\
  sh -c 'mysql -u$MARIADB_USER -p$MARIADB_PASSWORD $MARIADB_DATABASE < /dump.sql'

echo "📃📃 Copying production non-cache files and directories to workshop"
for dir in ./files/*; do
  ([[ ${dir} =~ "files/js" ]] || [[ ${dir} =~ "files/css" ]] || 
    [[ ${dir} =~ "files/php" ]] || [[ ${dir} =~ "files/README.md" ]] ) && continue
        set -- "$@" "$dir"
done

echo $@

tar cf - $@ | kubectl exec --context $WORKSHOP_CLUSTER_CONTEXT \
  -n $WORKSHOP_NAMESPACE -i $cms_pod --\
  tar xf - -C /var/www/html/sites/default

echo "🧹 Refreshing the cache"
kubectl exec --context $WORKSHOP_CLUSTER_CONTEXT \
  -n $WORKSHOP_NAMESPACE $cms_pod --\
  drush cr
