{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>
		{$lblLunch|ucfirst}: {$lblOrders}
	</h2>
</div>

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