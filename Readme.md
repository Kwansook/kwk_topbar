# KWK Topbar - PrestaShop Module

![PrestaShop Compatibility](https://img.shields.io/badge/PrestaShop-8.0.0%2B-blue)
![License](https://img.shields.io/badge/license-AFL%203.0-green)

A PrestaShop module to add customizable top bars with dynamic content, rotation, and color settings to your store's front-office.

## Features
- Manage multiple top bars via a dedicated admin interface.
- Configure content, start/end dates, background/text colors, optional links, and target blank.
- Set rotation interval and default colors in the admin.
- Smooth CSS transitions for message rotation (default: 5 seconds).
- Full translation support (`Modules.Kwk_topbar.Admin` and `Modules.Kwk_topbar.Shop`).
- Responsive design with vertical centering (2rem height).
- Includes test data for quick setup.

## Requirements
- PrestaShop 8.0.0 or higher
- PHP 7.2 or higher

## Installation
1. Download the `kwk_topbar` folder.
2. Place it in the `modules/` directory of your PrestaShop installation.
3. Go to **Modules > Module Manager** in the PrestaShop back-office and install the module.
4. Configure general settings (rotation interval, default colors) under **Modules > Kwk Topbar > Configure**.
5. Manage top bars under **Design > Top Bar**.

## Configuration
- **General Settings**: Set the rotation interval (in seconds) and default background/text colors.
- **Top Bar Management**: Add, edit, or delete top bars with content, dates, colors, and optional links.

## Changelog
### Version 1.0.1
- Added database table (`ps_kwk_topbar`) for top bar management.
- Implemented CRUD interface under "Modules" in the admin panel.
- Added configurable rotation interval via `data-rotation-interval`.
- Integrated translations for all texts.
- Ensured compatibility with PrestaShop 8.0.0+.

## License
This module is licensed under the [Academic Free License (AFL 3.0)](http://opensource.org/licenses/afl-3.0.php).

## Support
For issues or contributions, please open an issue or pull request on [GitHub](https://github.com/[your-username]/kwk_topbar).