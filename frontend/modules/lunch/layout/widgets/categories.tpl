{*
	variables that are available:
	- {$widgetBlogCategories}:
*}

{option:widgetLunchCategories}
	<section id="blogCategoriesWidget" class="mod">
		<div class="inner">
			<header class="hd">
				<h3>{$lblLunchCategories|ucfirst}</h3>
			</header>
			<div class="bd content">
				<ul>
					{iteration:widgetLunchCategories}
						<li style="font-size:14px">
							{$widgetLunchCategories.name}&nbsp;({$widgetLunchCategories.total})
							{option:widgetLunchCategories.items}
								<ul>
									{iteration:widgetLunchCategories.items}
									<li>{$widgetLunchCategories.items.name} - &euro; {$widgetLunchCategories.items.price} - <a href="{$widgetLunchCategories.items.url}">Add</a></li>
									{/iteration:widgetLunchCategories.items}
								</ul>
							{/option:widgetLunchCategories.items}
						</li>
					{/iteration:widgetLunchCategories}
				</ul>
			</div>
		</div>
	</section>
{/option:widgetLunchCategories}