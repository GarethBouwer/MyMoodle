# Bimoo

Moodle declaration stubs, including classes, functions and globals, thanks to [php-stubs/generator](https://github.com/php-stubs/generator), helping IDEs and static analyzers.

**Note:** The stubs file may be incomplete for your usage (generated for a limited portion of stuff), and may not be updated regularly. However, contributions, including issues and pull requests, will be really welcomed.

## How to use it?

First, thanks for using this.

Simply, require it in your `composer.json`:

```
composer require --dev machitgarha/bimoo
```

Now, your IDE (or text editor) should show you the suggestions for Moodle functions and classes. Not limited to this, you can guide your static analyzer as well.

## What is included?

First things first: The stubs file is generated from [official mirror Moodle repository](https://github.com/moodle/moodle/tree/master/lib). The updates to `stubs.php` must be done and commited manually from the repository, but the whole process to do so is almost automated (using `bin/generate-stubs` executable).

To see what paths are included and what not, see `data/path-list.json` file. You can use glob patterns in it, while it is not recommended (because it leads to huge stubs file).

## Release management

To follow Moodle versions properly, and prevent from conflicts and confusions, instead of using [semantic versioning](https://semver.org), we use the following method:

Each version is in the form of `<MoodleMajor>.<MoodleMinor>.<BimooUpdate>`.

`<MoodleMajor>` and `<MoodleMinor>` are the pieces of Moodle release number a version of Bimoo is based on, as stated [here](https://docs.moodle.org/dev/Process#Stable_maintenance_cycles). The reason why the bug-fix piece of Moodle version is not included, is what its official documentation says: "Releases like 2.2.1, 2.2.2, 2.2.3 etc only include fixes based on the latest major release (2.2) and never any significant new features or database changes".

`<BimooUpdate>` is the state of the current library for that specific Moodle version. In other words, considering a particular minor release of Moodle, it increases whenever an update is made to the library (i.e. the stubs file).

For example, `3.11.19` means the twentieth series of changes supporting Moodle 3.11.*. Pay attention, it has nothing to do with Moodle 3.11.19 (which, perhaps, will never be released).

Every Moodle version older than latest supported is followed in a separate branch. The branches are named as their Moodle version they follow, e.g. `3.9`. Ideally, `master` branch follows the latest Moodle version.

**Note:** There is no guarantee to support all versions Moodle currently supports. However, support is pretty much welcomed from the community (maybe you?).

## To-Do

-   Use GitHub Actions to automate the process. The workflow would be, cloning the repository of Moodle, switching to the latest stable version, run the generator for a list of predefined paths and files, commit the changes and push it back.

## Contributions

Feel free to do so.

### Re-generating stubs file

First, add your files and patterns to `data/path-list.json` file. Then, issue the following command for the `stubs.php` to be re-generated automatically:

```
./bin/generate-stubs <moodle-root-dir>
```

`<moodle-root-dir>` is the path to either a Moodle installation, or (a clone of) the official repository (see `--help` for more information).

That's it! Commit the changes, make a PR, and we would all be happy!

## License

Licensed under [GPL 3.0](./LICENSE.md).
