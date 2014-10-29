# CodeIgniter Spark

Spark is a way to pull down packages automatically

    $ tools/spark install -v1.2 gravatar_helper

And then you can load the package like so:

    $this->load->spark('gravatar_helper/1.2');
    echo Gravatar_helper::from_email('john.crepezzi@gmail.com');

---

## Adding a package

    $ tools/spark install -v1.2 gravatar
    $ tools/spark install gravatar # most recent version

## Removing a package

    $ tools/spark remove -v1.2 gravatar  # remove a specific version
    $ tools/spark remove gravatar -f  # remove all

## Reinstalling a package

    $ tools/spark reinstall -v1.2 gravatar  # reinstall a specific version
    $ tools/spark reinstall gravatar -f  # remove all versions and install latest

## Search for a package

    $ tools/spark search gravatar

## List installed packages

    $ tools/spark list

## Get Help

    $ tools/spark help

---

## Install

Go to your favorite CI project, and run (must have CURL installed):

    $ php -r "$(curl -fsSL http://www.getsparks.org/static/install.php)"
