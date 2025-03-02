CHANGELOG
=========

dev
---
 * Drop support for php 8.1
 * Drop support for symfony version < 6.4
 * Remove all deprecations
 * Add missing typehints (BC)
 * DataTable : Change select plugin / remove bulk actions (use display option insteed) (BC)
 * DataTable : Change tree plugin (BC)
 * DataTable : Change row child plugin (RowDetailsColumn) (BC)
 * DataTable : remove "RowModifier" and method setXRow() on builder. Use buildRowView instead (BC)
 * FormType : remove NestedTreeType / NestedParentTreeType replaced by NestedEntityType (BC)
 * Entity : remove NestedEntityInterface (not needed anymore) (BC)
 * JsResponse : remove response listener / builder is not exposed anymore on controller, use js() method to create a JsResponse instead (BC)

6.3
---
 * DataTable : EntityAdapter / NestedEntityAdapterType : add option `em` to specify which dbal connection is used
 * Update Js dependency, use webpack 5
 * Drop support for php 8.0


6.2
---
 * Fix : UmbrellaCollectionType with dropdowns hide the scrollbar #34
 * Fix : Opening second level menu closes parent #33
 * Fix : Cannot get ckeditor config to work #32

6.1
---
 * DataTable : add option `$resetPaging` for method `JsResponseBuilder::reloadTable()` to reset paging , add `umbrella_core.datatable.reset_paging_on_reload` to configure default behaviour of `$resetPaging` param.

6.0
---
 * Drop support for php 7.4
 * Drop support for symfony 5.4
 * Replace @Searchable and @SearchableField annotation by php8 attribute
 * Replace @Route annotation on Maker by php8 attribute
 * Use doctrine attribute

5.0
---
 * Menu :
   * Add `url()` and `target()` methods on MenuBuilder
 * DataTable :
   * Remove `load_url` option, use `load_route` and `load_route_params` option instead
   * Remove `toolbar_class` option, use `toolbar_template` to override class of toolbar template instead
   * Rename `DetailsHandleColumnType` column  by `DetailsColumnType`
   * Remove `CheckboxColumnType` column and `RadioColumnType` column, use `select` option instead (can be `multi`, `single` or `false`). Set option `select` will automatically add an internal column with checkbox or radio. 
   * Add `setRowSelectable` method for builder to determine if a row can be selected or not.
   * Remove `ManyColumnType` column (hard to use / customize).
   * Remove `tree_state` option, use `tree_expanded` option instead (can be `true` or `false`)
   * Add suffix `Type` to adapter type class.
   * Add `DataTableActionState` utils, to retrieve datatable state from an action (i.e : filter used, rows selected, columns order, ...)
   * option `table_class` replaced by `class` (revert)
   * option `class` replaced by `container_class` (revert)
   * Add option `stripe_class` to define stripe class of rows (ex : odd, even). Class `table-striped` is not used any more by default (cause glitch if datatable has extra rows).
 * DataTable (removed feature) :
   * Remove `DragHandleColumnType`, `RowReorder` helper, `rowreorder_route` and `rowreorder_route_params` options and remove `rowReorder` js datatable plugin.
 * Remove Widget component => Replaced by lightweight but more configurable `Datatable\Action` component:
   * Method `addWidget` / `removeWidget` / `hasWidget` doesn't exist anymore on Datatable builder. Use `addAction` /  `removeAction` / `hasAction` instead
   * Remove `WidgetColumnType` column, use `ActionColumnType` column instead.
 * Fix 
   * prevent open multiple confirm modal
 * Routing : Replace route annotation on vendor by config file (bundle best practice)
   * To load admin routes, you have to import:
     * `@UmbrellaAdminBundle/config/routes/profile.php` instead of `@UmbrellaAdminBundle/config/routes/user_profile.yaml`
     * `@UmbrellaAdminBundle/config/routes/user.php` + `@UmbrellaAdminBundle/config/routes/security.php` instead of `@UmbrellaAdminBundle/config/routes/user.yaml`
     * `@UmbrellaAdminBundle/config/routes/notification.php` instead of `@UmbrellaAdminBundle/config/routes/notification.yaml`
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
