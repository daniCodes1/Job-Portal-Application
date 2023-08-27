-- DROP TABLES

drop table R1_PositionNameTeam;
drop table R5_EmployeeNumPosition;
drop table R7_EmailPhone;
drop table R8_EmployeeNumEmail;
drop table ProduceApplication;
drop table CoverLetter;
drop table Resumes;
drop table Draft;
drop table HiringManager;
drop table Supervisor;
drop table Conducts;
drop table Reviews;
drop table JR1_ScheduleSalary;
drop table JR5_PositionDuties;
drop table JR7_DutyQualifications;
drop table JR9_ID_Qualifications;
drop table JR10_ID_Shift;
drop table Creates;
drop table AppliesFor;
drop table JR3_ID_SpotNum;
drop table Interview;
drop table R3_EmployeeNumName;
drop table AcceptDenyOffer;
drop table StoreApplication;
drop table CreateAccount;
drop table Applicant;

-- CREATE TABLES

CREATE TABLE Applicant(
applicant_email CHAR(30) PRIMARY KEY,
name_ CHAR(30),
phone_num INTEGER UNIQUE,
address_ CHAR(100)
);	

grant select on Applicant to public;

-- Employer Entities R1 - R8

CREATE TABLE R1_PositionNameTeam(
PrimaryPosition CHAR(30),
EmpName CHAR(30),
Team CHAR(30),
PRIMARY KEY(PrimaryPosition, EmpName)
);

grant select on R1_PositionNameTeam to public;


CREATE TABLE R3_EmployeeNumName(
employee_num INTEGER PRIMARY KEY,
EmpName CHAR(30)
);

grant select on R3_EmployeeNumName to public;

CREATE TABLE R5_EmployeeNumPosition(
employee_num INTEGER PRIMARY KEY,
PrimaryPosition CHAR(30)
);

grant select on R5_EmployeeNumPosition to public;

CREATE TABLE R7_EmailPhone(
emp_email CHAR(30) PRIMARY KEY,
emp_phone_num INTEGER
);

grant select on R7_EmailPhone to public;

CREATE TABLE R8_EmployeeNumEmail(
emp_phone_num INTEGER,
emp_email CHAR(30),
PRIMARY KEY(emp_phone_num, emp_email)
);

grant select on R8_EmployeeNumEmail to public;

CREATE TABLE CreateAccount(
applicant_email CHAR(30),
account_acc_num INTEGER PRIMARY KEY,
FOREIGN KEY(applicant_email) REFERENCES Applicant(applicant_email)
);

grant select on CreateAccount to public;

CREATE TABLE StoreApplication(
job_app_num INTEGER PRIMARY KEY,
ApplyDate INTEGER,
account_acc_num_sa INTEGER,
FOREIGN KEY(account_acc_num_sa) REFERENCES CreateAccount(account_acc_num)
);

grant select on StoreApplication to public;

CREATE TABLE ProduceApplication(
produceApp_num INTEGER PRIMARY KEY,
ApplyDate INTEGER,
produceEmail CHAR(30), 
FOREIGN KEY(produceEmail) REFERENCES Applicant(applicant_email)
);

grant select on ProduceApplication to public;

CREATE TABLE CoverLetter(
job_app_num_cv INTEGER PRIMARY KEY,
introduction CHAR(300),
FOREIGN KEY(job_app_num_cv) REFERENCES StoreApplication(job_app_num)
);

grant select on CoverLetter to public;

CREATE TABLE Resumes(
job_num INTEGER PRIMARY KEY,
resName CHAR(30),
experience CHAR(300),
education CHAR(300),
FOREIGN KEY(job_num) REFERENCES StoreApplication(job_app_num)
);

grant select on Resumes to public;

CREATE TABLE AcceptDenyOffer(
offer_employee_num INTEGER PRIMARY KEY,
StartDate INTEGER,
applicant_email CHAR(30),
FOREIGN KEY(applicant_email) REFERENCES Applicant(applicant_email)
);

grant select on AcceptDenyOffer to public;

CREATE TABLE Draft(
offer_employee_num INTEGER,
emp_employee_num INTEGER,
PRIMARY KEY(offer_employee_num, emp_employee_num),
FOREIGN KEY(offer_employee_num) REFERENCES AcceptDenyOffer(offer_employee_num) ON DELETE CASCADE,
FOREIGN KEY(emp_employee_num) REFERENCES R3_EmployeeNumName(employee_num)
);

grant select on Draft to public;

CREATE TABLE Supervisor(
emp_employee_num INTEGER PRIMARY KEY, 
fieldProject CHAR(30),
FOREIGN KEY(emp_employee_num) REFERENCES R3_EmployeeNumName(employee_num)
);

grant select on Supervisor to public;

CREATE TABLE HiringManager(
emp_employee_num INTEGER PRIMARY KEY, 
department CHAR(30), 
FOREIGN KEY(emp_employee_num) REFERENCES R3_EmployeeNumName(employee_num)
);

grant select on HiringManager to public;

CREATE TABLE Interview(
date_ INTEGER,
interviewer CHAR(30),
interviewee CHAR(30),
PRIMARY KEY(date_, interviewer, interviewee)
);

grant select on Interview to public;

CREATE TABLE Conducts(
emp_employee_num INTEGER, 
date_cd INTEGER,
interviewer CHAR(30),
interviewee CHAR(30),
PRIMARY KEY(emp_employee_num, date_cd, interviewer, interviewee),
FOREIGN KEY(emp_employee_num) REFERENCES R3_EmployeeNumName(employee_num),
FOREIGN KEY(date_cd, interviewer, interviewee) REFERENCES Interview(date_, interviewer, interviewee)
);

grant select on Conducts to public;

CREATE TABLE Reviews(
job_app_num INTEGER,
emp_employee_num INTEGER, 
PRIMARY KEY(job_app_num, emp_employee_num),
FOREIGN KEY(job_app_num) REFERENCES StoreApplication(job_app_num),
FOREIGN KEY(emp_employee_num) REFERENCES R3_EmployeeNumName(employee_num)
);

grant select on Reviews to public;

-- Job Listing Entities JR1 - JR10

CREATE TABLE JR1_ScheduleSalary(
ShiftSchedule CHAR(30) PRIMARY KEY,
Salary INTEGER
);

grant select on JR1_ScheduleSalary to public;

CREATE TABLE JR3_ID_SpotNum(
ReferenceID INTEGER PRIMARY KEY,
num_of_Spots INTEGER
);

grant select on JR3_ID_SpotNum to public;

CREATE TABLE JR5_PositionDuties(
Duties CHAR(300) PRIMARY KEY,
PositionName CHAR(30)
);

grant select on JR5_PositionDuties to public;

CREATE TABLE JR7_DutyQualifications(
Qualifications CHAR(300) PRIMARY KEY,
Duties CHAR(300)
);

grant select on JR7_DutyQualifications to public;

CREATE TABLE JR9_ID_Qualifications(
ReferenceID INTEGER PRIMARY KEY,
Qualifications CHAR(300)
);

grant select on JR9_ID_Qualifications to public;

CREATE TABLE JR10_ID_Shift(
ReferenceID INTEGER,
ShiftSchedule CHAR(30),
PRIMARY KEY(ReferenceID, ShiftSchedule)
);

grant select on JR10_ID_Shift to public;

CREATE TABLE Creates(
job_referID INTEGER,
emp_employee_num INTEGER,
PRIMARY KEY(job_referID, emp_employee_num),
FOREIGN KEY(job_referID) REFERENCES JR3_ID_SpotNum(ReferenceID),
FOREIGN KEY(emp_employee_num) REFERENCES R3_EmployeeNumName(employee_num)
);

grant select on Creates to public;

CREATE TABLE AppliesFor(
applyEmail CHAR(30),
applyReferenceID INTEGER,
PRIMARY KEY(applyEmail, applyReferenceID),
FOREIGN KEY(applyEmail) REFERENCES Applicant(applicant_email),
FOREIGN KEY(applyReferenceID) REFERENCES JR3_ID_SpotNum(ReferenceID)
);

grant select on AppliesFor to public;

-- INSERTION STATEMENTS (Some commented out due to lack of space)

-- Applicant

INSERT INTO Applicant(applicant_email, name_, phone_num, address_)
VALUES('sean@gmail.com', 'Sean', 2362844844, '4472 Steeles Ave E, Markham, ON L3R 0L4');

INSERT INTO Applicant(applicant_email, name_, phone_num, address_)
VALUES('dani@gmail.com', 'Dani', 7785754724, '23 Drewry Ave, Toronto, ON M2M 2E4');

INSERT INTO Applicant(applicant_email, name_, phone_num, address_)
VALUES('aaron@gmail.com', 'Aaron', 7784929582, '565 Bernard Ave #14, Kelowna, BC V1Y 8R2');

INSERT INTO Applicant(applicant_email, name_, phone_num, address_)
VALUES('gittu@gmail.com', 'Gittu', 6043841737, '10370 82 Ave NW, Edmonton, AB T6E 4E7');

INSERT INTO Applicant(applicant_email, name_, phone_num, address_)
VALUES('yan@gmail.com', 'Yan', 2363727371, '2435 Ch Duncan, Mount-Royal, QC H4P 2A2');

-- Create Account

INSERT INTO CreateAccount(applicant_email, account_acc_num)
VALUES('sean@gmail.com', 28485);

INSERT INTO CreateAccount(applicant_email, account_acc_num)
VALUES('dani@gmail.com', 57838);

INSERT INTO CreateAccount(applicant_email, account_acc_num)
VALUES('aaron@gmail.com', 45123);

INSERT INTO CreateAccount(applicant_email, account_acc_num)
VALUES('gittu@gmail.com', 72444);

INSERT INTO CreateAccount(applicant_email, account_acc_num)
VALUES('yan@gmail.com', 67671);

-- Store Application

INSERT INTO StoreApplication(job_app_num, ApplyDate, account_acc_num_sa)
VALUES(1, 12192000, 28485);

INSERT INTO StoreApplication(job_app_num, ApplyDate, account_acc_num_sa)
VALUES(2, 11301999, 57838);

INSERT INTO StoreApplication(job_app_num, ApplyDate, account_acc_num_sa)
VALUES(3, 3042012, 45123);

INSERT INTO StoreApplication(job_app_num, ApplyDate, account_acc_num_sa)
VALUES(4, 2122023, 72444);

INSERT INTO StoreApplication(job_app_num, ApplyDate, account_acc_num_sa)
VALUES(5, 11112011, 67671);

-- INSERT INTO StoreApplication(job_app_num, ApplyDate, account_acc_num_sa)
-- VALUES(6, 12192001, 28485);

-- INSERT INTO StoreApplication(job_app_num, ApplyDate, account_acc_num_sa)
-- VALUES(7, 11302000, 57838);

-- INSERT INTO StoreApplication(job_app_num, ApplyDate, account_acc_num_sa)
-- VALUES(8, 3042013, 45123);

-- INSERT INTO StoreApplication(job_app_num, ApplyDate, account_acc_num_sa)
-- VALUES(9, 2122024, 72444);

-- INSERT INTO StoreApplication(job_app_num, ApplyDate, account_acc_num_sa)
-- VALUES(10, 11112012, 67671);

-- Accept Deny Offer

INSERT INTO AcceptDenyOffer(offer_employee_num, StartDate, applicant_email)
VALUES(1, 12122023, 'sean@gmail.com');

INSERT INTO AcceptDenyOffer(offer_employee_num, StartDate, applicant_email)
VALUES(2, 12132023, 'dani@gmail.com');

INSERT INTO AcceptDenyOffer(offer_employee_num, StartDate, applicant_email)
VALUES(3, 12142023, 'aaron@gmail.com');

INSERT INTO AcceptDenyOffer(offer_employee_num, StartDate, applicant_email)
VALUES(4, 12152023, 'gittu@gmail.com');

INSERT INTO AcceptDenyOffer(offer_employee_num, StartDate, applicant_email)
VALUES(5, 12162023, 'yan@gmail.com');

-- R3 Employer Number and Name

INSERT INTO R3_EmployeeNumName(employee_num, EmpName)
VALUES(1111, 'Bill Gates');

INSERT INTO R3_EmployeeNumName(employee_num, EmpName)
VALUES(2222, 'Steve Jobs');

INSERT INTO R3_EmployeeNumName(employee_num, EmpName)
VALUES(3333, 'Elon Musk');

INSERT INTO R3_EmployeeNumName(employee_num, EmpName)
VALUES(4444, 'Jeff Bezos');

INSERT INTO R3_EmployeeNumName(employee_num, EmpName)
VALUES(5555, 'Tim Cook');

-- INSERT INTO R3_EmployeeNumName(employee_num, EmpName)
-- VALUES(6666, 'Jack Ma');

-- Interview

INSERT INTO Interview(date_, interviewer, interviewee)
VALUES(12122023, 'Bill Gates', 'Sean');

INSERT INTO Interview(date_, interviewer, interviewee)
VALUES(12202023, 'Bill Gates', 'Sean');

INSERT INTO Interview(date_, interviewer, interviewee)
VALUES(11092023, 'Elon Musk', 'Dani');

INSERT INTO Interview(date_, interviewer, interviewee)
VALUES(11202023, 'Elon Musk', 'Aaron');

INSERT INTO Interview(date_, interviewer, interviewee)
VALUES(11092023, 'Jeff Bezos', 'Dani');

-- INSERT INTO Interview(date_, interviewer, interviewee)
-- VALUES(11092023, 'Jeff Bezos', 'Aaron');

-- INSERT INTO Interview(date_, interviewer, interviewee)
-- VALUES(12012023, 'Tim Cook', 'Gittu');

-- INSERT INTO Interview(date_, interviewer, interviewee)
-- VALUES(12212023, 'Tim Cook', 'Yan');

-- INSERT INTO Interview(date_, interviewer, interviewee)
-- VALUES(11042023, 'Jack Ma', 'Sean');

-- INSERT INTO Interview(date_, interviewer, interviewee)
-- VALUES(11042023, 'Jack Ma', 'Yan');

-- JR3 Reference ID & Number of Spots

INSERT INTO JR3_ID_SpotNum(referenceID, num_of_Spots)
VALUES(1, 5);

INSERT INTO JR3_ID_SpotNum(referenceID, num_of_Spots)
VALUES(2, 2);

INSERT INTO JR3_ID_SpotNum(referenceID, num_of_Spots)
VALUES(3, 20);

INSERT INTO JR3_ID_SpotNum(referenceID, num_of_Spots)
VALUES(4, 20);

INSERT INTO JR3_ID_SpotNum(referenceID, num_of_Spots)
VALUES(5, 30);

INSERT INTO JR3_ID_SpotNum(referenceID, num_of_Spots)
VALUES(6, 5);

INSERT INTO JR3_ID_SpotNum(referenceID, num_of_Spots)
VALUES(7, 5);

-- R1 (Position & Name of Employer)

INSERT INTO R1_PositionNameTeam(PrimaryPosition, EmpName, Team)
VALUES('Communication', 'Bill Gates', 1);

INSERT INTO R1_PositionNameTeam(PrimaryPosition, EmpName, Team)
VALUES('Marketing Management', 'Steve Jobs', 4);

INSERT INTO R1_PositionNameTeam(PrimaryPosition, EmpName, Team)
VALUES('Customer Service', 'Elon Musk', 3);

INSERT INTO R1_PositionNameTeam(PrimaryPosition, EmpName, Team)
VALUES('Sales', 'Jeff Bezos', 2);

INSERT INTO R1_PositionNameTeam(PrimaryPosition, EmpName, Team)
VALUES('Cook', 'Tim Cook', 3);

-- INSERT INTO R1_PositionNameTeam(PrimaryPosition, EmpName, Team)
-- VALUES('Finance', 'Jack Ma', 5);

-- R5 Employer Number and Position

INSERT INTO R5_EmployeeNumPosition(employee_num, PrimaryPosition)
VALUES(1111, 'Communication');

INSERT INTO R5_EmployeeNumPosition(employee_num, PrimaryPosition)
VALUES(2222, 'Marketing Management');

INSERT INTO R5_EmployeeNumPosition(employee_num, PrimaryPosition)
VALUES(3333, 'Customer Service');

INSERT INTO R5_EmployeeNumPosition(employee_num, PrimaryPosition)
VALUES(4444, 'Sales');

INSERT INTO R5_EmployeeNumPosition(employee_num, PrimaryPosition)
VALUES(5555, 'Cook');

-- INSERT INTO R5_EmployeeNumPosition(employee_num, PrimaryPosition)
-- VALUES(6666, 'Finance');

-- R7 Employer Email and Phone

INSERT INTO R7_EmailPhone(emp_email, emp_phone_num)
VALUES('bill@gmail.com', 2363432123);

INSERT INTO R7_EmailPhone(emp_email, emp_phone_num)
VALUES('steve@gmail.com', 7783049193);

INSERT INTO R7_EmailPhone(emp_email, emp_phone_num)
VALUES('Elon@gmail.com', 7784939185);

INSERT INTO R7_EmailPhone(emp_email, emp_phone_num)
VALUES('Jeff@gmail.com', 2365838384);

INSERT INTO R7_EmailPhone(emp_email, emp_phone_num)
VALUES('whoLetTimCook@gmail.com', 6045818385);

-- INSERT INTO R7_EmailPhone(emp_email, emp_phone_num)
-- VALUES('jack@gmail.com', 2365838384);

-- R8 Employer Number and Email

INSERT INTO R8_EmployeeNumEmail(emp_phone_num, emp_email)
VALUES(1111, 'bill@gmail.com');

INSERT INTO R8_EmployeeNumEmail(emp_phone_num, emp_email)
VALUES(2222, 'steve@gmail.com');

INSERT INTO R8_EmployeeNumEmail(emp_phone_num, emp_email)
VALUES(3333, 'Elon@gmail.com');

INSERT INTO R8_EmployeeNumEmail(emp_phone_num, emp_email)
VALUES(4444, 'Jeff@gmail.com');

INSERT INTO R8_EmployeeNumEmail(emp_phone_num, emp_email)
VALUES(5555, 'whoLetTimCook@gmail.com');

-- INSERT INTO R8_EmployeeNumEmail(emp_phone_num, emp_email)
-- VALUES(6666, 'jack@gmail.com');

-- Produce Application

INSERT INTO ProduceApplication(produceApp_num, ApplyDate, produceEmail)
VALUES(11, 20232707, 'sean@gmail.com');

INSERT INTO ProduceApplication(produceApp_num, ApplyDate, produceEmail)
VALUES(12, 20232707, 'dani@gmail.com');

INSERT INTO ProduceApplication(produceApp_num, ApplyDate, produceEmail)
VALUES(13, 20232707, 'aaron@gmail.com');

INSERT INTO ProduceApplication(produceApp_num, ApplyDate, produceEmail)
VALUES(14, 20232707, 'gittu@gmail.com');

INSERT INTO ProduceApplication(produceApp_num, ApplyDate, produceEmail)
VALUES(15, 20232707, 'yan@gmail.com');

-- Cover Letter

INSERT INTO CoverLetter(job_app_num_cv, introduction)
VALUES(1, 'Hi my name is Sean and please recruit me.');

INSERT INTO CoverLetter(job_app_num_cv, introduction)
VALUES(2, 'Hi my name is Dani and please recruit me.');

INSERT INTO CoverLetter(job_app_num_cv, introduction)
VALUES(3, 'Hi my name is Aaron and please recruit me.');

INSERT INTO CoverLetter(job_app_num_cv, introduction)
VALUES(4, 'Hi my name is Gittu and please recruit me.');

INSERT INTO CoverLetter(job_app_num_cv, introduction)
VALUES(5, 'Hi my name is Yan and please recruit me.');

-- INSERT INTO CoverLetter(job_app_num_cv, introduction)
-- VALUES(6, 'Hi my name is Sean and please recruit me.');

-- INSERT INTO CoverLetter(job_app_num_cv, introduction)
-- VALUES(7, 'Hi my name is Dani and please recruit me.');

-- INSERT INTO CoverLetter(job_app_num_cv, introduction)
-- VALUES(8, 'Hi my name is Aaron and please recruit me.');

-- INSERT INTO CoverLetter(job_app_num_cv, introduction)
-- VALUES(9, 'Hi my name is Gittu and please recruit me.');

-- INSERT INTO CoverLetter(job_app_num_cv, introduction)
-- VALUES(10, 'Hi my name is Yan and please recruit me.');

-- Resumes 

INSERT INTO Resumes(job_num, resName, experience, education)
VALUES(1, 'Sean', '50 years in Google', '2 Bachelors');

INSERT INTO Resumes(job_num, resName, experience, education)
VALUES(2, 'Dani', '50 years in Microsoft', '3 Masters');

INSERT INTO Resumes(job_num, resName, experience, education)
VALUES(3, 'Aaron', '50 years in Tesla', '1 Bachelor, 1 Masters');

INSERT INTO Resumes(job_num, resName, experience, education)
VALUES(4, 'Gittu', '50 years in UBC', '1 Doctorate');

INSERT INTO Resumes(job_num, resName, experience, education)
VALUES(5, 'Yan', '50 years in UBC', '5 Masters');

-- INSERT INTO Resumes(job_num, education, experience, resName)
-- VALUES(6, 'Sean', '50 years in Google', '2 Bachelors');

-- INSERT INTO Resumes(job_num, education, experience, resName)
-- VALUES(7, 'Dani', '50 years in Microsoft', '3 Masters');

-- INSERT INTO Resumes(job_num, education, experience, resName)
-- VALUES(8, 'Aaron', '50 years in Tesla', '1 Bachelor, 1 Masters');

-- INSERT INTO Resumes(job_num, education, experience, resName)
-- VALUES(9, 'Gittu', '50 years in UBC', '1 Doctorate');

-- INSERT INTO Resumes(job_num, education, experience, resName)
-- VALUES(10, 'Yan', '50 years in UBC', '5 Masters');

-- Draft

INSERT INTO Draft(offer_employee_num, emp_employee_num)
VALUES(1, 1111);

INSERT INTO Draft(offer_employee_num, emp_employee_num)
VALUES(2, 2222);

INSERT INTO Draft(offer_employee_num, emp_employee_num)
VALUES(3, 3333);

INSERT INTO Draft(offer_employee_num, emp_employee_num)
VALUES(4, 4444);

INSERT INTO Draft(offer_employee_num, emp_employee_num)
VALUES(5, 5555);

-- Creates

INSERT INTO Creates(job_referID, emp_employee_num)
VALUES(1, 1111);

INSERT INTO Creates(job_referID, emp_employee_num)
VALUES(2, 2222);

INSERT INTO Creates(job_referID, emp_employee_num)
VALUES(3, 3333);

INSERT INTO Creates(job_referID, emp_employee_num)
VALUES(4, 4444);

INSERT INTO Creates(job_referID, emp_employee_num)
VALUES(5, 5555);

INSERT INTO Creates(job_referID, emp_employee_num)
VALUES(6, 2222);

INSERT INTO Creates(job_referID, emp_employee_num)
VALUES(7, 2222);

-- Supervisor

INSERT INTO Supervisor(emp_employee_num, fieldProject)
VALUES(1111, 'Pop Up Store');

INSERT INTO Supervisor(emp_employee_num, fieldProject)
VALUES(2222, 'Desserts');

INSERT INTO Supervisor(emp_employee_num, fieldProject)
VALUES(3333, 'Debugging');

INSERT INTO Supervisor(emp_employee_num, fieldProject)
VALUES(4444, 'Financial Analysis');

INSERT INTO Supervisor(emp_employee_num, fieldProject)
VALUES(5555, 'Phones');

-- Hiring Manager

INSERT INTO HiringManager(emp_employee_num, department)
VALUES(1111, 'Sales');

INSERT INTO HiringManager(emp_employee_num, department)
VALUES(2222, 'Food');

INSERT INTO HiringManager(emp_employee_num, department)
VALUES(3333, 'IT');

INSERT INTO HiringManager(emp_employee_num, department)
VALUES(4444, 'Data');

INSERT INTO HiringManager(emp_employee_num, department)
VALUES(5555, 'Cellular');

-- Conducts

INSERT INTO Conducts(emp_employee_num, date_cd, interviewer, interviewee)
VALUES(1111, 12122023, 'Bill Gates', 'Sean');

INSERT INTO Conducts(emp_employee_num, date_cd, interviewer, interviewee)
VALUES(1111, 12202023, 'Bill Gates', 'Sean');

INSERT INTO Conducts(emp_employee_num, date_cd, interviewer, interviewee)
VALUES(3333, 11092023, 'Elon Musk', 'Dani');

INSERT INTO Conducts(emp_employee_num, date_cd, interviewer, interviewee)
VALUES(3333, 11202023, 'Elon Musk', 'Aaron');

INSERT INTO Conducts(emp_employee_num, date_cd, interviewer, interviewee)
VALUES(4444, 11092023, 'Jeff Bezos', 'Dani');

-- INSERT INTO Conducts(emp_employee_num, date_cd, interviewer, interviewee)
-- VALUES(4444, 11092023, 'Jeff Bezos', 'Aaron');

-- INSERT INTO Conducts(emp_employee_num, date_cd, interviewer, interviewee)
-- VALUES(5555, 12012023, 'Tim Cook', 'Gittu');

-- INSERT INTO Conducts(emp_employee_num, date_cd, interviewer, interviewee)
-- VALUES(5555, 12212023, 'Tim Cook', 'Yan');

-- INSERT INTO Conducts(emp_employee_num, date_cd, interviewer, interviewee)
-- VALUES(6666, 11042023, 'Jack Ma', 'Sean');

-- INSERT INTO Conducts(emp_employee_num, date_cd, interviewer, interviewee)
-- VALUES(6666, 11042023, 'Jack Ma', 'Yan');

-- Reviews

INSERT INTO Reviews(job_app_num, emp_employee_num)
VALUES(1, 2222);

INSERT INTO Reviews(job_app_num, emp_employee_num)
VALUES(2, 1111);

INSERT INTO Reviews(job_app_num, emp_employee_num)
VALUES(3, 4444);

INSERT INTO Reviews(job_app_num, emp_employee_num)
VALUES(4, 3333);

INSERT INTO Reviews(job_app_num, emp_employee_num)
VALUES(5, 5555);

-- INSERT INTO Reviews(job_app_num, emp_employee_num)
-- VALUES(6, 1111);

-- INSERT INTO Reviews(job_app_num, emp_employee_num)
-- VALUES(7, 6666);

-- JR1 Schedule & Salary

INSERT INTO JR1_ScheduleSalary(ShiftSchedule, Salary)
VALUES('MTWTHF', 40534);

INSERT INTO JR1_ScheduleSalary(ShiftSchedule, Salary)
VALUES('Hybrid', 49998);

INSERT INTO JR1_ScheduleSalary(ShiftSchedule, Salary)
VALUES('Remote', 90615);

INSERT INTO JR1_ScheduleSalary(ShiftSchedule, Salary)
VALUES('WTHFS', 62983);

INSERT INTO JR1_ScheduleSalary(ShiftSchedule, Salary)
VALUES('On-Call', 10000);

-- INSERT INTO JR1_ScheduleSalary(ShiftSchedule, Salary)
-- VALUES('TTH', 76526);

-- INSERT INTO JR1_ScheduleSalary(ShiftSchedule, Salary)
-- VALUES('MWF', 54219);

-- JR5 Position Name & Duties

INSERT INTO JR5_PositionDuties(Duties, PositionName)
VALUES('Photocopying', 'Receptionist');

INSERT INTO JR5_PositionDuties(Duties, PositionName)
VALUES('Cashier', 'Retail Worker');

INSERT INTO JR5_PositionDuties(Duties, PositionName)
VALUES('Debug Code', 'Software Engineer');

INSERT INTO JR5_PositionDuties(Duties, PositionName)
VALUES('Meal Prep', 'Assistant Chef');

INSERT INTO JR5_PositionDuties(Duties, PositionName)
VALUES('Answer Calls', 'Customer Service Rep');

INSERT INTO JR5_PositionDuties(Duties, PositionName)
VALUES('Data Analysis', 'Financial Analyst');

INSERT INTO JR5_PositionDuties(Duties, PositionName)
VALUES('SQL Queries', 'Database Intern');

-- JR7 Qualifications & Duties

INSERT INTO JR7_DutyQualifications(Qualifications, Duties)
VALUES('Printing Experience', 'Photocopying');

INSERT INTO JR7_DutyQualifications(Qualifications, Duties)
VALUES('POS System Experience', 'Cashier');

INSERT INTO JR7_DutyQualifications(Qualifications, Duties)
VALUES('Java Experience', 'Debug Code');

INSERT INTO JR7_DutyQualifications(Qualifications, Duties)
VALUES('Proficiency in Food Handling', 'Meal Prep');

INSERT INTO JR7_DutyQualifications(Qualifications, Duties)
VALUES('Customer Service Experience', 'Answer Calls');

INSERT INTO JR7_DutyQualifications(Qualifications, Duties)
VALUES('Data Analyst Experience', 'Data Analysis');

INSERT INTO JR7_DutyQualifications(Qualifications, Duties)
VALUES('SQL Certificate', 'SQL Queries');

-- JR9 ReferenceID & Qualifications

INSERT INTO JR9_ID_Qualifications(ReferenceID, Qualifications)
VALUES(1, 'Printing Experience');

INSERT INTO JR9_ID_Qualifications(ReferenceID, Qualifications)
VALUES(2, 'POS System Experience');

INSERT INTO JR9_ID_Qualifications(ReferenceID, Qualifications)
VALUES(3, 'Java Experience');

INSERT INTO JR9_ID_Qualifications(ReferenceID, Qualifications)
VALUES(4, 'Proficiency in Food Handling');

INSERT INTO JR9_ID_Qualifications(ReferenceID, Qualifications)
VALUES(5, 'Customer Service Experience');

INSERT INTO JR9_ID_Qualifications(ReferenceID, Qualifications)
VALUES(6, 'Data Analyst Experience');

INSERT INTO JR9_ID_Qualifications(ReferenceID, Qualifications)
VALUES(7, 'SQL Certificate');

-- JR10 ReferenceID & Schedule

INSERT INTO JR10_ID_Shift(ReferenceID, ShiftSchedule)
VALUES(1, 'MTWTHF');

INSERT INTO JR10_ID_Shift(ReferenceID, ShiftSchedule)
VALUES(2, 'Hybrid');

INSERT INTO JR10_ID_Shift(ReferenceID, ShiftSchedule)
VALUES(3, 'Remote');

INSERT INTO JR10_ID_Shift(ReferenceID, ShiftSchedule)
VALUES(4, 'WTHFS');

INSERT INTO JR10_ID_Shift(ReferenceID, ShiftSchedule)
VALUES(5, 'On-Call');

INSERT INTO JR10_ID_Shift(ReferenceID, ShiftSchedule)
VALUES(1, 'Remote');

INSERT INTO JR10_ID_Shift(ReferenceID, ShiftSchedule)
VALUES(2, 'Remote');

INSERT INTO JR10_ID_Shift(ReferenceID, ShiftSchedule)
VALUES(6, 'Hybrid');

INSERT INTO JR10_ID_Shift(ReferenceID, ShiftSchedule)
VALUES(7, 'Hybrid');

-- AppliesFor

INSERT INTO AppliesFor(applyEmail, applyReferenceID)
VALUES('sean@gmail.com', 1);

INSERT INTO AppliesFor(applyEmail, applyReferenceID)
VALUES('dani@gmail.com', 2);

INSERT INTO AppliesFor(applyEmail, applyReferenceID)
VALUES('aaron@gmail.com', 3);

INSERT INTO AppliesFor(applyEmail, applyReferenceID)
VALUES('gittu@gmail.com', 4);

INSERT INTO AppliesFor(applyEmail, applyReferenceID)
VALUES('yan@gmail.com', 5);

-- INSERT INTO AppliesFor(applyEmail, applyReferenceID)
-- VALUES('sean@gmail.com', 6);

-- INSERT INTO AppliesFor(applyEmail, applyReferenceID)
-- VALUES('dani@gmail.com', 7);