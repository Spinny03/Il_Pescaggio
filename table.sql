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
    dishName varchar(255) NOT NULL,
    dishType varchar(255) NOT NULL,
    photoLink varchar(255) NOT NULL
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

INSERT INTO `dish`(`id`, `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES ( 1,10,"DESCRIZIONE DEL PIATTO",1,1,"Bigne"            ,"desserts","bigne.jpg");
INSERT INTO `dish`(`id`, `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES ( 2,10,"DESCRIZIONE DEL PIATTO",1,1,"Tiramisù"         ,"desserts","tiramisu.jpg");
INSERT INTO `dish`(`id`, `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES ( 3,10,"DESCRIZIONE DEL PIATTO",1,1,"Fritto Misto"     ,"fish","frittoMisto.jpg" );
INSERT INTO `dish`(`id`, `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES ( 4,10,"DESCRIZIONE DEL PIATTO",1,1,"Polpo e patate"   ,"fish","polpoPatate.jpg");
INSERT INTO `dish`(`id`, `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES ( 5,10,"DESCRIZIONE DEL PIATTO",1,1,"Margherita"       ,"pizza","margherita.jpg");
INSERT INTO `dish`(`id`, `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES ( 6,10,"DESCRIZIONE DEL PIATTO",1,1,"Marinara"         ,"pizza","marinara.jpg");
INSERT INTO `dish`(`id`, `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES ( 7,10,"DESCRIZIONE DEL PIATTO",1,1,"Insalata"         ,"vegan","insalata.jpg");
INSERT INTO `dish`(`id`, `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES ( 8,10,"DESCRIZIONE DEL PIATTO",1,1,"Minestra"         ,"vegan","minestra.jpg");
INSERT INTO `dish`(`id`, `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES ( 9,10,"DESCRIZIONE DEL PIATTO",1,1,"Fiorentina"       ,"meat","fiorentina.jpg");
INSERT INTO `dish`(`id`, `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,10,"DESCRIZIONE DEL PIATTO",1,1,"Spiedini"         ,"meat","spiedino.jpg");
INSERT INTO `dish`(`id`, `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (11,10,"DESCRIZIONE DEL PIATTO",1,1,"Classic Burger"   ,"burger","Cburger.jpg");
INSERT INTO `dish`(`id`, `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (12,10,"DESCRIZIONE DEL PIATTO",1,1,"Chess and Bacon"  ,"burger","chessBacon.jpg");
INSERT INTO `dish`(`id`, `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (13,10,"DESCRIZIONE DEL PIATTO",1,1,"Crostata"         ,"desserts","crostata.jpg");
INSERT INTO `dish`(`id`, `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (14,10,"DESCRIZIONE DEL PIATTO",1,1,"Profiterol"       ,"desserts","profiterol.jpg");
INSERT INTO `dish`(`id`, `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (15,10,"DESCRIZIONE DEL PIATTO",1,1,"Cheescake"        ,"desserts","cheescake.jpg");