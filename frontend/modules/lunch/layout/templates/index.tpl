{*
	variables that are available:
	- {$items}: contains an array with all posts, each element contains data about the post
*}

{option:!items}
	<div id="blogIndex">
		<section class="mod">
			<div class="inner">
				<div class="bd content">
					<p style="font-size:16px">{$msgLunchNoItems}</p>
				</div>
			</div>
		</section>
	</div>
{/option:!items}
{option:items}
{iteration:items}
	<div style="font-size:16px;">
			<span id="title" style="">{$items.name}</span>
			<span id="title" style="margin-left:25px; color: red;">&euro; {$items.price}</span>
			<span id="count" style="margin-left: 25px;">x {$items.count}</span>
			<span id="total" style="margin-left: 25px;">= &euro; {$items.total}</span>
			<span id="edit" style="margin-left: 25px;"><a href="{$url}">edit</a></span>
	</div>
	{/iteration:items}
	
	<span id="total" style="font-size:18px; color:red;">Total: &euro; {$total}</span>
	
	{form:orderForm}
		<p>
			<label for="name">{$lblName|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
			{$txtName} {$txtNameError}
		</p>
	{/form:orderForm}
	
	{include:core/layout/templates/pagination.tpl}
{/option:items}