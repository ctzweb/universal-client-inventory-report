# UCIR Roadmap

This roadmap outlines the planned development of the Universal Client Inventory Report (UCIR).

UCIR is being developed as a free, open-source reporting framework for WHMCS. The roadmap may evolve based on testing, platform changes, and community feedback.

---

## Completed

### Phase 01 — Baseline

- Initial WHMCS report interface
- Custom field selection
- CSV export
- Active client reporting
- Active hosting service reporting
- Active domain reporting

### Phase 02 — Report Types

- Complete Inventory Report
- Client Report
- Hosting Services Report
- Domain Report
- Dynamic CSV filenames

### Phase 03 — Client Summary

- One row per active client
- Hosting Service Count
- Domain Count
- Hosting Services list
- Domains list

### Phase 04 — Hosting Status Filtering

- Hosting Status filter
- Active, Suspended, Pending, Cancelled, and Terminated options
- Select All and Select None controls
- Inline validation and reporting messages

### Phase 05 — Domain Status Filtering

- Domain Status filter
- Active, Expired, Grace, Redemption, Pending, and Cancelled options
- Select All and Select None controls
- Domain filtering for Domain, Client, and Complete Inventory reports

### Phase 06 — Conditional Filters

- Hosting Status shown only when applicable
- Domain Status shown only when applicable
- Report interface adapts to the selected report type

### Phase 07 — Conditional Report Fields

- Report field groups shown only when relevant
- Hidden fields disabled so they are not submitted
- Cleaner report-specific interface

---

## Current Development

### Project Foundation and Documentation

- GitHub repository setup
- README
- Roadmap
- Changelog
- License
- Architecture documentation
- Development standards
- Standardized source file headers
- First public release preparation

---

## Compatibility and Modernization

Before major new feature development resumes, UCIR will undergo a compatibility review.

Planned work includes:

- Upgrade the primary WHMCS test installation
- Test UCIR against a current stable WHMCS release
- Identify and resolve version-specific issues
- Verify compatibility with older WHMCS 8.x installations where practical
- Minimize version-dependent code
- Document supported WHMCS and PHP versions
- Create a repeatable regression-testing checklist
- Maintain backward compatibility whenever reasonably practical

The project goal is to support as many actively maintained WHMCS versions as practical without compromising reliability or maintainability.

---

## Planned Features

### Phase 08 — Sorting

- Sort by client name
- Sort by company
- Sort by product
- Sort by service domain
- Sort by domain name
- Sort by server
- Sort by registration date
- Sort by next due date
- Ascending and descending order

### Additional Filters

- Product filter
- Server filter
- Registrar filter
- Billing cycle filter
- Client group filter
- Country filter
- Currency filter
- Date-range filters

### Export Formats

- Microsoft Excel
- PDF
- JSON
- XML
- Print-friendly reports

### Report Management

- Saved report profiles
- Reusable field selections
- Reusable filter selections
- Scheduled report generation
- Email delivery
- Report history

### Additional Report Types

- Expiring domains
- Suspended services
- Hosting without domains
- Domains without hosting
- Revenue summaries
- Server distribution
- Registrar distribution
- Billing summaries
- Additional WHMCS inventory modules

---

## Long-Term Vision

UCIR may eventually grow into a modular reporting platform for WHMCS.

Potential areas of future development include:

- Plugin and extension architecture
- Developer-defined report types
- Additional inventory modules
- API access
- Dashboard summaries
- Charts and visual reporting
- Custom calculated fields
- Role-based report access
- Importable and exportable report profiles

---

## Project Branding and Website

Future branding work may include:

- UCIR logo and wordmark
- GitHub social-preview image
- Cortez Web Services project page
- Screenshots and interface examples
- Documentation styling
- About UCIR page
- PayPal donation option
- Release graphics

UCIR branding should complement the Cortez Web Services identity while remaining distinct as its own project.

---

## Open-Source and Community Goals

UCIR will remain free and open source.

Community goals include:

- Public issue tracking
- GitHub Discussions
- Community testing
- Documentation contributions
- Code contributions
- Feature suggestions
- Voluntary project support

---

## Release Goals

### v0.7.0 — Foundation Release

Planned as the first public development release.

Includes:

- Four report types
- Client Summary reporting
- Custom field selection
- Hosting Status filtering
- Domain Status filtering
- Conditional filters
- Conditional report fields
- Dynamic CSV filenames
- Inline validation
- Project documentation
- Open-source license

### v1.0.0 — Initial Stable Release

The initial stable release will follow:

- Compatibility testing
- Installation documentation
- Regression testing
- License finalization
- Source header standardization
- Stable upgrade and release procedures

---

## Future Ideas

Ideas under consideration include:

- Additional export formats
- Saved and shareable reports
- Scheduled reports
- Email delivery
- Financial reporting
- Charts and dashboards
- Custom fields
- Third-party WHMCS module support
- Optional donation support
- Website-hosted documentation
