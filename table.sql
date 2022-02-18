/*
ALTER SESSION SET NLS_DATE_FORMAT = 'DD-MM-YYYY HH24:MI:SS';
*/

CREATE DATABASE Il_Pescaggio;
USE Il_Pescaggio;

CREATE TABLE rider(
    id int PRIMARY KEY AUTO_INCREMENT,
    nome varchar(255) NOT NULL,
    cognome varchar(255) NOT NULL,
    available TINYINT(1),
    salary int,
    ordersDelivered int
);

CREATE TABLE username(
    email varchar(255) PRIMARY KEY,
    firstName varchar(255),
    surname varchar(255),
    registrationDate timestamp,
    nCard varchar(255),
    via varchar(255),
    civ varchar(255),
    cap varchar(255),
    tel varchar(255),
    photoLink varchar(255),
    pasw varchar(255) NOT NULL
);

CREATE TABLE dish(
    id int PRIMARY KEY AUTO_INCREMENT,
    dishCost int NOT NULL,
    creationDate timestamp NOT NULL,
    description varchar(255) NOT NULL,
    gluten TINYINT(1) NOT NULL,
    lactose TINYINT(1) NOT NULL,
    dishName varchar(255) NOT NULL,
    dishType varchar(255) NOT NULL,
    photoLink varchar(255) NOT NULL
);

CREATE TABLE FOrder(
    id int PRIMARY KEY AUTO_INCREMENT,
    delivery TINYINT(1) NOT NULL,
    dateAndTimePay DATETIME,
    dateAndTimeDelivered DATETIME,
    idUser varchar(255) NOT NULL REFERENCES username(email),
    idRider int REFERENCES rider(id),
    reservations int,
    note varchar(255)
);

CREATE TABLE orderedFood(
    idOrder int NOT NULL REFERENCES foodOrder(id) ,
    idDish int NOT NULL REFERENCES dish(id) ,
    quantity int NOT NULL,
    primary key(idOrder,idDish)
);

CREATE TABLE cart(
    idUser varchar(255) NOT NULL REFERENCES username(email) ,
    idDish int NOT NULL REFERENCES dish(id) ,
    quantity int NOT NULL,
    delivery TINYINT(1),
    primary key(idUser,idDish)
);

INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Bigne"            ,"desserts","bigne.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Tiramisù"         ,"desserts","tiramisu.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Fritto Misto"     ,"fish","frittoMisto.jpg" );
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Polpo e patate"   ,"fish","polpoPatate.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Margherita"       ,"pizza","margherita.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Marinara"         ,"pizza","marinara.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Insalata"         ,"vegan","insalata.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Minestra"         ,"vegan","minestra.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Fiorentina"       ,"meat","fiorentina.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Spiedini"         ,"meat","spiedino.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Classic Burger"   ,"burger","Cburger.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Chess and Bacon"  ,"burger","chessBacon.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Crostata"         ,"desserts","crostata.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Profiterol"       ,"desserts","profiterol.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Cheescake"        ,"desserts","cheescake.jpg");

INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Bufala"           ,"pizza","bufala.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Chicken Burger"   ,"burger","chickenBurger.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Tomahawk"         ,"meat","tom.jpeg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Spaghetti allo scoglio","fish","scoglio.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"spaghetti al pomodoro","vegan","pomodoro.jpg");