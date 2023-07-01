ALTER TABLE APPLICATIONS ADD CONSTRAINT app_pk PRIMARY KEY (student_id,ad_id,send_date,is_rejected);

--Foreign keys

ALTER TABLE COMPANY ADD CONSTRAINT company_fk FOREIGN KEY (account_id) REFERENCES ACCOUNT(account_id) ON DELETE CASCADE;
show warnings;
ALTER TABLE STUDENT ADD CONSTRAINT student_FK FOREIGN KEY (account_id) REFERENCES ACCOUNT(account_id) ON DELETE CASCADE;
show warnings;
ALTER TABLE ADS ADD CONSTRAINT ad_FK FOREIGN KEY (company_id) REFERENCES COMPANY(company_id) ON DELETE CASCADE ON UPDATE CASCADE;
show warnings;
ALTER TABLE APPLICATIONS ADD CONSTRAINT app_FK1 FOREIGN KEY (student_id) REFERENCES STUDENT(student_id) ON DELETE CASCADE ON UPDATE CASCADE;
show warnings;
ALTER TABLE APPLICATIONS ADD CONSTRAINT app_FK2 FOREIGN KEY (ad_id) REFERENCES ADS(ad_id) ON DELETE CASCADE ON UPDATE CASCADE;
 