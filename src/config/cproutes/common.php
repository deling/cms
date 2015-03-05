<?php

return [
	'categories'                                                            => 'categories/category-index',
	'categories/<groupHandle:{handle}>'                                     => 'categories/category-index',
	'categories/<groupHandle:{handle}>/new'                                 => 'categories/edit-category',
	'categories/<groupHandle:{handle}>/<categoryId:\d+><slug:(?:-{slug})?>' => 'categories/edit-category',

	'dashboard/settings/new'                                                => ['template' => 'dashboard/settings/_widgetsettings'],
	'dashboard/settings/<widgetId:\d+>'                                     => ['template' => 'dashboard/settings/_widgetsettings'],

	'entries/<sectionHandle:{handle}>'                                      => ['template' => 'entries'],
	'entries/<sectionHandle:{handle}>/new'                                  => 'entries/edit-entry',
	'entries/<sectionHandle:{handle}>/<entryId:\d+><slug:(?:-{slug})?>'     => 'entries/edit-entry',

	'globals/<globalSetHandle:{handle}>'                                    => 'globals/edit-content',

	'updates/go/<handle:[^/]*>'                                             => ['template' => 'updates/_go'],

	'settings'                                                              => 'system-settings/settings-index',
	'settings/assets'                                                       => 'asset-sources/source-index',
	'settings/assets/sources/new'                                           => 'asset-sources/edit-source',
	'settings/assets/sources/<sourceId:\d+>'                                => 'asset-sources/edit-source',
	'settings/assets/transforms'                                            => 'asset-transforms/transform-index',
	'settings/assets/transforms/new'                                        => 'asset-transforms/edit-transform',
	'settings/assets/transforms/<transformHandle:{handle}>'                 => 'asset-transforms/edit-transform',
	'settings/categories'                                                   => 'categories/group-index',
	'settings/categories/new'                                               => 'categories/edit-category-group',
	'settings/categories/<groupId:\d+>'                                     => 'categories/edit-category-group',
	'settings/fields/<groupId:\d+>'                                         => ['template' => 'settings/fields'],
	'settings/fields/new'                                                   => ['template' => 'settings/fields/_edit'],
	'settings/fields/edit/<fieldId:\d+>'                                    => ['template' => 'settings/fields/_edit'],
	'settings/general'                                                      => 'system-settings/general-settings',
	'settings/globals/new'                                                  => 'system-settings/edit-global-set',
	'settings/globals/<globalSetId:\d+>'                                    => 'system-settings/edit-global-set',
	'settings/plugins/<pluginClass:{handle}>'                               => ['template' => 'settings/plugins/_settings'],
	'settings/sections'                                                     => 'sections/index',
	'settings/sections/new'                                                 => 'sections/edit-section',
	'settings/sections/<sectionId:\d+>'                                     => 'sections/edit-section',
	'settings/sections/<sectionId:\d+>/entrytypes'                          => 'sections/entry-types-index',
	'settings/sections/<sectionId:\d+>/entrytypes/new'                      => 'sections/edit-entry-type',
	'settings/sections/<sectionId:\d+>/entrytypes/<entryTypeId:\d+>'        => 'sections/edit-entry-type',
	'settings/tags'                                                         => 'tags/index',
	'settings/tags/new'                                                     => 'tags/edit-tag-group',
	'settings/tags/<tagGroupId:\d+>'                                        => 'tags/edit-tag-group',

	'utils/serverinfo'                                                      => 'utils/server-info',
	'utils/phpinfo'                                                         => 'utils/php-info',
	'utils/logs(/<currentLogFilename:[A-Za-z0-9\.]+>?'                      => 'utils/logs',
	'utils/deprecationerrors'                                               => 'utils/deprecation-errors',

	'myaccount'                                                             => ['route' => 'users/edit-user', 'defaults' => ['userId' => 'current']],

	'settings/routes' => [
		'template' => 'settings/routes',
		'variables' => [
			'tokens' => [
				'year'   => '\d{4}',
				'month'  => '(?:0?[1-9]|1[012])',
				'day'    => '(?:0?[1-9]|[12][0-9]|3[01])',
				'number' => '\d+',
				'page'   => '\d+',
				'slug'   => '[^\/]+',
				'tag'    => '[^\/]+',
				'*'      => '[^\/]+',
			]
		]
	],
];
