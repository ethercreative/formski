## 1.0.0-alpha.11 - 2018-11-28
### Fixed
- Fixed some content columns not being created when saving a form. **You will need to re-save all your forms!**

## 1.0.0-alpha.10 - 2018-11-26
### Changed
- `Form::overdue` now expects `DateTime` as its only argument.

### Improved
- Form and Submission's Find now type-hints for the correct class.

## 1.0.0-alpha.9 - 2018-11-23
### Fixed
- Fixed JS error when dragging new form fields

## 1.0.0-alpha.8 - 2018-11-23
### Improved
- Builder fields list and settings now sticky

## 1.0.0-alpha.7 - 2018-08-25
### Fixed
- Submission accounts for fields without labels

## 1.0.0-alpha.6 - 2018-08-25
### Added
- Added `overdue(User $user)` method to Forms

### Fixed
- Fixed `Days to Complete` not saving

## 1.0.0-alpha.5 - 2018-08-25
### Fixed
- Actually registering the field this time 😐 

## 1.0.0-alpha.4 - 2018-08-25
### Added
- Added form relational field

## 1.0.0-alpha.3 - 2018-08-21
### Added
- Added `{% craft.submissions.form(form).userId(userId).all() %}`

### Fixed
- Fixed setting `userId` on `craft.submissions` causing error

## 1.0.0-alpha.2 - 2018-08-21
### Fixed
- Fixed checkbox DB column type
- Fixed acceptance validation

## 1.0.0-alpha.1 - 2018-08-21
### Changed
- Initial Release