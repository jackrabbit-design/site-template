<?php

class Walker_Tertiary_Menu extends Walker_Nav_Menu
{
	// ! @var boolean $in_current_menu Are we in the current menu tree?
	var $in_current_menu = false;

	/**
	 * @see Walker::end_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Page data object. Not used.
	 * @param int $depth Depth of page. Not Used.
	 */
	function end_el( &$output, $item, $depth = 0, $args = array() )
	{
		if ( $this->in_current_menu && $depth > 0 )
		{
			parent::end_el($output, $item, $depth, $args);
		}
	}

	/**
	 * @see Walker::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() )
	{
		if ( $this->in_current_menu && $depth > 0 )
		{
			parent::end_lvl($output, $depth, $args);
		}
		
		/*
		If we're in the current menu and we are closing the top-level item
		then set $in_current_menu to false
		*/
		if ( $depth == 0 )
		{
			$this->in_current_menu = false;
		}
	}
	
	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param array $args
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
	{
		/*
		Is the beginning of the current menu?
		If so, set $in_current_menu to true.
		*/
		if ( $args->ancestor->ID == $item->current_item_ancestor )
		{
			$this->in_current_menu = true;
		}

		/*
		Only continue if we're in the current menu level
		*/
		if ( $this->in_current_menu && $depth > 0 )
		{
			parent::start_el($output, $item, $depth, $args, $id);
		}
	}
	
	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() )
	{
		if ( $this->in_current_menu && $depth > 0 )
		{
			parent::start_lvl($output, $depth, $args);
		}
	}
}