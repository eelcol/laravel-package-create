Please post here your readme file.

- Add the following to the core's composer.json:

"autoload": {
        "psr-4": {
            "#NAMESPACE1#\\#NAMESPACE2#\\": "packages/#TOPLEVELFOLDER#/#SUBLEVELFOLDER#/src/"
        }
    },

- run composer update or composer dump-autoload 