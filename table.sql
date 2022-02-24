/*
ALTER SESSION SET NLS_DATE_FORMAT = 'DD-MM-YYYY HH24:MI:SS';
*/

CREATE DATABASE Il_Pescaggio;
USE Il_Pescaggio;

CREATE TABLE rider(
    email varchar(255) PRIMARY KEY,
    pasw varchar(255) NOT NULL,
    riderName varchar(255) NOT NULL,
    riderSurname varchar(255) NOT NULL,
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
    dateAndTimePay timestamp,
    dateAndTimeDelivered DATETIME,
    idUser varchar(255) NOT NULL REFERENCES username(email),
    idRider int REFERENCES rider(id),
    reservations int,
    firstName varchar(255),
    surname varchar(255),
    via varchar(255),
    civ varchar(255),
    cap varchar(255),
    tel varchar(255),
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
    lastChange timestamp NOT NULL,
    catering TINYINT(1),
    primary key(idUser,idDish,catering)
);

INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Bigne"            ,"desserts","1.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Tiramisù"         ,"desserts","2.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Fritto Misto"     ,"fish","3.jpg" );
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Polpo e patate"   ,"fish","4.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Margherita"       ,"pizza","5.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Marinara"         ,"pizza","6.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Insalata"         ,"vegan","7.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Minestra"         ,"vegan","8.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Fiorentina"       ,"meat","9.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Spiedini"         ,"meat","10.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Classic Burger"   ,"burger","11.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Cheese and Bacon"  ,"burger","12.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Crostata"         ,"desserts","13.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Profiterol"       ,"desserts","14.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Cheesecake"        ,"desserts","15.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Bufala"           ,"pizza","16.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Chicken Burger"   ,"burger","17.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Tomahawk"         ,"meat","18.jpeg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Spaghetti allo scoglio","fish","19.jpg");
INSERT INTO `dish`( `dishCost`, `description`, `gluten`, `lactose`, `dishName`, `dishType`, `photoLink`) VALUES (10,"DESCRIZIONE DEL PIATTO",1,1,"Spaghetti al pomodoro","vegan","20.jpg");

INSERT INTO `rider`(`email`, `pasw`, `riderName`, `riderSurname`, `available`) VALUES ("mario.rossi@esempio.it","19513fdc9da4fb72a4a05eb66917548d3c90ff94d5419e1f2363eea89dfee1dd","Mario","Rossi",1);
INSERT INTO `rider`(`email`, `pasw`, `riderName`, `riderSurname`, `available`) VALUES ("luigi.blu@esempio.it","19513fdc9da4fb72a4a05eb66917548d3c90ff94d5419e1f2363eea89dfee1dd","Luigi","Blu",1);
INSERT INTO `username` (`email`, `firstName`, `surname`, `registrationDate`, `nCard`, `via`, `civ`, `cap`, `tel`, `photoLink`, `pasw`) VALUES('filippo.spinella.2003@calvino.edu.it', 'Filippo', 'Spinella', '2022-02-24 11:53:43', '0123 4567 7890', 'via Sestri', '17/11', '16154', '1234567891', 'filippo.spinella.2003@calvino.edu.it.jpg', '19513fdc9da4fb72a4a05eb66917548d3c90ff94d5419e1f2363eea89dfee1dd');