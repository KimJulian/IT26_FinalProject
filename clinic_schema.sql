CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'Staff'
);

INSERT INTO users (username, password) VALUES ('admin', '1234');

CREATE TABLE doctors (
    doctor_id INT PRIMARY KEY AUTO_INCREMENT,
    doctor_name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100)
);

CREATE TABLE patients (
    patient_id INT PRIMARY KEY AUTO_INCREMENT,
    patient_name VARCHAR(100) NOT NULL,
    admission_date DATE,
    diagnosis VARCHAR(255),
    doctor_id INT,
    status VARCHAR(50) DEFAULT 'Active',
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id)
);

INSERT INTO doctors (doctor_name, specialization) 
VALUES ('Dr. Juan Dela Cruz', 'Cardiology');

INSERT INTO patients (patient_name, admission_date, diagnosis, doctor_id) 
VALUES ('Kim Julian D. Mentopa', '2026-05-04', 'Hypertension', 1);