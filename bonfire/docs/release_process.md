# Release Process

## Files which need to be updated

* `/application/config/constants.php`: update `BONFIRE_VERSION`
* `/bonfire/docs/changelog.md`: move the version to be released from `Under development` to `Released versions`
* `/bonfire/docs/upgrade/{version}.md`: ensure the upgrade docs are up to date
* `/bonfire/docs/_toc.ini`: ensure the upgrade docs are listed in the docs ToC
* `/composer.json`: update `version`

## GitHub

* Push updated files to dev branch
* Create a pull request or create new branch from dev branch (switch to dev branch, if necessary, then create new branch)
* Go to the releases page and "Draft a new release"
