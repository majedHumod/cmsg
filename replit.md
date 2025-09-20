# Overview

This is a Laravel-based content management system designed for Arabic-speaking users. The application appears to be a comprehensive CMS platform with advanced features including user management, content editing, and multi-language support. The project is built on Laravel 12 with modern frontend technologies including Alpine.js, Tailwind CSS, and Vite for asset compilation.

# User Preferences

Preferred communication style: Simple, everyday language.

# Recent Changes

- **2025-09-20**: Successfully set up Laravel application for Replit environment
  - Configured Vite dev server to run on port 5000 with host `0.0.0.0`
  - Set up multi-service workflow running Laravel backend (port 8000), Vite frontend (port 5000), queue worker, and log tailing
  - Database migrations completed with SQLite setup
  - Deployment configuration added for production using VM target
  - All dependencies installed and verified working

# System Architecture

## Backend Architecture
- **Framework**: Laravel 12 with PHP 8.2+ requirement
- **Authentication**: Laravel Jetstream with Fortify for user authentication and registration
- **Authorization**: Spatie Laravel Permission package for role-based access control
- **Frontend Integration**: Livewire 3.0 for dynamic server-side rendering and reactivity

## Frontend Architecture
- **CSS Framework**: Tailwind CSS with forms and typography plugins
- **JavaScript Framework**: Alpine.js for lightweight client-side interactivity
- **Build Tool**: Vite with Laravel plugin for modern asset compilation
- **Responsive Design**: Mobile-first approach with RTL (Right-to-Left) support for Arabic content

## Development Features
- **Hot Module Replacement**: Enabled through Vite for faster development
- **Performance Optimization**: Build configuration includes code splitting, minification, and vendor chunking
- **Asset Management**: Optimized CSS code splitting and chunk size management

## Content Management Features
- **Advanced Editor**: Block-based editor system for flexible content creation
- **Template System**: Dynamic template engine for customizable page layouts
- **Media Library**: Advanced media management capabilities
- **Version Control**: Content revision system for tracking changes

## Planned Integrations
- **Payment Processing**: Multi-gateway payment system supporting various Middle Eastern payment methods
- **Communication**: Twilio SDK integration for SMS and communication features
- **API Support**: Plans for comprehensive REST API and GraphQL implementation

## Project Structure
- Standard Laravel directory structure with organized resource files
- Comprehensive documentation in Arabic outlining development phases
- Implementation plan spanning multiple development phases with clear milestones

# External Dependencies

## Core Dependencies
- **Laravel Framework**: Version 12.0 - Primary web application framework
- **Laravel Jetstream**: Version 5.3 - Authentication scaffolding with team management
- **Laravel Sanctum**: Version 4.0 - API token authentication
- **Livewire**: Version 3.0 - Full-stack framework for building dynamic interfaces

## Frontend Dependencies
- **Alpine.js**: Version 3.14.9 - Lightweight JavaScript framework
- **Tailwind CSS**: Version 3.4.0 - Utility-first CSS framework
- **Vite**: Version 6.0.11 - Modern build tool and development server
- **Axios**: Version 1.7.4 - HTTP client for API requests

## Utility Packages
- **Spatie Laravel Permission**: Version 6.16 - Role and permission management
- **Twilio SDK**: Latest - SMS and communication services
- **FakerPHP**: Version 1.23+ - Data generation for testing
- **Laravel Pint**: Version 1.13 - Code style fixer

## Development Tools
- **Laravel Sail**: Version 1.41 - Docker development environment
- **Laravel Pail**: Version 1.2.2 - Log viewer
- **PHPUnit**: Version 11.5.3 - Testing framework
- **Mockery**: Version 1.6 - Mocking framework for tests

## Build Dependencies
- **PostCSS**: Version 8.4.32 - CSS transformation tool
- **Autoprefixer**: Version 10.4.16 - CSS vendor prefixing
- **Concurrently**: Version 9.0.1 - Run multiple commands simultaneously