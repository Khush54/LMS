Library Management System (LMS)
Overview
A web-based Library Management System automates core library operations like book inventory management, issuing/returning books, and student resource requests (notes/PYQs). Developed as a minor project for BTech CSE at Universal Institute of Engineering and Technology, Lalru, Punjab. Replaces manual processes with a secure, role-based digital interface for admins and students.​

Key Features
Admin Panel: Manage books (add/delete/view), handle student registration, process issue/return requests, review notes/PYQ uploads.

Student Panel: Search/view available books, check issued/returned history, upload notes/PYQs for approval.

Responsive UI: Clean dashboard with sidebar navigation, theme toggle (light/dark), iframe-based module loading.

Security: Role-based login, session management, form validation to prevent common attacks.​

Tech Stack
Component	Technologies
Frontend	HTML5, CSS3, JavaScript, Bootstrap 5
Backend	PHP (server-side scripting, MySQLi)
Database	MySQL (normalized tables: admins, students, books, issued/returned, notes, PYQs)
Tools	XAMPP (Apache/MySQL), VS Code, phpMyAdmin
Database Schema
Books: id, title, author, publisher, isbn, category, copies, publishdate.

Students: id, name, rollnumber, email, course, regid, password.

Issued Books: studentid, bookid, issuedate, returndate.

Notes/PYQs: subject, filepath, status (pending/approved/rejected), timestamps.​

Installation & Setup
Clone repo: git clone <repo-url>.

Start XAMPP (Apache + MySQL).

Import database: Create librarydb in phpMyAdmin, run schema SQL from /db/schema.sql.

Place files in htdocs/lms/ and access http://localhost/lms/.

Default login: Admin (check config.php or create via registration).​

Usage
Admin: Login → Dashboard → Manage books/students/issues → Approve PYQ/notes.

Student: Register/Login → Browse books → Request issue/upload resources.

Tested for 1000+ books/500 users with <2s response time.​

Development Methodology
Followed Waterfall model: Requirements → Design (UML/ER) → Implementation → Testing (unit/integration/UAT) → Deployment. Gantt chart tracked 5-week timeline.​​

Future Enhancements
Fine calculations, barcode/RFID integration.

Cloud deployment, advanced search, notifications, e-books
