CREATE TABLE users(
userID        		MEDIUMINT UNSIGNED 		NOT NULL 	AUTO_INCREMENT,
firstname 			VARCHAR(20) 			NOT NULL,
lastname   			VARCHAR(40) 			NOT NULL,
username   			VARCHAR(30) 			NOT NULL,
email          		VARCHAR(40) 			NOT NULL,
password       		VARCHAR(50) 			NOT NULL,
age					VARCHAR(3)				NOT NULL,
gender 				VARCHAR(6) 				NOT NULL,
street 				VARCHAR(40)				NOT NULL,
city 				VARCHAR(30)				NOT NULL,
state 				CHAR(2)					NOT NULL,
zipCode				CHAR(5)					NOT NULL,
registrationDate 	DATETIME 				NOT NULL	DEFAULT NOW(),
userType           	CHAR(1) 				NOT NULL	DEFAULT 'r',
userStatus			VARCHAR(20) 			NOT NULL	DEFAULT 'ok',
userbookCount			CHAR(1) 			    NOT NULL	DEFAULT '0',
CONSTRAINT pk_userID PRIMARY KEY (userID)
);
CREATE TABLE login(
loginID        		MEDIUMINT UNSIGNED 		NOT NULL 	AUTO_INCREMENT,
userID				MEDIUMINT UNSIGNED		NOT NULL,
loginTime 			TIMESTAMP 				NOT NULL	DEFAULT NOW(),
CONSTRAINT pk_loginID PRIMARY KEY (loginID), 
FOREIGN KEY (userID)  REFERENCES users(userID)
);
CREATE TABLE book(
bookID        		MEDIUMINT UNSIGNED 		NOT NULL 	AUTO_INCREMENT,
title 				VARCHAR(60) 			NOT NULL,
authorfirstname 	VARCHAR(30) 			NOT NULL,
authorlastname   	VARCHAR(30) 			NOT NULL,
isbn   				VARCHAR(20) 			NOT NULL,
quantity         	VARCHAR(10)				NOT NULL,
genre          	 	VARCHAR(60) 			NOT NULL,
CONSTRAINT pk_bookID PRIMARY KEY (bookID)
);
CREATE TABLE reservation(
reservationID       MEDIUMINT UNSIGNED 		NOT NULL 	AUTO_INCREMENT,
userID				MEDIUMINT UNSIGNED		NOT NULL,
bookID				MEDIUMINT UNSIGNED		NOT NULL,
reservationStatus 	VARCHAR(20) 			NOT NULL,
reservDate	 		DATETIME				NOT NULL,
reservReturnDate   	DATETIME				NOT NULL	 DEFAULT NOW(),
CONSTRAINT pk_reservationID PRIMARY KEY (reservationID),
FOREIGN KEY (userID)  REFERENCES users(userID),
FOREIGN KEY (bookID)  REFERENCES book(bookID)
);
CREATE TABLE loan(
loanID 				MEDIUMINT UNSIGNED 		NOT NULL 	AUTO_INCREMENT,
userID				MEDIUMINT UNSIGNED		NOT NULL,
bookID				MEDIUMINT UNSIGNED		NOT NULL,
loanStatus 			VARCHAR(20) 			NOT NULL,
loanDate			DATETIME				NOT NULL,
loanRetunDate    	DATETIME				NOT NULL,
CONSTRAINT pk_loanID  PRIMARY KEY (loanID),
FOREIGN KEY (userID)  REFERENCES users(userID),
FOREIGN KEY (bookID)  REFERENCES book(bookID)
);
