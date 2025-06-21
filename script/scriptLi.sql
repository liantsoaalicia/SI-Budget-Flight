-- script a ajouter dans dolibarr

CREATE TABLE llx_departement (
    rowid INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

CREATE TABLE llx_user_departement (
    rowid INT AUTO_INCREMENT PRIMARY KEY,
    fk_user INT NOT NULL,
    fk_departement INT NOT NULL,
    FOREIGN KEY (fk_user) REFERENCES llx_user(rowid) ON DELETE CASCADE,
    FOREIGN KEY (fk_departement) REFERENCES llx_departement(rowid) ON DELETE CASCADE
);

INSERT INTO llx_departement (nom) VALUES ('Departement finance'), ('Departement marketing'), ('Departement IT');
INSERT INTO llx_user_departement (fk_user, fk_departement) VALUES (1, 1);