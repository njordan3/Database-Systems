CREATE TRIGGER updateEmployeeTrigger
	AFTER UPDATE ON Employee
	FOR EACH ROW
	EXECUTE FUNCTION updateEmployee();

CREATE TRIGGER removeEmployeeTrigger
	BEFORE DELETE ON Employee
	FOR EACH ROW
	EXECUTE FUNCTION deleteEmployee();

CREATE TRIGGER updateEmployeeInfoTrigger
	INSTEAD OF UPDATE ON employeeInfo
	FOR EACH ROW
	EXECUTE FUNCTION updateEmployeeInfo();
