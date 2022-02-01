/*
CREATE DATABASE myDB;
USE myDB;
CREATE TABLE prova(
    id timestamp PRIMARY KEY
)
ALTER SESSION SET NLS_DATE_FORMAT = 'DD-MM-YYYY HH24:MI:SS';
INSERT INTO prova VALUES (TO_DATE('2003/05/03 21:02:44', 'yyyy/mm/dd hh24:mi:ss'));
*/

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
    gluten NUMBER(1,0) NOT NULL,
    lactose NUMBER(1,0) NOT NULL,
    dishName varchar(255) NOT NULL
);

CREATE TABLE foodOrder(
    id int PRIMARY KEY,
    delivery NUMBER(1,0) NOT NULL,
    dateAndTimePay timestamp,
    dateAndTimeDelivered timestamp ,
    idUser varchar(255) REFERENCES username(email) NOT NULL,
    idRider int REFERENCES rider(id)
);

CREATE TABLE orderedFood(
    id int PRIMARY KEY,
    idOrder int REFERENCES foodOrder(id) NOT NULL,
    idDish int REFERENCES dish(id) NOT NULL,
    quantity int NOT NULL
);

