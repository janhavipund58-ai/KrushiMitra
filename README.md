🌱 Krushi Mitra – AI-Powered Agricultural Assistance System

Krushi Mitra is an AI-powered agricultural assistance platform designed to help farmers identify crop and plant diseases and get suitable treatment recommendations. The system combines Artificial Intelligence, Machine Learning, web development, and database management to provide a simple digital solution for crop-care and agricultural management.

📌 Project Overview

Farmers may face difficulties in identifying plant diseases at an early stage and selecting appropriate treatments. Krushi Mitra provides a user-friendly platform where users can upload an image of an affected plant or crop leaf.

The system analyzes the uploaded image using an AI/ML-based disease prediction module and provides information about the predicted disease, possible causes, preventive measures, and recommended treatment or agricultural medicine.

The project also includes management features for agricultural products, medicines, customers, stock, and billing.

🎯 Objectives

- Identify possible crop and plant diseases using AI/ML.
- Provide useful information about detected diseases.
- Recommend suitable treatments or agricultural medicines.
- Maintain crop, disease, and medicine information.
- Manage agricultural product inventory and stock.
- Manage customer information.
- Generate and manage sales/billing records.
- Provide a centralized dashboard for monitoring the system.
- Create an easy-to-use digital platform for agricultural assistance.

🚀 Key Features

🌱 Crop Management

- Add and manage crop information.
- Store crop-related details.
- View available crop information.

🔍 Plant Disease Detection

- Upload an image of a plant or crop leaf.
- Analyze the uploaded image.
- Predict the possible plant disease.
- Display disease-related information.

💊 Medicine Recommendation

- Display recommended medicines or treatments.
- Maintain medicine/product information.
- Provide basic preventive information.

📦 Inventory Management

- Add agricultural products and medicines.
- Track available stock.
- Update product quantities.
- Monitor inventory information.

👨‍🌾 Customer Management

- Add customer details.
- Store customer information.
- Manage customer records.

🧾 Billing and Sales

- Create sales records.
- Generate billing information.
- Maintain transaction details.
- Track products sold to customers.

📊 Dashboard

The dashboard provides an overview of important system information such as:

- Total crops
- Total diseases
- Total medicines/products
- Available stock
- Total customers
- Sales and billing information

🛠️ Technologies Used

Technology| Purpose
HTML| Web page structure
CSS| Website styling
JavaScript| Frontend interaction
PHP| Backend/server-side functionality
MySQL| Database management
XAMPP| Local development server
Python| Machine Learning / AI module
Machine Learning| Plant disease prediction

🏗️ System Architecture

                ┌─────────────────────┐
                │       User          │
                └──────────┬──────────┘
                           │
                           ▼
                ┌─────────────────────┐
                │   Krushi Mitra UI   │
                │    HTML/CSS/JS      │
                └──────────┬──────────┘
                           │
                           ▼
                ┌─────────────────────┐
                │    PHP Backend      │
                └───────┬───────┬─────┘
                        │       │
             ┌──────────┘       └──────────┐
             ▼                             ▼
    ┌─────────────────┐          ┌─────────────────┐
    │   MySQL Database│          │   ML Prediction │
    │                 │          │      Model      │
    └─────────────────┘          └────────┬────────┘
                                          │
                                          ▼
                                ┌──────────────────┐
                                │ Disease Prediction│
                                │ & Recommendation  │
                                └──────────────────┘

🧠 Machine Learning Module

The Machine Learning component is used for plant disease prediction.

Basic Workflow

Plant/Crop Image
       ↓
Image Upload
       ↓
Image Preprocessing
       ↓
ML Model
       ↓
Disease Prediction
       ↓
Disease Information
       ↓
Treatment / Medicine Recommendation

The exact ML algorithm depends on the trained model and dataset used in the project.

🗄️ Database

The MySQL database can maintain information related to:

- Users
- Crops
- Diseases
- Medicines
- Products
- Customers
- Stock
- Sales
- Billing

The database helps keep the application's information organized and accessible.

💻 Installation and Setup

1. Install XAMPP

Install XAMPP on your computer and start:

Apache
MySQL

2. Clone the Repository

git clone https://github.com/YOUR-USERNAME/KrushiMitra.git

3. Move the Project

Place the project folder inside:

C:\xampp\htdocs\

For example:

C:\xampp\htdocs\KrushiMitra

4. Configure the Database

Open:

http://localhost/phpmyadmin

Create the required database and import the project's SQL file if one is provided.

5. Configure Database Connection

Update the database connection file with your local MySQL settings.

Typical XAMPP settings are:

Host: localhost
Username: root
Password: 
Database: KrushiMitra

6. Run the Project

Open your browser and visit:

http://localhost/KrushiMitra/

If the main file is "index.php", you can also use:

http://localhost/KrushiMitra/index.php

📁 Example Project Structure

KrushiMitra/
│
├── index.php
├── dashboard.php
├── login.php
├── crops.php
├── diseases.php
├── medicines.php
├── products.php
├── customers.php
├── sales.php
├── billing.php
│
├── css/
│   └── style.css
│
├── js/
│   └── script.js
│
├── images/
│
├── uploads/
│
├── model/
│   └── disease_prediction_model
│
├── config/
│   └── db.php
│
└── database/
    └── KrushiMitra.sql

The actual file structure may differ depending on the implementation.

🔐 Security Considerations

The system can be enhanced with:

- User authentication
- Password hashing
- Input validation
- SQL injection prevention
- Secure image upload validation
- Session management
- Role-based access control

🌾 Future Scope

Future versions of Krushi Mitra can include:

- 📱 Mobile application
- 🌐 Multilingual support for regional languages
- 🤖 More advanced disease-detection models
- ☁️ Cloud deployment
- 🌦️ Weather-based crop recommendations
- 📈 Crop yield prediction
- 🛰️ Satellite/remote-sensing integration
- 🗺️ Location-based agricultural recommendations
- 🔔 Weather and disease alerts
- 💬 AI-based agricultural chatbot

🎓 Academic Project

Project Name: Krushi Mitra
Project Type: Academic Mini Project
Domain: Artificial Intelligence / Data Science / Agriculture
Purpose: AI-based crop disease detection and agricultural management

👩‍💻 Project Author

Janhavi Ganeshrao Pund

Artificial Intelligence and Data Science

📄 License

This project is developed for educational and academic purposes.
