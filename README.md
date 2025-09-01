# 🚀 Modern Portfolio Website

A beautiful, responsive portfolio website for **Deepak Kumar** - Full Stack Developer, featuring modern UI/UX design, dark mode, contact form with backend integration, and admin dashboard.

## ✨ Features

### 🎨 Modern Design
- **Dark/Light Mode Toggle** - Switch between themes with smooth transitions
- **Responsive Design** - Works perfectly on all devices
- **Modern Animations** - Smooth scroll effects and hover animations
- **Gradient Elements** - Beautiful gradient backgrounds and buttons
- **Professional Layout** - Clean, modern design following current trends

### 💼 Portfolio Sections
- **Hero Section** - Dynamic typing animation with role descriptions
- **About Section** - Professional summary and personal details
- **Skills Section** - Interactive progress bars with technical skills
- **Resume Section** - Complete professional experience and education
- **Projects Section** - Showcase of major projects with tech stacks
- **Contact Section** - Functional contact form with validation

### 🔧 Backend Features
- **PHP Contact Form** - Secure form processing with validation
- **Database Integration** - MySQL database for storing contact submissions
- **Email Notifications** - Automatic email alerts for new messages
- **Admin Dashboard** - Modern admin panel to manage contacts
- **Secure Authentication** - Login system for admin access

### 🛡️ Security Features
- **Input Validation** - Server-side validation for all form inputs
- **SQL Injection Protection** - Prepared statements for database queries
- **XSS Protection** - HTML entity encoding for user inputs
- **Session Management** - Secure admin session handling

## 🚀 Quick Start

### 1. Setup Database
```bash
# Run the setup script to create database and tables
http://your-domain.com/portfolio/setup.php
```

### 2. Configure Database
Edit `config/database.php` with your database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'portfolio_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 3. Access Portfolio
- **Main Portfolio**: `index.html`
- **Admin Login**: `admin/login.php`
- **Default Admin Credentials**: 
  - Username: `admin`
  - Password: `admin123`

## 📁 Project Structure

```
portfolio/
├── index.html              # Main portfolio page
├── setup.php              # Database setup script
├── README.md               # Project documentation
├── assets/
│   ├── css/
│   │   └── style.css       # Enhanced styles with dark mode
│   ├── js/
│   │   └── main.js         # JavaScript with dark mode & form handling
│   ├── img/                # Images and profile photos
│   └── vendor/             # Third-party libraries
├── config/
│   ├── database.php        # Database configuration
│   └── init.sql           # Database schema
├── forms/
│   └── contact.php         # Contact form backend
└── admin/
    ├── login.php           # Admin login page
    ├── index.php           # Admin dashboard
    └── logout.php          # Admin logout
```

## 🎯 Key Technologies

### Frontend
- **HTML5** - Semantic markup
- **CSS3** - Modern styling with CSS variables
- **JavaScript (ES6+)** - Interactive functionality
- **Bootstrap 5** - Responsive framework
- **AOS Library** - Scroll animations
- **SweetAlert2** - Beautiful alerts

### Backend
- **PHP 7.4+** - Server-side processing
- **MySQL** - Database management
- **PDO** - Database abstraction layer

### Libraries & Frameworks
- **Font Awesome** - Icons
- **Google Fonts** - Typography
- **BoxIcons** - Additional icons

## 🔧 Configuration

### Email Settings
To enable email notifications, configure your mail server in `forms/contact.php`:
```php
$admin_email = 'your-email@domain.com';
```

### Theme Customization
Modify CSS variables in `assets/css/style.css`:
```css
:root {
    --accent-color: #149ddd;
    --bg-color: #fff;
    --text-color: #272829;
}
```

## 📱 Responsive Breakpoints

- **Mobile**: < 768px
- **Tablet**: 768px - 1024px
- **Desktop**: > 1024px

## 🎨 Color Scheme

### Light Mode
- Primary: `#149ddd`
- Background: `#ffffff`
- Text: `#272829`
- Cards: `#ffffff`

### Dark Mode
- Primary: `#37b3ed`
- Background: `#1a1a1a`
- Text: `#e0e0e0`
- Cards: `#2d2d2d`

## 🔒 Security Best Practices

1. **Input Validation** - All user inputs are validated and sanitized
2. **Prepared Statements** - SQL injection protection
3. **Password Hashing** - Secure password storage using PHP's password_hash()
4. **Session Security** - Secure session management
5. **HTTPS Recommended** - Use SSL certificate in production

## 📊 Admin Dashboard Features

- **Contact Management** - View and manage contact submissions
- **Status Tracking** - Mark messages as read/unread
- **Statistics Dashboard** - Overview of contact metrics
- **Responsive Design** - Mobile-friendly admin interface
- **Real-time Updates** - AJAX-powered status updates

## 🚀 Performance Optimizations

- **CSS Variables** - Efficient theme switching
- **Lazy Loading** - Optimized image loading
- **Minified Assets** - Compressed CSS and JS
- **Caching Headers** - Browser caching optimization

## 🔄 Updates & Maintenance

### Regular Tasks
1. Update admin password regularly
2. Monitor contact submissions
3. Backup database periodically
4. Update dependencies

### Version Updates
- Check for library updates
- Test functionality after updates
- Maintain compatibility

## 📞 Support

For technical support or customization requests:
- **Email**: deepakkumar2781999@gmail.com
- **LinkedIn**: [linkedin.com/in/deepak-kumar-0718681a9](https://www.linkedin.com/in/deepak-kumar-0718681a9/)
- **GitHub**: [github.com/developer278](https://github.com/developer278)

## 📄 License

This project is created for **Deepak Kumar's** professional portfolio. Feel free to use as inspiration for your own portfolio projects.

---

**Built with ❤️ by Deepak Kumar - Full Stack Developer**
