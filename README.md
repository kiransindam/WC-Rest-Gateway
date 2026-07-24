# WC-Rest-Gateway

[![License: GPL v2](https://img.shields.io/badge/License-GPL_v2-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![WooCommerce](https://img.shields.io/badge/WooCommerce-8.0%2B-purple.svg)](https://woocommerce.com)
[![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-blue.svg)](https://wordpress.org)

A production-ready WooCommerce payment gateway that integrates with custom REST API payment providers, featuring secure webhook verification and automated CI/CD deployment.

## 🚀 Features

- **REST API Integration**: Process payments via external REST API providers
- **Secure Webhooks**: HMAC-SHA256 signature verification for webhook endpoints
- **WooCommerce Native**: Fully integrated with WooCommerce checkout flow
- **HPOS Compatible**: Supports WooCommerce High-Performance Order Storage
- **Test Mode**: Built-in sandbox mode for testing without real transactions
- **Automated Testing**: PHPUnit test suite included
- **CI/CD Ready**: GitHub Actions workflow for automated releases

## 📋 Requirements

- WordPress 6.0 or higher
- WooCommerce 8.0 or higher
- PHP 7.4 or higher
- MySQL 5.7 or higher

## 📦 Installation

### Method 1: Download from GitHub Releases (Recommended)

1. Go to the [Releases page](../../releases)
2. Download the latest `wc-rest-gateway.zip`
3. In WordPress admin, navigate to **Plugins → Add New → Upload Plugin**
4. Upload the zip file and click **Install Now**
5. Activate the plugin

### Method 2: Manual Installation

1. Download or clone this repository:
   ```bash
   git clone https://github.com/YOUR_USERNAME/wc-rest-gateway.git
