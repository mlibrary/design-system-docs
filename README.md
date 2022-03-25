# Design System Docs Site

## Developer Set-up
Clone the repo and cd into the repo directory
```
git clone git@github.com:mlibrary/design-system-docs.git
cd design-system-docs
```

Make everything in the drupal directory writeable by `www-data` aka uid 33. 
```
sudo chgrp -R 33 drupal/*
```
build the drupal image
```
docker-compose build
```

Start it up
```
docker-compose up -d
```

Apply the configuration
```
docker-compose run --rm drupal drush config:import --source /drupal-config
```

For drupal, in a browser go to `localhost:3333`
```
user: admin
password: password
```

## Saving Drupal Config
When a change to drupal site configuration should be commited to the repository run this command:
```
docker-compose run --rm drupal drush config:export --destination /drupal-config
```
Then commit the files generated in `drupal/config/`.


