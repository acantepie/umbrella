CHANGELOG
=========

5.x
---
 * DataTable :
   * Remove `load_url` option, use `load_route` and `load_route_params` option instead
   * Remove `rowreorder_url` option, use `rowreorder_route` and `rowreorder_route_params` option instead
   * Remove `toolbar_class` option, use `toolbar_template` to override class of toolbar template instead
   * Rename `DetailsHandleColumnType` column  by `DetailsColumnType`
   * Remove `CheckboxColumnType` column and `RadioColumnType` column, use `select` option instead (can be `multi`, `single` or `false`). Set option `select` will automatically add an internal column with checkbox or radio. 
   * Add `setRowSelectable` method for builder to determine if a row can be selected or not.
   * Remove `DragHandleColumnType` column, set option `rowreorder_route` or use method `setRowReorderUrl` of builder will automatically add an internal `drag` column.
   * Remove `ManyColumnType` (doesn't working).
   * Remove `tree_expanded` option, use `tree_expanded` option instead (can be `true` or `false`)
   * Remove `WidgetColumnType`, use `ActionColumnType` instead
   * Add suffix `Type` to adapter type class.
 * Widget :
   * Remove component
 * Fix 
   * prevent open multiple confirm modal
   
4.4
---
 * Add alias `UserMailerInterface` and `UserManagerInterface`  to allowing services `UserMailer` and `UserManager`to be overriden.
 * Add `template` options for Menu and Breadcrumb
 * Add `Offcanvas`
 * Fix `UmbrellaCollectionType` : missing prototype if allow_add was false, invalid header if entry_type was not compound. Replace `show_head` by `headless` option.
 * Remove doctrine Trait to facilitate override and the use of doctrine attributes :
   * Remove `TimestampTrait`, `OrderTrait`, `ActiveTrait`, `NestedTreeEntityTrait`
   * Use `Gedmo\Timestambable` to handle timestamp on `User`
 * Remove `ChoiceTypeExtension` => option `choices_as_values` was removed on `ChoiceType`
 * Use XML mapping instead of annotation mapping for doctrine

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
