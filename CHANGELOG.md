# Changelog

All notable changes to the Universal Client Inventory Report (UCIR) project will be documented in this file.

---

## [Unreleased]

### Planned

- WHMCS compatibility improvements
- Advanced sorting
- Expanded filtering
- Additional export formats

## [1.1.0] - 2026-07-18

### Added

- Hosting recurring amount export field
- Domain recurring amount export field
- Hosting Annual Recurring Revenue (ARR)
- Domain Annual Recurring Revenue (ARR)
- Hosting projected revenue fields for:
  - This month
  - Next 3 months
  - Next 6 months
  - Next 12 months
- Revenue projection controls in the report interface

### Changed

- Improved report field organization and visibility
- Consolidated UCIR interface JavaScript into `html_builder.php`
- Improved hosting revenue projection calculations by counting billing events within each projection period

### Removed

- Obsolete `assets/js/ucir.js` file

### Fixed

- Global Select All Fields and Clear All Fields controls
- Section-level Select All and Select None controls
- Monthly hosting projection values incorrectly using full ARR

## [0.7.0] - 2026-07-10

### Added

### Project

- Initial public Foundation Release
- MIT License
- GitHub project repository
- Project README
- Development roadmap
- Project changelog
- Architecture documentation
- Development standards

#### Reporting Engine

- Complete Inventory Report
- Client Report
- Hosting Services Report
- Domain Report

#### Client Summary

- One row per active client
- Active Hosting Service count
- Active Domain count
- Hosting Service list
- Domain list

#### Report Customization

- Configurable report fields
- Category-based field organization
- Select All / Select None controls
- Group-level field selection

#### Filtering

- Hosting Status filter
- Domain Status filter
- Conditional filter visibility
- Inline validation messages

#### User Interface

- Conditional report field visibility
- Report-specific interface
- Dynamic report selection
- Cleaner report workflow

#### Export Engine

- Dynamic CSV filenames
- CSV field mapping
- Modular export engine

#### Architecture

- Modular query engine
- Data mapper
- Field definition system
- HTML interface builder
- Separated export engine

### Changed

- Reorganized project into modular components.
- Improved report generation workflow.
- Simplified report selection interface.
- Improved validation and user feedback.
- Standardized report generation across all report types.

### Fixed

- Eliminated blank white validation pages.
- Corrected report-type switching.
- Corrected conditional field submission.
- Improved report filtering behavior.
- Improved inventory mapping consistency.

---

## Future Releases

Future releases will be documented here as development progresses.
