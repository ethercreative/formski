# formski
A form building plugin for Craft CMS

## Usage

Create `_form.twig` in your templates folder:

```twig
<form method="post">
	{{ csrfInput() }}
	<input type="hidden" name="action" value="formski/forms/send">
	<input type="hidden" name="formId" value="{{ form.id }}">
	{% for rowUid, fields in form.fieldLayout %}
		<div>
			{% for fieldUid in fields %}
				{% set fieldSettings = form.fieldSettings[fieldUid] %}
				<label>
					<span>{{ fieldSettings.label }}{{ fieldSettings.required ? ' *' }}</span>
					<span>{{ fieldSettings.instructions }}</span>
					{% switch fieldSettings._type %}
					{% case "text" %}
						<input
							name="fields[{{ fieldUid }}]"
							type="{{ fieldSettings.type }}"
							placeholder="{{ fieldSettings.placeholder }}"
							{#{{ fieldSettings.required ? 'required' }}#}
							value="{{ submission is defined ? submission[fieldUid] }}"
						/>
					{% case "textarea" %}
						<textarea
							name="fields[{{ fieldUid }}]"
							placeholder="{{ fieldSettings.placeholder }}"
							rows="{{ fieldSettings.rows }}"
							{{ fieldSettings.required ? 'required' }}
						>{{ submission is defined ? submission[fieldUid] }}</textarea>
					{% default %}
						{{ fieldSettings._type }}
					{% endswitch %}
					{% if submission is defined and submission.getErrors(fieldUid) %}
						<span style="color:darkred">{{ submission.getFirstError(fieldUid) }}</span>
					{% endif %}
				</label>
			{% endfor %}
		</div>
	{% endfor %}

	<button>Send</button>
</form>

{% if submission is defined %}
	<pre><code>{{ dump(submission.getErrors()) }}</code></pre>
{% endif %}
```