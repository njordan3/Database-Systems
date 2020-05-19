-- This function returns the shopID of an employee based on their who their manager is
CREATE OR REPLACE FUNCTION getShopFromManager(mID INTEGER)
RETURNS INTEGER AS
$$
DECLARE
	shpID	INTEGER;
BEGIN
	SELECT h.ShopID INTO shpID
	FROM (
		SELECT *
		FROM Employee
		WHERE EmployeeID = mID ) AS Manager
	INNER JOIN Hires h
	ON Manager.EmployeeID = h.EmployeeID;
	RETURN shpID;
END;
$$ LANGUAGE plpgsql;

-- This function finds the average menu items 
-- filled between the top N baristas
/*CREATE OR REPLACE FUNCTION averageSales(N INTEGER)
RETURNS REAL AS
$$
DECLARE
	average	REAL;
BEGIN
	SELECT ROUND(AVG(s.orders_filled), 2) INTO average
	FROM (
		SELECT SUM(s.Item_quantity) orders_filled
		FROM (
			SELECT *
			FROM Employee
			WHERE job_type = 'Barista' ) AS Baristas
		INNER JOIN Fills f
		ON Baristas.EmployeeID = f.EmployeeID
		INNER JOIN SOrder s
		ON f.SOrderID = s.SOrderID
		GROUP BY Baristas.EmployeeID
		ORDER BY orders_Filled DESC
		LIMIT N ) AS s;
	RETURN average;
END;
$$ LANGUAGE plpgsql;*/

-- This function removes an employee based on their employeeID, and
-- if the employee is a manager, then it sets manager_ssn to NULL
-- for the employees who were managed by that manager.
CREATE OR REPLACE FUNCTION removeEmployee( empID INTEGER )
RETURNS VOID AS
$$
DECLARE
	target	RECORD;
	mID	INTEGER;
	cur_emp	CURSOR FOR
		SELECT ManagerID
		FROM Employee e INNER JOIN (
			SELECT EmployeeID FROM Employee 
			WHERE EmployeeID = empID ) AS emp
		ON e.ManagerID = emp.EmployeeID;
BEGIN
	SELECT Department_name INTO target
	FROM Works_for w INNER JOIN Department d
	ON w.DepartmentID = d.DepartmentID 
	WHERE w.EmployeeID = empID;
	
	IF target.Department_name = 'Manager' THEN
		OPEN cur_emp;
		LOOP
			FETCH cur_emp INTO mID;
			EXIT WHEN NOT FOUND;
			UPDATE Employee
			SET ManagerID = NULL
			WHERE ManagerID = mID;
		END LOOP;
		CLOSE cur_emp;
	END IF;
	DELETE FROM Employee WHERE EmployeeID = empID;
EXCEPTION
	WHEN OTHERS THEN
	RAISE 'Removing employee % from database failed due to %',empID,SQLERRM;
END;
$$ LANGUAGE plpgsql;

-- This function adds a new employee into the database
-- by adding a record to the Employee and Employs tables
CREATE OR REPLACE FUNCTION addEmployee(
	n	VARCHAR(30),
	social	VARCHAR(11),
	addr	VARCHAR(30),
	bd	DATE,
	s	sex,
	j	job,
	wage	NUMERIC(4, 2),
	sal	INTEGER,
	shpID	INTEGER,
	mID	INTEGER,
	sdate	DATE
) RETURNS VOID AS
$$
DECLARE
	empID	INTEGER;
	deptID	INTEGER;
BEGIN
	-- set the values of empID, shpID, and deptID
	SELECT MAX(EmployeeID)+1 INTO empID FROM Employee;
	--SELECT getShopFromManager(mID) INTO shpID;
	SELECT DepartmentID INTO deptID FROM Department WHERE Department_name = j;

	SET TRANSACTION READ WRITE;
	
	INSERT INTO Employee(EmployeeID,Name,SSN,Address,Birth_date,Sex,ManagerID)
	VALUES (empID,n,social,addr,bd,s,mID);

	INSERT INTO Works_for(DepartmentID,EmployeeID,Dept_start,Dept_end,Wage,Salary)
	VALUES (deptID,empID,sdate,edate,wage,sal);

	INSERT INTO Hires(ShopID,EmployeeID,Hire_date,Leave_date)
	VALUES (shpID,empID,sdate,edate);

EXCEPTION
	WHEN OTHERS THEN
	raise 'addEmployee failed due to %', SQLERRM;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fillSOrder( 
	sordID 	INTEGER,
	empID 	INTEGER,
	rec_time	TIMESTAMP
) RETURNS VOID AS
$$
DECLARE
	fill_time	TIMESTAMP;
BEGIN
	fill_time = CURRENT_TIMESTAMP(0);
	INSERT INTO Fills(SOrderID,EmployeeID,Time_received,Time_delivered) 
	VALUES (sordID, empID, rec_time, fill_time);

EXCEPTION
	WHEN OTHERS THEN
	raise 'fillSOrder failed due to %', SQLERRM;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION addSOrder(
	sID	INTEGER,
	cID	INTEGER,
	phone	VARCHAR(12),
	name	VARCHAR(30),
	member	BOOLEAN,
	disc	NUMERIC(5,2),
	is_onl	BOOLEAN,
	product	VARCHAR(20),
	s	size_type,
	q	INTEGER,
	s_instr	VARCHAR(50),
	price	NUMERIC(4,2),
	eID	INTEGER,
	p_info	payment_type
) RETURNS VOID AS
$$
DECLARE
	pID	INTEGER;
	p_date	TIMESTAMP;
BEGIN
	--SELECT MAX(SOrderID)+1 INTO sID FROM SOrder;
	--SELECT MAX(CustomerID)+1 INTO cID FROM Customer;
	SELECT ProductID INTO pID FROM Menu_item WHERE Product_name = product AND size = s;
	p_date = CURRENT_TIMESTAMP(0);

	SET TRANSACTION READ WRITE;
	IF NOT EXISTS (SELECT CustomerID FROM Customer WHERE CustomerID=cID) THEN
		INSERT INTO Customer(CustomerID,Phone_num,Customer_name,Membership_status)
		VALUES (cID,phone,name,member);
	END IF;
	
	IF NOT EXISTS (SELECT SOrderID FROM SOrder WHERE SOrderID=sID) THEN
		INSERT INTO SOrder (SOrderID,CustomerID,Discount,Is_online)
		VALUES (sID,cID,disc,is_onl);
	END IF;

	INSERT INTO Has (ProductID,SOrderID,Product_price,Product_quantity,Special_instr)
	VALUES (pID,sID,price,q,s_instr);

	IF NOT EXISTS (SELECT SOrderID, EmployeeID FROM Payed_to WHERE SOrderID=sID AND EmployeeID=eID) THEN
		INSERT INTO Payed_to (SOrderID,EmployeeID,Pay_info,Pay_date)
		VALUES (sID,eID,p_info,p_date);
	END IF;

EXCEPTION
	WHEN OTHERS THEN
	raise 'addSOrder failed due to %', SQLERRM;
END;
$$ LANGUAGE plpgsql;

-- This function is called after an update to Employee
-- to run a cascaded update on tables that reference Employee
/*CREATE OR REPLACE FUNCTION updateEmployee()
RETURNS TRIGGER AS
$$
BEGIN
	IF NEW.EmployeeID <> OLD.EmployeeID THEN
        	UPDATE Employs
        	SET EmployeeID = NEW.EmployeeID
        	WHERE EmployeeID = OLD.EmployeeID;

        	UPDATE Checks
        	SET EmployeeID = NEW.EmployeeID
        	WHERE EmployeeID = OLD.EmployeeID;

        	UPDATE Fills
        	SET EmployeeID = NEW.EmployeeID
        	WHERE EmployeeID = OLD.EmployeeID;

        	UPDATE Payed_to
        	SET EmployeeID = NEW.EmployeeID
        	WHERE EmployeeID = OLD.EmployeeID;

        	UPDATE Places
        	SET EmployeeID = NEW.EmployeeID
        	WHERE EmployeeID = OLD.EmployeeID;
	END IF;
	RETURN NEW;
EXCEPTION
	WHEN OTHERS THEN
	RAISE 'updateEmployee Trigger Function failed due to %', SQLERRM;
END;
$$ LANGUAGE plpgsql;


-- This function is called after a deletion of Employee
-- to run a cascaded deletions on tables that reference Employee
CREATE OR REPLACE FUNCTION deleteEmployee()
RETURNS TRIGGER AS
$$
BEGIN
        DELETE FROM Employs
        WHERE EmployeeID = OLD.EmployeeID;

        DELETE FROM Checks
        WHERE EmployeeID = OLD.EmployeeID;

        DELETE FROM Fills
        WHERE EmployeeID = OLD.EmployeeID;

        DELETE FROM Payed_to
        WHERE EmployeeID = OLD.EmployeeID;

        DELETE FROM Places
        WHERE EmployeeID = OLD.EmployeeID;
	RETURN OLD;
EXCEPTION
	WHEN OTHERS THEN
	RAISE 'deleteEmployee Trigger Function failed due to %', SQLERRM;	
END;
$$ LANGUAGE plpgsql;


-- This function is called after an update to employeeInfo
-- to update the tables that are included in the view
CREATE OR REPLACE FUNCTION updateEmployeeInfo()
RETURNS TRIGGER AS
$$
BEGIN
	UPDATE CoffeeShop SET
		ShopID = NEW.ShopID
		WHERE ShopID = OLD.ShopID;
	UPDATE Employee SET
		Name = NEW.Name,
		EmployeeID = NEW.EmployeeID,
		Job_type = New.Job_type
		WHERE EmployeeID = OLD.EmployeeID;
	RETURN NEW;
EXCEPTION
	WHEN OTHERS THEN
	RAISE 'insteadEmployeeInfo Trigger Function failed due to %', SQLERRM;
END;
$$ LANGUAGE plpgsql;*/
