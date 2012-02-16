{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblLunch|ucfirst}: {$lblEditMenuItem}</h2>
</div>

{form:editMenuItem}
	<div class="tabs">
		<ul>
			<li><a href="#tabContent">{$lblContent|ucfirst}</a></li>
			<li><a href="#tabSEO">{$lblSEO|ucfirst}</a></li>
		</ul>

		<div id="tabContent">
			<table width="100%">
				<tr>
					<td id="leftColumn">
						<p>
							<label for="name">{$lblName|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
							{$txtName} {$txtNameError}
							
							<label for="name">{$lblPrice|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
							{$txtPrice} {$txtPriceError}
							
							<label for="category">{$lblCategory|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
							{$ddmCategory} {$ddmCategoryError}
						</p>
					</td>
				</tr>
			</table>
		</div>

		<div id="tabSEO">
			{include:{$BACKEND_CORE_PATH}/layout/templates/seo.tpl}
		</div>
	</div>

	<div class="fullwidthOptions">
			<a href="{$var|geturl:'delete_menu_item'}&amp;id={$item.id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
				<span>{$lblDelete|ucfirst}</span>
			</a>
			<div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
				<p>
					{$msgConfirmDeleteCategory|sprintf:{$item.name}}
				</p>
			</div>
		<div class="buttonHolderRight">
			<input id="editButton" class="inputButton button mainButton" type="submit" name="edit" value="{$lblEditMenuItem|ucfirst}" />
		</div>
	</div>
{/form:editMenuItem}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}