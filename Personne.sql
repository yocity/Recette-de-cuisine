DROP TABLE IF EXISTS `Personne`;
CREATE TABLE Personne (
  idP INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  dateN DATE NOT NULL
);

INSERT INTO `Personne` (`idP`, `nom`, `dateN`) VALUES
(1, 'Devignes', '2001-01-20'),
(2, 'Chambeaux','2002-02-04'),
(3, 'Bernard', '1998-03-20'),
(4, 'Dupont', '1999-06-12'),
(5, 'Durand', '2000-03-15'),
(6, 'Martin', '2001-08-18'),
(7, 'Lefevre', '2002-09-21'),
(8, 'Leroy', '2003-10-24'),
(9, 'Moreau', '2004-11-27'),
(10, 'Lambert', '2005-12-30');

