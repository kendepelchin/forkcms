{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>
		{$lblOrders|ucfirst}: {$lblLunchOrderDetail}
	</h2>
</div>

{option:dataGrid}
	<div class="dataGridHolder">
	<div class="tableHeading">
			<h3>{$detailDate|ucfirst}</h3>
		</div>
		{$dataGrid}
	</div>
	
	<span style="font-size:12pt;">Total of <span style="color: red; font-weight:bold;">&euro; {$total|ucfirst}</span> for {$detailDate|ucfirst}</span>
	+ Button die linkt naar frontend
{/option:dataGrid}
{option:!dataGrid}<p>{$msgNoOrders|sprintf:{$var|geturl:'add_category'}}</p>{/option:!dataGrid}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}