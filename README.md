<div align="center">

# 📚 LibraSPHERE
### *Smart Academic Library Portal — Where Books Meet Knowledge*

**A full-stack PHP/MySQL Library Management System that bridges physical book circulation with a verified digital academic resource hub.**

[![Live Demo](https://img.shields.io/badge/🌐_Live_Demo-lms--easy.infinityfreeapp.com-22c55e?style=for-the-badge)](https://lms-easy.infinityfreeapp.com/)
[![GitHub Repo](https://img.shields.io/badge/GitHub-Khush54%2FLMS-181717?style=for-the-badge&logo=github)](https://github.com/Khush54/LMS)
[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Deployment](https://img.shields.io/badge/Deployed_on-InfinityFree-06b6d4?style=for-the-badge)](https://infinityfree.net)
[![Stars](https://img.shields.io/github/stars/Khush54/LMS?style=for-the-badge&color=facc15)](https://github.com/Khush54/LMS/stargazers)

<br/>

> **⚡ Try it live** → [lms-easy.infinityfreeapp.com](https://lms-easy.infinityfreeapp.com/)  
> Public demo is **read-only** to protect the live database — explore freely!

</div>

---

## 📌 Table of Contents

- [Introduction](#-introduction)
- [Why LibraSPHERE?](#-why-librasphere)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Architecture](#-architecture)
- [How It Works — Complete Flow](#-how-it-works--complete-flow)
- [Folder Structure](#-folder-structure)
- [Important Files Explained](#-important-files-explained)
- [Database Design](#-database-design)
- [Authentication Flow](#-authentication-flow)
- [Admin Portal Guide](#-admin-portal-guide)
- [Student Portal Guide](#-student-portal-guide)
- [Smart Search & Recommendations](#-smart-search--recommendations)
- [Analytics Module](#-analytics-module)
- [Notes & PYQ Workflow](#-notes--pyq-workflow)
- [Installation Guide](#-installation-guide)
- [Environment Variables / Config](#-environment-variables--config)
- [Deployment](#-deployment)
- [Security Features](#-security-features)
- [Performance Optimizations](#-performance-optimizations)
- [Why This Project Stands Out](#-why-this-project-stands-out)
- [Challenges Faced](#-challenges-faced)
- [Learnings](#-learnings)
- [Future Improvements](#-future-improvements)
- [Contributors](#-contributors)

---

## 🌟 Introduction

**LibraSPHERE** is a complete, production-ready **Library Management System** built with PHP and MySQL that goes far beyond a basic CRUD app. It was designed to solve the very real day-to-day problems that college and school libraries face:

- 📖 Librarians struggle to track which books are issued, to whom, and when they're due back.
- 🎓 Students have no central, verified place to find notes and previous-year question papers.
- 📊 Administrators have no quick way to see what resources are in demand or which books are overdue.

LibraSPHERE solves all three problems in a single, beautiful, role-aware web portal.

### 🎯 What Does It Do?

| For Admins | For Students |
|---|---|
| Manage the entire book catalogue (30+ titles, 280+ copies) | Browse & search the book catalogue |
| Issue and return books with due-date tracking | View their own issued/returned books |
| See overdue books at a glance | Upload notes and PYQs for admin review |
| Review and approve student-uploaded notes & PYQs | Access approved academic resources |
| View analytics on demand and resource trends | Get personalised book recommendations |
| Run smart search across all resources | Use smart search across books and resources |

### 👥 Who Can Use It?

- 🏫 **College & school libraries** — to replace paper registers and Excel sheets
- 🧑‍💻 **Developers & students** — as a portfolio project, reference implementation, or learning resource
- 🎓 **CS/IT students** — to learn real-world PHP, MySQL, session authentication, and MVC-lite architecture
- 👩‍🏫 **Educators** — as a teaching example for full-stack web development

---

## 💡 Why LibraSPHERE?

> Most beginner library projects stop at book CRUD. LibraSPHERE goes further.

The project was born from a real observation: college libraries still use paper registers for book tracking, and students share notes through informal WhatsApp groups with no quality control. LibraSPHERE addresses both pain points with a polished, role-gated web portal.

**Real problems → Real solutions:**

```
❌ Paper registers get lost         →  ✅ Digital issue/return with timestamps
❌ No due-date visibility           →  ✅ Overdue tracking on the dashboard
❌ Unverified pirated notes         →  ✅ Admin-reviewed upload pipeline
❌ Students can't find PYQs         →  ✅ Searchable, categorised PYQ hub
❌ No demand insight for librarians →  ✅ Analytics with most-issued books
```

---

## 🖥️ Live Demo & Screenshots

🔗 **Live URL:** [https://lms-easy.infinityfreeapp.com/](https://lms-easy.infinityfreeapp.com/)

> The public demo uses a **read-only admin account** to prevent accidental data changes. All features are visible and explorable.

### Demo Credentials

| Role | Username | Password |
|------|----------|----------|
| Admin (Demo – Read Only) | `demo` | `demo123` |
| Student | Register a new account on the portal |  |

---

### 🗺️ Architecture Diagram

```
┌─────────────────────────────────────────────────────┐
│                    BROWSER (Client)                  │
│         HTML5 + Bootstrap 5 + JavaScript             │
└───────────────────────┬─────────────────────────────┘
                        │  HTTP Request
                        ▼
┌─────────────────────────────────────────────────────┐
│               PHP Application Layer                  │
│  ┌──────────┐  ┌──────────┐  ┌──────────────────┐  │
│  │ auth.php │  │config.php│  │ Business Logic   │  │
│  │(Sessions)│  │(DB conn) │  │ (*.php modules)  │  │
│  └──────────┘  └──────────┘  └──────────────────┘  │
└───────────────────────┬─────────────────────────────┘
                        │  MySQLi Queries
                        ▼
┌─────────────────────────────────────────────────────┐
│                  MySQL Database                      │
│  books │ students │ issued_books │ returned_books    │
│  notes_requests │ pyq_requests                       │
└─────────────────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────┐
│              /uploads/ directory                     │
│     (PDFs for notes and PYQ files)                   │
└─────────────────────────────────────────────────────┘
```

---

## ✨ Features

### 🔐 Authentication & Access Control

- [x] Session-based PHP authentication (no third-party libraries needed)
- [x] Two separate login flows: Admin and Student
- [x] Every protected page calls `require_admin()` or `require_student()` from `auth.php`
- [x] Read-only **public demo mode** with `is_demo_admin()` guard — writes are blocked with a SweetAlert popup
- [x] Automatic redirect to `login.html` for unauthenticated access

### 📘 Book Management (Admin)

- [x] Add books with title, author, category, ISBN, and stock count
- [x] Full book catalogue view with stock-level awareness
- [x] Issue books to students (records issue date + expected return date)
- [x] Accept book returns (moves record from `issued_books` → `returned_books`)
- [x] Overdue detection via SQL `WHERE return_date < CURDATE()`

### 🎓 Student Portal

- [x] Self-registration with course/department information
- [x] View personally issued and returned books
- [x] Browse the full catalogue
- [x] Upload notes and PYQs for review
- [x] Access all admin-approved academic resources
- [x] Personalised book recommendations based on course + history

### 🔍 Smart Search

- [x] `smart_search.php` queries across books, approved notes, and PYQs simultaneously
- [x] Returns consolidated, categorised results in a single response

### 📊 Analytics Dashboard

- [x] Most-issued books ranking
- [x] Category coverage overview
- [x] Overdue books count
- [x] Pending notes and PYQ review pipeline stats

### 🔔 Notifications

- [x] AJAX-powered notification panel (`get_notifications.php`, `get_note_notification.php`)
- [x] Alerts for pending uploads awaiting admin review

### 🌗 Dark / Light Mode

- [x] Bootstrap 5's `data-bs-theme="auto"` respects the OS preference automatically

---

## 🛠️ Tech Stack

| Layer | Technology | Purpose |
|-------|-----------|---------|
| **Frontend** | HTML5, CSS3 | Page structure and custom styles |
| **UI Framework** | Bootstrap 5.3 | Responsive grid, components, dark mode |
| **Fonts** | Google Fonts – Poppins | Clean, modern typography |
| **Alerts** | SweetAlert2 | Beautiful confirmation and error modals |
| **Scripting** | Vanilla JavaScript | AJAX calls, UI interactions |
| **Backend** | PHP 8.x | Server-side logic, session management |
| **Database** | MySQL | Relational data storage, CRUD |
| **DB Interface** | MySQLi (OOP) | Secure parameterised queries |
| **File Storage** | Local `/uploads/` | PDF storage for notes and PYQs |
| **Local Dev** | XAMPP | Apache + PHP + MySQL stack |
| **IDE** | VS Code | Development environment |
| **Deployment** | InfinityFree | Free PHP/MySQL web hosting |

---

## 🏗️ Architecture

LibraSPHERE follows a **flat-file MVC-lite** pattern — a practical approach for PHP projects without a framework:

```
┌─────────────────────────────────────────────────────────────────┐
│  VIEW LAYER (HTML templates embedded in PHP / standalone HTML)  │
│  index.html  login.html  dashboard.php  user_dashboard.php …   │
├─────────────────────────────────────────────────────────────────┤
│  CONTROLLER LAYER (PHP request handlers)                        │
│  login_admin.php  login_student.php  issue_book.php             │
│  return_book.php  submit_notes.php  submit_pyq.php …            │
├─────────────────────────────────────────────────────────────────┤
│  MODEL / UTILITY LAYER                                          │
│  auth.php (session guards)  config.php (DB connection)          │
│  smart_search.php  recommendations.php  analytics.php           │
├─────────────────────────────────────────────────────────────────┤
│  DATA LAYER                                                     │
│  MySQL DB: books, students, issued_books, returned_books,       │
│            notes_requests, pyq_requests                         │
└─────────────────────────────────────────────────────────────────┘
```

**Key Architectural Decisions:**

- All database credentials are stored in `config.php` (excluded from version control via `.gitignore`; a `config.sample.php` template is committed instead).
- `auth.php` is a central guard file — every protected page includes it first.
- No framework dependency means the code is readable by beginners without prior framework knowledge.
- File uploads land in `/uploads/` with server-validated MIME types.

---

## 🔄 How It Works — Complete Flow

This section explains the entire user journey from the moment someone visits the site to the moment data is written to the database — step by step.

---

### Step 1 — Landing Page

The visitor hits `index.html` or `home.html` — a static welcome page that presents two options: **Admin Login** and **Student Login/Register**.

```
User visits lms-easy.infinityfreeapp.com
         │
         ▼
    index.html / home.html
    (Choose role: Admin or Student)
```

---

### Step 2 — Authentication

#### Admin Login

```
login.html  →  POST to login_admin.php
                    │
                    ├── Query: SELECT * FROM admins WHERE username=?
                    ├── Verify password (password_verify)
                    ├── Set $_SESSION['admin_name']
                    │   (+ $_SESSION['is_demo_admin'] for demo account)
                    └── Redirect → home.php (Admin Home)
```

#### Student Login / Registration

```
register.html  →  POST to register_student.php
                       │
                       ├── Validate input (server-side)
                       ├── Hash password with password_hash()
                       ├── INSERT INTO students (name, email, course, …)
                       └── Redirect → login.html

login.html  →  POST to login_student.php
                    │
                    ├── SELECT * FROM students WHERE email=?
                    ├── password_verify()
                    ├── Set $_SESSION['student_id'], $_SESSION['student_name']
                    └── Redirect → user_home.php
```

---

### Step 3 — Protected Dashboards

Every PHP page that requires a logged-in user starts with:

```php
require_once("auth.php");
require_admin();   // or require_student()
```

If the session variable is missing, `auth.php` immediately redirects to `login.html` — the user never sees protected content.

---

### Step 4 — Admin Workflow

```
home.php (Admin Home)
    │
    ├── dashboard.php ────────── Live stats: total books, issued, returned,
    │                            overdue, pending notes, pending PYQs
    │
    ├── add_book.html ──────────  Form → book.php (INSERT INTO books)
    │
    ├── issue.html ─────────────  Form → issue_book.php
    │                            ┌─────────────────────────────────┐
    │                            │ 1. Verify student exists        │
    │                            │ 2. Verify book is in stock      │
    │                            │ 3. INSERT INTO issued_books     │
    │                            │ 4. UPDATE books SET stock = -1  │
    │                            └─────────────────────────────────┘
    │
    ├── return.html ─────────────  Form → return_book.php
    │                            ┌─────────────────────────────────┐
    │                            │ 1. Look up issued_books record  │
    │                            │ 2. INSERT INTO returned_books   │
    │                            │ 3. DELETE FROM issued_books     │
    │                            │ 4. UPDATE books SET stock = +1  │
    │                            └─────────────────────────────────┘
    │
    ├── issuedbooks.php ─────────  View all currently issued books
    ├── returnedbooks.php ───────  View all return history
    ├── book_stock.php ──────────  Stock levels per title
    ├── analytics.php ─────────── Charts and ranking tables
    ├── review_notes.php ───────── Approve / reject student note uploads
    ├── review_pyq.php ─────────── Approve / reject student PYQ uploads
    └── smart_search.php ───────── Search across books + resources
```

---

### Step 5 — Student Workflow

```
user_home.php (Student Home)
    │
    ├── user_dashboard.php ──────  Personal stats: books issued, returned
    │
    ├── upload_notes.html ───────  Form → submit_notes.php
    │                            ┌──────────────────────────────────────┐
    │                            │ 1. Validate file (PDF, size limit)   │
    │                            │ 2. Move to /uploads/                 │
    │                            │ 3. INSERT INTO notes_requests        │
    │                            │    with status = 'pending'           │
    │                            └──────────────────────────────────────┘
    │
    ├── upload_pyq.html ─────────  Same flow → submit_pyq.php
    │
    ├── view_notes.php ──────────  SELECT * FROM notes_requests WHERE status='approved'
    ├── view_pyq.php ───────────── SELECT * FROM pyq_requests  WHERE status='approved'
    ├── recommendations.php ─────  Personalised book list based on course + history
    └── smart_search.php ────────── Search the library
```

---

### Step 6 — Notification System

A JavaScript `setInterval` on the admin home page periodically fires AJAX requests to `get_notifications.php` and `get_note_notification.php`. These scripts return pending-count JSON, and the frontend updates the notification badge without a full page reload.

```
Admin page (home.php)
    │
    ├── setInterval(fetch('/get_notifications.php'), 30s)
    │        └─ returns { pending_pyqs: N }
    └── setInterval(fetch('/get_note_notification.php'), 30s)
             └─ returns { pending_notes: N }
```

---

## 📁 Folder Structure

```
LMS/
│
├── 📄 index.html              # Public landing page
├── 📄 home.html               # Static home page (pre-login)
├── 📄 login.html              # Shared login page (admin + student)
├── 📄 register.html           # Student registration page
├── 📄 issue.html              # Admin: book issue form (UI only)
├── 📄 return.html             # Admin: book return form (UI only)
├── 📄 add_book.html           # Admin: add new book form (UI only)
├── 📄 upload_notes.html       # Student: note upload form
├── 📄 upload_pyq.html         # Student: PYQ upload form
│
├── 🔐 auth.php                # Session guards & demo-mode helpers
├── ⚙️  config.php             # DB connection (gitignored)
├── 📋 config.sample.php       # Config template committed to repo
│
├── 🏠 home.php                # Admin home / navigation hub
├── 📊 dashboard.php           # Admin: live stats dashboard
├── 👤 user_home.php           # Student home / navigation hub
├── 📊 user_dashboard.php      # Student: personal stats
│
├── 📚 book.php                # Handles add-book form submission
├── 📦 book_stock.php          # Shows stock levels per book
├── 📤 issue_book.php          # Processes book issue (write to DB)
├── 📥 return_book.php         # Processes book return (write to DB)
├── 📋 issuedbooks.php         # View all currently issued books
├── 📋 returnedbooks.php       # View all returned books
│
├── 🔑 login_admin.php         # Admin login form handler
├── 🔑 login_student.php       # Student login form handler
├── 📝 register_student.php    # Student registration handler
├── 🚪 logout.php              # Destroys session, redirects to login
│
├── 🔍 smart_search.php        # Searches books + notes + PYQs
├── 💡 recommendations.php     # Personalised book recommendations
├── 📈 analytics.php           # Admin analytics and charts
│
├── 📄 submit_notes.php        # Student note upload handler
├── 📄 submit_pyq.php          # Student PYQ upload handler
├── ✅ review_notes.php        # Admin: approve/reject notes
├── ✅ review_pyq.php          # Admin: approve/reject PYQs
├── 👁️  view_notes.php         # Student: view approved notes
├── 👁️  view_pyq.php           # Student: view approved PYQs
│
├── 🔔 get_notifications.php   # AJAX: pending PYQ count
├── 🔔 get_note_notification.php # AJAX: pending notes count
│
├── 🎨 style.css               # Custom stylesheet
├── ⚡ script.js               # JS: dark mode toggle, AJAX, UI
│
├── 📁 uploads/                # Uploaded PDFs (notes & PYQs)
│
├── 🖼️  admin_portal.png       # Admin portal screenshot
├── 🖼️  Student_portal.png     # Student portal screenshot
│
├── 📄 .gitignore              # Excludes config.php & uploads
└── 📄 README.md               # Project documentation
```

---

## 📂 Important Files Explained

### `auth.php` — The Security Layer

This is the most critical file in the project. Every protected page includes it first.

```php
function require_admin()     // Redirects to login if no admin session
function require_student()   // Redirects to login if no student session
function is_demo_admin()     // Returns true if the demo account is logged in
function block_demo_admin()  // Blocks writes for demo users, shows SweetAlert
```

**Why it matters:** Rather than copy-pasting session checks on every page, all protection logic is centralised here. One change secures the entire app.

---

### `config.php` — Database Connection

```php
$connection = new mysqli($host, $user, $password, $database);
```

This file is listed in `.gitignore` and **never committed**. Contributors clone the repo, copy `config.sample.php` to `config.php`, and fill in their own credentials. This is a best practice that prevents credential leakage.

---

### `dashboard.php` — Admin Command Centre

Runs 7 SQL aggregate queries on page load and displays live KPIs:

- Total books in catalogue
- Currently issued books
- Books returned to date
- Total registered students
- Overdue books (`return_date < CURDATE()`)
- Pending notes awaiting review
- Pending PYQs awaiting review

Each KPI card uses Bootstrap's contextual colour classes (`bg-primary-subtle`, `bg-danger-subtle`, etc.) with hover animations.

---

### `smart_search.php` — Unified Search

Instead of searching books alone, this module runs parallel queries:

```sql
-- Books
SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR category LIKE ?

-- Notes
SELECT * FROM notes_requests WHERE title LIKE ? AND status = 'approved'

-- PYQs
SELECT * FROM pyq_requests WHERE title LIKE ? AND status = 'approved'
```

Results are merged and rendered in categorised sections on a single results page.

---

### `recommendations.php` — Personalised Discovery

Queries the student's borrowing history and course field, then suggests books in the same categories they've borrowed most, or books relevant to their course — giving a lightweight, SQL-powered recommendation engine with no external API.

---

### `analytics.php` — Demand Intelligence

Provides admins with:
- **Top 5 most-issued books** — ranked by issue frequency
- **Category coverage** — how many titles exist per subject category
- **Overdue list** — books past their due date with student contact info

---

## 🗄️ Database Design

### Entity-Relationship Overview

```
admins          students
  │                │
  │ issues         │ borrows
  ▼                ▼
issued_books ──► returned_books
                   │
students ──────► notes_requests
students ──────► pyq_requests
```

### Table Schemas

#### `books`
| Column | Type | Notes |
|--------|------|-------|
| `id` | INT PK AUTO_INCREMENT | |
| `title` | VARCHAR(255) | |
| `author` | VARCHAR(255) | |
| `category` | VARCHAR(100) | Used for recommendations |
| `isbn` | VARCHAR(50) | |
| `stock` | INT | Decremented on issue, incremented on return |

#### `students`
| Column | Type | Notes |
|--------|------|-------|
| `id` | INT PK AUTO_INCREMENT | |
| `name` | VARCHAR(100) | |
| `email` | VARCHAR(100) UNIQUE | Used as login identifier |
| `password` | VARCHAR(255) | `password_hash()` output |
| `course` | VARCHAR(100) | Used for recommendations |
| `roll_number` | VARCHAR(50) | |

#### `issued_books`
| Column | Type | Notes |
|--------|------|-------|
| `id` | INT PK AUTO_INCREMENT | |
| `student_id` | INT FK → students | |
| `book_id` | INT FK → books | |
| `issue_date` | DATE | |
| `return_date` | DATE | Expected return date; compared with CURDATE() for overdue |

#### `returned_books`
| Column | Type | Notes |
|--------|------|-------|
| `id` | INT PK AUTO_INCREMENT | |
| `student_id` | INT FK | |
| `book_id` | INT FK | |
| `issue_date` | DATE | Original issue date |
| `return_date` | DATE | Actual return date |

#### `notes_requests`
| Column | Type | Notes |
|--------|------|-------|
| `id` | INT PK AUTO_INCREMENT | |
| `student_id` | INT FK | |
| `title` | VARCHAR(255) | |
| `subject` | VARCHAR(100) | |
| `file_path` | VARCHAR(255) | Relative path under /uploads/ |
| `status` | ENUM('pending','approved','rejected') | Default: pending |
| `uploaded_at` | TIMESTAMP | |

#### `pyq_requests`
Same structure as `notes_requests`, for previous year question papers.

---

## 🔐 Authentication Flow

```
┌───────────────────────────────────────────────────────────┐
│                      Login Request                        │
│                  (POST from login.html)                   │
└───────────────────────────┬───────────────────────────────┘
                            │
              ┌─────────────▼─────────────┐
              │    login_admin.php OR      │
              │    login_student.php       │
              └─────────────┬─────────────┘
                            │
              ┌─────────────▼─────────────┐
              │  SELECT from DB by email/ │
              │  username                 │
              │  password_verify()        │
              └─────────────┬─────────────┘
                            │
               ┌────────────┴────────────┐
          FAIL │                         │ SUCCESS
               ▼                         ▼
        Show error msg          Set session variables:
                                $_SESSION['admin_name']
                                  OR
                                $_SESSION['student_id']
                                $_SESSION['student_name']
                                          │
                                          ▼
                                 Redirect to home.php
                                   or user_home.php
```

### Demo Admin Guard

The demo admin account has `$_SESSION['is_demo_admin'] = true`. Any write operation (add book, issue book, approve notes, etc.) calls `block_demo_admin()` first. If the demo flag is set, a SweetAlert modal is shown and the script exits without touching the database.

```php
function block_demo_admin($redirect = null) {
    require_admin();
    if (is_demo_admin()) {
        demo_block_message($redirect);  // Shows SweetAlert, exits
    }
}
```

---

## 👨‍💼 Admin Portal Guide

| Action | Navigate to | Underlying file |
|--------|------------|-----------------|
| View dashboard KPIs | Dashboard | `dashboard.php` |
| Add a new book | Add Book | `add_book.html` → `book.php` |
| Issue a book | Issue Book | `issue.html` → `issue_book.php` |
| Accept a return | Return Book | `return.html` → `return_book.php` |
| View all issued books | Issued Books | `issuedbooks.php` |
| View return history | Returned Books | `returnedbooks.php` |
| Check stock levels | Book Stock | `book_stock.php` |
| Approve/reject notes | Review Notes | `review_notes.php` |
| Approve/reject PYQs | Review PYQs | `review_pyq.php` |
| View analytics | Analytics | `analytics.php` |
| Search everything | Smart Search | `smart_search.php` |

---

## 🎓 Student Portal Guide

| Action | Navigate to | Underlying file |
|--------|------------|-----------------|
| View personal stats | My Dashboard | `user_dashboard.php` |
| Browse catalogue | Books | `book.php` |
| Upload notes | Upload Notes | `upload_notes.html` → `submit_notes.php` |
| Upload PYQs | Upload PYQs | `upload_pyq.html` → `submit_pyq.php` |
| View approved notes | Notes Library | `view_notes.php` |
| View approved PYQs | PYQ Library | `view_pyq.php` |
| Get recommendations | For You | `recommendations.php` |
| Search everything | Smart Search | `smart_search.php` |

---

## 🔍 Smart Search & Recommendations

### Smart Search

`smart_search.php` accepts a single query string and fans it out to three tables simultaneously using parameterised `LIKE` queries. Results are returned in three collapsible sections:

- **Books** — matching title, author, or category
- **Notes** — approved notes matching the keyword
- **PYQs** — approved question papers matching the keyword

This means a student searching "Operating Systems" will see both books on the topic AND any uploaded notes and PYQs — a significant UX improvement over single-table search.

### Recommendations Engine

`recommendations.php` implements a lightweight collaborative-filtering-inspired approach using pure SQL:

1. Fetch the student's borrowing history → extract categories
2. Count category frequency → identify the top preferred category
3. SELECT books in that category that the student hasn't yet borrowed
4. Also query books relevant to the student's registered course field

No external ML library is used — the intelligence comes from structured relational queries.

---

## 📈 Analytics Module

`analytics.php` generates an admin-only intelligence report:

```sql
-- Most issued books
SELECT b.title, COUNT(*) as issue_count
FROM issued_books ib
JOIN books b ON ib.book_id = b.id
GROUP BY b.id
ORDER BY issue_count DESC
LIMIT 10;

-- Category coverage
SELECT category, COUNT(*) as book_count
FROM books
GROUP BY category
ORDER BY book_count DESC;

-- Overdue books
SELECT s.name, s.email, b.title, ib.return_date
FROM issued_books ib
JOIN students s ON ib.student_id = s.id
JOIN books b    ON ib.book_id    = b.id
WHERE ib.return_date < CURDATE();
```

Results are displayed in sortable Bootstrap tables, giving librarians actionable insight into resource demand, coverage gaps, and at-risk borrowings.

---

## 📝 Notes & PYQ Workflow

This is one of the most distinctive features of LibraSPHERE:

```
Student                 Server                 Admin
   │                       │                     │
   │── Upload PDF ─────────▶│                     │
   │   (upload_notes.html)  │ Validate MIME type  │
   │                        │ Save to /uploads/   │
   │                        │ INSERT status='pending'
   │                        │                     │
   │                        │── Notification ────▶│
   │                        │   (AJAX badge)       │
   │                        │                     │
   │                        │◀── Review ──────────│
   │                        │    (review_notes.php)│
   │                        │                     │
   │                        │ UPDATE status=       │
   │                        │ 'approved'/'rejected'│
   │                        │                     │
   │◀── Visible in ─────────│                     │
   │    view_notes.php       │                     │
```

**Why this matters:** Any student could upload copyrighted or low-quality material. The admin review gate ensures only verified, useful resources reach the student community.

---

## 🛠️ Installation Guide

### Prerequisites

| Tool | Version | Purpose |
|------|---------|---------|
| XAMPP | 8.x | Apache + PHP + MySQL |
| PHP | 8.0+ | Backend runtime |
| MySQL | 8.0+ | Database |
| Git | Latest | Clone the repository |
| Browser | Any modern | Frontend access |

### Step-by-Step Setup

**1. Clone the repository**

```bash
git clone https://github.com/Khush54/LMS.git
cd LMS
```

**2. Place files in the web server root**

```bash
# On Windows with XAMPP:
# Copy the LMS folder to: C:\xampp\htdocs\LMS

# On Linux/macOS with XAMPP:
# Copy to: /opt/lampp/htdocs/LMS
```

**3. Set up the database**

```sql
-- Open phpMyAdmin → Create a new database
CREATE DATABASE lms_db;
USE lms_db;

-- Create the admins table
CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- Create the students table
CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  course VARCHAR(100),
  roll_number VARCHAR(50)
);

-- Create the books table
CREATE TABLE books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  author VARCHAR(255),
  category VARCHAR(100),
  isbn VARCHAR(50),
  stock INT DEFAULT 1
);

-- Create the issued_books table
CREATE TABLE issued_books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  book_id INT NOT NULL,
  issue_date DATE NOT NULL,
  return_date DATE NOT NULL,
  FOREIGN KEY (student_id) REFERENCES students(id),
  FOREIGN KEY (book_id)    REFERENCES books(id)
);

-- Create the returned_books table
CREATE TABLE returned_books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  book_id INT NOT NULL,
  issue_date DATE,
  return_date DATE,
  FOREIGN KEY (student_id) REFERENCES students(id),
  FOREIGN KEY (book_id)    REFERENCES books(id)
);

-- Create the notes_requests table
CREATE TABLE notes_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  title VARCHAR(255),
  subject VARCHAR(100),
  file_path VARCHAR(255),
  status ENUM('pending','approved','rejected') DEFAULT 'pending',
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id)
);

-- Create the pyq_requests table
CREATE TABLE pyq_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  title VARCHAR(255),
  subject VARCHAR(100),
  file_path VARCHAR(255),
  status ENUM('pending','approved','rejected') DEFAULT 'pending',
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id)
);

-- Insert a default admin account (password: admin123)
INSERT INTO admins (username, password)
VALUES ('admin', '$2y$10$YourBcryptHashHere');
```

> 💡 To generate the correct bcrypt hash, run this in PHP:
> ```php
> echo password_hash('admin123', PASSWORD_DEFAULT);
> ```

**4. Configure the database connection**

```bash
# Copy the sample config
cp config.sample.php config.php
```

Edit `config.php`:

```php
<?php
$host       = "localhost";
$user       = "root";         // your MySQL username
$password   = "";             // your MySQL password
$database   = "lms_db";      // the database you created

$connection = new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
```

**5. Create the uploads directory**

```bash
mkdir uploads
# On Linux/macOS: ensure it is writable
chmod 755 uploads
```

**6. Start XAMPP and access the app**

```
Open XAMPP Control Panel
Start → Apache
Start → MySQL

Open browser → http://localhost/LMS/
```

---

## 🔑 Environment Variables / Config

> LibraSPHERE uses a `config.php` file (not environment variables) for configuration. The file is gitignored for security.

| Variable | Description | Example |
|----------|-------------|---------|
| `$host` | MySQL host | `localhost` |
| `$user` | MySQL username | `root` |
| `$password` | MySQL password | `your_password` |
| `$database` | Database name | `lms_db` |

**`config.sample.php`** is committed to the repo as a template. Never commit your actual `config.php`.

---

## 🚀 Deployment

### Deploying to InfinityFree (as used in this project)

1. **Create a free account** at [infinityfree.net](https://infinityfree.net)
2. **Create a hosting account** and note your MySQL credentials from the control panel
3. **Upload files** via the File Manager or FTP (FileZilla recommended)
   - Upload all `.php`, `.html`, `.css`, `.js` files to `htdocs/`
   - Create the `uploads/` directory with write permissions
4. **Create the MySQL database** using InfinityFree's phpMyAdmin and run the schema above
5. **Update `config.php`** with InfinityFree MySQL host, username, password, and DB name
6. **Test** by visiting your InfinityFree subdomain

### Deploying to Any PHP Hosting

The same process applies to cPanel hosts, DigitalOcean, AWS Lightsail (LAMP), or any VPS with Apache/Nginx + PHP + MySQL. The application has zero framework dependencies, so no build step is required.

---

## 🔒 Security Features

| Feature | Implementation |
|---------|---------------|
| **Password hashing** | `password_hash()` with bcrypt (PHP default) |
| **Password verification** | `password_verify()` — no plain-text comparison |
| **SQL injection prevention** | MySQLi prepared statements with `?` placeholders throughout |
| **Session protection** | `session_start()` called once in `auth.php`; session variables checked before every action |
| **XSS mitigation** | `htmlspecialchars()` on all user-sourced output |
| **Demo mode isolation** | Write operations blocked at the PHP layer for demo accounts |
| **Credential separation** | `config.php` gitignored; `config.sample.php` committed without credentials |
| **File upload validation** | MIME type and extension validation before accepting uploads |

---

## ⚡ Performance Optimizations

- **Minimal dependencies** — no heavy frameworks means sub-second page loads even on shared hosting
- **Targeted SQL queries** — only the columns needed are selected; no `SELECT *` in production queries
- **Aggregate queries pre-computed** — dashboard stats use `COUNT()` at the DB level, not PHP array loops
- **Bootstrap 5 CDN** — browser caches the framework across visits; no self-hosted copies needed
- **AJAX notifications** — only the badge counter is refreshed, not the full page
- **Session reuse** — `session_status() === PHP_SESSION_NONE` check prevents double `session_start()` errors

---

## 🌟 Why This Project Stands Out

Most student library projects you'll find online have 3–5 PHP files and basic CRUD. LibraSPHERE is different because:

1. **Dual-role architecture** — Two completely separate portal experiences, not just a single dashboard with hidden buttons
2. **Demo mode** — Recruiters and reviewers can explore the admin portal safely without risking data corruption
3. **Academic resource hub** — Adds genuine value to students beyond just borrowing books
4. **Admin review pipeline** — Mirrors real-world content moderation workflows
5. **SQL-powered recommendations** — Personalisation without any ML library or external API
6. **AJAX notifications** — Real-time feel without a JavaScript framework
7. **Dark/Light mode** — OS-native preference detection via Bootstrap 5
8. **Production deployed** — Not just a localhost demo — actually live and accessible

---

## 🧩 Challenges Faced

| Challenge | How It Was Solved |
|-----------|------------------|
| Keeping demo admin truly read-only | Centralised `block_demo_admin()` function called at the top of every write handler |
| Overdue detection across time zones | Used MySQL's `CURDATE()` instead of PHP `date()` to keep date comparison consistent at the DB level |
| File upload security on shared hosting | MIME type validation + ENUM extension whitelist before saving to `/uploads/` |
| Cross-role session conflicts | Separate session keys (`admin_name` vs `student_id`) with distinct guard functions |
| InfinityFree MySQL connection quirks | Used the full remote MySQL hostname from the InfinityFree panel instead of `localhost` |
| Bootstrap 5 dark mode in PHP-rendered pages | Used `data-bs-theme="auto"` on `<html>` so the OS preference is respected without JavaScript |

---

## 🎓 Learnings

Building LibraSPHERE taught us:

- How to architect a **role-based access control** system from scratch using PHP sessions — without any framework or library
- How to design a **normalised relational schema** with foreign keys and compound queries
- The value of **centralising security logic** (`auth.php`) versus repeating it on every page
- How to implement a **file upload pipeline** with server-side validation in PHP
- How to use **SweetAlert2** for UX-friendly blocking interactions (the demo mode alert)
- How to deploy a PHP/MySQL application to **shared hosting** with all its quirks (MySQL hostnames, file permissions)
- The difference between **aggregate SQL** (dashboard stats) and **transactional SQL** (issue/return operations)
- How **AJAX polling** can give a real-time feel to a fully server-rendered application

---

## 🔭 Future Improvements

- [ ] **Email notifications** — Send due-date reminder emails using PHPMailer or SMTP
- [ ] **Barcode scanning** — Integrate a JavaScript barcode reader for faster book issue/return
- [ ] **Fine calculation** — Automatically compute and display overdue fines
- [ ] **REST API** — Expose book catalogue and student data as JSON for a mobile app frontend
- [ ] **React/Vue frontend** — Rebuild the UI as a SPA consuming the REST API
- [ ] **Role: Super Admin** — A top-level role to manage multiple branch libraries
- [ ] **Book reservation** — Allow students to reserve books currently issued to others
- [ ] **Advanced analytics** — Month-over-month issue trends with Chart.js visualisations
- [ ] **Docker containerisation** — `Dockerfile` + `docker-compose.yml` for one-command local setup
- [ ] **CI/CD pipeline** — GitHub Actions to lint and test on every push
- [ ] **PDF preview** — In-browser PDF viewer for notes/PYQs instead of download-only

---

## 👥 Contributors

<table>
  <tr>
    <td align="center">
      <a href="https://github.com/Khush54">
        <img src="https://github.com/Khush54.png" width="80" style="border-radius:50%"/><br/>
        <sub><b>Khushpreet Kaur</b></sub>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/Sahid934">
        <img src="https://github.com/Sahid934.png" width="80" style="border-radius:50%"/><br/>
        <sub><b>Sahid Alam</b></sub>
      </a>
    </td>
  </tr>
</table>

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

```bash
# Fork the repo
# Create your feature branch
git checkout -b feature/your-feature-name

# Commit your changes
git commit -m "feat: add your feature description"

# Push and open a Pull Request
git push origin feature/your-feature-name
```

Please follow standard PHP coding conventions and test your changes locally with XAMPP before submitting a PR.

---

## 🌐 Links

| Resource | URL |
|----------|-----|
| 🚀 Live Demo | [lms-easy.infinityfreeapp.com](https://lms-easy.infinityfreeapp.com/) |
| 📁 Repository | [github.com/Khush54/LMS](https://github.com/Khush54/LMS) |
| 🐛 Report a Bug | [Open an Issue](https://github.com/Khush54/LMS/issues) |

---

<div align="center">

Built with ❤️ by [Khushpreet Kaur](https://github.com/Khush54) & [Sahid Alam](https://github.com/Sahid934)

⭐ **Star this repo if it helped you!** ⭐

</div>
