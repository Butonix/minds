<?php
/**
 * Main content filter
 *
 * Select between user, friends, and all content
 *
 * @uses $vars['filter_context']  Filter context: all, friends, mine
 * @uses $vars['filter_override'] HTML for overriding the default filter (override)
 * @uses $vars['context']         Page context (override)
 */

if (isset($vars['filter_override'])) {
	echo $vars['filter_override'];
	return true;
}

$context = elgg_extract('context', $vars, elgg_get_context());

if (elgg_is_logged_in() && $context) {
	$username = elgg_get_logged_in_user_entity()->username;
	$filter_context = elgg_extract('filter_context', $vars, 'all');

	// generate a list of default tabs
	$tabs = array(
		'featured' => array(
                'text' => elgg_echo('Featured'),
                'href' => (isset($vars['trending_link'])) ? $vars['trending_link'] : "$context/list/featured",
                'selected' => ($filter_context == 'featured'),
                'priority' => 1,
                'item_class'=>'elgg-menu-item-hover-over'
        ),
		'all' => array(
			'text' => elgg_echo('all'),
			'href' => (isset($vars['all_link'])) ? $vars['all_link'] : "$context/all",
			'selected' => ($filter_context == 'all'),
			'priority' => 100,
		),
		'trending' => array(
                        'text' => elgg_echo('trending').elgg_view_menu('trending'),
                        'href' => (isset($vars['trending_link'])) ? $vars['trending_link'] : "$context/trending",
                        'selected' => ($filter_context == 'trending'),
                        'priority' => 200,
                        'item_class'=>'elgg-menu-item-hover-over'
                ),
		'mine' => array(
			'text' => elgg_echo('mine'),
			'href' => (isset($vars['mine_link'])) ? $vars['mine_link'] : "$context/owner/$username",
			'selected' => ($filter_context == 'mine'),
			'priority' => 300,
		),
		'friend' => array(
			'text' => elgg_echo('friends'),
			'href' => (isset($vars['friend_link'])) ? $vars['friend_link'] : "$context/friends/$username",
			'selected' => ($filter_context == 'friends'),
			'priority' => 400,
		),
	);

	if(!elgg_is_active_plugin('analytics')){
		unset($tabs['trending']);
	}
	
	foreach ($tabs as $name => $tab) {
		$tab['name'] = $name;
		
		elgg_register_menu_item('filter', $tab);
	}

	echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz minds-trending-filter'));
}
