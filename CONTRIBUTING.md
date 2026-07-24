# Contributing to WooCommerce REST API Gateway

Thank you for your interest in contributing! This document provides guidelines and information for contributors.

## Code of Conduct

This project follows the [WordPress Code of Conduct](https://wordpress.org/about/code-of-conduct/). Please be respectful and inclusive in all interactions.

## How to Contribute

### Reporting Bugs

1. Check if the issue already exists in [GitHub Issues](../../issues)
2. Create a new issue with:
   - Clear title and description
   - Steps to reproduce
   - Expected vs actual behavior
   - WordPress/WooCommerce versions
   - PHP version

### Suggesting Features

1. Open a [GitHub Discussion](../../discussions) or Issue
2. Describe the feature and its benefits
3. Provide use cases if possible

### Submitting Pull Requests

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Follow WordPress Coding Standards
4. Write tests for new functionality
5. Commit your changes: `git commit -m 'Add amazing feature'`
6. Push to your branch: `git push origin feature/amazing-feature`
7. Open a Pull Request

## Development Setup

### Prerequisites

- PHP 7.4+
- Composer
- Node.js (optional, for asset compilation)
- Local WordPress environment (LocalWP, Docker, etc.)

### Installation

```bash
# Clone your fork
git clone https://github.com/YOUR_USERNAME/wc-rest-gateway.git

# Install dependencies
composer install

# Set up local WordPress with WooCommerce
# (Use LocalWP or your preferred method)
