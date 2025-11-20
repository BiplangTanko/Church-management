 Church Management System

A web based application built with PHP and MySQL, designed to help churches manage sermons, prayer requests, member activities, and administrative operations. This system was created as a final year project and is tailored for churches like Church of Pentecost, Asokwa, or any ministry that needs a digital platform for internal coordination.

---

## **Features**

### **Admin Panel**

* Secure admin login and logout
* Upload sermons in PDF, MP3, or MP4 format
* View, search, filter, paginate, and delete sermons
* Manage church membership records
* View and respond to submitted prayer requests

### **Member Panel**

* Secure member login and logout
* Submit prayer requests
* View and download sermons
* Filter sermons by title or preacher
* Switch between grid and list views with pagination
* Watch sermon videos or play audio files directly in the browser

---

## **Database Structure**

### **sermons**

| Field       | Type         |
| ----------- | ------------ |
| id          | INT (PK, AI) |
| title       | VARCHAR(255) |
| description | TEXT         |
| preacher    | VARCHAR(255) |
| sermon_date | DATE         |
| file_path   | TEXT         |

### **prayer_requests**

| Field        | Type         |
| ------------ | ------------ |
| id           | INT (PK, AI) |
| member_id    | INT (FK)     |
| title        | VARCHAR(255) |
| message      | TEXT         |
| submitted_at | TIMESTAMP    |

### **admin and members**

(Used for authentication and panel access)

---

## **Folder Structure**

Organized to separate admin operations, member functionalities, assets, and backend logic.

**Admin Credentials for Demo:**
username: admin
password: admin123

---

## **Access to the Full Source Code**

This repository contains a limited demo version of the project.
The complete production ready source code, including advanced features and the full admin module, is available for purchase.

If you want full access, contact me directly on WhatsApp : 09028085168 or reach out via my portfolio website https://biplangport.infinityfreeapp.com/my-portfolio/uploads/contact.php. There I will show you a guide on the complete system setup.
This helps support future development and ensures the project continues to grow.

