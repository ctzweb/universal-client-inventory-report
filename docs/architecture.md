# UCIR Architecture

Universal Client Inventory Report (UCIR)

---

# Purpose

The Universal Client Inventory Report (UCIR) is designed as a modular reporting framework for WHMCS.

Rather than producing a single predefined report, UCIR provides a reusable architecture that allows administrators to generate customized reports from multiple WHMCS inventory sources.

The architecture is designed to be:

- Modular
- Extensible
- Maintainable
- Readable
- Compatible with multiple WHMCS versions whenever practical

---

# Design Philosophy

UCIR follows several guiding principles.

## Separation of Responsibilities

Each component has a single primary responsibility.

Examples include:

- User Interface
- Database Queries
- Data Mapping
- Field Definitions
- Export Generation

Keeping these responsibilities separate makes the project easier to understand, test, and extend.

---

## Modular Design

New features should be added by extending existing modules rather than modifying unrelated components.

Examples include:

- New report types
- Additional filters
- Additional export formats
- New inventory modules

Whenever possible, new functionality should integrate with the existing framework instead of creating parallel implementations.

---

## Readability

UCIR favors readable, well-organized code over clever or highly condensed solutions.

Code should be easy to understand by future contributors and maintainers.

---

## Compatibility

Whenever practical, UCIR should support multiple actively maintained WHMCS versions.

Version-specific code should be minimized and clearly documented when unavoidable.

---

# High-Level Architecture

The reporting workflow follows a layered architecture.

```
Administrator

        │

        ▼

HTML Interface

        │

        ▼

Query Engine

        │

        ▼

Data Mapper

        │

        ▼

Filtering

        │

        ▼

Sorting

        │

        ▼

Export Engine

        │

        ▼

CSV
Excel (planned)
PDF (planned)
JSON (planned)
XML (planned)
```

Each layer performs one specific task before passing the data to the next stage.

---

# Component Overview

## HTML Interface

Responsible for:

- Report selection
- Field selection
- Filter selection
- Sorting options
- User interaction
- Validation messages

The interface should adapt dynamically to the selected report type whenever possible.

---

## Query Engine

Responsible for retrieving raw data from WHMCS.

Current query modules include:

- Client Queries
- Hosting Service Queries
- Domain Queries

Future inventory modules should provide their own query components.

---

## Data Mapper

The Data Mapper converts raw WHMCS database records into a consistent internal format.

This allows the export engine to work with standardized data regardless of the original database structure.

The mapper is responsible for:

- Client Summary calculations
- Inventory normalization
- Consistent field naming

---

## Field Definitions

Field Definitions provide a central location for describing every available export field.

Each field includes:

- Identifier
- Display name
- Category
- Source
- Mapping
- Default visibility

Adding a new export field should normally require changes only to:

- Field Definitions
- Data Mapper

---

## Export Engine

The Export Engine converts standardized inventory records into export formats.

Current format:

- CSV

Planned formats:

- Microsoft Excel
- PDF
- JSON
- XML

Future export formats should use the same standardized inventory records whenever possible.

---

# Report Generation Lifecycle

Every report follows the same lifecycle.

1. Administrator selects report options.
2. Interface validates user input.
3. Query Engine retrieves data.
4. Data Mapper standardizes records.
5. Filters are applied.
6. Sorting is applied.
7. Export Engine generates the selected output format.

This consistent workflow allows new report types and export formats to integrate with minimal architectural changes.

---

# Extensibility

UCIR is intended to grow over time.

Potential future modules include:

- Invoices
- SSL Certificates
- Support Tickets
- Quotes
- Licensing
- Custom Fields
- Third-party WHMCS modules

The architecture is intended to support these additions without requiring major redesign.

---

# Compatibility Strategy

The project aims to support as many actively maintained WHMCS versions as practical.

Compatibility testing should be performed before major public releases.

Version-specific code should be isolated whenever possible.

---

# Future Direction

The long-term vision for UCIR is to evolve from an inventory reporting module into a complete reporting framework for WHMCS.

Future enhancements should continue to build upon the existing architecture rather than replacing it.
