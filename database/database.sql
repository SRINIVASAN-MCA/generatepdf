CREATE TABLE tour_booking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_id VARCHAR(100) NOT NULL,
    username VARCHAR(255) NOT NULL,
    tour_name VARCHAR(100) NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    adults INT NOT NULL,
    children INT NOT NULL,
    inclusion VARCHAR(255) NOT NULL,
    exclusion VARCHAR(255) NOT NULL,
    cost INT NOT NULL,
    tour_image VARCHAR(3000) NULL,
    notes VARCHAR(3000) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE vacation_summary (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stay VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    image VARCHAR(255) NOT NULL,
    itinerary_content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fk_tour_booking INT NOT NULL,
    FOREIGN KEY (fk_tour_booking) REFERENCES tour_booking(id)
);
