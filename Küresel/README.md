# Küresel Etki Zinciri (Global Impact Chain)

## Project Overview

A blockchain-based supply chain transparency platform that revolutionizes consumer understanding of environmental and social impacts of products.

### Vision
Reshape the consumption economy based on transparency and accountability, creating a sustainable and ethical global supply chain ecosystem.

### Mission  
Deliver the story of each product, along with its environmental and social costs, to the end consumer in an immutable and verifiable way.

## Core Values

- **Transparency**: A system with no hidden doors, where the source of every data is known
- **Accountability**: An environment where companies must prove their claims
- **Trust**: Immutable and reliable recording ensured by blockchain technology
- **Fair Competition**: A market where companies compete not only on price but also on ethics and sustainability values

## Architecture

The system consists of four main actors and three fundamental layers:

### Actors:
1. **Supply Chain Participants**: All companies in the process from raw materials to retail
2. **Independent Auditors & Validators**: Organizations that verify data through random sampling
3. **Consumers**: Individuals who purchase products and scan QR codes
4. **Platform Administrators**: Consortium responsible for network health and protocol development

### Layers:
1. **Data Entry & Validation Layer**: Data input, smart contract validation, blockchain recording
2. **Data Processing & Calculation Layer**: Impact score calculation, environmental and social metrics
3. **End User Interface Layer**: QR code scanning, product profile display

## Technology Stack

- **Backend**: PHP 8.x with modern OOP principles
- **Database**: MySQL for traditional data storage
- **Blockchain**: Smart contracts for immutable data storage
- **Frontend**: HTML5, CSS3, JavaScript
- **QR Codes**: PHP QR code generation and scanning
- **APIs**: RESTful architecture

## Getting Started

### Prerequisites

- PHP 8.0 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Composer
- Node.js (for frontend development)

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd Küresel
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   # Edit .env with your database and blockchain configuration
   ```

4. **Set up database**
   
   **Option A: Using MySQL Command Line**
   ```bash
   mysql -u root -p
   CREATE DATABASE kuresel_etki_zinciri CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   exit;
   mysql -u root -p kuresel_etki_zinciri < database/install_safe.sql
   ```
   
   **Option B: Using Installation Script**
   ```bash
   php install.php
   ```
   
   **Option C: Manual phpMyAdmin Import**
   - Create database `kuresel_etki_zinciri`
   - Import `database/install_safe.sql`

5. **Configure web server**
   - Point document root to the project directory
   - Enable mod_rewrite (Apache) or equivalent (Nginx)

6. **Access the application**
   - URL: `http://localhost/Küresel`
   - Admin login: `admin@kuresaletzinciri.com` / `password`

### Quick Start with XAMPP

1. Copy project to `C:\xampp\htdocs\Küresel`
2. Start Apache and MySQL in XAMPP
3. Create database `kuresel_etki_zinciri` in phpMyAdmin
4. Import `database/install_safe.sql` in phpMyAdmin
5. Edit `.env` file with database credentials:
   ```env
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=kuresel_etki_zinciri
   DB_USERNAME=root
   DB_PASSWORD=
   ```
6. Run `composer install` in project directory
7. Access `http://localhost/Küresel`
8. Login with: `admin@kuresaletzinciri.com` / `password`

## API Endpoints

All API endpoints are prefixed with `/api/v1/`:

### Authentication Endpoints

- `POST /api/v1/auth/login` - User login
- `POST /api/v1/auth/register` - User registration
- `POST /api/v1/auth/logout` - User logout
- `GET /api/v1/auth/profile` - Get user profile

### Product Endpoints

- `GET /api/v1/products` - List products
- `GET /api/v1/products/{uuid}` - Get product details
- `POST /api/v1/products` - Create product (company only)
- `PUT /api/v1/products/{uuid}` - Update product (company only)

### QR Code Endpoints

- `POST /api/v1/qr/scan` - Scan QR code and get product info

### Blockchain Endpoints

- `GET /api/v1/blockchain/status` - Get blockchain network status

## Directory Structure

```
Küresel/
├── api/                    # REST API endpoints
│   ├── router.php         # Main API router
│   └── v1/                # API version 1
│       ├── auth/          # Authentication endpoints
│       ├── products/      # Product endpoints
│       ├── companies/     # Company endpoints
│       ├── qr/           # QR code endpoints
│       └── blockchain/   # Blockchain endpoints
├── assets/                # Static assets
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── images/           # Images
├── blockchain/            # Blockchain integration
├── classes/               # PHP classes and models
│   ├── Database.php      # Database connection
│   ├── User.php          # User model
│   ├── Company.php       # Company model
│   ├── Product.php       # Product model
│   ├── Validator.php     # Validator model
│   ├── Auth.php          # Authentication class
│   ├── Middleware.php    # Request middleware
│   ├── QRCodeGenerator.php # QR code generation
│   └── BlockchainIntegration.php # Blockchain interface
├── config/                # Configuration files
│   ├── app.php           # Application config
│   ├── database.php      # Database config
│   └── blockchain.php    # Blockchain config
├── database/              # Database files
│   ├── schema.sql        # Database schema
│   └── install.php       # Database installer
├── frontend/              # Web interface
│   ├── components/       # Reusable components
│   └── pages/            # Web pages
├── uploads/               # File uploads
├── qr-codes/             # Generated QR codes
├── tests/                # Unit tests
├── vendor/               # Composer dependencies
├── .env                  # Environment configuration
├── .env.example          # Environment template
├── composer.json         # Composer dependencies
├── index.php             # Main entry point
├── install.php           # Installation script
└── README.md             # This file
```

## Features

### Core Features

- ✅ **User Management**: Registration, authentication, role-based access
- ✅ **Company Profiles**: Registration, verification, transparency scoring
- ✅ **Product Management**: Product registration, batch tracking, QR generation
- ✅ **Validator System**: Independent validation, reputation management
- ✅ **QR Code Integration**: Generation, scanning, product lookup
- ✅ **Blockchain Recording**: Immutable data storage, integrity verification
- ✅ **API Architecture**: RESTful APIs for all operations
- ✅ **Responsive Frontend**: Mobile-friendly web interface

### Planned Features

- 🔄 **Impact Scoring**: Environmental and social impact calculation
- 🔄 **Supply Chain Visualization**: Interactive supply chain mapping
- 🔄 **Advanced Validation**: Multi-party validation workflows
- 🔄 **Token Economics**: Validator incentives and company stakes
- 🔄 **Mobile Apps**: Native iOS and Android applications
- 🔄 **Smart Contracts**: Automated validation and payments
- 🔄 **Data Analytics**: Supply chain insights and reporting
- 🔄 **Third-party Integrations**: ERP, certification bodies

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

### Development Guidelines

- Follow PSR-12 coding standards
- Write unit tests for new features
- Update documentation
- Use meaningful commit messages

## Testing

```bash
# Run unit tests
composer test

# Run static analysis
composer analyse
```

## Security

- CSRF protection on all forms
- SQL injection prevention with prepared statements
- Password hashing with bcrypt
- Rate limiting on API endpoints
- Input validation and sanitization
- Secure session management

## Environment Variables

Key environment variables to configure:

```env
# Database
DB_HOST=localhost
DB_PORT=3306
DB_NAME=kuresel_etki_zinciri
DB_USERNAME=root
DB_PASSWORD=

# Application
APP_URL=http://localhost/Küresel
APP_ENV=development
APP_DEBUG=true

# Blockchain
BLOCKCHAIN_RPC_URL=http://localhost:8545
BLOCKCHAIN_NETWORK_ID=1337

# Security
APP_KEY=your-secret-key-here
```

## Troubleshooting

### Common Issues

1. **Database Connection Failed**
   - Check MySQL service is running
   - Verify database credentials in `.env`
   - Ensure database exists

2. **QR Code Generation Fails**
   - Check `qr-codes/` directory permissions
   - Verify GD extension is installed
   - Check file system write permissions

3. **API Endpoints Not Working**
   - Enable mod_rewrite (Apache)
   - Check web server configuration
   - Verify API routing

4. **Blockchain Connection Issues**
   - Check blockchain RPC URL
   - Verify network connectivity
   - Review blockchain configuration

### Getting Help

- Check the troubleshooting section
- Review error logs
- Open an issue on GitHub
- Contact the development team

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

- Built with modern PHP and web technologies
- Inspired by supply chain transparency initiatives
- Designed for sustainability and social impact

---

**Küresel Etki Zinciri** - Revolutionizing supply chain transparency through blockchain technology.