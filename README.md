# Expense Management System

A Laravel-based expense management system with authentication, role-based access (Admin, Manager, Employee), and multi-level expense approval workflow.

---

## 🚀 Features
- User authentication (Login/Signup/Password reset)
- Role-based dashboards
- Expense submission with receipts
- Multi-level approval (Manager → Finance → Admin)
- Currency conversion using API
- Responsive UI (Bootstrap)

---

## 🧑‍💻 Demo Accounts

### Admin
- Email: admin@example.com  
- Password: password  

### Manager
- Email: manager@example.com  
- Password: password  

### Employee
- Email: employee@example.com  
- Password: password  

---

## ⚙️ Installation

```bash
git clone https://github.com/YOUR-USERNAME/expense-manager.git
cd expense-manager
composer install
cp .env.example .env
php artisan key:generate
