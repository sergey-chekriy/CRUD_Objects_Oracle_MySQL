CREATE TABLESPACE crudobj_perm_01
  DATAFILE 'crudobj_perm.dat' 
  SIZE 10M
  ONLINE;

CREATE TEMPORARY TABLESPACE crudobj_temp_01
  TEMPFILE 'crudobj_temp.dbf'
  SIZE 5M
  AUTOEXTEND OFF;


CREATE USER CRUDObjects_user
  IDENTIFIED BY abc123
  DEFAULT TABLESPACE crudobj_perm_01
  TEMPORARY TABLESPACE crudobj_temp_01
  QUOTA 5M on crudobj_perm_01;

GRANT create session TO CRUDObjects_user;
GRANT create table TO CRUDObjects_user;
GRANT create sequence TO CRUDObjects_user;


CREATE TABLE CRUDObjects_user.example_user (
        user_id NUMBER NOT NULL,
        first_name VARCHAR2(25) NOT NULL,
        last_name VARCHAR2(25) NOT NULL,
        contact_email VARCHAR2(45) NOT NULL,
        CONSTRAINT user_pk PRIMARY KEY (user_id)
      );

CREATE SEQUENCE CRUDObjects_user.example_user_id_seq;
    

INSERT INTO CRUDObjects_user.example_user (user_id,first_name,last_name,contact_email) VALUES 
                         (CRUDObjects_user.example_user_id_seq.nextval, 'Richard', 'Hendricks', 'richard.hendricks@piedpiper.com');

INSERT INTO CRUDObjects_user.example_user (user_id,first_name,last_name,contact_email) VALUES 
                         (CRUDObjects_user.example_user_id_seq.nextval, 'Erlich', 'Bachmann', 'erlich.bachmann@piedpiper.com');

INSERT INTO CRUDObjects_user.example_user (user_id,first_name,last_name,contact_email) VALUES 
                         (CRUDObjects_user.example_user_id_seq.nextval, 'Bertram', 'Gilfoyle', 'bertram.gilfoyle@piedpiper.com');


COMMIT;

