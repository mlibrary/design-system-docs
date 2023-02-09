INTRODUCTION
------------

The Build Hooks module is meant to be used in cases where Drupal is used as a content repository for one or more
frontend sites.
The typical use case is for Gatsby sites powered by the
[Gatsby Drupal source plugin](https://www.gatsbyjs.org/packages/gatsby-source-drupal/):
in this type of setup Drupal holds the content and a separate Gatsby site is built using the Drupal installation
as the content source.

This module mainly provides privileged users with a UI to:
 * Trigger deployments of the connected static site(s) at will, or automatically via cron or when an entity is modified.
 * View a log of the content that has been created, modified or deleted since the last deployment, by environment.

 * For a full description of the module, visit the project page:
   https://www.drupal.org/project/build_hooks

 * To submit bug reports and feature suggestions, or to track changes:
   https://www.drupal.org/project/issues/build_hooks

REQUIREMENTS
------------

This module requires no additional contrib module, but is dependent on these core modules:

 * Views
 * Dblog

INSTALLATION AND CONFIGURATION
------------------------------

1. Install as you would normally install a contributed Drupal module. Visit:
   https://www.drupal.org/documentation/install/modules-themes/modules-7
   for further information.

2. Visit the path `/admin/config/build_hooks/settings` and select the types of entities that you want to include in the
log. For example: content (nodes), media entities, etc. As a general rule, it makes sense to select here the entities
that are used in the static front end site. (For example, logging changes to user entities is probably not required,
but this varies depending on your setup).
3. (optional) If you want to connect to CircleCI environments, enable the `build_hooks_circleci` module, and insert
your CircleCI api key at the page: `/admin/config/build_hooks_circleci/buildhookscircleciconfig`
(PLEASE NOTE:) in context where the site configuration is committed to git, consider instead getting that value from
environment variables so that you don't commit confidential information to git, for example adding something like this
 to your `settings.php`:
    ```
    $config['build_hooks_circleci.circleCiConfig']['circleci_api_key'] = getenv('YOUR_ENV_VAR_HERE');
    ```
4. Go to `/admin/structure/frontend_environment` and add one or more frontend environments, filling the required data
 for each.
You can choose the type of environment, depending on the plugin types defined in your site.
Also, you can decide whether the environment should be
deployed automatically on cron or entity updates, or manually only.
5. The toolbar now should show your environments
6. Edit, delete or create entities of the types you have selected in step 1:
you should see the counter next to each environment in the toolbar increment.
7. When ready to trigger a deployment, click on the toolbar item for the environment,
and you will be taken to a page where you will see the changelog and a button to trigger the deployment.

ENVIRONMENT TYPES
------------

The `build_hooks` base module comes with a "Generic" environment type.
The `build_hooks_circleci` submodule provides an environment type for Circle CI, and the `build_hooks_netlify` one
 for netlify.

Environments are defined as configuration entities supported by plugins: check the `build_hooks_circleci` for an example
 of how you can create your own frontend environment type.

MAINTAINERS
-----------

Current maintainers:
 * Mario Vercellotti (2.x version) (vermario) - https://www.drupal.org/user/121062
 * Jesus Manuel Olivas (jmolivas) - https://www.drupal.org/user/678770
