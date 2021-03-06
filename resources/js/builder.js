import h from "./helpers/h";
import showdown from "showdown";

class Builder {

	// Properties
	// =========================================================================

	// Properties: Static
	// -------------------------------------------------------------------------

	static _uidChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	static _uidCharsLength = Builder._uidChars.length;

	static md = new showdown.Converter();

	// Properties: Instance
	// -------------------------------------------------------------------------

	editingField : HTMLElement = null;
	editingSettings : HTMLElement = null;

	// Constructor
	// =========================================================================

	constructor ({ fieldLayout, fieldSettings } = {}) {
		this.fieldsWrap = document.getElementById("formskiFields");
		this.formWrap = document.getElementById("formskiForm");
		this.settingsWrap = document.getElementById("field-settings");

		this.initFieldTemplates();

		// Pre-populate existing fields
		let previousRow = this.formWrap.firstElementChild;
		let firstField = null;

		if (fieldLayout && fieldSettings) {
			for (let [rowUid, fields] of Object.entries(fieldLayout)) {
				// Create the row
				const row = this.createRow(this.formWrap, previousRow, rowUid);

				// Add the new H drop zone
				this.formWrap.insertBefore(this.getDropZone("h"), row);

				// Create the fields
				for (let i = 0, l = fields.length; i < l; ++i) {
					const fieldUid = fields[i];
					const settings = fieldSettings[fieldUid];
					const fieldType = settings._type;
					delete settings._type;

					// Create the field
					const field = this.createField(
						row,
						fieldType,
						fieldUid,
						settings
					);

					// If is the first field
					if (i === 0) {
						// Replace the <!-- Field --> comment w/ the new field
						row.replaceChild(field, this.getRowFieldComment(row));

						if (firstField === null)
							firstField = field;
					} else {
						// Add the new field & a new V drop zone
						row.insertBefore(field, row.lastElementChild);
						row.insertBefore(this.getDropZone("v"), field);
					}

					// Update field UI based of settings
					for (let [name, value] of Object.entries(settings)) {
						let target = { value };

						if (name === "required") target = { checked: value };
						else if (name === "options") target = { value: Object.values(value) };

						this.onSettingChange(field, name, { target });
					}
				}

				previousRow = row.nextElementSibling;
			}
		}

		// Bind events
		this.initDragDrop();
		this.bindClickEvents();

		// Set first field as editing (if one exists)
		if (firstField) this.editField(firstField);
	}

	// Init
	// =========================================================================

	// Init: Fields
	// -------------------------------------------------------------------------

	initFieldTemplates () {
		this.rowTemplate = document.getElementById("formskiRow");
		this.dropZoneHTemplate = document.getElementById("formskiDropZoneH");
		this.dropZoneVTemplate = document.getElementById("formskiDropZoneV");
		this.fieldTemplates = {};

		const fieldTemplates = document.querySelectorAll("[data-formski-field]");

		for (let i = 0, l = fieldTemplates.length; i < l; ++i) {
			const tmpl = fieldTemplates[i];
			this.fieldTemplates[tmpl.dataset.formskiField] = tmpl;
		}
	}

	// Init: Drag / Drop
	// -------------------------------------------------------------------------

	initDragDrop () {
		const fields = this.fieldsWrap.querySelectorAll("[data-type]");

		for (let i = 0, l = fields.length; i < l; ++i) {
			const field = fields[i];
			field.addEventListener(
				"dragstart",
				this.onFieldTypeDrag.bind(this, field)
			);
		}

		this.bindDropZones();
	}

	// Actions
	// =========================================================================

	// Actions: Add Field
	// -------------------------------------------------------------------------

	createField (row, type, uid = this.getUid(), settings = null) {
		// Get the field template & a UID
		const field  = this.getFieldTemplate(type)
			, rowUid = row.dataset.rowUid;

		// Set the UID data
		field.setAttribute("data-uid", uid);

		// Add layout input
		const layout = document.createElement("input");
		layout.setAttribute("type", "hidden");
		layout.setAttribute("name", "fieldLayout[][" + rowUid + "][]");
		layout.value = uid;
		field.appendChild(layout);

		// Create the settings
		this.createFieldSettings(field, type, uid, settings);

		return field;
	}

	createRow (form, before, uid = this.getUid()) {
		const row = this.getRowTemplate();

		// Row UID
		row.setAttribute("data-row-uid", uid);

		return form.insertBefore(row, before);
	}

	addFieldToForm (before, type) {
		const form = before.parentNode;

		// Add Row
		const row = this.createRow(form, before);

		// Add Field
		const field = this.createField(row, type);
		row.replaceChild(field, this.getRowFieldComment(row));

		// Add new H drop zone
		form.insertBefore(this.getDropZone("h"), row);

		// Bind new drop zones
		this.bindDropZones();

		// Bind new clicks
		this.bindClickEvents();

		// Edit Field
		this.editField(field);
	}

	addFieldToRow (before, type) {
		const row = before.parentNode;

		// Add Field
		const field = this.createField(row, type);
		row.insertBefore(field, before);

		// Add new V drop zone
		row.insertBefore(this.getDropZone("v"), field);

		// Bind new drop zones
		this.bindDropZones();

		// Bind new clicks
		this.bindClickEvents();

		// Edit Field
		this.editField(field);
	}

	// Actions: Edit Field
	// -------------------------------------------------------------------------

	editField (field) {
		// Skip if already editing
		if (this.editingField === field)
			return;

		// Add edit class to field
		if (this.editingField !== null)
			this.editingField.classList.remove("edit");

		this.editingField = field;
		this.editingField.classList.add("edit");

		// Show settings
		if (this.editingSettings !== null)
			this.editingSettings.classList.add("hidden");

		this.editingSettings =
			this.settingsWrap.querySelector("[data-uid='" + field.dataset.uid + "']");
		this.editingSettings.classList.remove("hidden");
	}

	// Actions: Delete Field
	// -------------------------------------------------------------------------

	deleteFieldByUid (uid) {
		// Remove settings
		const settings = this.settingsWrap.querySelector("[data-uid='" + uid + "']");
		settings.parentNode.removeChild(settings);

		// Remove field
		const field = this.formWrap.querySelector("[data-uid='" + uid + "']");
		const row = field.parentNode
			, beingEdited = field === this.editingField;

		if (row.children.length === 4) {
			// If field is only field in row, remove the row and next drop zone
			const parent = row.parentNode;
			const dropZone = row.nextElementSibling;
			parent.removeChild(row);
			parent.removeChild(dropZone);
		} else {
			// Else remove the field and next drop zone
			const dropZone = field.nextElementSibling;
			row.removeChild(field);
			row.removeChild(dropZone);
		}

		// If active, select another field
		if (beingEdited) {
			this.editingField = null;
			this.editingSettings = null;

			const nextField = this.formWrap.querySelector("[data-uid]");
			if (nextField) this.editField(nextField);
		}
	}

	// Actions: Field Settings
	// -------------------------------------------------------------------------

	createFieldSettings (uiField, type, uid, fieldSettings = null) {
		if (fieldSettings === null) {
			fieldSettings = {
				label: "Label",
			};

			if (type !== "heading" && type !== "description") {
				fieldSettings.handle = "";
				fieldSettings.instructions = "";
				fieldSettings.required = false;
			}

			switch (type) {
				case "text":
					fieldSettings.type = "text";
					fieldSettings.placeholder = "";
					break;
				case "textarea":
					fieldSettings.placeholder = "";
					fieldSettings.rows = 5;
					break;
				case "dropdown":
				case "radio":
				case "checkbox":
					fieldSettings.options = [
						{ label: "Label", value: "value", default: "0" },
					];
					break;
				case "acceptance":
					fieldSettings.required = true;
					break;
				case "description":
					delete fieldSettings.label;
					fieldSettings.description = "Enter your description";
					break;
			}
		}

		this.settingsWrap.appendChild(h("div", {
			class: "meta hidden",
			"data-uid": uid
		}, [
			h("div", {
				class: "formski-settings-type",
			}, this.getFriendlyTypeName(type)),

			h("input", {
				type: "hidden",
				name: `fieldSettings[${uid}][_type]`,
				value: type,
			}),

			...Object.entries(fieldSettings).map(([name, value]) => (
				this.createSettingsField(uiField, uid, typeof value, name, value)
			)),

			h("footer", { class: "footer" }, [
				h("button", {
					class: "btn small",
					type: "button",
					click: this.onDeleteFieldClick.bind(this, uid),
				}, "Delete")
			]),
		]));
	}

	// Events
	// =========================================================================

	// Events: Drag / Drop Binders
	// -------------------------------------------------------------------------

	bindDropZones () {
		const dropZones = this.formWrap.querySelectorAll("[class*='formski-form-drop']");

		for (let i = 0, l = dropZones.length; i < l; ++i) {
			const dropZone = dropZones[i];

			if (dropZone.dropBound === true)
				continue;

			dropZone.dropBound = true;

			const type = ~dropZone.className.indexOf("-h") ? "h" : "v";

			dropZone.addEventListener(
				"dragover",
				this.onDropZoneDragOver.bind(this, dropZone)
			);

			dropZone.addEventListener(
				"dragleave",
				this.onDropZoneDragLeave.bind(this, dropZone)
			);

			dropZone.addEventListener(
				"drop",
				this.onDropZoneDrop.bind(this, dropZone, type)
			);
		}
	}

	// Events: Drag / Drop Handlers
	// -------------------------------------------------------------------------

	onFieldTypeDrag = (field, e) => {
		e.dataTransfer.setData("text/field", field.dataset.type);
	};

	onDropZoneDragOver = (zone, e) => {
		e.preventDefault();

		if (![...e.dataTransfer.types].includes("text/field"))
			return;

		zone.classList.add("drop");
	};

	onDropZoneDragLeave = zone => {
		zone.classList.remove("drop");
	};

	onDropZoneDrop = (zone, zoneType, e) => {
		e.preventDefault();
		zone.classList.remove("drop");
		const type = e.dataTransfer.getData("text/field");

		if (zoneType === "h") this.addFieldToForm(zone, type);
		else this.addFieldToRow(zone, type);
	};

	// Events: Click Binders
	// -------------------------------------------------------------------------

	bindClickEvents () {
		const fields = this.formWrap.getElementsByClassName("formski-field");

		for (let i = 0, l = fields.length; i < l; ++i) {
			const field = fields[i];

			if (field.clickBound === true)
				continue;

			field.clickBound = true;

			field.addEventListener("click", this.onFieldClick.bind(this, field));
		}
	}

	// Events: Click Handlers
	// -------------------------------------------------------------------------

	onFieldClick = (field, e) => {
		e.preventDefault();
		this.editField(field);
	};

	onDeleteFieldClick = (uid, e) => {
		e.preventDefault();

		if (confirm("Are you sure?"))
			this.deleteFieldByUid(uid);
	};

	// Events: Settings Handlers
	// -------------------------------------------------------------------------

	onSettingChange = (field, name, e) => {
		const value = e.target.value;

		switch (name) {
			case "label":
				field.querySelector(".formski-field-label").textContent = value;
				break;
			case "instructions":
				field.querySelector(".formski-field-instructions").textContent = value;
				break;
			case "type":
				field.querySelector("input").setAttribute("type", value);
				break;
			case "placeholder":
				field.querySelector("input,textarea").setAttribute("placeholder", value);
				break;
			case "rows":
				field.querySelector("textarea").setAttribute("rows", value);
				break;
			case "required":
				field.querySelector(".formski-field-label")
					 .classList[e.target.checked ? "add" : "remove"]("required");
				break;
			case "description": {
				field.firstElementChild.innerHTML = Builder.md.makeHtml(value);
				break;
			}
			case "options": {
				// Remove old values that aren't in new
				[...field.querySelectorAll("input:not([type='hidden']), option")].forEach(i => {
					if (i.nodeName.toLowerCase() === "option")
						i.parentNode.removeChild(i);
					else
						i.parentNode.parentNode.removeChild(i.parentNode);
				});

				// Add new values
				const inputType = field.dataset.type;
				switch (inputType) {
					case "dropdown": {
						const select = field.querySelector("select");
						value.forEach(v => {
							select.appendChild(h("option", {
								value: v.value,
								selected: v.default === "1",
							}, v.label));
						});
						break;
					}
					default: {
						value.forEach(v => {
							field.insertBefore(
								h("label", {}, [
									h("input", {
										type: inputType,
										value: v.value,
										checked: v.default === "1",
									}),
									" ",
									h("span", {}, v.label),
								]),
								field.lastElementChild
							);
						});
						break;
					}
				}

				break;
			}
		}
	};

	// Helpers
	// =========================================================================

	getRowTemplate () {
		return document.importNode(
			this.rowTemplate.content,
			true
		).firstElementChild;
	}

	getFieldTemplate (type) {
		return document.importNode(
			this.fieldTemplates[type].content,
			true
		).firstElementChild;
	}

	getDropZone (type) {
		return document.importNode(
			(type === "h" ? this.dropZoneHTemplate : this.dropZoneVTemplate).content,
			true
		).firstElementChild;
	}

	getUid (l = 5) {
		let array = new Uint8Array(l);
		crypto.getRandomValues(array);
		array = array.map(x => Builder._uidChars.charCodeAt(x % Builder._uidCharsLength));
		return String.fromCharCode.apply(null, array);
	}

	createSettingsField (uiField, uid, type, name, value) {
		const labelId = "label" + this.getUid(10)
			, onSettingChange = this.onSettingChange.bind(this, uiField, name);

		const inputName = `fieldSettings[${uid}][${name}]`;
		let f, cls = "field";

		if (name === "type") {
			f = h("div", { class: "select" }, [
				h("select", {
					name: inputName,
					change: onSettingChange,
				}, [
					h("option", { value: "text" }, "Text"),
					h("option", { value: "email" }, "Email"),
					h("option", { value: "tel" }, "Phone"),
					h("option", { value: "url" }, "URL"),
					h("option", { value: "date" }, "Date"),
					h("option", { value: "time" }, "Time"),
					h("option", { value: "datetime-local" }, "Date Time"),
				]),
			]);

			f.firstElementChild.value = value;
		} else if (name === "description") {
			f = h("textarea", {
				class: "text fullwidth",
				name: inputName,
				input: (e) => {
					e.target.style.height = "";
					e.target.style.height = e.target.scrollHeight + "px";
					onSettingChange(e);
				}
			}, value);

			cls += " wide";
		} else {
			switch (type) {
				case "boolean": {
					f = [
						h("input", {
							type: "hidden",
							name: inputName,
							value: "0",
						}),
						h("input", {
							type: "checkbox",
							name: inputName,
							value: "1",
							input: onSettingChange,
							checked: value,
						}),
					];
					break;
				}
				case "object":
					f = this.createSettingsTable(type, inputName, value, onSettingChange);
					cls += " wide";
					break;
				default: {
					f = h("input", {
						class: "text fullwidth",
						type: type === "number" ? "number" : "text",
						autocomplete: "off",
						name: inputName,
						value,
						input: onSettingChange,
					});
				}
			}
		}

		return h("div", { class: cls }, [
			h("div", { class: "heading" }, [
				h("label", { id: labelId }, this.capitalize(name)),
			]),
			h("div", { class: "input" }, f)
		]);
	}

	getFriendlyTypeName (type) {
		switch (type) {
			case "radio":
				return "Radio Buttons";
			case "textarea":
				return "Text Area";
			case "checkbox":
				return "Checkboxes";
			default:
				return this.capitalize(type);
		}
	}

	capitalize (str) {
		return str[0].toUpperCase() + str.slice(1);
	}

	getRowFieldComment (row) {
		for (let i = 0, l = row.childNodes.length; i < l; ++i)
			if (row.childNodes[i].nodeName === "#comment")
				return row.childNodes[i];

		return null;
	}

	// Helpers: Table
	// -------------------------------------------------------------------------

	createSettingsTable (type, name, value, onSettingChange) {
		let self = null,
			body = null;

		const onChange = () => {
			const out = {};
			const fields = self.querySelectorAll("input, textarea");

			for (let i = 0, l = fields.length; i < l; ++i) {
				const field = fields[i];
				const type = field.getAttribute("type");
				const value = type === "checkbox" ? field.checked : field.value;

				const [,, key, name] =
					/.*(\[.*]){2}\[(.*)]\[(.*)]/.exec(field.getAttribute("name"));

				if (!out.hasOwnProperty(key)) out[key] = {};
				out[key][name] = value;
			}

			onSettingChange({ target: { value: Object.values(out) } });
		};

		const table = h("table", {
			class: "shadow-box editable",
			ref: el => { self = el; }
		}, [
			h("thead", {}, [
				h("tr", {}, [
					h("th", {}, "Label"),
					h("th", {}, "Value"),
					h("th", {}, "Default?"),
					h("th", {}, ""),
				]),
			]),
			h("tbody", {
				ref: el => { body = el; }
			}, Object.values(value).map(value => (
				this.createTableRow(name, value, onChange)
			))),
		]);

		return [
			table,
			h("div", {
				class: "btn add icon",
				click: () => {
					body.appendChild(this.createTableRow(name, null, onChange));
				}
			}, "Add an option"),
		];
	}

	createTableRow (name, value, onChange) {
		const id = this.getUid();
		name = name + "[" + this.getUid() + "]";
		let self = null;

		return h("tr", {
			ref: el => { self = el; }
		}, [
			h("td", {
				class: "textual",
			}, [
				h("textarea", {
					rows: 1,
					input: onChange,
					name: name + "[label]",
				}, value ? value.label: ""),
			]),
			h("td", {
				class: "textual",
			}, [
				h("textarea", {
					rows: 1,
					input: onChange,
					name: name + "[value]",
				}, value ? value.value: ""),
			]),
			h("td", {
				class: "thin",
			}, [
				h("input", {
					type: "hidden",
					value: "0",
					name: name + "[default]",
				}),
				h("input", {
					type: "checkbox",
					value: "1",
					checked: value ? value.default === "1" : false,
					id,
					change: onChange,
					name: name + "[default]",
				}),
				h("label", { for: id }),
			]),
			h("td", {
				class: "thin action"
			}, [
				h("a", {
					class: "delete icon",
					title: "Delete",
					role: "button",
					click: () => {
						self.parentNode.removeChild(self);
						onChange();
					}
				}),
			]),
		]);
	}

}

window.FormskiBuilder = Builder;