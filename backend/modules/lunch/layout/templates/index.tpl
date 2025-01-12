{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>
		{$lblLunch|ucfirst}:

		{option:!filterCategory}{$lblMenuItems}{/option:!filterCategory}
		{option:filterCategory}{$msgMenuItemsFor|sprintf:{$filterCategory.name}}{/option:filterCategory}
	</h2>

	<div class="buttonHolderRight">
		{option:filterCategory}<a href="{$var|geturl:'add_menu_item':null:'&category={$filterCategory.id}'}" class="button icon iconAdd" title="{$lblAdd|ucfirst}">{/option:filterCategory}
		{option:!filterCategory}<a href="{$var|geturl:'add_menu_item'}" class="button icon iconAdd" title="{$lblAdd|ucfirst}">{/option:!filterCategory}
			<span>{$lblAdd|ucfirst}</span>
		</a>
	</div>
</div>

{form:filter}
	<p class="oneLiner">
		<label for="category">{$msgShowOnlyItemsInCategoryLunch}</label>
		&nbsp;{$ddmCategory} {$ddmCategoryError}
	</p>
{/form:filter}

{option:dgPosts}
	<div class="dataGridHolder">
		<div class="tableHeading">
			<h3>{$lblItemsAvailable|ucfirst}</h3>
		</div>
		{$dgPosts}
	</div>
{/option:dgPosts}

{option:!dgPosts}
	{option:filterCategory}<p>{$msgNoItems|sprintf:{$var|geturl:'add':null:'&category={$filterCategory.id}'}}</p>{/option:filterCategory}
	{option:!filterCategory}<p>{$msgNoItems|sprintf:{$var|geturl:'add'}}</p>{/option:!filterCategory}
{/option:!dgPosts}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}