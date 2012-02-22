<h4>Edit your order: {$info.name}</h4>

{form:editOrderForm}
		<p>
			<label for="count">{$lblCount|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
			{$txtCount} {$txtCountError}
		</p>
		
		<p class="bigInput{option:txtMessageError} errorArea{/option:txtMessageError}">
			<label for="note">{$lblNote|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
			{$txtNote} {$txtNoteError}
		</p>
		
		<p>
			<input class="inputSubmit" type="submit" name="editOrder" value="{$msgEditOrderButton|ucfirst}" />
		</p>
		
		<a href="/lunch">Back to overview</a>
{/form:editOrderForm}