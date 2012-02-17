/**
 * Interaction for the lunch module
 *
 */
jsBackend.lunch =
{
	// init, something like a constructor
	init: function()
	{
		// variables
		$title = $('#title');

		jsBackend.lunch.controls.init();

		// do meta
		if($title.length > 0) $title.doMeta();
	}
}

jsBackend.lunch.controls =
{
	currentCategory: null,

	// init, something like a constructor
	init: function()
	{
		// variables
		
		$filter = $('#filter');
		$filterCategory = $('#filter #category');

		
		$filterCategory.on('change', function(e)
		{
			$filter.submit();
		});

		jsBackend.lunch.controls.currentCategory = $categoryId.val();
	}
}

$(jsBackend.lunch.init);