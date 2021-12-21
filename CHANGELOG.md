CHANGELOG
=========

4.x
---
 * Add alias `UserMailerInterface` and `UserManagerInterface`  to allowing services `UserMailer` and `UserManager`to be overriden.
 * Add `template` options for Menu and Breadcrumb
 * Add `Offcanvas`
 * Fix `UmbrellaCollectionType` : missing prototype if allow_add was false, invalid header if entry_type was not compound. Replace `show_head` by `headless` option.
 * Prepare doctrine XML mapping on bundle :
   * Remove `TimestampTrait`, `OrderTrait`, `ActiveTrait`
   * Use `Gedmo\Timestambable` to handle timestamp on `User`
   * Remove Doctrine trait on Base entity

4.3
---
* Rework `Menu` component to use context options on build and render.

4.2
---
* Remove `HTMLUtils`.

4.1
---

4.0
---

* Change minimun Symfony requirments to `Symfony` version `5.4`
* Remove `Hyper` theme, use `AdminKit` theme (Free version with MIT licence)
* Remove js library `select2.js`, replaced by `Tom Select`.
* `Choice2Type` replaced by `UmbrellaChoiceType` (same behaviour)
* `Entity2Type` replaced by `UmbrellaEntityType` (same behaviour)
* Add ColumnType `RadioColumnType` (same as `CheckboxColumnType` but with radio button)
* Rewrite some javascript components using `JQuery` on vanilla Js
* Fix all deprections on `Symfony` 5.4 (i.e. type method returning non-`void` type)
* DataTable : option `class` replaced by `table-class`, add options `class` and `toolbar_class` to specify css class of table container and toolbar
