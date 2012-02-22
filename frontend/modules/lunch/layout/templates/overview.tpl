<h4>Overview for {$next}</h4>

{option:!items}
	<div id="blogIndex">
		<section class="mod">
			<div class="inner">
				<div class="bd content">
					<p style="font-size:16px">{$msgLunchNoItems}</p>
					<a href="/lunch">Order</a>
				</div>
			</div>
		</section>
	</div>
	{/option:!items}
{option:items}
	<div id="orders" style="min-height:150px">
		{iteration:items}
			<div style="font-size:16px; margin-bottom:5px; ">
				<span class="title" >{$items.name}</span>
				<span class="item" style="margin-left:25px;">{$items.item}</span>
				<span class="count" style="margin-left: 25px;">x {$items.count}</span>
				<span class="price" style="margin-left:25px; color: red;">&euro; {$items.price}</span>
				<span class="total" style="margin-left: 25px;">= &euro; {$items.subtotal}</span>
				
				<div class="note" style="margin-left:60px; font-size:12px;">
						{$items.note}
				</div>
			</div>
		{/iteration:items}
	</div>
	<p id="total" style="font-size:18px; color:red;width:250px; text-align:right">Total: &euro; {$total}</span>
	
	<div id="blogIndex">
		<section class="mod">
			<div class="inner">
				<div class="bd content">
					<a href="/lunch">Order</a>
				</div>
			</div>
		</section>
	</div>
{/option:items}