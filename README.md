# Church Management System

A web-based application built with PHP and MySQL to help manage sermons, prayer requests, member activities, and administrative functions for churches like **Church of Pentecost, Asokwa**.

---

## 🛠 Features

### 👤 Admin Panel
- Secure admin login/logout
- Upload sermons (PDF, MP3, MP4)
- View, search, filter, paginate, and delete sermons
- Manage church members
- View submitted prayer requests

### 🙋 Member Panel
- Secure member login/logout
- Submit prayer requests
- View and download sermons
  - Filter by title/preacher
  - View in rows/columns with pagination
  - Play video/audio sermons or download PDFs

---

## 🗃 Database Structure

### `sermons`
| Field         | Type         |
|--------------|--------------|
| id           | INT (PK, AI) |
| title        | VARCHAR(255) |
| description  | TEXT         |
| preacher     | VARCHAR(255) |
| sermon_date  | DATE         |
| file_path    | TEXT         |

### `prayer_requests`
| Field         | Type         |
|--------------|--------------|
| id           | INT (PK, AI) |
| member_id    | INT (FK)     |
| title        | VARCHAR(255) |
| message      | TEXT         |
| submitted_at | TIMESTAMP    |

### `admin` and `members`
(Assumed from implementation)

---

## 📁 Folder Structure

## ADMIN CREDENTIALS:
username-admin
password-admin123