/*
ALTER SESSION SET NLS_DATE_FORMAT = 'DD-MM-YYYY HH24:MI:SS';
*/

CREATE DATABASE Il_Pescaggio;
USE Il_Pescaggio;

CREATE TABLE rider(
    id int PRIMARY KEY,
    nome varchar(255) NOT NULL,
    cognome varchar(255) NOT NULL
);

CREATE TABLE username(
    email varchar(255) PRIMARY KEY,
    firstName varchar(255) NOT NULL,
    surname varchar(255) NOT NULL,
    registrationDate timestamp NOT NULL,
    nCard int NOT NULL,
    via varchar(255) NOT NULL,
    civ int NOT NULL,
    cap int NOT NULL,
    tel int NOT NULL,
    pasw varchar(255) NOT NULL
);

CREATE TABLE dish(
    id int PRIMARY KEY,
    dishCost int NOT NULL,
    creationDate timestamp NOT NULL,
    description varchar(255) NOT NULL,
    gluten TINYINT(1) NOT NULL,
    lactose TINYINT(1) NOT NULL,
    dishName varchar(255) NOT NULL
);

CREATE TABLE foodOrder(
    id int PRIMARY KEY,
    delivery TINYINT(1) NOT NULL,
    dateAndTimePay DATETIME,
    dateAndTimeDelivered DATETIME,
    idUser varchar(255) NOT NULL REFERENCES username(email) ,
    idRider int REFERENCES rider(id)
);

CREATE TABLE orderedFood(
    id int PRIMARY KEY,
    idOrder int NOT NULL REFERENCES foodOrder(id) ,
    idDish int NOT NULL REFERENCES dish(id) ,
    quantity int NOT NULL
);