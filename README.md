# site-template

Original Developer: **Your Name**

### 3.1.0
- Functions File
- Added helper file
- Adjusted browser_class(); 
- Adjusted the_slug();

### 3.0.0
- Everything that's been done since 2.1.0
- Remove MSSmartTagsPreventParsing meta

### 2.1.0
- Update jQuery to 3.1.0
- Remove jQuery source map
- Add search.php to theme
- Update modernizr to 3.3.1

### 2.0.0
- Moved files into _build and _wp folders by default
- Added debug condition to wp-config
- Removed emoji support from functions
- Removed style-editor and function from wp theme folder
- Removed Cycle from default jQuery plugins
- Updated Modernizr to 3.2.0. Added more features

---

### ACF Fields Practices
1. Make ACF changes in staging GUI
2. Copy PHP into staging `functions-acf-fields.php`
3. Download `acf-export-YYYY-MM-DD.json` to repo, leave in Github and do not upload
4. Upload staging `functions-acf-fields.php` to live

### Versioning
Jackrabbit will be utilizing a three-point numbering system.
- Point 1 (1.#.#) is used for site launches, phases, and site redesigns.
- Point 2 (#.1.#) is used for major edits, such as the finalizing of new templates, adding new sections or modules, etc.
- Point 3 (#.#.1) is used for minor structure/style/script edits, such as SDS edits, bug fixes, etc.
Each point can go past a single digit of iterations (for instance, the 11th major edit without a site launch would be 0.11.0, and not 1.1.0).

### Emojis
Here's a list of useful emojis for tracking changes (stripped from [Atom's commit messages](https://github.com/atom/atom/blob/master/CONTRIBUTING.md#git-commit-messages)):
- :bug: `:bug:` when fixing a bug
- :art: `:art:` when improving the format/structure of the code
- :fire: `:fire:` when removing code or files
- :lock: `:lock:` when dealing with security
