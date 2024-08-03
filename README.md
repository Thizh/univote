# Univote Student Voting System

Welcome to Univote, a comprehensive student voting system designed to streamline administrative tasks and enhance the voting experience. This guide will help you set up both the frontend and backend components of the project.

## Table of Contents

1. [Introduction](#introduction)
2. [Prerequisites](#prerequisites)
3. [Setup Instructions](#setup-instructions)
   - [Frontend](#frontend)
   - [Backend](#backend)
4. [Running the Project](#running-the-project)
   - [Frontend](#frontend-1)
   - [Backend](#backend-1)

## Introduction

Univote is built with a modern tech stack:
- **Frontend:** React with Vite
- **Backend:** Laravel

This project enhancing student voting system in university student elections.

## Prerequisites

Ensure you have the following installed on your machine:

- **Node.js** (for the frontend)
- **npm** or **yarn** (for managing frontend packages)
- **PHP** (>= 8.2) (for the backend)
- **Composer** (for managing backend dependencies)
- **XAMPP** or another compatible software

## Setup Instructions

**Clone the Repository**

   ```bash
   git clone https://github.com/Thizh/univote.git
   ```

### Frontend

   ```bash
   cd univote_frontend
   ```

1. **Install Dependencies**

   ```bash
   npm install
   #or
   yarn install
   ```

2. **Run the Development Server**

   ```bash
   npm run dev
   #or
   yarn dev
   ```

   The frontend should now be accessible at http://localhost:3000.

### Backend

  ```bash
  cd univote_backend
  ```

1. **Install Dependencies**

   ```bash
   composer install
   ```

2. **Configuration**

   Copy the .env.example file to .env and update the database and other configurations.

   ```bash
   cp .env.example .env
   ```

3. **Start Database system**
   
   Ensure that your database system (MySQL) is running. You can start MySQL via XAMPP or another compatible software.

4. **Generate Application Key**

   ```bash
   php artisan key:generate
   ```
5. **Run Migrations**

   ```bash
   php artisan migrate
   ```

6. **Run the Development Server**

   ```bash
   php artisan serve
   ```

   The backend should now be accessible at http://localhost:8000.


## Running The Project

### Frontend

  To run the frontend in development mode, use

   ```bash
   npm run dev
   # or
   yarn dev
   ```

### Backend

  To run the backend in development mode, use:

  ```bash
  php artisan serve
  ```

Ensure that both the frontend and backend are running for full functionality of the application.  
GOOD LUCK!
