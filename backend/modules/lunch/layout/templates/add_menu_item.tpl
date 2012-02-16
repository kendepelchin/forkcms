{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblLunch|ucfirst}: {$lblAddMenuItem}</h2>
</div>

{form:addMenuItem}
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
		<div class="buttonHolderRight">
			<input id="addButton" class="inputButton button mainButton" type="submit" name="add" value="{$lblAddMenuItem|ucfirst}" />
		</div>
	</div>
{/form:addMenuItem}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}