# Changelog

All notable changes to this project are documented in this file.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project uses semantic version tags.

## [Unreleased]

### Added

- MIT license file for repository-level license clarity.
- Release checklist for fresh install, API, admin, import, Docker, and release
  notes verification.

### Changed

- README now reflects the current `0.3.1` application state, `/api/v1` public
  API, `/admin` Filament panel, `/health` endpoint, and Docker Compose runtime.
- Project structure notes now describe implemented modules instead of planned
  repository, DTO, or console command layers.

### Documentation

- Added current capabilities, roadmap, quality commands, and license sections
  to the README.
- Clarified that exercise dataset import is a Filament admin workflow at
  `/admin/import-exercises`.
- Cross-checked API, admin, deployment, and production checklist docs for
  consistency with the current routes and setup.

### Infrastructure

- Documented the existing CI and Docker publish workflows without changing
  application behavior.

## [0.3.1] - 2026-05-07

### Changed

- Release baseline for the current portfolio polish pass.
- Fixed the default app name used by the health endpoint release path.

### Documentation

- Production readiness documentation and checklist are available for deployment
  verification.

### Infrastructure

- Docker publishing workflow builds release images with SBOM and provenance
  attestations.
