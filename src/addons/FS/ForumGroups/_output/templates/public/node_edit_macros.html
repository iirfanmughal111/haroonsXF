<xf:macro name="title" arg-node="!">
	<xf:textboxrow name="node[title]" value="{$node.title}" label="{{ phrase('title') }}" />
</xf:macro>

<xf:macro name="description" arg-node="!">
	<xf:textarearow name="node[description]" value="{$node.description}" autosize="true"
		label="{{ phrase('description') }}"
		hint="{{ phrase('you_may_use_html') }}"
		explain="{{ phrase('node_description_explain') }}" />
</xf:macro>

<xf:macro name="node_name" arg-node="!" arg-optional="{{ true }}">
	<xf:textboxrow name="node[node_name]" value="{$node.node_name}"
		label="{{ phrase('url_portion') }}"
		hint="{{ $optional ? phrase('optional') : '' }}"
		explain="{{ $optional ? phrase('if_entered_url_to_forum_not_contain_id_change_breaks_urls') : '' }}"
		dir="ltr" />
</xf:macro>

<xf:macro name="position" arg-node="!" arg-nodeTree="!">
	<xf:selectrow name="node[parent_node_id]" value="{$node.parent_node_id}"
		label="{{ phrase('parent_node') }}">

		<xf:option value="0">{{ phrase('(none)') }}</xf:option>

		<xf:foreach loop="$nodeTree.getFlattened(0)" value="$treeEntry">
			<xf:option value="{$treeEntry.record.node_id}">{{ repeat('--', $treeEntry.depth) }} {$treeEntry.record.title}</xf:option>
		</xf:foreach>
	</xf:selectrow>

	<xf:macro template="display_order_macros" name="row"
		arg-name="node[display_order]"
		arg-value="{$node.display_order}"
		arg-explain="{{ phrase('position_of_this_item_relative_to_other_nodes_with_same_parent') }}" />

	<xf:checkboxrow>
		<xf:option name="node[display_in_list]" selected="$node.display_in_list"
			label="{{ phrase('display_in_node_list') }}"
			hint="{{ phrase('if_unselected_users_will_not_see_this_node_in_list') }}" />
	</xf:checkboxrow>
</xf:macro>

<xf:macro name="style" arg-node="!" arg-styleTree="!">
	<xf:checkboxrow>
		<xf:option name="style_override" selected="$node.style_id"
			label="{{ phrase('override_user_style_choice') }}"
			explain="{{ phrase('if_specified_all_visitors_view_using_selected_style') }}">

			<xf:select name="node[style_id]" value="{$node.style_id}">
				<xf:foreach loop="$styleTree.getFlattened(0)" value="$treeEntry">
					<xf:option value="{$treeEntry.record.style_id}">{{ repeat('--', $treeEntry.depth) }} {$treeEntry.record.title}</xf:option>
				</xf:foreach>
			</xf:select>

		</xf:option>
	</xf:checkboxrow>
</xf:macro>

<xf:macro name="navigation" arg-node="!" arg-navChoices="!">
	<xf:set var="$defaultValue" value="{{ $node.Parent.effective_navigation_id ?: 'forums' }}" />
	<xf:set var="$defaultNav" value="{$navChoices.{$defaultValue}}" />
	<xf:selectrow name="node[navigation_id]" value="{$node.navigation_id}"
		label="{{ phrase('navigation_section') }}"
		explain="{{ phrase('navigation_section_explain') }}">

		<xf:option value="">{{ phrase('default') . ($defaultNav ? ' (' . $defaultNav.title . ')' : '') }}</xf:option>

		<xf:foreach loop="$navChoices" value="$nav">
			<xf:if is="$nav.navigation_id != '_default'">
				<xf:option value="{$nav.navigation_id}">{$nav.title}</xf:option>
			</xf:if>
		</xf:foreach>
	</xf:selectrow>
</xf:macro>