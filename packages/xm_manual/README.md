# XIMA TYPO3 Manual Extension

This extension provides a new page type for generating a editor manual that can be viewed in the backend or downloaded as PDF.

## Installation

1. Install via composer
2. Create a new page in the page tree
   1. Select doctype "Manual page"
   2. Check "Use as Root Page"
   3. Include static PageTS template
3. Create new TypoScript template
   1. Include static TypoScript template


## Export

To export the pagetree of the manual, you could use the following command:

```
typo3cms impexp:export --type t3d_compressed --levels 999 --table _ALL --include-related --include-static sys_file_storage _ALL --pid <UID>
```

## Import

```
typo3cms impexp:import --update-records  fileadmin/user_upload/_temp_/importexport/<FILENAME>.t3d
```
