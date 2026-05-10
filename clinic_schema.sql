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
    course VARCHAR(100),
    school_year VARCHAR(50),
    date_recorded DATE,    
    diagnosis VARCHAR(255),
    meds_given VARCHAR(255),      
    item_id INT,                  
    doctor_id INT,               
    status VARCHAR(50) DEFAULT 'Active',
    FOREIGN KEY (doctor_id) REFERENCES doctors(doctor_id),
    FOREIGN KEY (item_id) REFERENCES inventory(item_id)
);

CREATE TABLE inventory (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    item_name VARCHAR(255) NOT NULL,
    category ENUM('Medicine','Capsule','Supplies') NOT NULL,
    stock_quantity INT DEFAULT 0,
    unit VARCHAR(50) DEFAULT 'pcs',
    expiry_date DATE,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO doctors (doctor_name, specialization) VALUES 
('Dr. Juan Dela Cruz', 'Cardiology'),
('Dr. Maria Santos', 'Pediatrics');