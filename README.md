# BoiLagbe - Online Bookstore

A PHP/MySQL-based online bookstore application where users can browse books, add them to cart, and make purchases.

## Features

- User Registration & Login
- Product Browsing & Search
- Shopping Cart Management
- Secure Checkout Process
- Order History & Tracking
- Admin Dashboard
- Responsive Design for Mobile & Desktop
- Contact Form

## Prerequisites

- XAMPP (with PHP 7.4+ and MySQL)
- Web browser (Chrome, Firefox, or Edge recommended)
- Text editor (VS Code, Sublime Text, or similar)

## Installation Steps

1. **Install XAMPP**
   - Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
   - Make sure Apache and MySQL services are running in XAMPP Control Panel

2. **Project Setup**
   - Clone or download this repository
   - Extract/copy the project folder to `C:\xampp\htdocs\` (Windows) or `/opt/lampp/htdocs/` (Linux)
   - Rename the folder to `bookstore-main` if it's not already named that

3. **Database Setup (IMPORTANT)**
   - Open your web browser and go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Click "New" in the left sidebar
   - Enter "shop_db" as the database name
   - Click "Create"
   - Select the "shop_db" database
   - Go to the "Import" tab
   - Click "Choose File" and select the `dbqueries` file from the project
   - Click "Go" to import the database structure
   - IMPORTANT: Verify that the following tables are created with EXACT names:
     - register (for user accounts - do not rename to users)
     - cart
     - message
     - orders
     - products
   - If any tables are missing, try importing the `dbqueries` file again

   

4. **Configuration Check**
   - Open `config.php` in the project folder
   - Verify the database connection settings:
     ```php
     $conn = mysqli_connect('localhost','root','','shop_db')
     ```
   - The default settings are:
     - Host: localhost
     - Username: root
     - Password: (empty)
     - Database: shop_db

5. **Access the Website**
   - Open your web browser
   - Go to [http://localhost/bookstore-main](http://localhost/bookstore-main)
   - The website should now be running

## Default Admin Account
- Email: admin@gmail.com
- Password: 123
- **IMPORTANT**: Change these credentials immediately after first login


## Create New Admin Account (If Default Admin Doesn't Work)
1. Go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Select the "shop_db" database
3. Click on the "register" table
4. Click "Insert" tab
5. Fill in these values:
   - id: leave empty (auto-increments)
   - name: admin
   - email: your-email@example.com
   - password: (enter your desired password)
   - user_type: admin
6. Click "Go" to create the account
7. You can now login with your new admin credentials



## Project Structure

```
bookstore-main/
├── admin/              # Admin dashboard files
├── assets/            # CSS, JS, and image files
├── includes/          # Reusable PHP components
├── config.php         # Database configuration
├── dbqueries         # Database schema
├── cart.php          # Shopping cart functionality
├── shop.php          # Product listing page
├── login.php         # User authentication
└── index.php         # Homepage
```

## Common Issues & Solutions

1. **"Unknown database 'shop_db'" Error**
   - This means the database hasn't been created
   - Follow the database setup steps above

2. **"Table 'shop_db.cart' doesn't exist" Error**
   - This means the database tables weren't created properly
   - Go back to phpMyAdmin and verify all tables exist
   - If missing, use the manual SQL commands provided above

3. **"Table 'shop_db.users' doesn't exist" Error**
   - The user table is named `register`, not `users`
   - Create the `register` table using the SQL commands above
   - Do not rename the table to `users`

4. **Database Connection Failed**
   - Verify MySQL is running in XAMPP Control Panel
   - Check database credentials in `config.php`
   - Confirm database name is exactly "shop_db"

5. **Page Not Found Error**
   - Check project folder location
   - Verify Apache is running in XAMPP
   - Confirm URL matches folder structure

## Security Recommendations

1. **Database Security**
   - Change the default MySQL root password
   - Create a separate database user with limited privileges
   - Regularly backup your database
   - Use prepared statements for all queries

2. **Authentication**
   - Change default admin credentials immediately
   - Implement password complexity requirements
   - Add rate limiting for login attempts
   - Use secure session management

3. **File Security**
   - Set proper file permissions
   - Validate all file uploads
   - Store uploaded files outside web root
   - Keep all software updated

4. **General Security**
   - Enable HTTPS
   - Implement CSRF protection
   - Sanitize all user inputs
   - Use secure headers
   - Regular security audits
