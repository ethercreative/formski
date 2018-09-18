/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./resources/js/builder.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/builder.js":
/*!*********************************!*\
  !*** ./resources/js/builder.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance"); }

function _iterableToArrayLimit(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance"); }

function _iterableToArray(iter) { if (Symbol.iterator in Object(iter) || Object.prototype.toString.call(iter) === "[object Arguments]") return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = new Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var Builder =
/*#__PURE__*/
function () {
  // Properties
  // =========================================================================
  // Properties: Static
  // -------------------------------------------------------------------------
  // Properties: Instance
  // -------------------------------------------------------------------------
  // Constructor
  // =========================================================================
  function Builder() {
    var _this = this;

    _classCallCheck(this, Builder);

    _defineProperty(this, "editingField", null);

    _defineProperty(this, "editingSettings", null);

    _defineProperty(this, "onFieldTypeDrag", function (field, e) {
      e.dataTransfer.setData("text/field", field.dataset.type);
    });

    _defineProperty(this, "onDropZoneDragOver", function (zone, e) {
      e.preventDefault();
      if (!_toConsumableArray(event.dataTransfer.types).includes("text/field")) return;
      zone.classList.add("drop");
    });

    _defineProperty(this, "onDropZoneDragLeave", function (zone) {
      zone.classList.remove("drop");
    });

    _defineProperty(this, "onDropZoneDrop", function (zone, zoneType, e) {
      e.preventDefault();
      zone.classList.remove("drop");
      var type = e.dataTransfer.getData("text/field");
      if (zoneType === "h") _this.addFieldToForm(zone, type);else _this.addFieldToRow(zone, type);
    });

    _defineProperty(this, "onFieldClick", function (field, e) {
      e.preventDefault();

      _this.editField(field);
    });

    _defineProperty(this, "onDeleteFieldClick", function (uid, e) {
      e.preventDefault();
      if (confirm("Are you sure?")) _this.deleteFieldByUid(uid);
    });

    this.fieldsWrap = document.getElementById("formskiFields");
    this.formWrap = document.getElementById("formskiForm");
    this.settingsWrap = document.getElementById("field-settings");
    this.initFieldTemplates();
    this.initDragDrop();
    this.bindClickEvents();
  } // Init
  // =========================================================================
  // Init: Fields
  // -------------------------------------------------------------------------


  _createClass(Builder, [{
    key: "initFieldTemplates",
    value: function initFieldTemplates() {
      this.rowTemplate = document.getElementById("formskiRow");
      this.dropZoneHTemplate = document.getElementById("formskiDropZoneH");
      this.dropZoneVTemplate = document.getElementById("formskiDropZoneV");
      this.fieldTemplates = {};
      var fieldTemplates = document.querySelectorAll("[data-formski-field]");

      for (var i = 0, l = fieldTemplates.length; i < l; ++i) {
        var tmpl = fieldTemplates[i];
        this.fieldTemplates[tmpl.dataset.formskiField] = tmpl;
      }
    } // Init: Drag / Drop
    // -------------------------------------------------------------------------

  }, {
    key: "initDragDrop",
    value: function initDragDrop() {
      var fields = this.fieldsWrap.querySelectorAll("[data-type]");

      for (var i = 0, l = fields.length; i < l; ++i) {
        var field = fields[i];
        field.addEventListener("dragstart", this.onFieldTypeDrag.bind(this, field));
      }

      this.bindDropZones();
    } // Actions
    // =========================================================================
    // Actions: Add Field
    // -------------------------------------------------------------------------

  }, {
    key: "createField",
    value: function createField(row, type) {
      // Get the field template & a UID
      var field = this.getFieldTemplate(type),
          rowUid = row.dataset.rowUid,
          uid = this.getUid(); // Set the UID data

      field.setAttribute("data-uid", uid); // Add layout input

      var layout = document.createElement("input");
      layout.setAttribute("type", "hidden");
      layout.setAttribute("name", "fieldLayout[" + rowUid + "][]");
      layout.value = uid;
      field.appendChild(layout); // Create the settings

      this.createFieldSettings(type, uid);
      return field;
    }
  }, {
    key: "createRow",
    value: function createRow(form, before) {
      var row = this.getRowTemplate(),
          uid = this.getUid(); // Row UID

      row.setAttribute("data-row-uid", uid); // Row layout input

      var layout = document.createElement("input");
      layout.setAttribute("type", "hidden");
      layout.setAttribute("name", "rowLayout[]");
      layout.value = uid;
      row.appendChild(layout);
      return form.insertBefore(row, before);
    }
  }, {
    key: "addFieldToForm",
    value: function addFieldToForm(before, type) {
      var form = before.parentNode; // Add Row

      var row = this.createRow(form, before); // Add Field

      var field = this.createField(row, type);
      row.replaceChild(field, row.childNodes[3]); // Add new H drop zone

      form.insertBefore(this.getDropZone("h"), row); // Bind new drop zones

      this.bindDropZones(); // Bind new clicks

      this.bindClickEvents(); // Edit Field

      this.editField(field);
    }
  }, {
    key: "addFieldToRow",
    value: function addFieldToRow(before, type) {
      var row = before.parentNode; // Add Field

      var field = this.createField(row, type);
      row.insertBefore(field, before); // Add new V drop zone

      row.insertBefore(this.getDropZone("v"), field); // Bind new drop zones

      this.bindDropZones(); // Bind new clicks

      this.bindClickEvents(); // Edit Field

      this.editField(field);
    } // Actions: Edit Field
    // -------------------------------------------------------------------------

  }, {
    key: "editField",
    value: function editField(field) {
      // Skip if already editing
      if (this.editingField === field) return; // Add edit class to field

      if (this.editingField !== null) this.editingField.classList.remove("edit");
      this.editingField = field;
      this.editingField.classList.add("edit"); // Show settings

      if (this.editingSettings !== null) this.editingSettings.classList.add("hidden");
      this.editingSettings = this.settingsWrap.querySelector("[data-uid='" + field.dataset.uid + "']");
      this.editingSettings.classList.remove("hidden");
    } // Actions: Delete Field
    // -------------------------------------------------------------------------

  }, {
    key: "deleteFieldByUid",
    value: function deleteFieldByUid(uid) {
      // Remove settings
      var settings = this.settingsWrap.querySelector("[data-uid='" + uid + "']");
      settings.parentNode.removeChild(settings); // Remove field

      var field = this.formWrap.querySelector("[data-uid='" + uid + "']");
      var row = field.parentNode,
          beingEdited = field === this.editingField;

      if (row.children.length === 4) {
        // If field is only field in row, remove the row and next drop zone
        var parent = row.parentNode;
        var dropZone = row.nextElementSibling;
        parent.removeChild(row);
        parent.removeChild(dropZone);
      } else {
        // Else remove the field and next drop zone
        var _dropZone = field.nextElementSibling;
        row.removeChild(field);
        row.removeChild(_dropZone);
      } // If active, select another field


      if (beingEdited) {
        this.editingField = null;
        this.editingSettings = null;
        var nextField = this.formWrap.querySelector("[data-uid]");
        if (nextField) this.editField(nextField);
      }
    } // Actions: Field Settings
    // -------------------------------------------------------------------------

  }, {
    key: "createFieldSettings",
    value: function createFieldSettings(type, uid) {
      var fieldSettings = {
        handle: "",
        label: "Label",
        instructions: "",
        required: false
      };

      switch (type) {
        case "text":
          fieldSettings.type = "text";
          fieldSettings.placeholder = "";
          break;

        case "textarea":
          fieldSettings.type = "text";
          fieldSettings.placeholder = "";
          fieldSettings.rows = 5;
          break;

        case "dropdown":
        case "radio":
        case "checkbox":
          fieldSettings.options = [{
            label: "Label",
            value: "value",
            default: false
          }];
          break;
      } // Settings wrapping div


      var settingsDiv = document.createElement("div");
      settingsDiv.setAttribute("data-uid", uid);
      settingsDiv.className = "meta hidden";
      this.settingsWrap.appendChild(settingsDiv); // Field Type

      var fieldType = document.createElement("div");
      fieldType.classList.add("formski-settings-type");
      fieldType.textContent = this.getFriendlyTypeName(type);
      settingsDiv.appendChild(fieldType); // Field Type input

      var typeInput = document.createElement("input");
      typeInput.setAttribute("type", "hidden");
      typeInput.setAttribute("name", "fieldSettings[".concat(uid, "][_type]"));
      typeInput.value = type;
      settingsDiv.appendChild(typeInput); // Settings Fields

      var _arr = Object.entries(fieldSettings);

      for (var _i = 0; _i < _arr.length; _i++) {
        var _arr$_i = _slicedToArray(_arr[_i], 2),
            name = _arr$_i[0],
            value = _arr$_i[1];

        var field = this.createSettingsField(uid, _typeof(value), name, value);
        settingsDiv.appendChild(field);
      } // Footer


      var footer = document.createElement("footer");
      footer.className = "footer";
      settingsDiv.appendChild(footer); // Delete

      var deleteBtn = document.createElement("button");
      deleteBtn.textContent = "Delete";
      deleteBtn.className = "btn small";
      deleteBtn.setAttribute("type", "button");
      deleteBtn.addEventListener("click", this.onDeleteFieldClick.bind(this, uid));
      footer.appendChild(deleteBtn);
    } // Events
    // =========================================================================
    // Events: Drag / Drop Binders
    // -------------------------------------------------------------------------

  }, {
    key: "bindDropZones",
    value: function bindDropZones() {
      var dropZones = this.formWrap.querySelectorAll("[class*='formski-form-drop']");

      for (var i = 0, l = dropZones.length; i < l; ++i) {
        var dropZone = dropZones[i];
        if (dropZone.dropBound === true) continue;
        dropZone.dropBound = true;
        var type = ~dropZone.className.indexOf("-h") ? "h" : "v";
        dropZone.addEventListener("dragover", this.onDropZoneDragOver.bind(this, dropZone));
        dropZone.addEventListener("dragleave", this.onDropZoneDragLeave.bind(this, dropZone));
        dropZone.addEventListener("drop", this.onDropZoneDrop.bind(this, dropZone, type));
      }
    } // Events: Drag / Drop Handlers
    // -------------------------------------------------------------------------

  }, {
    key: "bindClickEvents",
    // Events: Click Binders
    // -------------------------------------------------------------------------
    value: function bindClickEvents() {
      var fields = this.formWrap.getElementsByClassName("formski-field");

      for (var i = 0, l = fields.length; i < l; ++i) {
        var field = fields[i];
        if (field.clickBound === true) continue;
        field.clickBound = true;
        field.addEventListener("click", this.onFieldClick.bind(this, field));
      }
    } // Events: Click Handlers
    // -------------------------------------------------------------------------

  }, {
    key: "getRowTemplate",
    // Helpers
    // =========================================================================
    value: function getRowTemplate() {
      return document.importNode(this.rowTemplate.content, true).firstElementChild;
    }
  }, {
    key: "getFieldTemplate",
    value: function getFieldTemplate(type) {
      return document.importNode(this.fieldTemplates[type].content, true).firstElementChild;
    }
  }, {
    key: "getDropZone",
    value: function getDropZone(type) {
      return document.importNode((type === "h" ? this.dropZoneHTemplate : this.dropZoneVTemplate).content, true).firstElementChild;
    }
  }, {
    key: "getUid",
    value: function getUid() {
      var l = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 5;
      var array = new Uint8Array(l);
      crypto.getRandomValues(array);
      array = array.map(function (x) {
        return Builder._uidChars.charCodeAt(x % Builder._uidCharsLength);
      });
      return String.fromCharCode.apply(null, array);
    }
  }, {
    key: "createSettingsField",
    value: function createSettingsField(uid, type, name, value) {
      var labelId = "label" + this.getUid(10);
      var field = document.createElement("div");
      field.classList.add("field");
      var heading = document.createElement("div");
      heading.classList.add("heading");
      field.appendChild(heading);
      var label = document.createElement("label");
      label.setAttribute("id", labelId);
      label.textContent = this.capitalize(name);
      heading.appendChild(label);
      var input = document.createElement("div");
      input.classList.add("input");
      field.appendChild(input);
      var inputName = "fieldSettings[".concat(uid, "][").concat(name, "]");

      if (name === "type") {
        var wrap = document.createElement("div");
        wrap.className = "select";
        input.appendChild(wrap);
        var f = document.createElement("select");
        f.setAttribute("name", inputName);
        [["text", "Text"], ["email", "Email"], ["tel", "Phone"], ["url", "URL"], ["date", "Date"], ["time", "Time"], ["datetime-local", "Date Time"]].forEach(function (opt) {
          var o = document.createElement("option");
          o.setAttribute("value", opt[0]);
          o.textContent = opt[1];
          f.appendChild(o);
        });
        f.value = value;
        wrap.appendChild(f);
        return field;
      }

      switch (type) {
        case "boolean":
          input.textContent = "TODO: Lightswitch";
          break;

        case "object":
          input.textContent = "TODO: Table";
          break;

        default:
          {
            var _f = document.createElement("input");

            _f.className = "text fullwidth";

            _f.setAttribute("type", type === "number" ? "number" : "text");

            _f.setAttribute("autocomplete", "off");

            _f.setAttribute("name", inputName);

            _f.value = value;
            input.appendChild(_f);
          }
      }

      return field;
    }
  }, {
    key: "getFriendlyTypeName",
    value: function getFriendlyTypeName(type) {
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
  }, {
    key: "capitalize",
    value: function capitalize(str) {
      return str[0].toUpperCase() + str.slice(1);
    }
  }]);

  return Builder;
}();

_defineProperty(Builder, "_uidChars", "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789");

_defineProperty(Builder, "_uidCharsLength", Builder._uidChars.length);

window.FormskiBuilder = Builder;

/***/ })

/******/ });
//# sourceMappingURL=builder.js.map