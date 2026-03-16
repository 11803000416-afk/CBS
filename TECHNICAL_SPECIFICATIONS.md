# TECHNICAL SPECIFICATIONS DOCUMENT
## Car Broker System (CBS)

---

## 1. System Overview

The Car Broker System is a web-based application developed using the **Laravel 11** framework with a **MySQL** database backend. The system provides comprehensive vehicle listing management, buyer-seller facilitation, and transaction tracking capabilities through a responsive, secure web interface.

---

## 2. Technology Architecture

### 2.1 Technology Stack

| Component | Technology | Version | Rationale |
|-----------|-----------|---------|-----------|
| **Framework** | Laravel | 11.x | Enterprise-grade PHP framework with built-in security, ORM, and scaffolding |
| **Language** | PHP | 8.1+ | Server-side logic and business rule implementation |
| **Frontend Framework** | Tailwind CSS | Latest | Utility-first CSS framework for rapid, responsive UI development |
| **Module Bundler** | Vite | 5.4.21 | Next-generation frontend tooling with HMR for development |
| **Database** | MySQL | 8.0+ | Relational database with strong ACID compliance |
| **Authentication** | Laravel Sanctum | Latest | Token-based API authentication and session management |
| **ORM** | Eloquent | Latest | Laravel's query builder for database operations |
| **Build Tool** | npm | 9.6.7+ | Node package management and dependency resolution |
| **JavaScript Runtime** | Node.js | 18.17.1+ | Server-side JavaScript execution for build processes |

### 2.2 Architecture Layers

**Presentation Layer**
- HTML5 semantic markup
- CSS3 with Tailwind utilities
- ES6+ JavaScript with event-driven programming
- AJAX for asynchronous interactions
- Responsive design patterns

**Application Layer**
- Laravel routing system with RESTful conventions
- MVC (Model-View-Controller) pattern implementation
- Service layer for business logic isolation
- Middleware for cross-cutting concerns
- Request validation pipelines

**Data Access Layer**
- Eloquent ORM for object-relational mapping
- Query builder for complex queries
- Repository pattern for data abstraction
- Migration system for schema management

**Database Layer**
- Normalized MySQL schema (3NF compliance)
- Transaction support with ACID guarantees
- Referential integrity through foreign keys
- Indexing for performance optimization

---

## 3. Database Specifications

### 3.1 Core Tables

#### users_table
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20),
    address TEXT,
    role ENUM('admin', 'broker', 'buyer', 'seller') DEFAULT 'buyer',
    status ENUM('active', 'inactive') DEFAULT 'active',
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
);
```

#### vehicles_table
```sql
CREATE TABLE vehicles (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    brand VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    price DECIMAL(12, 2) NOT NULL,
    description TEXT,
    mileage INT,
    condition ENUM('excellent', 'good', 'fair', 'poor') DEFAULT 'good',
    transmission_type ENUM('manual', 'automatic') DEFAULT 'manual',
    fuel_type ENUM('petrol', 'diesel', 'hybrid', 'electric'),
    color VARCHAR(50),
    registration_number VARCHAR(50) UNIQUE,
    seller_id BIGINT UNSIGNED NOT NULL,
    status ENUM('available', 'sold', 'listed') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_brand (brand),
    INDEX idx_model (model),
    INDEX idx_year (year),
    INDEX idx_price (price),
    INDEX idx_status (status),
    INDEX idx_seller_id (seller_id)
);
```

#### transactions_table
```sql
CREATE TABLE transactions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    vehicle_id BIGINT UNSIGNED NOT NULL,
    buyer_id BIGINT UNSIGNED,
    seller_id BIGINT UNSIGNED,
    broker_id BIGINT UNSIGNED NOT NULL,
    sale_price DECIMAL(12, 2) NOT NULL,
    transaction_date DATETIME NOT NULL,
    payment_method ENUM('cash', 'bank_transfer', 'cheque', 'other') DEFAULT 'cash',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (broker_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_vehicle_id (vehicle_id),
    INDEX idx_buyer_id (buyer_id),
    INDEX idx_seller_id (seller_id),
    INDEX idx_broker_id (broker_id),
    INDEX idx_transaction_date (transaction_date)
);
```

#### buyers_table & sellers_table
Similar structures with user_id foreign key and additional role-specific fields.

---

## 4. API Specifications

### 4.1 Core Endpoints

#### Authentication Endpoints
```
POST   /api/auth/login              - User login
POST   /api/auth/register           - User registration
POST   /api/auth/logout             - User logout
POST   /api/auth/refresh            - Token refresh
GET    /api/auth/profile            - Get user profile
```

#### Vehicle Management Endpoints
```
GET    /api/vehicles                - List all vehicles
POST   /api/vehicles                - Create new vehicle
GET    /api/vehicles/{id}           - Get vehicle details
PUT    /api/vehicles/{id}           - Update vehicle
DELETE /api/vehicles/{id}           - Delete vehicle
GET    /api/vehicles/search         - Search vehicles with filters
```

#### Transaction Endpoints
```
GET    /api/transactions            - List transactions
POST   /api/transactions            - Create new transaction
GET    /api/transactions/{id}       - Get transaction details
PUT    /api/transactions/{id}       - Update transaction
```

#### Administrative Endpoints
```
GET    /api/admin/users             - List all users
POST   /api/admin/users             - Create user
PUT    /api/admin/users/{id}        - Update user
DELETE /api/admin/users/{id}        - Delete user
GET    /api/admin/reports           - Generate reports
```

### 4.2 Response Format

All API responses follow a consistent JSON structure:
```json
{
    "success": true|false,
    "message": "Human-readable status message",
    "data": { /* Response data */ },
    "errors": { /* Validation or error details */ }
}
```

---

## 5. Security Specifications

### 5.1 Authentication & Authorization

- **Password Security**: Bcrypt hashing with automatic salt generation
- **Session Management**: Secure HTTP-only cookies with CSRF tokens
- **Token-Based Auth**: JWT tokens for API authentication with expiration
- **Role-Based Access Control**: Four-tier permission system (Admin, Broker, Buyer, Seller)
- **Multi-User Support**: Concurrent session handling with session management

### 5.2 Input Validation

- **Server-Side Validation**: All inputs validated on server before processing
- **Data Type Checking**: Strict type validation for all parameters
- **Length Validation**: Maximum/minimum length constraints enforced
- **Format Validation**: Email, phone, and numeric format validation
- **SQL Injection Prevention**: Parameterized queries and ORM usage

### 5.3 Output Encoding

- **HTML Encoding**: All user-generated content escaped for display
- **XSS Prevention**: Content Security Policy headers implemented
- **CSRF Protection**: Token validation on all state-changing operations

### 5.4 Data Protection

- **Encryption in Transit**: HTTPS/TLS for all communications
- **Secure Headers**: HSTS, X-Frame-Options, X-Content-Type-Options
- **Database Security**: Role-based database access controls
- **Audit Logging**: All critical operations logged for accountability

---

## 6. Performance Specifications

### 6.1 Performance Targets

| Metric | Target | Tolerance |
|--------|--------|-----------|
| Page Load Time | < 2 seconds | < 3 seconds |
| API Response Time | < 500ms | < 1 second |
| Database Query Time | < 200ms | < 500ms |
| Concurrent Users | 100+ | Graceful degradation |
| Availability | 99.5% | Monitored monthly |

### 6.2 Optimization Strategies

- **Database**: Query optimization, indexing, lazy loading
- **Frontend**: Asset minification, image optimization, lazy loading
- **Caching**: Query result caching, view caching, route caching
- **CDN**: Static asset delivery through content delivery networks

---

## 7. Deployment Specifications

### 7.1 Production Environment

**Server Requirements**
- Apache/Nginx web server with PHP-FPM
- PHP 8.1+ with required extensions (mysql, curl, mbstring, json)
- MySQL 8.0+ database server
- Minimum 2GB RAM, 10GB disk space
- 24/7 monitoring and maintenance

**Environment Configuration**
- Application environment: `production`
- Debug mode: Disabled
- Detailed error logging enabled
- SSL/TLS certificates configured
- Email service integration

### 7.2 Development Environment

- Laravel Vite development server
- Hot Module Replacement (HMR) enabled
- Detailed debug information available
- Local MySQL database for testing

---

## 8. Maintenance and Monitoring

### 8.1 Logging and Monitoring

- Application error logs (storage/logs/laravel.log)
- Database query logging for performance analysis
- User activity audit trail
- System resource monitoring (CPU, Memory, Disk)

### 8.2 Backup Strategy

- Daily database backups
- Weekly full application backups
- Backup verification and restoration testing
- Off-site backup storage

### 8.3 Security Updates

- Regular framework updates and patches
- Dependency vulnerability scanning
- Monthly security audits
- Penetration testing (quarterly)

---

## 9. Compliance and Standards

- **WCAG 2.1 Level AA**: Accessibility compliance
- **RESTful API Design**: Standard REST conventions
- **OWASP Top 10**: Security best practices implementation
- **ISO/IEC 27001**: Information security standards alignment
- **Clean Code**: PSR-12 PHP coding standards

---

## 10. Future Enhancements

- Advanced analytics and reporting dashboard
- Mobile native applications (iOS/Android)
- Real-time notifications using WebSockets
- AI-powered vehicle recommendation engine
- Integration with external vehicle databases
- Payment gateway integration
- SMS and email notification system

---

**Document Version**: 1.0  
**Last Updated**: March 2026  
**Prepared For**: Academic Evaluation and Professional Deployment
