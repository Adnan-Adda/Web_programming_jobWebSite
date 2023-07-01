
--update account id in student/company table
delimiter //
CREATE TRIGGER insert_account_id_fk 
AFTER INSERT
ON ACCOUNT
FOR EACH ROW
BEGIN
	SET @account_id = NEW.account_id;
	IF NEW.group_type='s' THEN
	INSERT INTO STUDENT(account_id) VALUES(@account_id);
	END IF;
	IF NEW.group_type='c' THEN
	INSERT INTO COMPANY(account_id) VALUES(@account_id);
	END IF;
END; //

delimiter ;

--update password if new password not empty
delimiter //

CREATE TRIGGER update_account_passwd
BEFORE UPDATE ON ACCOUNT FOR EACH ROW
BEGIN
	SET @len=LENGTH(NEW.password);
	IF @len <1 THEN
	set NEW.password=OLD.password;
    END IF;
END; //

delimiter ;

--update company name if the new name not empty
delimiter //

CREATE TRIGGER update_company_name
BEFORE UPDATE ON COMPANY FOR EACH ROW
BEGIN
SET @len=LENGTH(NEW.company_name);
	IF @len <1 
	THEN
	set NEW.company_name=OLD.company_name;
	END IF;
END; //

delimiter ;