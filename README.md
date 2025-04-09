# projetWEB

Pour la table user :
INSERT INTO checklist_dbv2.users_table ( user_lastname, user_firstname, user_fullname, user_role, user_email, user_pswd)
SELECT,nom as user_lastname, prenom AS user_firstname , nom_complet as user_fullname, poste as user_role, email as user_email, pswd as user_pswd
FROM checklist_db.users_table;

Pour la table tasks : 
Create table tasks_table as select * from checklist_db.tasks_table

Pour la table new employee : 
Create table new_employee_table as select * from checklist_db.new_employee_table
ALTER TABLE new_employee_table
RENAME COLUMN nom TO user_lastname;
RENAME COLUMN prenom TO user_firstname;
RENAME COLUMN intitulé_poste TO user_role;
