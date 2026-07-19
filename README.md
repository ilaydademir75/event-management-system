# 🎉 Event Management System

A web-based **Event Management System** developed as part of the **Web Programming** course. The project allows users to explore events, receive personalized recommendations based on their interests, purchase event tickets, and enables administrators to manage users, events, and announcements through an admin panel.

---

## 📖 Overview

The system was designed to provide a complete event management experience for both users and administrators.

Users can register, log in after administrator approval, update their passwords, select their interests, browse events, view weather information for each event, add tickets to their cart, and complete ticket purchases.

Administrators can approve user registrations, create and delete events, publish announcements, and manage the overall system.

---

## ✨ Features

### 👤 User

* User registration and login
* Administrator approval before first login
* Password change after first login
* Interest selection
* Personalized event recommendations
* Browse available events
* Weather information for events
* Shopping cart
* Ticket purchasing
* Student and Full ticket options
* Credit Card and Bank Transfer payment methods

### 🔐 Administrator

* Approve user accounts
* Add events
* Delete events
* Publish announcements
* View pending user requests
* Manage users

---

## 🛠️ Technologies Used

* **Frontend:** HTML, CSS, JavaScript
* **Backend:** PHP
* **Database:** MySQL
* **Server:** WampServer
* **Version Control:** Git & GitHub
* **IDE:** Visual Studio Code

---

## 📂 Project Structure

```text
event-management-system/
│
├── frontend/
│   ├── register.html
│   ├── login.html
│   ├── home.html
│   ├── interest.html
│   ├── sepet.html
│   ├── change-password.html
│   └── admin.html
│
└── backend/
    ├── register.php
    ├── login.php
    ├── change-password.php
    ├── add-event.php
    ├── delete-event.php
    ├── add-announcement.php
    ├── approve-user.php
    ├── delete-user.php
    ├── get-events.php
    ├── get-announcements.php
    ├── get-pending-users.php
    ├── save-interests.php
    ├── add-to-cart.php
    ├── get-cart.php
    └── purchase.php
```

---

## 🗄️ Database

The application uses a **MySQL** database.

Main database tables include:

* `users`
* `events`
* `announcements`
* `user_interests`

---

## 🌟 Highlights

* Role-based user authentication
* Administrator approval system
* Personalized event recommendations
* Weather information integration
* Shopping cart functionality
* Ticket purchasing system
* Event quota management
* Announcement management

---
This project was developed for educational purposes.
