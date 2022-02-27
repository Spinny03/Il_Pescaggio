/*
ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS';
*//*
1. in attesa, 2. in preparazione, 3. in consegna , -1 non accettato
*//*
admin:
    email: admin@ilpescaggio.it
    password: =FfUgKru[>%,4vGv
rider:
    email: nome.cognome@ilpescaggio.it
    password: Password1
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
    photoLink varchar(255) NOT NULL,
    visible TINYINT(1) NOT NULL
);

CREATE TABLE FOrder(
    id int PRIMARY KEY AUTO_INCREMENT,
    orderStatus int NOT NULL,
    delivery TINYINT(1) NOT NULL,
    dateAndTimePay timestamp,
    dateAndTimeDelivered DATETIME,
    idUser varchar(255) NOT NULL REFERENCES username(email),
    idRider varchar(255) REFERENCES rider(id),
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

INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (2,"È un piccolo dolce composto da un guscio di pasta choux ripieno di creme",0,0,"Bigne","desserts","1.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (4,"Il tiramisù è un dolce e prodotto agroalimentare tradizionale diffuso in tutto il territorio italiano",0,0,"Tiramisù","desserts","2.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (8,"La frittura di paranza è una frittura di pesce di piccolo taglio diffusa in molte zone d'Italia.",0,0,"Fritto Misto","fish","3.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (9,"Questo piatto veneziano di tenero polpo bollito viene ravvivato con un condimento al limone e peperoncino.",0,0,"Polpo e patate"   ,"fish","4.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (5,"La pizza Margherita è la tipica pizza napoletana, condita con pomodoro, mozzarella e origano",0,0,"Margherita","pizza","5.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (4,"La pizza alla marinara è una tipica pizza napoletana condita con pomodoro, aglio, origano, olio",0,0,"Marinara","pizza","6.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (6,"Il vino bianco è un prodotto ottenuto dalla fermentazione alcolica del mosto di uva a bacca bianca",0,0,"Vino Bianco","drink","7.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (7,"Il vino rosso è un tipo di vino ottenuto da vitigni di colore scuro",0,0,"Vino Rosso","drink","8.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (25,"La bistecca alla fiorentina è un taglio di carne di scottona di razza Chianina",0,0,"Fiorentina","meat","9.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (12,"Spiedino di carne di maiale e verdure miste",0,0,"Spiedini","meat","10.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (10,"Classico burger americano con carne di angus e patatine di contorno",0,0,"Classic Burger","burger","11.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (13,"Cheesburger con bacon crocante",0,0,"Cheese and Bacon","burger","12.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (4,"Crostata fatta in casa con marmellata di lamponi",0,0,"Crostata","desserts","13.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (6,"Il profiterole è un bignè riempito di crema pasticcera, panna e ricoperto con una crema di cioccolato",0,0,"Profiterol","desserts","14.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (5,"La cheesecake è composto da una base di pasta biscotto su cui poggia un alto strato di crema di formaggio fresco",0,0,"Cheesecake","desserts","15.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (7,"La pizza alla Bufala è condita con pomodoro, mozzarella di bufala e origano",0,0,"Bufala","pizza","16.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (12,"Panino con petto di pollo speziato e salse",0,0,"Chicken Burger","burger","17.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (30,"Taglio pregiato di carne",0,0,"Tomahawk","meat","18.jpeg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (11,"Gli spaghetti allo scoglio sono a base di spaghetti e di frutti di mare come molluschi e crostacei",0,0,"Spaghetti allo scoglio","fish","19.jpg",1);
INSERT INTO dish( dishCost, description, gluten, lactose, dishName, dishType, photoLink, visible) VALUES (6,"Il vino spumante è un vino caratterizzato da rilevante effervescenza, dovuta ad anidride carbonica disciolta",0,0,"Spumante","drink","20.jpg",1);

INSERT INTO rider(email, pasw, riderName, riderSurname, available) VALUES ("mario.rossi@ilpescaggio.it","19513fdc9da4fb72a4a05eb66917548d3c90ff94d5419e1f2363eea89dfee1dd","Mario","Rossi",1);
INSERT INTO rider(email, pasw, riderName, riderSurname, available) VALUES ("luigi.blu@ilpescaggio.it","19513fdc9da4fb72a4a05eb66917548d3c90ff94d5419e1f2363eea89dfee1dd","Luigi","Blu",1);
INSERT INTO username (email, firstName, surname, registrationDate, nCard, via, civ, cap, tel, photoLink, pasw) VALUES('filippo.spinella.2003@calvino.edu.it', 'Filippo', 'Spinella', '2022-02-24 11:53:43', '0123 4567 7890', 'via Sestri', '17/11', '16154', '1234567891', 'filippo.spinella.2003@calvino.edu.it.jpg', '19513fdc9da4fb72a4a05eb66917548d3c90ff94d5419e1f2363eea89dfee1dd');
INSERT INTO username (email, firstName, surname, registrationDate, nCard, via, civ, cap, tel, photoLink, pasw) VALUES('filippo.spinella2003@hotmail.com', 'Filippo', 'Spinella', '2022-02-24 11:53:43', '0123 4567 7890', 'via Sestri', '17/11', '16154', '1234567891', 'filippo.spinella2003@hotmail.com.jpg', '19513fdc9da4fb72a4a05eb66917548d3c90ff94d5419e1f2363eea89dfee1dd');
INSERT INTO username (email, firstName, surname, registrationDate, nCard, via, civ, cap, tel, photoLink, pasw) VALUES ('barle.ale@gmail.com', 'Alessio', 'Barletta', '2022-02-25 10:08:50', '1234 1234 1234', 'via San Giovanni Battista', '12/89', '16654', '3333333333', 'barle.ale@gmail.com.jpg', '267f761ffdb09daa1d2ff11dc825dc94edd53a086b410972e6a0d6826b020c54');
INSERT INTO username (email, firstName, surname, registrationDate, nCard, via, civ, cap, tel, pasw) VALUES ('admin@ilpescaggio.it', 'Admin', 'Ilpescaggio', '2022-01-01 00:00:00', '1234 1234 1234 1234', 'via sestri', '1/1', '16154', '3333333333', 'a5e0be58497edf2d7aadd72005f858967a550aaccc21e3f80efd5c4398013869');