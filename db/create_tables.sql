CREATE TABLE COMPANY(
	company_id		INT AUTO_INCREMENT primary key NOT NULL,
	account_id		INT NOT NULL,
	logo			VARCHAR(100),
	company_name	VARCHAR(50),
	website			VARCHAR(100));

CREATE TABLE ADS(
	ad_id				INT AUTO_INCREMENT primary key NOT NULL,
	company_id			INT NOT NULL,
	title				VARCHAR(50),
	job_area			VARCHAR(50),
	city				VARCHAR(50),
	province			VARCHAR(50),
	post				VARCHAR(5000),
	qualifications		VARCHAR(50),
	job_duration		VARCHAR(200),
	is_published		boolean,
	publish_date		DATE DEFAULT CURRENT_DATE(),
	end_date			DATE);

CREATE TABLE ACCOUNT(
	account_id			INT AUTO_INCREMENT primary key NOT NULL,
	group_type			CHAR(1),
	email				VARCHAR(50),
	password			VARCHAR(100));

CREATE TABLE STUDENT(
	student_id		INT AUTO_INCREMENT primary key NOT NULL,
	account_id		INT NOT NULL,
	student_name	VARCHAR(100),
	phone_num		VARCHAR(15),
	cv_email		VARCHAR(100),
	education		VARCHAR(1000),
	skills			VARCHAR(1000),
	summary			VARCHAR(1000),
	other			VARCHAR(1000));

CREATE TABLE APPLICATIONS(
	student_id		INT NOT NULL,
	ad_id			INT NOT NULL,
	is_rejected		boolean DEFAULT false,
	send_date		DATE DEFAULT CURRENT_DATE());