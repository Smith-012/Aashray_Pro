# 🏡 Aashray – Rental Property Management System  
A modern & professional web-based platform for discovering, managing, and renting residential and commercial properties.

<p align="center">
  <img src="logo.png" alt="Aashray Logo" width="120"/>
</p>

---

## 🔰 Tech Stack  
<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white" />
  <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white" />
  <img src="https://img.shields.io/badge/Bootstrap%205-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" />
  <img src="https://img.shields.io/badge/JavaScript-F7E018?style=for-the-badge&logo=javascript&logoColor=black" />
  <img src="https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white" />
  <img src="https://img.shields.io/badge/JSON-000000?style=for-the-badge&logo=json&logoColor=white" />
</p>

---

## ✨ Overview  
**Aashray** is a rental property management system designed to bring transparency, convenience, and automation to the rental process.  
It provides a centralized platform where users can search for rental properties and admins can manage property listings, tenants, payments, and feedback.  
The project aims to reduce manual effort, increase transparency, and offer a user-friendly experience for both administrators and tenants.  
It serves two main roles:

- **Tenants/Users** — browse properties, apply for rent, make payments, and manage their profiles  
- **Admins** — manage properties, tenants, payments, queries, invoices, and feedback  

---

## 🚀 Features  

### 👤 User Features  
- Create account & login  
- View property listings  
- Check testimonial reviews  
- Search & filter by type & location (PG, Flat, Farmhouse, Office, etc.)  
- Apply for rent with verification documents  
- Make online payments (UPI/Card etc.)  
- View / Download invoice  
- Submit feedback  
- Manage profile  
- Contact admin for queries  

### 👨‍💼 Admin Features  
- Admin login  
- Manage all property listings  
- View & manage tenants & users  
- View booking details & invoices  
- Review payment details  
- View & manage contact queries & feedback  
- View bank details  
- Access invoices and rental records  
- Dashboard overview  

---

## 🎯 Project Objectives  
Based on the final project documentation, the key objectives of **Aashray** are:

### ✔ User-Friendly Interface  
Provide an intuitive and smooth UI for both admins and tenants.

### ✔ Scalability  
Ensure the system can be expanded with new features, more users, and additional property categories.

### ✔ Secure System  
Protect user data, payments, and login sessions with proper security measures.

### ✔ Centralized Record-Keeping  
Maintain organized records of properties, tenants, payments, and bookings in a unified database.

### ✔ Customer Satisfaction  
Deliver accurate, accessible, and reliable property information that meets user needs.

---

## 🔍 Purpose  
The purpose of **Aashray** is to:

- Streamline the rental property management process  
- Replace traditional manual processes with an automated digital solution  
- Improve communication between tenants and admin  
- Offer transparency in rental terms, payments, and documentation  
- Make the rental experience **faster, more efficient, and user-friendly**

Aashray reduces manual paperwork, miscommunication, delays, and disorganized records—bringing the entire rental lifecycle online.

---

## 📌 Scope  
The scope of the project includes:

### ✔ Property Listings  
Supports apartments, PGs, farmhouses, offices, and other residential rentals.

### ✔ Rental Application  
Users can apply online with verification documents.

### ✔ Payment Module  
Allows secure online payments with invoices.

### ✔ Phase-1 Limitations  
Early version has limited features but includes the core rental workflow.

### ✔ Scalable Architecture  
Designed to support:
- More property categories  
- More users  
- Future enhancements (mobile app, broker portals, AI recommendations)

---

## 🛠 Requirements  

### **Hardware**
- Intel i3 / Ryzen 3 or above  
- 2 GB RAM minimum  
- 50 GB HDD/SSD  

### **Software**
- Windows OS  
- VS Code  
- Any Browser  
- MySQL Database  
- XAMPP/WAMP/LAMP Server  

---

## 🧱 Database Structure  
The system uses a relational MySQL database including:

| Table Name       | Description |
|------------------|-------------|
| **users**        | Stores user account details |
| **admin**        | Admin login credentials |
| **properties**   | Property listings & owner data |
| **tenants**      | Tenant/booking information |
| **payments**     | Payment & transaction details |
| **invoice**      | Invoice generation records |
| **feedback**     | User reviews & ratings |
| **contact_us**   | User queries/messages |
| **bank_detail**  | Admin-side bank details |

---

## 🏗 System Design  
### Included in documentation:
- Context Level DFD  
- Level-1 DFD (Admin & User)  
- Level-2 DFD (Admin & User)  
- Data Dictionary  
- ER Concepts  
- Scheduling Plan  
- Architectural Flow  

---

## 🖥️ Module Descriptions  

### **1. Registration & Login**  
Basic authentication for both **Users** and **Admin**.  
Includes functionalities such as:  
- User registration  
- Secure login  
- Forgot Password / Reset Password  
- Session management  

---

### **2. Property Management**  
Admin can perform full CRUD operations for rental property listings:  
- Add / Edit / Delete properties  
- Manage property type (PG, Apartment, Office, Farmhouse, etc.)  
- Update rent amount, address, and other details  
- Upload property images  
- Manage property availability status  

---

### **3. Tenant & Booking Management**  
Manages complete booking lifecycle including:  
- Tenant information  
- Verification documents (Aadhar, PAN, etc.)  
- Booking dates & duration  
- Rent period (Daily / Monthly)  
- Payment status tracking  
- Check-in / Check-out dates  

---

### **4. Payment System**  
Secure payment flow supporting online transactions:  
- UPI, Card, Net Banking options  
- Stores transaction details (ID, date, amount, mode)  
- Booking-wise payment linking  
- Invoice auto-generation  
- Payment history for admin  

---

### **5. Feedback & Contact**  
Provides communication channels between users and admin:  
- Users can submit feedback about property or experience  
- Users can submit contact queries  
- Admin can view, manage, and respond to feedback & queries  
- Helps improve system usability and customer support  

---

## ⚠️ Limitations  
- No cancellation feature for bookings  
- Only two roles (Admin & User)  
- System performance may degrade under high traffic  
- Manual updates needed for expanding property categories  
- Limited customization for advanced users  

---

## 🚀 Future Enhancements  
- Mobile App (Android / iOS)  
- AI-based property recommendations  
- Subscription-based premium features  
- Integration with brokers & agencies  
- Interactive map-based property search  
- Auto-reminder notifications  
- Multi-role access (Owner / Agent / Staff)  

---

## <p align="center">Made with ❤️ by Sp</p>
