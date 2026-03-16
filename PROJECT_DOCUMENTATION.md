# Car Broker System (CBS)
## Comprehensive Project Documentation

---

## Executive Summary

The **Car Broker System (CBS)** is a comprehensive web-based application developed to digitalize and streamline vehicle brokerage operations in Bhutan. This system addresses critical inefficiencies in traditional manual car brokerage processes by providing brokers, buyers, sellers, and administrators with a centralized platform for managing car listings, facilitating buyer-seller connections, and maintaining accurate transaction records. The application has been designed and developed following a systematic Software Development Life Cycle (SDLC) approach, encompassing requirements analysis, system design, iterative development, comprehensive testing, and full documentation.

---

## 1. Project Objectives

### Primary Objectives

The Car Broker System is designed to achieve the following strategic objectives:

1. **Process Digitalization** – Transform manual, paper-based car brokerage operations into a digital, technology-enabled platform that improves efficiency and accuracy.

2. **Centralized Information Management** – Establish a unified repository for managing vehicle listings, customer information, and transaction records, eliminating information fragmentation and reducing data loss.

3. **Enhanced User Experience** – Provide an intuitive, user-friendly interface that simplifies navigation and reduces the learning curve for brokers, buyers, and sellers.

4. **Operational Efficiency** – Reduce administrative overhead, streamline search processes, and accelerate transaction cycles through automated workflows and intelligent search capabilities.

5. **Data Integrity and Security** – Implement robust security mechanisms to protect sensitive user and transaction data while ensuring system reliability and availability.

### Secondary Objectives

- Facilitate effective communication and interaction between multiple stakeholder groups (buyers, sellers, brokers, and administrators)
- Maintain comprehensive audit trails for compliance and transparency purposes
- Provide administrative oversight capabilities for system monitoring and management

---

## 2. Project Scope

### Scope of Deliverables

This comprehensive project encompasses the complete Software Development Life Cycle (SDLC) and includes the following major deliverables:

#### 2.1 Analysis and Design Phase
- Comprehensive system requirements specification
- User role and interaction analysis
- System architecture and design documentation
- Database schema design with Entity-Relationship (ER) diagrams
- User interface wireframes for all major system pages
- Use case diagrams depicting actor interactions

#### 2.2 Development Phase
- Fully functional web-based application
- Database implementation with normalized schema
- Implementation of all core business functionalities
- Input validation mechanisms and error handling
- Security features including authentication and authorization
- Version-controlled source code repository

#### 2.3 Testing and Quality Assurance Phase
- Functional testing of all implemented features
- System integration testing
- User acceptance testing with stakeholder feedback
- Performance optimization and refinement
- Security vulnerability testing

#### 2.4 Documentation Phase
- Complete technical documentation
- System architecture documentation
- Database documentation with schema diagrams
- User manuals and system administration guidelines
- Installation and deployment instructions
- Screenshots demonstrating all major features

#### 2.5 Deployment and Presentation
- Fully functional demonstration-ready system
- Comprehensive project presentation
- Installation and deployment instructions for evaluators
- System administration guidelines

---

## 3. Problem Statement and Justification

### Current Situation Analysis

Car brokerage operations in Bhutan currently rely on inefficient manual processes that include:

- **Information Management**: Vehicle information, buyer details, and seller records are maintained through notebooks, physical documents, and scattered communication channels
- **Communication Inefficiency**: Brokers communicate with buyers and sellers primarily through telephone calls and social media platforms, leading to communication delays and miscommunications
- **Search and Discovery**: The process of matching suitable vehicles with potential buyers is time-consuming and relies on broker recall and manual searching through physical records
- **Record Keeping**: Transaction records are maintained manually, making historical data retrieval difficult and prone to errors
- **Scalability Issues**: As the brokerage business grows, manual processes become increasingly difficult to manage and maintain

### Identified Problems

1. **Information Fragmentation** – Critical business information is scattered across multiple sources, making it difficult to maintain data consistency and accessibility
2. **Data Loss Risk** – Important information may be lost, misplaced, or inadvertently deleted due to lack of centralized storage
3. **Operational Inefficiency** – Time-consuming manual processes reduce broker productivity and increase operational costs
4. **Lack of Transparency** – Absence of centralized records makes it difficult to track transaction history and maintain audit trails
5. **Limited Scalability** – Manual systems cannot effectively accommodate business growth and increased transaction volume

### Proposed Solution

The Car Broker System addresses these challenges by implementing a centralized, digital platform that enables:

- Organized storage and retrieval of car listings, buyer information, and seller details
- Streamlined communication between brokers, buyers, and sellers through a unified platform
- Efficient vehicle search capabilities with multiple filtering options
- Automated transaction recording and historical tracking
- Role-based access and administrative oversight capabilities

---

## 4. System Requirements Specification

### 4.1 Functional Requirements

#### User Management Module
- **FR-1.1**: System shall provide user registration functionality allowing new users to create accounts
- **FR-1.2**: System shall provide secure login functionality with username and password authentication
- **FR-1.3**: System shall enable users to update and manage their profile information
- **FR-1.4**: System shall provide logout functionality for session termination
- **FR-1.5**: System shall implement role-based user profiles (Admin, Broker, Buyer, Seller)

#### Car Listing Management Module
- **FR-2.1**: System shall allow brokers to add new car listings with comprehensive vehicle information
- **FR-2.2**: System shall enable brokers to update existing car listing details
- **FR-2.3**: System shall provide brokers with car listing deletion capabilities
- **FR-2.4**: System shall allow authenticated users to view all available car listings
- **FR-2.5**: System shall display detailed vehicle information upon user request

#### Car Search and Discovery Module
- **FR-3.1**: System shall provide search functionality based on vehicle brand/manufacturer
- **FR-3.2**: System shall enable filtering by vehicle model
- **FR-3.3**: System shall allow filtering by price range
- **FR-3.4**: System shall support filtering by year of manufacture
- **FR-3.5**: System shall display search results in an organized and user-friendly format

#### Transaction Management Module
- **FR-4.1**: System shall provide functionality for recording car sales transactions
- **FR-4.2**: System shall maintain comprehensive transaction history records
- **FR-4.3**: System shall display transaction details including buyer, seller, broker, and sale information
- **FR-4.4**: System shall provide transaction reporting capabilities

#### Administrative Management Module
- **FR-5.1**: System shall provide admin dashboard for system monitoring
- **FR-5.2**: System shall enable administrators to manage user accounts and roles
- **FR-5.3**: System shall provide administrators with visibility into all car listings
- **FR-5.4**: System shall allow administrators to monitor transaction records
- **FR-5.5**: System shall provide system configuration and management capabilities

### 4.2 Non-Functional Requirements

#### Usability Requirements (NF-1)
- **NF-1.1**: The user interface shall adhere to modern web design principles with intuitive navigation
- **NF-1.2**: System shall require minimal training for users to operate effectively
- **NF-1.3**: All pages shall have consistent layout, styling, and navigation elements
- **NF-1.4**: The system shall provide clear error messages and user guidance

#### Performance Requirements (NF-2)
- **NF-2.1**: Page load time shall not exceed 3 seconds under normal operating conditions
- **NF-2.2**: Database queries shall complete within 500 milliseconds
- **NF-2.3**: System shall handle concurrent user sessions without performance degradation
- **NF-2.4**: Search and filtering operations shall return results within acceptable time frames

#### Security Requirements (NF-3)
- **NF-3.1**: User authentication shall employ secure password hashing algorithms
- **NF-3.2**: System shall enforce role-based access control (RBAC) for all features
- **NF-3.3**: Sensitive data shall be encrypted in transit and at rest
- **NF-3.4**: System shall implement CSRF and XSS protection mechanisms
- **NF-3.5**: User sessions shall have appropriate timeout mechanisms

#### Reliability Requirements (NF-4)
- **NF-4.1**: System shall maintain 99.5% uptime availability
- **NF-4.2**: Data shall be stored redundantly to prevent information loss
- **NF-4.3**: System shall implement automated backup mechanisms
- **NF-4.4**: Recovery procedures shall enable system restoration within acceptable timeframes

#### Compatibility Requirements (NF-5)
- **NF-5.1**: System shall function across all major web browsers (Chrome, Firefox, Safari, Edge)
- **NF-5.2**: System shall be responsive and function on desktop, tablet, and mobile devices
- **NF-5.3**: System shall support modern web standards and protocols

---

## 5. System Design and Architecture

### 5.1 Use Case Diagram

The Car Broker System serves four primary system actors:

#### 1. Administrator
**Responsibilities:**
- User account management and role assignment
- System monitoring and oversight
- Car listing management and validation
- Transaction record monitoring
- System configuration and maintenance

**Use Cases:**
- Manage Users
- Monitor Car Listings
- View Transaction Records
- Configure System Settings
- Generate Reports

#### 2. Broker
**Responsibilities:**
- Adding and maintaining car listings
- Facilitating buyer-seller connections
- Recording completed transactions
- Managing profile information

**Use Cases:**
- Add Car Listings
- Update Car Information
- Delete Car Listings
- Record Transactions
- Manage Profile
- View Assigned Transactions

#### 3. Buyer
**Responsibilities:**
- Searching for suitable vehicles
- Viewing detailed car information
- Inquiring about vehicles
- Managing buyer profile

**Use Cases:**
- View Car Listings
- Search Cars
- View Car Details
- Contact Broker
- Manage Profile

#### 4. Seller
**Responsibilities:**
- Listing vehicles for sale through brokers
- Managing seller information
- Tracking transaction status

**Use Cases:**
- View Car Listings
- Manage Profile
- View Transaction History

### 5.2 System Architecture

The Car Broker System is implemented using a modern web application architecture:

```
┌─────────────────────────────────────────────────────────┐
│                    User Interface Layer                  │
│         (HTML5, CSS3, JavaScript, Tailwind CSS)         │
└────────────────────┬────────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────────┐
│                Application Layer                         │
│         (Laravel Framework, PHP 8.x)                    │
│    - Routing & Controller Layer                         │
│    - Business Logic Implementation                      │
│    - Authentication & Authorization                    │
└────────────────────┬────────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────────┐
│                 Data Access Layer                        │
│    (Eloquent ORM, Query Builder)                        │
└────────────────────┬────────────────────────────────────┘
                     │
┌────────────────────▼────────────────────────────────────┐
│                Database Layer                           │
│         (MySQL/MariaDB, Normalized Schema)             │
└─────────────────────────────────────────────────────────┘
```

### 5.3 Technology Stack

| Layer | Technology | Version | Purpose |
|-------|-----------|---------|---------|
| **Frontend** | HTML5 | Latest | Markup Structure |
| **Frontend** | CSS3 | Latest | Styling |
| **Frontend** | JavaScript (ES6+) | Latest | Interactivity |
| **Frontend** | Tailwind CSS | Latest | Utility-First Styling |
| **Frontend** | Vite | v5.4.21 | Build Optimization |
| **Backend** | Laravel | Latest | Web Framework |
| **Backend** | PHP | 8.x | Server-Side Language |
| **Database** | MySQL/MariaDB | Latest | Data Persistence |
| **Build Tools** | Node.js | v18.17.1 | JavaScript Runtime |
| **Build Tools** | npm | v9.6.7 | Package Management |

---

## 6. Database Design

### 6.1 Entity-Relationship Model

The database schema incorporates the following primary entities:

#### Users Entity
Stores user account information and authentication credentials

```sql
Users
├── user_id (PK)
├── name
├── email (UNIQUE)
├── password (hashed)
├── phone_number
├── address
├── role (Admin | Broker | Buyer | Seller)
├── created_at
├── updated_at
└── status (active | inactive)
```

#### Vehicles Entity
Maintains comprehensive car listing information

```sql
Vehicles
├── vehicle_id (PK)
├── brand
├── model
├── year
├── price
├── description
├── mileage
├── condition
├── transmission_type
├── fuel_type
├── color
├── registration_number
├── seller_id (FK → Users)
├── created_at
├── updated_at
└── status (available | sold | listed)
```

#### Buyers Entity
Stores buyer-specific information

```sql
Buyers
├── buyer_id (PK)
├── user_id (FK → Users)
├── preferred_brand
├── budget_min
├── budget_max
├── created_at
└── updated_at
```

#### Sellers Entity
Maintains seller-specific information

```sql
Sellers
├── seller_id (PK)
├── user_id (FK → Users)
├── company_name (optional)
├── created_at
└── updated_at
```

#### Transactions Entity
Records completed vehicle transactions

```sql
Transactions
├── transaction_id (PK)
├── vehicle_id (FK → Vehicles)
├── buyer_id (FK → Buyers)
├── seller_id (FK → Sellers)
├── broker_id (FK → Users)
├── sale_price
├── transaction_date
├── payment_method
├── notes
├── created_at
└── updated_at
```

### 6.2 Database Normalization

The schema adheres to Third Normal Form (3NF) principles:
- Elimination of redundant data
- Proper entity separation and relationships
- Referential integrity through foreign key constraints
- Atomic data values without repeating groups

---

## 7. Implementation Details

### 7.1 Development Approach

The system has been developed using an iterative, feature-driven approach:

1. **Phase 1: Authentication Module** – User registration, login, and session management
2. **Phase 2: Core Data Management** – Vehicle, buyer, and seller information management
3. **Phase 3: Search and Discovery** – Advanced filtering and search capabilities
4. **Phase 4: Transaction Management** – Recording and tracking vehicle transactions
5. **Phase 5: Administrative Functions** – System monitoring and management capabilities
6. **Phase 6: Security Hardening** – Input validation, error handling, and security measures

### 7.2 Core Features Implementation

#### Authentication and Authorization
- Secure user registration with email verification
- Password hashing using industry-standard algorithms
- Role-based access control (RBAC) with granular permissions
- Session management with appropriate timeout mechanisms
- Secure login/logout functionality

#### Vehicle Listing Management
- Comprehensive form validation for vehicle information
- File upload capabilities for vehicle images
- CRUD operations (Create, Read, Update, Delete) for vehicle listings
- Status tracking for vehicle availability
- Historical record maintenance

#### Search and Filtering System
- Multi-parameter search capabilities
- Advanced filtering by brand, model, year, and price range
- Full-text search on vehicle descriptions
- Paginated result sets for improved performance
- Sorting options by relevance, price, or date

#### Transaction Processing
- Transaction recording with comprehensive details
- Relationship mapping between vehicles, buyers, and sellers
- Transaction history tracking and reporting
- Receipt generation and documentation

#### Administrative Dashboard
- System overview and statistics
- User management interface
- Activity monitoring and logging
- Listing management capabilities
- Transaction records and reporting

### 7.3 Security Implementation

#### Input Validation
- Server-side validation of all user inputs
- Prevention of SQL injection through parameterized queries
- Cross-Site Scripting (XSS) prevention through output encoding
- Cross-Site Request Forgery (CSRF) token validation

#### Authentication Security
- Secure password storage using bcrypt hashing
- Session management with secure cookies
- Protection against brute-force attacks
- Account lockout mechanisms after failed attempts

#### Data Protection
- Encryption of sensitive data in transit (HTTPS/TLS)
- Role-based access control for data visibility
- Audit logging for critical operations
- Database-level constraints for referential integrity

---

## 8. User Interface Design

### 8.1 Key Interface Pages

#### 1. Authentication Pages
- **Login Page**: Secure credential entry with password visibility toggle
- **Registration Page**: User account creation with validation
- **Password Recovery**: Email-based password reset functionality

#### 2. User Dashboard
- Role-specific dashboard for different user types
- Quick access to primary functions
- Activity summary and notifications
- Profile management options

#### 3. Vehicle Listing Pages
- **Browse Listings**: Grid view of all available vehicles
- **Add Vehicle**: Form for broker vehicle listing entry
- **Edit Vehicle**: Modification interface for existing listings
- **Vehicle Details**: Comprehensive vehicle information display

#### 4. Search and Discovery
- Advanced search interface with multiple filter options
- Results with pagination and sorting options
- Quick view and detailed view options
- Saved searches and favorite listings (future enhancement)

#### 5. Transaction Management
- Transaction recording interface
- Transaction history and reporting
- Receipt generation and viewing

#### 6. Administrative Interface
- **Admin Dashboard**: System overview and statistics
- **User Management**: User account and role management
- **Listing Oversight**: Review and management of car listings
- **Transaction Monitoring**: Transaction records and reporting

### 8.2 Design Principles

- **Responsive Design**: All pages function optimally on desktop, tablet, and mobile devices
- **Accessibility**: WCAG 2.1 compliance for inclusive user experience
- **Consistency**: Uniform styling and navigation across all pages
- **User-Centric**: Intuitive interfaces requiring minimal training
- **Performance**: Optimized assets and efficient rendering

---

## 9. Testing and Quality Assurance

### 9.1 Testing Strategy

#### Unit Testing
- Controller and model business logic testing
- Database query validation
- Form validation rule verification
- Helper function testing

#### Integration Testing
- Authentication workflow testing
- Database transaction testing
- API endpoint testing
- Cross-module functionality verification

#### Functional Testing
- Feature completeness verification
- User workflow testing
- Error handling validation
- Edge case testing

#### User Acceptance Testing (UAT)
- Stakeholder validation of features
- Usability assessment
- Performance evaluation
- Real-world scenario testing

### 9.2 Quality Metrics

- Code coverage for critical functions
- Page load time monitoring
- Database query optimization
- Error rate tracking and resolution
- User feedback and issue resolution

---

## 10. Installation and Deployment

### 10.1 System Requirements

#### Server Requirements
- **PHP**: Version 8.1 or higher
- **MySQL/MariaDB**: Version 5.7 or higher
- **Node.js**: Version 18.x or higher
- **Composer**: Latest version

#### Client Requirements
- Modern web browser (Chrome, Firefox, Safari, Edge)
- JavaScript enabled
- Minimum screen resolution: 1024x768 pixels

### 10.2 Installation Steps

**Step 1: Environment Setup**
```bash
cd C:\xampp\htdocs\CBS
cp .env.example .env
php artisan key:generate
```

**Step 2: Package Installation**
```bash
composer install
npm install
```

**Step 3: Database Configuration**
```bash
# Edit .env with database credentials
php artisan migrate
php artisan db:seed
```

**Step 4: Asset Building**
```bash
npm run build
```

**Step 5: Application Launch**
```bash
php artisan serve
# Access at http://127.0.0.1:8000
```

### 10.3 Default Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@cbs.bt | password |
| Broker | broker@cbs.bt | password |
| Buyer | buyer@cbs.bt | password |

---

## 11. System Documentation

### 11.1 Administrator Guide

Administrators have access to complete system oversight and management capabilities including:
- User account creation, modification, and role assignment
- Monitoring of car listings and transaction records
- System configuration and maintenance
- Report generation and data export
- Security and backup management

### 11.2 Broker Guide

Brokers can efficiently manage vehicle listings and transactions:
- Adding and updating vehicle listings with comprehensive details
- Searching and matching buyers with available vehicles
- Recording completed transactions
- Managing profile and account information
- Accessing transaction history and reports

### 11.3 Buyer/Seller Guide

End-users can easily navigate the system to find and manage vehicle information:
- Browsing available vehicle listings
- Using advanced search and filtering capabilities
- Viewing detailed vehicle information
- Managing profile information
- Viewing transaction history

---

## 12. Project Deliverables Summary

### Delivered Components

✓ **Analysis and Design Documents**
  - Requirements specification
  - System architecture documentation
  - Database design and ER diagrams
  - User interface wireframes
  - Use case diagrams

✓ **Fully Functional Web Application**
  - Complete feature implementation
  - Responsive user interface
  - Secure authentication system
  - Database integration

✓ **Quality Assurance**
  - Functional testing completion
  - Bug resolution and optimization
  - Performance tuning
  - Security hardening

✓ **Comprehensive Documentation**
  - Technical documentation
  - User manuals and guides
  - Administrator guides
  - Installation and deployment instructions

✓ **Demonstration-Ready System**
  - Fully operational application
  - Sample data for demonstration
  - Pre-configured test accounts
  - Complete feature showcase capability

---

## 13. Conclusion

The Car Broker System represents a comprehensive solution to the challenges of manual car brokerage operations in Bhutan. By implementing a modern, secure, and user-friendly digital platform, the system enables brokers, buyers, and sellers to conduct vehicle transactions more efficiently and transparently.

The development of CBS has demonstrated the application of core software engineering principles including:
- Systematic requirements analysis and design
- Iterative development and testing methodologies
- Security-conscious implementation practices
- Professional documentation and knowledge transfer
- Comprehensive quality assurance

The system is fully functional, thoroughly tested, and ready for operational deployment. All project objectives have been achieved, and the system is prepared for presentation and evaluation.

---

**Project Status:** ✓ **COMPLETE** and **DEMONSTRATION-READY**

**Date Completed:** March 2026

---

## Appendix: Additional Resources

### A. External Documentation
- [Laravel Documentation](https://laravel.com/docs)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [PHP Documentation](https://www.php.net/docs.php)

### B. Version Control
- Repository: Git-based version control system
- Commit History: Complete development tracking and version management
- Branch Strategy: Main branch for stable releases, development for feature work

### C. Contact and Support
For system inquiries and support, please refer to the administrator contact information in the system's Help section.

---

*This documentation represents the complete Car Broker System project, developed in accordance with Software Development Life Cycle (SDLC) best practices and professional standards for academic evaluation.*
